<?php
/**
 * Chatbot behavior settings component.
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
        <h2><?php echo esc_html( 'Behavior Settings' ); ?></h2>

        <p>
            <?php
            echo esc_html(
                'Control how the chatbot opens and behaves on your website.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-chatbot-enabled">
            <?php echo esc_html( 'Enable Chatbot' ); ?>
        </label>

        <select
            id="htr-chatbot-enabled"
            name="htr_chatbot_enabled"
        >
            <option value="1">
                <?php echo esc_html( 'Enabled' ); ?>
            </option>

            <option value="0">
                <?php echo esc_html( 'Disabled' ); ?>
            </option>
        </select>

        <p class="description">
            <?php
            echo esc_html(
                'Enable or disable the chatbot on the website.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-auto-open">
            <?php echo esc_html( 'Auto Open Chat' ); ?>
        </label>

        <select
            id="htr-auto-open"
            name="htr_auto_open"
        >
            <option value="0">
                <?php echo esc_html( 'Disabled' ); ?>
            </option>

            <option value="1">
                <?php echo esc_html( 'Enabled' ); ?>
            </option>
        </select>

        <p class="description">
            <?php
            echo esc_html(
                'Automatically open the chat window when a visitor loads the page.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-auto-open-delay">
            <?php echo esc_html( 'Auto Open Delay' ); ?>
        </label>

        <input
            type="number"
            id="htr-auto-open-delay"
            name="htr_auto_open_delay"
            value="5"
            min="0"
            max="60"
        >

        <p class="description">
            <?php
            echo esc_html(
                'Set the delay before the chat window opens automatically, in seconds.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-show-close-button">
            <?php echo esc_html( 'Show Close Button' ); ?>
        </label>

        <select
            id="htr-show-close-button"
            name="htr_show_close_button"
        >
            <option value="1">
                <?php echo esc_html( 'Yes' ); ?>
            </option>

            <option value="0">
                <?php echo esc_html( 'No' ); ?>
            </option>
        </select>

        <p class="description">
            <?php
            echo esc_html(
                'Choose whether visitors can close the chat window.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-message-limit">
            <?php echo esc_html( 'Message Character Limit' ); ?>
        </label>

        <input
            type="number"
            id="htr-message-limit"
            name="htr_message_limit"
            value="2000"
            min="100"
            max="10000"
        >

        <p class="description">
            <?php
            echo esc_html(
                'Set the maximum number of characters allowed in a visitor message.'
            );
            ?>
        </p>
    </div>
</div>
