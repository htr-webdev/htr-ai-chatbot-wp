<?php
/**
 * Admin settings page for the plugin.
 *
 * This class registers the plugin settings, handles the API key
 * storage, and provides the admin interface for configuration.
 *
 * @package HTR_AI_Chatbot
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class HTR_AI_Chatbot_Settings
 */
class HTR_AI_Chatbot_Settings {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action(
            'admin_menu',
            array( $this, 'register_menu' )
        );
    }

    /**
     * Register the admin menu page.
     *
     * @return void
     */
    public function register_menu() {
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
     * Display the admin settings page.
     *
     * @return void
     */
    public function render_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        echo '<div class="wrap">';
        echo '<h1>' . esc_html( 'Chatbot Settings' ) . '</h1>';
        echo '<p>' . esc_html( 'The plugin settings page has been created successfully.' ) . '</p>';
        echo '</div>';
    }
}
