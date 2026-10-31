<?php
/**
 * Load admin settings assets.
 *
 * @package HTR_AI_Chatbot
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class HTR_AI_Chatbot_Settings_Assets
 */
class HTR_AI_Chatbot_Settings_Assets {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action(
            'admin_enqueue_scripts',
            array( $this, 'enqueue_admin_assets' )
        );
    }

    /**
     * Enqueue settings assets.
     *
     * @param string $hook Current admin page hook.
     * @return void
     */
    public function enqueue_admin_assets( $hook ) {
        if ( 'toplevel_page_htr-ai-chatbot' !== $hook ) {
            return;
        }

        wp_enqueue_style(
            'htr-ai-chatbot-settings',
            HTR_AI_CHATBOT_URL . 'assets/css/settings.css',
            array(),
            HTR_AI_CHATBOT_VERSION
        );

        wp_enqueue_style(
            'htr-ai-chatbot-widget-logs',
            HTR_AI_CHATBOT_URL . 'assets/css/widget-logs.css',
            array( 'htr-ai-chatbot-settings' ),
            HTR_AI_CHATBOT_VERSION
        );

        wp_enqueue_script(
            'htr-ai-chatbot-settings',
            HTR_AI_CHATBOT_URL . 'assets/js/settings.js',
            array(),
            HTR_AI_CHATBOT_VERSION,
            true
        );

        wp_localize_script(
            'htr-ai-chatbot-settings',
            'htrAdminSettings',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'htr_test_api_connection' ),
            )
        );
    }
}