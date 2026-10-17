<?php
/**
 * Chatbot settings component.
 *
 * @package HTR_AI_Chatbot
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="htr-settings-card">
    <div class="htr-settings-card__header">
        <h2><?php echo esc_html( 'Chatbot Configuration' ); ?></h2>

        <p>
            <?php
            echo esc_html(
                'Configure the chatbot name and its default messages.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-chatbot-name">
            <?php echo esc_html( 'Assistant Name' ); ?>
        </label>

        <input
            type="text"
            id="htr-chatbot-name"
            name="htr_chatbot_name"
            placeholder="<?php echo esc_attr( 'AI Assistant' ); ?>"
        >

        <p class="description">
            <?php
            echo esc_html(
                'Set the name displayed in the chatbot header.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-welcome-message">
            <?php echo esc_html( 'Welcome Message' ); ?>
        </label>

        <textarea
            id="htr-welcome-message"
            name="htr_welcome_message"
            rows="3"
            placeholder="<?php echo esc_attr( 'Hello! How can I help you today?' ); ?>"
        ></textarea>

        <p class="description">
            <?php
            echo esc_html(
                'This message is displayed when a visitor opens the chatbot.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-input-placeholder">
            <?php echo esc_html( 'Input Placeholder' ); ?>
        </label>

        <input
            type="text"
            id="htr-input-placeholder"
            name="htr_input_placeholder"
            placeholder="<?php echo esc_attr( 'Type your message...' ); ?>"
        >

        <p class="description">
            <?php
            echo esc_html(
                'Set the placeholder text inside the message input field.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-fallback-message">
            <?php echo esc_html( 'Fallback Message' ); ?>
        </label>

        <textarea
            id="htr-fallback-message"
            name="htr_fallback_message"
            rows="3"
            placeholder="<?php echo esc_attr( 'Sorry, something went wrong. Please try again.' ); ?>"
        ></textarea>

        <p class="description">
            <?php
            echo esc_html(
                'Set the message displayed when the chatbot cannot process a request.'
            );
            ?>
        </p>
    </div>
</div>
