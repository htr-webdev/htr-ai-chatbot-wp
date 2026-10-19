<?php
/**
 * Admin settings page for the plugin.
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
        $settings_menu = new HTR_AI_Chatbot_Settings_Menu();

        add_action(
            'admin_menu',
            array( $settings_menu, 'register' )
        );

        new HTR_AI_Chatbot_Settings_Assets();

        $settings_saver = new HTR_AI_Chatbot_Settings_Saver();

        add_action(
            'admin_post_htr_save_settings',
            array( $settings_saver, 'save' )
        );
    }
}
