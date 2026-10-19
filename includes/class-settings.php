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

    echo '</div>';
}
}
