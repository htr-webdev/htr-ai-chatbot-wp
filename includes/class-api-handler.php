<?php
/**
 * Handles communication with the AI API.
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
     * Send a message to an OpenAI-compatible API.
     *
     * @param string $message The user's message.
     * @return string The generated response or an empty string on failure.
     */
    public function send_message( $message ) {
        if ( ! is_string( $message ) ) {
            return '';
        }

        $message = trim( $message );

        if ( '' === $message ) {
            return '';
        }

        $settings = get_option( 'htr_ai_chatbot_settings', array() );

        if ( ! is_array( $settings ) ) {
            return '';
        }

        $message_limit = isset( $settings['message_limit'] )
            ? absint( $settings['message_limit'] )
            : 2000;

        $message_limit = min( 10000, max( 100, $message_limit ) );

        if ( strlen( $message ) > $message_limit ) {
            return '';
        }

        $api_key = get_option( 'htr_ai_chatbot_api_key', '' );

        if ( ! is_string( $api_key ) || '' === trim( $api_key ) ) {
            return '';
        }

        $provider = isset( $settings['api_provider'] )
            ? $settings['api_provider']
            : 'openai';

        if ( 'openai' !== $provider ) {
            return '';
        }

        $model = isset( $settings['api_model'] )
            ? sanitize_text_field( $settings['api_model'] )
            : 'gpt-4o-mini';

        if ( '' === $model ) {
            return '';
        }

        $endpoint = isset( $settings['api_endpoint'] )
            && is_string( $settings['api_endpoint'] )
                ? trim( $settings['api_endpoint'] )
                : '';

        if ( '' === $endpoint ) {
            $endpoint = 'https://api.openai.com/v1/chat/completions';
        }

        $endpoint = $this->validate_endpoint( $endpoint );

        if ( false === $endpoint ) {
            return '';
        }

        $request_body = array(
            'model'    => $model,
            'messages' => array(
                array(
                    'role'    => 'user',
                    'content' => $message,
                ),
            ),
        );

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

        if ( is_wp_error( $response ) ) {
            return '';
        }

        if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
            return '';
        }

        $body = wp_remote_retrieve_body( $response );

        if ( ! is_string( $body ) || '' === $body ) {
            return '';
        }

        $data = json_decode( $body, true );

        if (
            ! is_array( $data ) ||
            ! isset( $data['choices'][0]['message']['content'] ) ||
            ! is_string( $data['choices'][0]['message']['content'] )
        ) {
            return '';
        }

        return trim( $data['choices'][0]['message']['content'] );
    }

    /**
     * Validate an HTTPS endpoint before making an outbound request.
     *
     * WordPress performs additional destination validation when the
     * request is sent through wp_safe_remote_post().
     *
     * @param string $endpoint The configured API endpoint.
     * @return string|false Valid endpoint or false.
     */
    private function validate_endpoint( $endpoint ) {
        if (
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

        $host = strtolower( rtrim( $parts['host'], '.' ) );

        if (
            '' === $host ||
            'localhost' === $host ||
            substr( $host, -10 ) === '.localhost' ||
            substr( $host, -6 ) === '.local' ||
            substr( $host, -6 ) === '.internal'
        ) {
            return false;
        }

        // Require a public DNS hostname rather than an IP literal.
        if ( false !== filter_var( $host, FILTER_VALIDATE_IP ) ) {
            return false;
        }

        // Only HTTPS's standard port is allowed.
        if ( isset( $parts['port'] ) && 443 !== (int) $parts['port'] ) {
            return false;
        }

        // Reject malformed hostnames and single-label hostnames.
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

        $validated_url = wp_http_validate_url( $endpoint );

        if ( false === $validated_url ) {
            return false;
        }

        return $validated_url;
    }
}
