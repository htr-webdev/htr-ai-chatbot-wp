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
        add_action(
            'admin_menu',
            array( $this, 'register_menu' )
        );

        add_action(
            'admin_enqueue_scripts',
            array( $this, 'enqueue_admin_assets' )
        );
        add_action(
            'admin_post_htr_save_settings',
            array( $this, 'save_settings' )
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
     * Load admin assets only on the plugin settings page.
     *
     * @param string $hook Current admin page hook.
     * @return void
     */
    public function enqueue_admin_assets( $hook ) {
    if ( 'toplevel_page_htr-ai-chatbot' !== $hook ) {
        return;
    }

    $plugin_file = dirname( __DIR__ ) . '/htr-ai-chatbot.php';

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

    /**
     * Display the admin settings page.
     *
     * @return void
     */
public function render_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $components_path = dirname( __DIR__ ) . '/components/';

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


/**
 * Save plugin settings.
 *
 * @return void
 */
public function save_settings() {
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

