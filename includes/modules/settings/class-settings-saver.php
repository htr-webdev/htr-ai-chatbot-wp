<?php
/**
 * Handle saving plugin settings.
 *
 * @package HTR_AI_Chatbot
 */

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
            wp_die( esc_html( 'You are not allowed to change these settings.' ) );
        }

        check_admin_referer( 'htr_save_settings', 'htr_settings_nonce' );

        $input = wp_unslash( $_POST );

        $stored = get_option( 'htr_ai_chatbot_settings', array() );

        if ( ! is_array( $stored ) ) {
            $stored = array();
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

        $settings = wp_parse_args( $stored, $defaults );

        if ( isset( $input['htr_api_provider'] ) ) {
            $provider = sanitize_key( $input['htr_api_provider'] );

            if ( in_array( $provider, array( '', 'avalai', 'openai', 'custom' ), true ) ) {
                $settings['api_provider'] = $provider;
            }
        }

        if ( isset( $input['htr_custom_model'] ) ) {
            $settings['custom_model'] = sanitize_text_field(
                $input['htr_custom_model']
            );

            $settings['api_model'] = '' === $settings['custom_model']
                ? ''
                : 'custom';
        }

        if ( isset( $input['htr_api_endpoint'] ) ) {
            $settings['api_endpoint'] = esc_url_raw(
                trim( $input['htr_api_endpoint'] )
            );
        }

        $api_key = get_option( 'htr_ai_chatbot_api_key', '' );

        if (
            '' === $api_key &&
            isset( $stored['api_key'] ) &&
            is_string( $stored['api_key'] )
        ) {
            $api_key = $stored['api_key'];
        }

        if (
            isset( $input['htr_remove_api_key'] ) &&
            '1' === $input['htr_remove_api_key']
        ) {
            delete_option( 'htr_ai_chatbot_api_key' );
        } elseif (
            isset( $input['htr_api_key'] ) &&
            is_string( $input['htr_api_key'] ) &&
            '' !== trim( $input['htr_api_key'] )
        ) {
            update_option(
                'htr_ai_chatbot_api_key',
                sanitize_text_field( $input['htr_api_key'] ),
                false
            );
        } elseif ( '' !== $api_key ) {
            update_option( 'htr_ai_chatbot_api_key', $api_key, false );
        }

        unset( $settings['api_key'] );

        $text_fields = array(
            'htr_chatbot_name'      => 'chatbot_name',
            'htr_input_placeholder' => 'input_placeholder',
        );

        foreach ( $text_fields as $input_key => $setting_key ) {
            if ( isset( $input[ $input_key ] ) && is_string( $input[ $input_key ] ) ) {
                $settings[ $setting_key ] = sanitize_text_field( $input[ $input_key ] );
            }
        }

        $textarea_fields = array(
            'htr_welcome_message'  => 'welcome_message',
            'htr_fallback_message' => 'fallback_message',
        );

        foreach ( $textarea_fields as $input_key => $setting_key ) {
            if ( isset( $input[ $input_key ] ) && is_string( $input[ $input_key ] ) ) {
                $settings[ $setting_key ] = sanitize_textarea_field( $input[ $input_key ] );
            }
        }

        if ( isset( $input['htr_primary_color'] ) ) {
            $color = sanitize_hex_color( $input['htr_primary_color'] );

            if ( $color ) {
                $settings['primary_color'] = $color;
            }
        }

        if ( isset( $input['htr_chatbot_position'] ) ) {
            $position = sanitize_key( $input['htr_chatbot_position'] );

            if ( in_array( $position, array( 'bottom-right', 'bottom-left' ), true ) ) {
                $settings['chatbot_position'] = $position;
            }
        }

        foreach ( array(
            'htr_chatbot_width'  => array( 'chatbot_width', 280, 600 ),
            'htr_chatbot_height' => array( 'chatbot_height', 350, 800 ),
            'htr_auto_open_delay' => array( 'auto_open_delay', 1, 60 ),
            'htr_message_limit'  => array( 'message_limit', 100, 10000 ),
        ) as $key => $config ) {
            if ( isset( $input[ $key ] ) && is_scalar( $input[ $key ] ) ) {
                $settings[ $config[0] ] = min(
                    $config[2],
                    max( $config[1], absint( $input[ $key ] ) )
                );
            }
        }

        if ( isset( $input['htr_header_style'] ) ) {
            $style = sanitize_key( $input['htr_header_style'] );

            if ( in_array( $style, array( 'solid', 'gradient' ), true ) ) {
                $settings['header_style'] = $style;
            }
        }

        foreach ( array(
            'htr_chatbot_enabled'   => 'chatbot_enabled',
            'htr_auto_open'         => 'auto_open',
            'htr_show_close_button' => 'show_close_button',
        ) as $input_key => $setting_key ) {
            if ( isset( $input[ $input_key ] ) ) {
                $settings[ $setting_key ] = '1' === $input[ $input_key ] ? 1 : 0;
            }
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