<?php
/**
 * Register the plugin settings menu.
 *
 * @package HTR_AI_Chatbot
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class HTR_AI_Chatbot_Settings_Menu
 */
class HTR_AI_Chatbot_Settings_Menu {

    /**
     * Register the settings menu.
     *
     * @return void
     */
    public function register() {
        add_menu_page(
            'Chatbot Settings',
            'AI Chatbot',
            'manage_options',
            'htr-ai-chatbot',
            array( $this, 'render_page' ),
            'dashicons-format-chat'
        );
    }

    /**
     * Render the settings page.
     *
     * @return void
     */
    public function render_page() {
        $settings_page = new HTR_AI_Chatbot_Settings_Page();
        $settings_page->render();
    }
}
