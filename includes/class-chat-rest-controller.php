<?php
/**
 * REST API controller for chat messages.
 *
 * @package HTR_AI_Chatbot
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Handles public chat REST API requests.
 */
class HTR_AI_Chatbot_Chat_REST_Controller {

    /**
     * REST API namespace.
     *
     * @var string
     */
    private $namespace = 'htr-ai-chatbot/v1';

    /**
     * REST API route.
     *
     * @var string
     */
    private $route = '/chat';

    /**
     * Maximum requests per client within the rate limit window.
     *
     * @var int
     */
    private $rate_limit = 5;

    /**
     * Rate limit window in seconds.
     *
     * @var int
     */
    private $rate_window = 60;

    /**
     * Maximum accepted request body size in bytes.
     *
     * @var int
     */
    private $max_body_size = 16384;

    /**
     * Register REST API hooks.
     */
    public function __construct() {
        add_action(
            'rest_api_init',
            array( $this, 'register_routes' )
        );
    }

    /**
     * Register the chat endpoint.
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            $this->route,
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( $this, 'handle_message' ),
                'permission_callback' => '__return_true',
            )
        );
    }

    /**
     * Validate, process, and log a chat request.
     *
     * @param WP_REST_Request $request REST API request.
     * @return WP_REST_Response|WP_Error
     */
    public function handle_message( $request ) {
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
            $data = json_decode( $body, true );

            if ( is_array( $data ) ) {
                $capture['response_model'] = isset( $data['model'] )
                    ? sanitize_text_field( $data['model'] )
                    : null;

                $capture['response_id'] = isset( $data['id'] )
                    ? sanitize_text_field( $data['id'] )
                    : null;
            }

            return $response;
        };

        add_filter( 'http_response', $capture_response, 10, 3 );

        $result = $this->process_message( $request );

        remove_filter( 'http_response', $capture_response, 10 );

        $duration = round(
            ( microtime( true ) - $started_at ) * 1000
        );

        $settings = get_option( 'htr_ai_chatbot_settings', array() );

        if ( ! is_array( $settings ) ) {
            $settings = array();
        }

        $provider = isset( $settings['api_provider'] )
            ? sanitize_key( $settings['api_provider'] )
            : '';

        $model = isset( $settings['custom_model'] )
            ? sanitize_text_field( $settings['custom_model'] )
            : '';

        $endpoint = isset( $settings['api_endpoint'] )
            ? esc_url_raw( $settings['api_endpoint'] )
            : '';

        if ( '' === $endpoint ) {
            $endpoint = 'Provider default';
        }

        $params = $request->get_json_params();

        $message = (
            is_array( $params ) &&
            isset( $params['message'] ) &&
            is_string( $params['message'] )
        )
            ? sanitize_textarea_field( $params['message'] )
            : '';

        $log = array(
            'timestamp'       => current_time( 'mysql' ),
            'source'          => 'frontend_widget',
            'provider'        => $provider ? $provider : 'Not configured',
            'requested_model' => $model ? $model : 'Not configured',
            'endpoint'        => $endpoint,
            'http_status'     => $capture['http_status'],
            'response_model'  => $capture['response_model'],
            'response_id'     => $capture['response_id'],
            'duration_ms'     => $duration,
            'result'          => is_wp_error( $result ) ? 'Failed' : 'Success',
            'user_message'    => substr( $message, 0, 2000 ),
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
                : 'request';

            if ( isset( $capture['transport_error'] ) ) {
                $log['transport_error'] = $capture['transport_error'];
            }
        } else {
            $response_data = $result->get_data();

            $log['assistant_response'] = (
                is_array( $response_data ) &&
                isset( $response_data['message'] ) &&
                is_string( $response_data['message'] )
            )
                ? substr(
                    sanitize_textarea_field( $response_data['message'] ),
                    0,
                    4000
                )
                : '';
        }

        HTR_AI_Chatbot_Widget_Logger::add_log( $log );

        return $result;
    }

    /**
     * Process and validate a chat message.
     *
     * @param WP_REST_Request $request REST API request.
     * @return WP_REST_Response|WP_Error
     */
    private function process_message( $request ) {
        $content_length = $request->get_header( 'content-length' );

        if (
            is_string( $content_length ) &&
            '' !== $content_length &&
            ctype_digit( $content_length ) &&
            (int) $content_length > $this->max_body_size
        ) {
            return new WP_Error(
                'htr_chat_request_too_large',
                __( 'The request is too large.', 'htr-ai-chatbot' ),
                array( 'status' => 413 )
            );
        }

        $raw_body = $request->get_body();

        if ( strlen( $raw_body ) > $this->max_body_size ) {
            return new WP_Error(
                'htr_chat_request_too_large',
                __( 'The request is too large.', 'htr-ai-chatbot' ),
                array( 'status' => 413 )
            );
        }

        $params = $request->get_json_params();

        if (
            ! is_array( $params ) ||
            ! isset( $params['message'] ) ||
            ! is_string( $params['message'] )
        ) {
            return new WP_Error(
                'htr_chat_invalid_message',
                __( 'Please provide a valid message.', 'htr-ai-chatbot' ),
                array( 'status' => 400 )
            );
        }

        $message = trim( $params['message'] );

        if ( '' === $message ) {
            return new WP_Error(
                'htr_chat_empty_message',
                __( 'Please enter a message.', 'htr-ai-chatbot' ),
                array( 'status' => 400 )
            );
        }

        $settings = get_option( 'htr_ai_chatbot_settings', array() );

        $message_limit = (
            is_array( $settings ) &&
            isset( $settings['message_limit'] )
        )
            ? absint( $settings['message_limit'] )
            : 100;

        $message_limit = min( 10000, max( 100, $message_limit ) );

        if ( strlen( $message ) > $message_limit ) {
            return new WP_Error(
                'htr_chat_message_too_long',
                __( 'Your message exceeds the configured length limit.', 'htr-ai-chatbot' ),
                array( 'status' => 400 )
            );
        }

        $message = sanitize_textarea_field( $message );

        if ( '' === trim( $message ) ) {
            return new WP_Error(
                'htr_chat_invalid_message',
                __( 'Please enter a valid message.', 'htr-ai-chatbot' ),
                array( 'status' => 400 )
            );
        }

        if ( ! $this->is_rate_limit_allowed() ) {
            return new WP_Error(
                'htr_chat_rate_limit',
                __( 'Too many messages. Please wait a moment and try again.', 'htr-ai-chatbot' ),
                array( 'status' => 429 )
            );
        }

        if ( ! class_exists( 'HTR_AI_Chatbot_API_Handler' ) ) {
            return $this->service_unavailable();
        }

        $api_handler = new HTR_AI_Chatbot_API_Handler();
        $reply       = $api_handler->send_message( $message );

        if ( is_wp_error( $reply ) ) {
            return $this->handle_api_error( $reply );
        }

        if ( ! is_string( $reply ) || '' === trim( $reply ) ) {
            return $this->service_unavailable();
        }

        return new WP_REST_Response(
            array(
                'success' => true,
                'message' => $reply,
            ),
            200
        );
    }

    /**
     * Handle an API error without exposing sensitive information.
     *
     * @param WP_Error $error API error.
     * @return WP_Error
     */
    private function handle_api_error( $error ) {
        if ( current_user_can( 'manage_options' ) ) {
            $error_message = $error->get_error_message();
            $error_code    = $error->get_error_code();
            $error_data    = $error->get_error_data();

            $status = 502;

            if (
                is_array( $error_data ) &&
                isset( $error_data['status'] ) &&
                is_numeric( $error_data['status'] )
            ) {
                $status = absint( $error_data['status'] );
            }

            if ( $status < 400 || $status > 599 ) {
                $status = 502;
            }

            return new WP_Error(
                'htr_chat_api_error',
                sprintf(
                    /* translators: %1$s: API error code, %2$s: API error message. */
                    __( 'API Error (%1$s): %2$s', 'htr-ai-chatbot' ),
                    sanitize_text_field( $error_code ),
                    sanitize_text_field( $error_message )
                ),
                array(
                    'status' => $status,
                )
            );
        }

        return $this->service_unavailable();
    }

    /**
     * Check whether the client can send another request.
     *
     * @return bool
     */
    private function is_rate_limit_allowed() {
        $client_ip = isset( $_SERVER['REMOTE_ADDR'] )
            ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) )
            : '';

        if (
            '' === $client_ip ||
            false === filter_var( $client_ip, FILTER_VALIDATE_IP )
        ) {
            return false;
        }

        $transient_key = 'htr_chat_rate_' . hash_hmac(
            'sha256',
            $client_ip,
            wp_salt( 'auth' )
        );

        $request_count = get_transient( $transient_key );

        if ( false === $request_count ) {
            set_transient(
                $transient_key,
                1,
                $this->rate_window
            );

            return true;
        }

        if ( (int) $request_count >= $this->rate_limit ) {
            return false;
        }

        set_transient(
            $transient_key,
            (int) $request_count + 1,
            $this->rate_window
        );

        return true;
    }

    /**
     * Return a generic service error.
     *
     * @return WP_Error
     */
    private function service_unavailable() {
        return new WP_Error(
            'htr_chat_service_unavailable',
            __( 'Sorry, the assistant is temporarily unavailable. Please try again later.', 'htr-ai-chatbot' ),
            array( 'status' => 502 )
        );
    }
}