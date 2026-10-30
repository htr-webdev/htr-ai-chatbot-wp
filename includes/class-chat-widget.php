<?php
/**
 * Renders and manages the chat widget on the frontend.
 *
 * This class handles the HTML output, asset enqueuing, and
 * the initial setup of the chat interface.
 *
 * @package HTR_AI_Chatbot
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class HTR_AI_Chatbot_Chat_Widget
 */
class HTR_AI_Chatbot_Chat_Widget {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'wp_footer', array( $this, 'render_widget' ) );
    }

    /**
     * Enqueue frontend widget assets.
     *
     * @return void
     */
    public function enqueue_assets() {
        if ( is_admin() ) {
            return;
        }

        wp_enqueue_style(
            'htr-ai-chatbot-widget',
            HTR_AI_CHATBOT_URL . 'assets/css/chat-widget.css',
            array(),
            HTR_AI_CHATBOT_VERSION
        );

        wp_enqueue_script(
            'htr-ai-chatbot-widget',
            HTR_AI_CHATBOT_URL . 'assets/js/chat-widget.js',
            array(),
            HTR_AI_CHATBOT_VERSION,
            true
        );
    }

    /**
     * Render the chat widget HTML.
     *
     * @return void
     */
    public function render_widget() {
        if ( is_admin() ) {
            return;
        }
        ?>
        <div class="htr-chat-widget">
            <button
                type="button"
                class="htr-chat-widget__launcher"
                aria-label="<?php esc_attr_e( 'Open chat', 'htr-ai-chatbot' ); ?>"
                aria-expanded="false"
                aria-controls="htr-chat-widget-window"
            >
                <span aria-hidden="true">Chat</span>
            </button>

            <section
                id="htr-chat-widget-window"
                class="htr-chat-widget__window"
                role="dialog"
                aria-label="<?php esc_attr_e( 'Chat window', 'htr-ai-chatbot' ); ?>"
                aria-hidden="true"
                hidden
            >
                <header class="htr-chat-widget__header">
                    <h2 class="htr-chat-widget__title">
                        <?php esc_html_e( 'Chat Assistant', 'htr-ai-chatbot' ); ?>
                    </h2>

                    <button
                        type="button"
                        class="htr-chat-widget__close"
                        aria-label="<?php esc_attr_e( 'Close chat', 'htr-ai-chatbot' ); ?>"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </header>

                <div
                    class="htr-chat-widget__messages"
                    role="log"
                    aria-live="polite"
                    aria-relevant="additions"
                >
                    <div class="htr-chat-widget__message htr-chat-widget__message--assistant">
                        <p>
                            <?php esc_html_e( 'Hello! How can I help you today?', 'htr-ai-chatbot' ); ?>
                        </p>
                    </div>
                </div>

                <form class="htr-chat-widget__form">
                    <label
                        class="screen-reader-text"
                        for="htr-chat-widget-input"
                    >
                        <?php esc_html_e( 'Type your message', 'htr-ai-chatbot' ); ?>
                    </label>

                    <textarea
                        id="htr-chat-widget-input"
                        class="htr-chat-widget__input"
                        rows="1"
                        placeholder="<?php esc_attr_e( 'Type your message...', 'htr-ai-chatbot' ); ?>"
                    ></textarea>

                    <button
                        type="submit"
                        class="htr-chat-widget__send"
                        aria-label="<?php esc_attr_e( 'Send message', 'htr-ai-chatbot' ); ?>"
                    >
                        <?php esc_html_e( 'Send', 'htr-ai-chatbot' ); ?>
                    </button>
                </form>
            </section>
        </div>
        <?php
    }
}