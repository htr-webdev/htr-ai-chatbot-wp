<?php
/**
 * Store and retrieve frontend widget logs.
 *
 * @package HTR_AI_Chatbot
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class HTR_AI_Chatbot_Widget_Logger
 */
class HTR_AI_Chatbot_Widget_Logger {

    /**
     * WordPress option used for widget logs.
     *
     * @var string
     */
    const OPTION_NAME = 'htr_ai_chatbot_widget_logs';

    /**
     * Maximum number of stored logs.
     *
     * @var int
     */
    const MAX_LOGS = 50;

    /**
     * Register AJAX actions.
     */
    public function __construct() {
        add_action(
            'wp_ajax_htr_fetch_widget_logs',
            array( $this, 'fetch_logs' )
        );
    }

    /**
     * Store a widget request log.
     *
     * @param array $log Log data.
     * @return void
     */
    public static function add_log( $log ) {
        if ( ! is_array( $log ) ) {
            return;
        }

        $logs = get_option( self::OPTION_NAME, array() );

        if ( ! is_array( $logs ) ) {
            $logs = array();
        }

        array_unshift( $logs, $log );

        $logs = array_slice( $logs, 0, self::MAX_LOGS );

        if ( false === get_option( self::OPTION_NAME, false ) ) {
            add_option( self::OPTION_NAME, $logs, '', false );
        } else {
            update_option( self::OPTION_NAME, $logs, false );
        }
    }

    /**
     * Retrieve stored widget logs.
     *
     * @return array
     */
    public static function get_logs() {
        $logs = get_option( self::OPTION_NAME, array() );

        return is_array( $logs ) ? $logs : array();
    }

    /**
     * Return widget logs to an authorized administrator.
     *
     * @return void
     */
    public function fetch_logs() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error(
                array(
                    'message' => 'Permission denied.',
                ),
                403
            );
        }

        check_ajax_referer(
            'htr_test_api_connection',
            'nonce'
        );

        wp_send_json_success(
            array(
                'logs' => self::get_logs(),
            )
        );
    }
}