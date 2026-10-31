<?php
/**
 * Handles communication with AI providers.
 *
 * @package HTR_AI_Chatbot
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class HTR_AI_Chatbot_API_Handler
 */
class HTR_AI_Chatbot_API_Handler {

    /**
     * Default provider endpoints.
     *
     * @var array
     */
    private $provider_endpoints = array(
        'openai' => 'https://api.openai.com/v1/chat/completions',
        'avalai' => 'https://api.avalai.ir/v1/chat/completions',
    );

    /**
     * Send a message to an AI provider.
     *
     * @param string $message The user's message.
     * @return string|WP_Error Assistant response or error.
     */
    public function send_message( $message ) {

        // Validate the message.
        if ( ! is_string( $message ) ) {
            return $this->create_error(
                'invalid_message',
                'The message must be a string.',
                'validation'
            );
        }

        $message = trim( $message );

        if ( '' === $message ) {
            return $this->create_error(
                'empty_message',
                'The message cannot be empty.',
                'validation'
            );
        }

        // Retrieve plugin settings.
        $settings = get_option(
            'htr_ai_chatbot_settings',
            array()
        );

        if ( ! is_array( $settings ) ) {
            return $this->create_error(
                'invalid_settings',
                'Plugin settings could not be loaded.',
                'settings'
            );
        }

        // Validate message length.
        $message_limit = isset( $settings['message_limit'] )
            ? absint( $settings['message_limit'] )
            : 2000;

        $message_limit = min(
            10000,
            max( 100, $message_limit )
        );

        if ( strlen( $message ) > $message_limit ) {
            return $this->create_error(
                'message_too_long',
                'The message exceeds the configured length limit.',
                'validation'
            );
        }

        // Retrieve the API key.
        $api_key = get_option(
            'htr_ai_chatbot_api_key',
            ''
        );

        if (
            ! is_string( $api_key ) ||
            '' === trim( $api_key )
        ) {
            return $this->create_error(
                'missing_api_key',
                'API key is missing. Please configure your API key in the plugin settings.',
                'configuration'
            );
        }

        // Resolve the selected provider.
        $provider = isset( $settings['api_provider'] )
            && is_string( $settings['api_provider'] )
                ? sanitize_key( $settings['api_provider'] )
                : 'avalai';

        $supported_providers = array(
            'openai',
            'avalai',
            'custom',
        );

        if ( ! in_array( $provider, $supported_providers, true ) ) {
            return $this->create_error(
                'unsupported_provider',
                'The selected API provider is not supported.',
                'configuration'
            );
        }

        // Resolve the selected model.
        $model = $this->get_model( $settings );

        if ( false === $model ) {
            return $this->create_error(
                'invalid_model',
                'The selected model ID is missing or invalid.',
                'configuration'
            );
        }

        // Resolve and validate the API endpoint.
        $endpoint = $this->get_endpoint(
            $provider,
            $settings
        );

        if ( false === $endpoint ) {
            return $this->create_error(
                'invalid_endpoint',
                'The API endpoint is invalid or unavailable. Check the endpoint URL in plugin settings.',
                'endpoint'
            );
        }

        // Prepare the API request.
        $request_body = array(
            'model'    => $model,
            'messages' => array(
                array(
                    'role'    => 'user',
                    'content' => $message,
                ),
            ),
        );

        // Send the API request.
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
                'body'                => wp_json_encode( $request_body ),
            )
        );

        // Handle connection errors.
        if ( is_wp_error( $response ) ) {
            $connection_error = $response->get_error_message();

            return $this->create_error(
                'api_connection_error',
                'API connection failed: ' . $connection_error,
                'connection'
            );
        }

        // Retrieve HTTP response information.
        $status_code = wp_remote_retrieve_response_code( $response );
        $body        = wp_remote_retrieve_body( $response );

        // Handle unsuccessful HTTP responses.
        if ( $status_code < 200 || $status_code >= 300 ) {
            $provider_message = $this->extract_provider_error( $body );

            return $this->create_error(
                'api_http_error',
                sprintf(
                    'API returned HTTP %1$d: %2$s',
                    $status_code,
                    $provider_message
                ),
                'api_request',
                array(
                    'status_code' => $status_code,
                )
            );
        }

        // Validate response body.
        if (
            ! is_string( $body ) ||
            '' === trim( $body )
        ) {
            return $this->create_error(
                'empty_api_response',
                'The API returned an empty response body.',
                'response',
                array(
                    'status_code' => $status_code,
                )
            );
        }

        // Decode the JSON response.
        $data = json_decode( $body, true );

        if (
            JSON_ERROR_NONE !== json_last_error() ||
            ! is_array( $data )
        ) {
            return $this->create_error(
                'invalid_api_json',
                'The API returned invalid JSON: ' . json_last_error_msg(),
                'response',
                array(
                    'status_code' => $status_code,
                )
            );
        }

        // Handle provider errors inside a successful HTTP response.
        if ( isset( $data['error'] ) ) {
            return $this->create_error(
                'api_provider_error',
                'API error: ' . $this->extract_provider_error( $body ),
                'api_response',
                array(
                    'status_code' => $status_code,
                )
            );
        }

        // Extract the assistant response.
        if (
            ! isset( $data['choices'][0]['message']['content'] ) ||
            ! is_string( $data['choices'][0]['message']['content'] )
        ) {
            return $this->create_error(
                'unexpected_api_response',
                'The API response has an unexpected structure.',
                'response',
                array(
                    'status_code' => $status_code,
                )
            );
        }

        $assistant_message = trim(
            $data['choices'][0]['message']['content']
        );

        if ( '' === $assistant_message ) {
            return $this->create_error(
                'empty_assistant_response',
                'The API returned an empty assistant message.',
                'response',
                array(
                    'status_code' => $status_code,
                )
            );
        }

        return $assistant_message;
    }

    /**
     * Resolve the model ID.
     *
     * @param array $settings Plugin settings.
     * @return string|false Model ID or false.
     */
    private function get_model( $settings ) {

        $selected_model = isset( $settings['api_model'] )
            && is_string( $settings['api_model'] )
                ? sanitize_text_field( $settings['api_model'] )
                : 'gpt-4o-mini';

        if ( 'gpt-4o-mini' === $selected_model ) {
            return 'gpt-4o-mini';
        }

        if ( 'custom' !== $selected_model ) {
            return false;
        }

        if (
            ! isset( $settings['custom_model'] ) ||
            ! is_string( $settings['custom_model'] )
        ) {
            return false;
        }

        $custom_model = trim(
            sanitize_text_field( $settings['custom_model'] )
        );

        if (
            '' === $custom_model ||
            strlen( $custom_model ) > 200
        ) {
            return false;
        }

        return $custom_model;
    }

    /**
     * Resolve and validate the API endpoint.
     *
     * @param string $provider Selected provider.
     * @param array  $settings Plugin settings.
     * @return string|false Valid endpoint or false.
     */
    private function get_endpoint( $provider, $settings ) {

        $endpoint = isset( $settings['api_endpoint'] )
            && is_string( $settings['api_endpoint'] )
                ? trim( $settings['api_endpoint'] )
                : '';

        // Use the provider's default endpoint when available.
        if ( '' === $endpoint ) {
            if ( ! isset( $this->provider_endpoints[ $provider ] ) ) {
                return false;
            }

            $endpoint = $this->provider_endpoints[ $provider ];
        }

        return $this->validate_endpoint( $endpoint );
    }

    /**
     * Validate an HTTPS endpoint before making an outbound request.
     *
     * @param string $endpoint The configured API endpoint.
     * @return string|false Valid endpoint or false.
     */
    private function validate_endpoint( $endpoint ) {

        if (
            ! is_string( $endpoint ) ||
            '' === $endpoint ||
            preg_match( '/[\x00-\x20\x7f\\\\]/', $endpoint )
        ) {
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

        $host = strtolower(
            rtrim( $parts['host'], '.' )
        );

        // Reject local and internal hostnames.
        if (
            '' === $host ||
            'localhost' === $host ||
            substr( $host, -10 ) === '.localhost' ||
            substr( $host, -6 ) === '.local' ||
            substr( $host, -9 ) === '.internal'
        ) {
            return false;
        }

        // Reject IP address literals.
        if ( false !== filter_var( $host, FILTER_VALIDATE_IP ) ) {
            return false;
        }

        // Only allow the standard HTTPS port.
        if (
            isset( $parts['port'] ) &&
            443 !== (int) $parts['port']
        ) {
            return false;
        }

        // Validate hostname structure.
        if (
            strlen( $host ) > 253 ||
            false === strpos( $host, '.' ) ||
            ! preg_match(
                '/^(?=.{1,253}$)(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,63}$/i',
                $host
            )
        ) {
            return false;
        }

        // Apply WordPress URL validation.
        $validated_url = wp_http_validate_url( $endpoint );

        if ( false === $validated_url ) {
            return false;
        }

        return $validated_url;
    }

    /**
     * Extract an error message from an API response.
     *
     * @param string $body API response body.
     * @return string Error description.
     */
    private function extract_provider_error( $body ) {

        if ( ! is_string( $body ) || '' === trim( $body ) ) {
            return 'The provider returned an empty error response.';
        }

        $data = json_decode( $body, true );

        if ( ! is_array( $data ) || ! isset( $data['error'] ) ) {
            return 'The provider did not return a readable error message.';
        }

        $error = $data['error'];
        $message = '';

        if ( is_string( $error ) ) {
            $message = $error;
        } elseif (
            is_array( $error ) &&
            isset( $error['message'] ) &&
            is_string( $error['message'] )
        ) {
            $message = $error['message'];
        }

        if ( '' === trim( $message ) ) {
            return 'The provider returned an unspecified error.';
        }

        // Remove HTML and sanitize the error message.
        $message = wp_strip_all_tags( $message );
        $message = sanitize_text_field( $message );

        // Prevent excessively long error messages.
        if ( strlen( $message ) > 500 ) {
            $message = substr( $message, 0, 500 ) . '...';
        }

        return $message;
    }

    /**
     * Create a structured API error.
     *
     * @param string $code    Error code.
     * @param string $message Error message.
     * @param string $stage   Error stage.
     * @param array  $data    Additional error data.
     * @return WP_Error
     */
    private function create_error( $code, $message, $stage, $data = array() ) {

        $error_data = array_merge(
            array(
                'stage' => $stage,
            ),
            $data
        );

        return new WP_Error(
            $code,
            $message,
            $error_data
        );
    }
}
