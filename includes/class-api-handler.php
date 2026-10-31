<?php
/**
 * Handles communication with AI providers.
 *
 * @package HTR_AI_Chatbot
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class HTR_AI_Chatbot_API_Handler
 */
class HTR_AI_Chatbot_API_Handler {

    /**
     * Provider endpoints.
     *
     * @var array
     */
    private $provider_endpoints = array(
        'openai' => 'https://api.openai.com/v1/chat/completions',
        'avalai' => 'https://api.avalai.ir/v1/chat/completions',
    );

    /**
     * Send a message.
     *
     * @param string $message User message.
     * @return string|WP_Error
     */
    public function send_message( $message ) {
        if ( ! is_string( $message ) || '' === trim( $message ) ) {
            return $this->create_error( 'invalid_message', 'A valid message is required.', 'validation' );
        }

        $settings = get_option( 'htr_ai_chatbot_settings', array() );

        if ( ! is_array( $settings ) ) {
            return $this->create_error( 'invalid_settings', 'Plugin settings are invalid.', 'settings' );
        }

        $provider = isset( $settings['api_provider'] )
            ? sanitize_key( $settings['api_provider'] )
            : '';

        if ( ! in_array( $provider, array( 'openai', 'avalai', 'custom' ), true ) ) {
            return $this->create_error( 'missing_provider', 'No valid API provider is configured.', 'configuration' );
        }

        $model = isset( $settings['custom_model'] ) && is_string( $settings['custom_model'] )
            ? trim( sanitize_text_field( $settings['custom_model'] ) )
            : '';

        if ( '' === $model || strlen( $model ) > 200 ) {
            return $this->create_error( 'missing_model', 'A valid model ID is required.', 'configuration' );
        }

        $api_key = get_option( 'htr_ai_chatbot_api_key', '' );

        if ( ! is_string( $api_key ) || '' === trim( $api_key ) ) {
            return $this->create_error( 'missing_api_key', 'API key is not configured.', 'configuration' );
        }

        $endpoint = isset( $settings['api_endpoint'] ) && is_string( $settings['api_endpoint'] )
            ? trim( $settings['api_endpoint'] )
            : '';

        if ( '' === $endpoint ) {
            if ( ! isset( $this->provider_endpoints[ $provider ] ) ) {
                return $this->create_error( 'missing_endpoint', 'An API endpoint is required for this provider.', 'configuration' );
            }

            $endpoint = $this->provider_endpoints[ $provider ];
        }

        $endpoint = $this->validate_endpoint( $endpoint );

        if ( false === $endpoint ) {
            return $this->create_error( 'invalid_endpoint', 'The API endpoint is invalid or unavailable.', 'endpoint' );
        }

        $response = wp_safe_remote_post(
            $endpoint,
            array(
                'timeout'             => 30,
                'redirection'         => 0,
                'limit_response_size' => 1048576,
                'headers'             => array(
                    'Authorization' => 'Bearer ' . trim( $api_key ),
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ),
                'body' => wp_json_encode(
                    array(
                        'model'    => $model,
                        'messages' => array(
                            array(
                                'role'    => 'user',
                                'content' => trim( $message ),
                            ),
                        ),
                    )
                ),
            )
        );

        if ( is_wp_error( $response ) ) {
            return $this->create_error(
                'api_connection_error',
                'API connection failed: ' . sanitize_text_field( $response->get_error_message() ),
                'connection'
            );
        }

        $status = wp_remote_retrieve_response_code( $response );
        $body   = wp_remote_retrieve_body( $response );

        if ( $status < 200 || $status >= 300 ) {
            return $this->create_error(
                'api_http_error',
                sprintf(
                    'API returned HTTP %1$d: %2$s',
                    $status,
                    $this->extract_provider_error( $body )
                ),
                'api_request',
                array( 'status' => $status )
            );
        }

        $data = json_decode( $body, true );

        if ( ! is_array( $data ) || JSON_ERROR_NONE !== json_last_error() ) {
            return $this->create_error(
                'invalid_api_json',
                'The API returned invalid JSON.',
                'response',
                array( 'status' => $status )
            );
        }

        if ( isset( $data['error'] ) ) {
            return $this->create_error(
                'api_provider_error',
                $this->extract_provider_error( $body ),
                'api_response',
                array( 'status' => $status )
            );
        }

        $content = isset( $data['choices'][0]['message']['content'] )
            ? $data['choices'][0]['message']['content']
            : '';

        if ( ! is_string( $content ) || '' === trim( $content ) ) {
            return $this->create_error(
                'empty_assistant_response',
                'The API returned an empty assistant response.',
                'response',
                array( 'status' => $status )
            );
        }

        return trim( $content );
    }

    /**
     * Validate endpoint URL.
     *
     * @param string $endpoint Endpoint.
     * @return string|false
     */
    private function validate_endpoint( $endpoint ) {
        if ( ! is_string( $endpoint ) || '' === $endpoint || preg_match( '/[\x00-\x20\x7f\\\\]/', $endpoint ) ) {
            return false;
        }

        $parts = wp_parse_url( $endpoint );

        if (
            ! is_array( $parts ) ||
            empty( $parts['scheme'] ) ||
            'https' !== strtolower( $parts['scheme'] ) ||
            empty( $parts['host'] ) ||
            isset( $parts['user'] ) ||
            isset( $parts['pass'] ) ||
            isset( $parts['query'] ) ||
            isset( $parts['fragment'] )
        ) {
            return false;
        }

        $host = strtolower( rtrim( $parts['host'], '.' ) );

        if (
            'localhost' === $host ||
            substr( $host, -10 ) === '.localhost' ||
            substr( $host, -6 ) === '.local' ||
            substr( $host, -9 ) === '.internal' ||
            false !== filter_var( $host, FILTER_VALIDATE_IP )
        ) {
            return false;
        }

        if ( isset( $parts['port'] ) && 443 !== (int) $parts['port'] ) {
            return false;
        }

        if (
            strlen( $host ) > 253 ||
            false === strpos( $host, '.' ) ||
            ! preg_match( '/^(?=.{1,253}$)(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,63}$/i', $host )
        ) {
            return false;
        }

        return wp_http_validate_url( $endpoint );
    }

    /**
     * Extract provider error.
     *
     * @param string $body Response body.
     * @return string
     */
    private function extract_provider_error( $body ) {
        $data = json_decode( $body, true );

        if ( isset( $data['error']['message'] ) && is_string( $data['error']['message'] ) ) {
            return substr(
                sanitize_text_field( wp_strip_all_tags( $data['error']['message'] ) ),
                0,
                500
            );
        }

        if ( isset( $data['error'] ) && is_string( $data['error'] ) ) {
            return substr( sanitize_text_field( $data['error'] ), 0, 500 );
        }

        return 'The provider returned an unspecified error.';
    }

    /**
     * Create structured error.
     *
     * @param string $code Error code.
     * @param string $message Error message.
     * @param string $stage Error stage.
     * @param array  $data Additional data.
     * @return WP_Error
     */
    private function create_error( $code, $message, $stage, $data = array() ) {
        return new WP_Error(
            $code,
            $message,
            array_merge(
                array( 'stage' => $stage ),
                $data
            )
        );
    }
}