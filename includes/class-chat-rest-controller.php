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
     * Maximum accepted message length in bytes.
     *
     * @var int
     */
    private $max_message_length = 10000;

    /**
     * Register the REST API hooks.
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
     * Validate and process a chat message.
     *
     * @param WP_REST_Request $request REST API request.
     * @return WP_REST_Response|WP_Error
     */
    public function handle_message( $request ) {
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

        if ( ! is_array( $params ) || ! isset( $params['message'] ) || ! is_string( $params['message'] ) ) {
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

        if ( strlen( $message ) > $this->max_message_length ) {
            return new WP_Error(
                'htr_chat_message_too_long',
                __( 'Your message is too long. Please shorten it and try again.', 'htr-ai-chatbot' ),
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
     * Check whether the client can send another request.
     *
     * @return bool
     */
    private function is_rate_limit_allowed() {
        $client_ip = isset( $_SERVER['REMOTE_ADDR'] )
            ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) )
            : '';

        if ( '' === $client_ip || ! filter_var( $client_ip, FILTER_VALIDATE_IP ) ) {
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
