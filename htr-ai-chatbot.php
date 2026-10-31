<?php
/**
 * Plugin Name:       HTR AI Chatbot for WordPress
 * Plugin URI:        https://github.com/htr-webdev/htr-ai-chatbot-wp
 * Description:       An AI-powered chatbot plugin for WordPress that helps small businesses automate customer support.
 * Version:           0.1.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Habib Mirzaei
 * Author URI:        https://www.linkedin.com/in/habib-mirzaei1995/
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       htr-ai-chatbot
 * Domain Path:       /languages
 *
 * @package HTR_AI_Chatbot
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'HTR_AI_CHATBOT_VERSION', '0.1.0' );
define( 'HTR_AI_CHATBOT_PATH', plugin_dir_path( __FILE__ ) );
define( 'HTR_AI_CHATBOT_URL', plugin_dir_url( __FILE__ ) );

require_once HTR_AI_CHATBOT_PATH . 'includes/class-api-handler.php';
require_once HTR_AI_CHATBOT_PATH . 'includes/class-chat-widget.php';
require_once HTR_AI_CHATBOT_PATH . 'includes/class-chat-rest-controller.php';

require_once HTR_AI_CHATBOT_PATH . 'includes/modules/settings/class-settings-assets.php';
require_once HTR_AI_CHATBOT_PATH . 'includes/modules/settings/class-settings-page.php';
require_once HTR_AI_CHATBOT_PATH . 'includes/modules/settings/class-settings-menu.php';
require_once HTR_AI_CHATBOT_PATH . 'includes/modules/settings/class-settings-saver.php';
require_once HTR_AI_CHATBOT_PATH . 'includes/modules/settings/class-settings-reset.php';
require_once HTR_AI_CHATBOT_PATH . 'includes/modules/settings/class-api-tester.php';

require_once HTR_AI_CHATBOT_PATH . 'includes/class-settings.php';

/**
 * Initialize the plugin.
 *
 * @return void
 */
function htr_ai_chatbot_init() {
    new HTR_AI_Chatbot_Settings();
    new HTR_AI_Chatbot_Chat_Widget();
    new HTR_AI_Chatbot_Chat_REST_Controller();
}
add_action( 'plugins_loaded', 'htr_ai_chatbot_init' );