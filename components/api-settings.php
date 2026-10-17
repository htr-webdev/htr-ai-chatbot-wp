<?php
/**
 * API settings component.
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
        <h2><?php echo esc_html( 'API Configuration' ); ?></h2>
        <p>
            <?php
            echo esc_html(
                'Configure the API connection used by your AI chatbot.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-api-provider">
            <?php echo esc_html( 'API Provider' ); ?>
        </label>

        <select id="htr-api-provider" name="htr_api_provider">
            <option value="openai">
                <?php echo esc_html( 'OpenAI' ); ?>
            </option>
        </select>

        <p class="description">
            <?php
            echo esc_html(
                'Select the provider that powers your chatbot.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-api-key">
            <?php echo esc_html( 'API Key' ); ?>
        </label>

        <input
            type="password"
            id="htr-api-key"
            name="htr_api_key"
            placeholder="<?php echo esc_attr( 'Enter your API key' ); ?>"
            autocomplete="off"
        >

        <p class="description">
            <?php
            echo esc_html(
                'Enter the API key provided by your AI service provider.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-api-model">
            <?php echo esc_html( 'AI Model' ); ?>
        </label>

        <select id="htr-api-model" name="htr_api_model">
            <option value="gpt-4o-mini">
                <?php echo esc_html( 'GPT-4o mini' ); ?>
            </option>
        </select>

        <p class="description">
            <?php
            echo esc_html(
                'Choose the AI model you want your chatbot to use.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-api-endpoint">
            <?php echo esc_html( 'API Endpoint' ); ?>
        </label>

        <input
            type="url"
            id="htr-api-endpoint"
            name="htr_api_endpoint"
            placeholder="<?php echo esc_attr( 'https://api.example.com/v1' ); ?>"
        >

        <p class="description">
            <?php
            echo esc_html(
                'Optional endpoint URL for a compatible API provider.'
            );
            ?>
        </p>
    </div>
</div>
