<?php
/**
 * Render the plugin settings page.
 *
 * @package HTR_AI_Chatbot
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class HTR_AI_Chatbot_Settings_Page
 */
class HTR_AI_Chatbot_Settings_Page {

    /**
     * Render the settings page.
     *
     * @return void
     */
    public function render() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }


$settings = wp_parse_args(
    get_option( 'htr_ai_chatbot_settings', array() ),
    array(
        'api_provider'       => 'openai',
        'api_model'          => 'gpt-4o-mini',
        'api_endpoint'       => '',
        'chatbot_name'       => '',
        'welcome_message'    => '',
        'input_placeholder'  => '',
        'fallback_message'   => '',
        'primary_color'      => '#2563EB',
        'chatbot_position'   => 'bottom-right',
        'chatbot_width'      => 380,
        'chatbot_height'     => 550,
        'header_style'       => 'solid',
        'chatbot_enabled'    => 1,
        'auto_open'          => 0,
        'auto_open_delay'    => 5,
        'show_close_button'  => 1,
        'message_limit'      => 2000,
    )
);

        
        $components_path = dirname( __DIR__, 3 ) . '/components/';

        echo '<div class="wrap htr-settings-page">';

        require $components_path . 'settings-header.php';

        echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" id="htr-settings-form">';

        echo '<input type="hidden" name="action" value="htr_save_settings">';

        wp_nonce_field( 'htr_save_settings', 'htr_settings_nonce' );

        require $components_path . 'settings-tabs.php';

        echo '<div class="htr-settings-panels">';

        echo '<section
            id="htr-panel-api"
            class="htr-settings-panel is-active"
            role="tabpanel"
            aria-labelledby="htr-tab-api"
            data-panel="api"
        >';
        require $components_path . 'api-settings.php';
        echo '</section>';

        echo '<section
            id="htr-panel-chatbot"
            class="htr-settings-panel"
            role="tabpanel"
            aria-labelledby="htr-tab-chatbot"
            data-panel="chatbot"
            hidden
        >';
        require $components_path . 'chatbot-settings.php';
        echo '</section>';

        echo '<section
            id="htr-panel-appearance"
            class="htr-settings-panel"
            role="tabpanel"
            aria-labelledby="htr-tab-appearance"
            data-panel="appearance"
            hidden
        >';
        require $components_path . 'appearance-settings.php';
        echo '</section>';

        echo '<section
            id="htr-panel-behavior"
            class="htr-settings-panel"
            role="tabpanel"
            aria-labelledby="htr-tab-behavior"
            data-panel="behavior"
            hidden
        >';
        require $components_path . 'behavior-settings.php';
        echo '</section>';

        echo '</div>';

        require $components_path . 'settings-actions.php';

        echo '</form>';

        echo '</div>';
    }
}
