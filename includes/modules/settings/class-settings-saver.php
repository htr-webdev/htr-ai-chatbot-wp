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

        $stored_settings = get_option(
            'htr_ai_chatbot_settings',
            array()
        );

        if ( ! is_array( $stored_settings ) ) {
            $stored_settings = array();
        }

        $defaults = array(
            'api_provider'      => 'openai',
            'api_model'          => 'gpt-4o-mini',
            'api_endpoint'      => '',
            'api_key'            => '',
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

        $settings = wp_parse_args( $stored_settings, $defaults );

        // API settings.
        if (
            isset( $input['htr_api_provider'] ) &&
            is_string( $input['htr_api_provider'] )
        ) {
            $provider = sanitize_text_field(
                $input['htr_api_provider']
            );

            if ( in_array( $provider, array( 'openai' ), true ) ) {
                $settings['api_provider'] = $provider;
            }
        }

        if (
            isset( $input['htr_api_model'] ) &&
            is_string( $input['htr_api_model'] )
        ) {
            $model = sanitize_text_field(
                $input['htr_api_model']
            );

            if ( in_array( $model, array( 'gpt-4o-mini' ), true ) ) {
                $settings['api_model'] = $model;
            }
        }

        if (
            isset( $input['htr_api_endpoint'] ) &&
            is_string( $input['htr_api_endpoint'] )
        ) {
            $settings['api_endpoint'] = esc_url_raw(
                $input['htr_api_endpoint']
            );
        }

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
        $text_fields = array(
            'htr_chatbot_name'      => 'chatbot_name',
            'htr_input_placeholder' => 'input_placeholder',
        );

        foreach ( $text_fields as $input_key => $setting_key ) {
            if (
                isset( $input[ $input_key ] ) &&
                is_string( $input[ $input_key ] )
            ) {
                $settings[ $setting_key ] = sanitize_text_field(
                    $input[ $input_key ]
                );
            }
        }

        $textarea_fields = array(
            'htr_welcome_message' => 'welcome_message',
            'htr_fallback_message' => 'fallback_message',
        );

        foreach ( $textarea_fields as $input_key => $setting_key ) {
            if (
                isset( $input[ $input_key ] ) &&
                is_string( $input[ $input_key ] )
            ) {
                $settings[ $setting_key ] = sanitize_textarea_field(
                    $input[ $input_key ]
                );
            }
        }

        // Appearance settings.
        if (
            isset( $input['htr_primary_color'] ) &&
            is_string( $input['htr_primary_color'] )
        ) {
            $color = sanitize_hex_color(
                $input['htr_primary_color']
            );

            if ( $color ) {
                $settings['primary_color'] = $color;
            }
        }

        if (
            isset( $input['htr_chatbot_position'] ) &&
            is_string( $input['htr_chatbot_position'] )
        ) {
            $position = sanitize_text_field(
                $input['htr_chatbot_position']
            );

            if (
                in_array(
                    $position,
                    array( 'bottom-right', 'bottom-left' ),
                    true
                )
            ) {
                $settings['chatbot_position'] = $position;
            }
        }

        if ( isset( $input['htr_chatbot_width'] ) && is_scalar( $input['htr_chatbot_width'] ) ) {
            $settings['chatbot_width'] = min(
                600,
                max( 280, absint( $input['htr_chatbot_width'] ) )
            );
        }

        if ( isset( $input['htr_chatbot_height'] ) && is_scalar( $input['htr_chatbot_height'] ) ) {
            $settings['chatbot_height'] = min(
                800,
                max( 350, absint( $input['htr_chatbot_height'] ) )
            );
        }

        if (
            isset( $input['htr_header_style'] ) &&
            is_string( $input['htr_header_style'] )
        ) {
            $header_style = sanitize_text_field(
                $input['htr_header_style']
            );

            if (
                in_array(
                    $header_style,
                    array( 'solid', 'gradient' ),
                    true
                )
            ) {
                $settings['header_style'] = $header_style;
            }
        }

        // Behavior settings.
        $boolean_fields = array(
            'htr_chatbot_enabled'   => 'chatbot_enabled',
            'htr_auto_open'         => 'auto_open',
            'htr_show_close_button' => 'show_close_button',
        );

        foreach ( $boolean_fields as $input_key => $setting_key ) {
            if (
                isset( $input[ $input_key ] ) &&
                is_string( $input[ $input_key ] )
            ) {
                if ( '1' === $input[ $input_key ] ) {
                    $settings[ $setting_key ] = 1;
                } elseif ( '0' === $input[ $input_key ] ) {
                    $settings[ $setting_key ] = 0;
                }
            }
        }

        if ( isset( $input['htr_auto_open_delay'] ) && is_scalar( $input['htr_auto_open_delay'] ) ) {
            $settings['auto_open_delay'] = min(
                60,
                max( 1, absint( $input['htr_auto_open_delay'] ) )
            );
        }

        if ( isset( $input['htr_message_limit'] ) && is_scalar( $input['htr_message_limit'] ) ) {
            $settings['message_limit'] = min(
                10000,
                max( 100, absint( $input['htr_message_limit'] ) )
            );
        }

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
