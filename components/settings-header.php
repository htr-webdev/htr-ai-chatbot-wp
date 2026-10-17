<?php
/**
 * Settings page header component.
 *
 * @package HTR_AI_Chatbot
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="htr-settings-header">
    <div class="htr-settings-header__content">
        <h1><?php echo esc_html( 'AI Chatbot Settings' ); ?></h1>

        <p>
            <?php
            echo esc_html(
                'Configure your AI chatbot and customize its appearance.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-header__badge">
        <span class="dashicons dashicons-format-chat"></span>
        <span><?php echo esc_html( 'HTR AI Chatbot' ); ?></span>
    </div>
</div>
