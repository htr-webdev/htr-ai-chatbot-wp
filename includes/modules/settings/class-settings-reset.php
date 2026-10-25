<?php
/**
 * Handle resetting plugin settings.
 *
 * @package HTR_AI_Chatbot
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class HTR_AI_Chatbot_Settings_Reset
 */
class HTR_AI_Chatbot_Settings_Reset {

    /**
     * Reset plugin settings to their defaults.
     *
     * @return void
     */
    public function reset() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die(
                esc_html( 'You are not allowed to reset these settings.' )
            );
        }

        check_admin_referer(
            'htr_reset_settings',
            'htr_reset_settings_nonce'
        );

        $defaults = array(
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
        );

        update_option( 'htr_ai_chatbot_settings', $defaults );
        delete_option( 'htr_ai_chatbot_api_key' );

        wp_safe_redirect(
            add_query_arg(
                array(
                    'page'       => 'htr-ai-chatbot',
                    'htr_reset'  => 'success',
                ),
                admin_url( 'admin.php' )
            )
        );

        exit;
    }
}
