<?php
/**
 * Handle saving plugin settings.
 *
 * @package HTR_AI_Chatbot
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class HTR_AI_Chatbot_Settings_Saver
 */
class HTR_AI_Chatbot_Settings_Saver {

    /**
     * Save plugin settings.
     *
     * @return void
     */
    public function save() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die(
                esc_html( 'You are not allowed to change these settings.' )
            );
        }

        check_admin_referer(
            'htr_save_settings',
            'htr_settings_nonce'
        );

        $input = wp_unslash( $_POST );

        $settings = get_option(
            'htr_ai_chatbot_settings',
            array()
        );

        if ( ! is_array( $settings ) ) {
            $settings = array();
        }

        // API settings.
        $provider = isset( $input['htr_api_provider'] )
            ? sanitize_text_field( $input['htr_api_provider'] )
            : 'openai';

        $settings['api_provider'] = in_array(
            $provider,
            array( 'openai' ),
            true
        ) ? $provider : 'openai';

        $model = isset( $input['htr_api_model'] )
            ? sanitize_text_field( $input['htr_api_model'] )
            : 'gpt-4o-mini';

        $settings['api_model'] = in_array(
            $model,
            array( 'gpt-4o-mini' ),
            true
        ) ? $model : 'gpt-4o-mini';

        $settings['api_endpoint'] = isset( $input['htr_api_endpoint'] )
            ? esc_url_raw( $input['htr_api_endpoint'] )
            : '';

        if (
            isset( $input['htr_api_key'] ) &&
            is_string( $input['htr_api_key'] ) &&
            '' !== trim( $input['htr_api_key'] )
        ) {
            $settings['api_key'] = sanitize_text_field(
                $input['htr_api_key']
            );
        }

        // Chatbot settings.
        $settings['chatbot_name'] = isset( $input['htr_chatbot_name'] )
            ? sanitize_text_field( $input['htr_chatbot_name'] )
            : '';

        $settings['welcome_message'] = isset( $input['htr_welcome_message'] )
            ? sanitize_textarea_field( $input['htr_welcome_message'] )
            : '';

        $settings['input_placeholder'] = isset( $input['htr_input_placeholder'] )
            ? sanitize_text_field( $input['htr_input_placeholder'] )
            : '';

        $settings['fallback_message'] = isset( $input['htr_fallback_message'] )
            ? sanitize_textarea_field( $input['htr_fallback_message'] )
            : '';

        // Appearance settings.
        $color = isset( $input['htr_primary_color'] )
            ? sanitize_hex_color( $input['htr_primary_color'] )
            : false;

        $settings['primary_color'] = $color
            ? $color
            : ( $settings['primary_color'] ?? '#2563EB' );

        $position = isset( $input['htr_chatbot_position'] )
            ? sanitize_text_field( $input['htr_chatbot_position'] )
            : 'bottom-right';

        $settings['chatbot_position'] = in_array(
            $position,
            array( 'bottom-right', 'bottom-left' ),
            true
        ) ? $position : 'bottom-right';

        $settings['chatbot_width'] = isset( $input['htr_chatbot_width'] )
            ? min( 600, max( 280, absint( $input['htr_chatbot_width'] ) ) )
            : 380;

        $settings['chatbot_height'] = isset( $input['htr_chatbot_height'] )
            ? min( 800, max( 350, absint( $input['htr_chatbot_height'] ) ) )
            : 550;

        $header_style = isset( $input['htr_header_style'] )
            ? sanitize_text_field( $input['htr_header_style'] )
            : 'solid';

        $settings['header_style'] = in_array(
            $header_style,
            array( 'solid', 'gradient' ),
            true
        ) ? $header_style : 'solid';

        // Behavior settings.
        $settings['chatbot_enabled'] = isset( $input['htr_chatbot_enabled'] )
            && '1' === $input['htr_chatbot_enabled'] ? 1 : 0;

        $settings['auto_open'] = isset( $input['htr_auto_open'] )
            && '1' === $input['htr_auto_open'] ? 1 : 0;

        $settings['auto_open_delay'] = isset( $input['htr_auto_open_delay'] )
            ? min( 60, absint( $input['htr_auto_open_delay'] ) )
            : 5;

        $settings['show_close_button'] = isset( $input['htr_show_close_button'] )
            && '1' === $input['htr_show_close_button'] ? 1 : 0;

        $settings['message_limit'] = isset( $input['htr_message_limit'] )
            ? min( 10000, max( 100, absint( $input['htr_message_limit'] ) ) )
            : 2000;

        update_option( 'htr_ai_chatbot_settings', $settings );

        wp_safe_redirect(
            add_query_arg(
                array(
                    'page'      => 'htr-ai-chatbot',
                    'htr_saved' => '1',
                ),
                admin_url( 'admin.php' )
            )
        );

        exit;
    }
}
