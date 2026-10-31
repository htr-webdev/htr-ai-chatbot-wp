<?php
/**
 * API settings component.
 *
 * @package HTR_AI_Chatbot
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$api_provider = isset( $settings['api_provider'] ) ? $settings['api_provider'] : '';
$custom_model = isset( $settings['custom_model'] ) ? $settings['custom_model'] : '';
$api_endpoint = isset( $settings['api_endpoint'] ) ? $settings['api_endpoint'] : '';

$stored_api_key = get_option( 'htr_ai_chatbot_api_key', '' );

if ( '' === $stored_api_key ) {
    $legacy = get_option( 'htr_ai_chatbot_settings', array() );

    if ( is_array( $legacy ) && isset( $legacy['api_key'] ) ) {
        $stored_api_key = $legacy['api_key'];
    }
}
?>

<div class="htr-settings-card">
    <div class="htr-settings-card__header">
        <h2>API Configuration</h2>
        <p>Configure the API connection used by your AI chatbot.</p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-api-provider">API Provider</label>

        <select id="htr-api-provider" name="htr_api_provider">
            <option value="" <?php selected( $api_provider, '' ); ?>>Select a provider</option>
            <option value="avalai" <?php selected( $api_provider, 'avalai' ); ?>>AvalAI</option>
            <option value="openai" <?php selected( $api_provider, 'openai' ); ?>>OpenAI</option>
            <option value="custom" <?php selected( $api_provider, 'custom' ); ?>>Custom OpenAI-Compatible API</option>
        </select>
    </div>

    <div class="htr-settings-field">
        <label for="htr-api-key">API Key</label>

        <input
            type="password"
            id="htr-api-key"
            name="htr_api_key"
            autocomplete="off"
            placeholder="Enter your API key"
        >

        <p class="description">
            <?php echo esc_html( '' !== $stored_api_key ? 'API key is configured.' : 'No API key is configured.' ); ?>
        </p>

        <label for="htr-remove-api-key">
            <input type="checkbox" id="htr-remove-api-key" name="htr_remove_api_key" value="1">
            Remove the saved API key
        </label>

        <p class="description">Leave blank to keep the previously saved API key.</p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-custom-model">Model ID</label>

        <input
            type="text"
            id="htr-custom-model"
            name="htr_custom_model"
            value="<?php echo esc_attr( $custom_model ); ?>"
            placeholder="Enter the exact model ID"
        >

        <p class="description">Enter the exact model ID provided by your API provider.</p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-api-endpoint">API Endpoint</label>

        <input
            type="url"
            id="htr-api-endpoint"
            name="htr_api_endpoint"
            value="<?php echo esc_attr( $api_endpoint ); ?>"
            placeholder="https://api.example.com/v1/chat/completions"
        >

        <p class="description">
            Optional for providers with a built-in endpoint. Required for custom providers.
        </p>
    </div>
</div>