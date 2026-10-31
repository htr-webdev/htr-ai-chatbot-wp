<?php
/**
 * Render the plugin settings page.
 *
 * @package HTR_AI_Chatbot
 */

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

        $defaults = array(
            'api_provider'       => '',
            'api_model'          => '',
            'custom_model'       => '',
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
        );

        $stored_settings = get_option( 'htr_ai_chatbot_settings', array() );

        $settings = wp_parse_args(
            is_array( $stored_settings ) ? $stored_settings : array(),
            $defaults
        );

        $components_path = dirname( __DIR__, 3 ) . '/components/';

        echo '<div class="wrap htr-settings-page">';

        if (
            isset( $_GET['htr_reset'] ) &&
            'success' === sanitize_text_field( wp_unslash( $_GET['htr_reset'] ) )
        ) {
            echo '<div class="notice notice-success is-dismissible"><p>';
            echo esc_html( 'Settings have been reset successfully.' );
            echo '</p></div>';
        }

        if (
            isset( $_GET['htr_saved'] ) &&
            '1' === sanitize_text_field( wp_unslash( $_GET['htr_saved'] ) )
        ) {
            echo '<div class="notice notice-success is-dismissible"><p>';
            echo esc_html( 'Settings saved successfully.' );
            echo '</p></div>';
        }

        require $components_path . 'settings-header.php';

        echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" id="htr-settings-form">';

        echo '<input type="hidden" name="action" value="htr_save_settings">';

        wp_nonce_field( 'htr_save_settings', 'htr_settings_nonce' );
        wp_nonce_field( 'htr_reset_settings', 'htr_reset_settings_nonce' );

        require $components_path . 'settings-tabs.php';

        echo '<div class="htr-settings-panels">';

        $panels = array(
            'api'        => 'api-settings.php',
            'chatbot'    => 'chatbot-settings.php',
            'appearance' => 'appearance-settings.php',
            'behavior'   => 'behavior-settings.php',
            'test'       => 'test-settings.php',
        );

        foreach ( $panels as $panel => $file ) {
            $active = 'api' === $panel;

            printf(
                '<section id="htr-panel-%1$s" class="htr-settings-panel%2$s" role="tabpanel" aria-labelledby="htr-tab-%1$s" data-panel="%1$s"%3$s>',
                esc_attr( $panel ),
                $active ? ' is-active' : '',
                $active ? '' : ' hidden'
            );

            require $components_path . $file;

            echo '</section>';
        }

        echo '</div>';

        require $components_path . 'settings-actions.php';

        echo '</form>';
        echo '</div>';
    }
}