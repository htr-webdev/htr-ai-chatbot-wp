<?php
/**
 * Settings actions component.
 *
 * @package HTR_AI_Chatbot
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="htr-settings-actions">
    <div class="htr-settings-actions__info">
        <span class="dashicons dashicons-info-outline"></span>

        <p>
            <?php
            echo esc_html(
                'Changes will take effect after settings are saved.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-actions__buttons">
        <button
            type="button"
            class="button button-secondary"
            id="htr-reset-settings"
        >
            <?php echo esc_html( 'Reset Settings' ); ?>
        </button>

        <button
            type="submit"
            class="button button-primary"
            id="htr-save-settings"
        >
            <span class="dashicons dashicons-saved"></span>
            <?php echo esc_html( 'Save Settings' ); ?>
        </button>
    </div>
</div>
