<?php
/**
 * Chatbot appearance settings component.
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
        <h2><?php echo esc_html( 'Appearance Settings' ); ?></h2>

        <p>
            <?php
            echo esc_html(
                'Customize the colors, position, and visual style of your chatbot.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-primary-color">
            <?php echo esc_html( 'Primary Color' ); ?>
        </label>

        <input
            type="color"
            id="htr-primary-color"
            name="htr_primary_color"
            value="<?php echo esc_attr( $settings['primary_color'] ); ?>"
        >

        <p class="description">
            <?php
            echo esc_html(
                'Choose the main color used for the chatbot interface.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-chatbot-position">
            <?php echo esc_html( 'Widget Position' ); ?>
        </label>

        <select
            id="htr-chatbot-position"
            name="htr_chatbot_position"
        >
            <option
                value="bottom-right"
                <?php selected( $settings['chatbot_position'], 'bottom-right' ); ?>
            >
                <?php echo esc_html( 'Bottom Right' ); ?>
            </option>

            <option
                value="bottom-left"
                <?php selected( $settings['chatbot_position'], 'bottom-left' ); ?>
            >
                <?php echo esc_html( 'Bottom Left' ); ?>
            </option>
        </select>

        <p class="description">
            <?php
            echo esc_html(
                'Choose where the chatbot launcher appears on the screen.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-chatbot-width">
            <?php echo esc_html( 'Chat Window Width' ); ?>
        </label>

        <input
            type="number"
            id="htr-chatbot-width"
            name="htr_chatbot_width"
            value="<?php echo esc_attr( $settings['chatbot_width'] ); ?>"
            min="280"
            max="600"
        >

        <p class="description">
            <?php
            echo esc_html(
                'Set the chat window width in pixels, between 280 and 600.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-chatbot-height">
            <?php echo esc_html( 'Chat Window Height' ); ?>
        </label>

        <input
            type="number"
            id="htr-chatbot-height"
            name="htr_chatbot_height"
            value="<?php echo esc_attr( $settings['chatbot_height'] ); ?>"
            min="350"
            max="800"
        >

        <p class="description">
            <?php
            echo esc_html(
                'Set the chat window height in pixels, between 350 and 800.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-header-style">
            <?php echo esc_html( 'Header Style' ); ?>
        </label>

        <select
            id="htr-header-style"
            name="htr_header_style"
        >
            <option
                value="solid"
                <?php selected( $settings['header_style'], 'solid' ); ?>
            >
                <?php echo esc_html( 'Solid Color' ); ?>
            </option>

            <option
                value="gradient"
                <?php selected( $settings['header_style'], 'gradient' ); ?>
            >
                <?php echo esc_html( 'Gradient' ); ?>
            </option>
        </select>

        <p class="description">
            <?php
            echo esc_html(
                'Choose the visual style of the chatbot header.'
            );
            ?>
        </p>
    </div>
</div>
