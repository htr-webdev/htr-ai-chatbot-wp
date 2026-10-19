<?php
/**
 * Load admin settings assets.
 *
 * @package HTR_AI_Chatbot
 */

// Prevent direct access.
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
     * Load admin assets only on the plugin settings page.
     *
     * @param string $hook Current admin page hook.
     * @return void
     */
    public function enqueue_admin_assets( $hook ) {
        if ( 'toplevel_page_htr-ai-chatbot' !== $hook ) {
            return;
        }

        $plugin_file = dirname( __DIR__, 3 ) . '/htr-ai-chatbot.php';

        wp_enqueue_style(
            'htr-ai-chatbot-settings',
            plugin_dir_url( $plugin_file ) . 'assets/css/settings.css',
            array(),
            '0.1.0'
        );

        wp_enqueue_script(
            'htr-ai-chatbot-settings',
            plugin_dir_url( $plugin_file ) . 'assets/js/settings.js',
            array(),
            '0.1.0',
            true
        );
    }
}
