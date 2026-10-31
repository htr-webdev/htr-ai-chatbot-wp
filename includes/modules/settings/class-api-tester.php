<?php
/**
 * Handle API connection tests.
 *
 * @package HTR_AI_Chatbot
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class HTR_AI_Chatbot_API_Tester
 */
class HTR_AI_Chatbot_API_Tester {

    /**
     * Register AJAX action.
     */
    public function __construct() {
        add_action(
            'wp_ajax_htr_test_api_connection',
            array( $this, 'test_connection' )
        );
    }

    /**
     * Test the configured API connection.
     *
     * @return void
     */
    public function test_connection() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error(
                array( 'message' => 'Permission denied.' ),
                403
            );
        }

        check_ajax_referer(
            'htr_test_api_connection',
            'nonce'
        );

        $settings = get_option( 'htr_ai_chatbot_settings', array() );

        if ( ! is_array( $settings ) ) {
            wp_send_json_error(
                array( 'message' => 'Invalid plugin settings.' ),
                400
            );
        }

        $provider = isset( $settings['api_provider'] )
            ? sanitize_key( $settings['api_provider'] )
            : '';

        $model = isset( $settings['api_model'] ) &&
            'custom' === $settings['api_model'] &&
            isset( $settings['custom_model'] )
                ? sanitize_text_field( $settings['custom_model'] )
                : '';

        $endpoint = isset( $settings['api_endpoint'] )
            ? esc_url_raw( $settings['api_endpoint'] )
            : '';

        $started_at = microtime( true );

        $capture = array(
            'http_status'    => null,
            'response_model' => null,
            'response_id'    => null,
            'transport_error' => null,
        );

        $capture_response = function ( $response, $args, $url ) use ( &$capture ) {
            if ( is_wp_error( $response ) ) {
                $capture['transport_error'] = sanitize_text_field(
                    $response->get_error_message()
                );

                return $response;
            }

            $capture['http_status'] = wp_remote_retrieve_response_code( $response );

            $body = wp_remote_retrieve_body( $response );
            $decoded = json_decode( $body, true );

            if ( is_array( $decoded ) ) {
                $capture['response_model'] = isset( $decoded['model'] )
                    ? sanitize_text_field( $decoded['model'] )
                    : null;

                $capture['response_id'] = isset( $decoded['id'] )
                    ? sanitize_text_field( $decoded['id'] )
                    : null;
            }

            return $response;
        };

        add_filter( 'http_response', $capture_response, 10, 3 );

        $handler = new HTR_AI_Chatbot_API_Handler();

        $result = $handler->send_message(
            'Reply with exactly: API connection successful.'
        );

        remove_filter( 'http_response', $capture_response, 10 );

        $duration = round(
            ( microtime( true ) - $started_at ) * 1000
        );

        $log = array(
            'timestamp'       => current_time( 'mysql' ),
            'provider'        => $provider ? $provider : 'Not configured',
            'requested_model' => $model ? $model : 'Not configured',
            'endpoint'        => $endpoint ? $endpoint : 'Provider default',
            'http_status'     => $capture['http_status'],
            'response_model'  => $capture['response_model'],
            'response_id'     => $capture['response_id'],
            'duration_ms'     => $duration,
            'result'          => is_wp_error( $result ) ? 'Failed' : 'Success',
        );

        if ( is_wp_error( $result ) ) {
            $error_data = $result->get_error_data();

            $log['error_code'] = $result->get_error_code();
            $log['error_message'] = sanitize_textarea_field(
                $result->get_error_message()
            );

            $log['error_stage'] = (
                is_array( $error_data ) &&
                isset( $error_data['stage'] )
            )
                ? sanitize_text_field( $error_data['stage'] )
                : 'unknown';

            if ( isset( $capture['transport_error'] ) ) {
                $log['transport_error'] = $capture['transport_error'];
            }

            wp_send_json_error(
                array(
                    'message' => $result->get_error_message(),
                    'log'     => $log,
                ),
                200
            );
        }

        $log['assistant_response'] = sanitize_textarea_field( $result );

        wp_send_json_success(
            array(
                'message' => 'API connection successful.',
                'log'     => $log,
            )
        );
    }
}