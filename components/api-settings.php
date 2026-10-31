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

$custom_model = isset( $settings['custom_model'] )
    && is_string( $settings['custom_model'] )
        ? $settings['custom_model']
        : '';

$api_provider = isset( $settings['api_provider'] )
    && is_string( $settings['api_provider'] )
        ? $settings['api_provider']
        : 'avalai';

$api_model = isset( $settings['api_model'] )
    && is_string( $settings['api_model'] )
        ? $settings['api_model']
        : 'gpt-4o-mini';

$api_endpoint = isset( $settings['api_endpoint'] )
    && is_string( $settings['api_endpoint'] )
        ? $settings['api_endpoint']
        : '';
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
            <option
                value="avalai"
                <?php selected( $api_provider, 'avalai' ); ?>
            >
                <?php echo esc_html( 'AvalAI' ); ?>
            </option>

            <option
                value="openai"
                <?php selected( $api_provider, 'openai' ); ?>
            >
                <?php echo esc_html( 'OpenAI' ); ?>
            </option>

            <option
                value="custom"
                <?php selected( $api_provider, 'custom' ); ?>
            >
                <?php echo esc_html( 'Custom OpenAI-Compatible API' ); ?>
            </option>
        </select>

        <p class="description">
            <?php
            echo esc_html(
                'Choose AvalAI, OpenAI, or another OpenAI-compatible API provider.'
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

        <?php
        $stored_api_key = get_option( 'htr_ai_chatbot_api_key', '' );

        if ( '' === $stored_api_key ) {
            $legacy_settings = get_option(
                'htr_ai_chatbot_settings',
                array()
            );

            if (
                is_array( $legacy_settings ) &&
                isset( $legacy_settings['api_key'] ) &&
                is_string( $legacy_settings['api_key'] )
            ) {
                $stored_api_key = $legacy_settings['api_key'];
            }
        }
        ?>

        <?php if ( '' !== $stored_api_key ) : ?>
            <p class="description">
                <?php echo esc_html( 'API key is configured.' ); ?>
            </p>
        <?php else : ?>
            <p class="description">
                <?php echo esc_html( 'No API key is configured.' ); ?>
            </p>
        <?php endif; ?>

        <div class="htr-settings-field">
            <label for="htr-remove-api-key">
                <input
                    type="checkbox"
                    id="htr-remove-api-key"
                    name="htr_remove_api_key"
                    value="1"
                >
                <?php echo esc_html( 'Remove the saved API key' ); ?>
            </label>

            <p class="description">
                <?php
                echo esc_html(
                    'Select this option to permanently remove the saved API key when you save settings.'
                );
                ?>
            </p>
        </div>

        <p class="description">
            <?php
            echo esc_html(
                'Leave blank to keep the previously saved API key.'
            );
            ?>
        </p>
    </div>

    <div class="htr-settings-field">
        <label for="htr-api-model">
            <?php echo esc_html( 'AI Model' ); ?>
        </label>

        <select id="htr-api-model" name="htr_api_model">
            <option
                value="gpt-4o-mini"
                <?php selected( $api_model, 'gpt-4o-mini' ); ?>
            >
                <?php echo esc_html( 'GPT-4o mini' ); ?>
            </option>

            <option
                value="custom"
                <?php selected( $api_model, 'custom' ); ?>
            >
                <?php echo esc_html( 'Custom Model' ); ?>
            </option>
        </select>

        <div
            id="htr-custom-model-field"
            <?php echo 'custom' !== $api_model ? 'hidden' : ''; ?>
        >
            <label for="htr-custom-model">
                <?php echo esc_html( 'Custom Model ID' ); ?>
            </label>

            <input
                type="text"
                id="htr-custom-model"
                name="htr_custom_model"
                value="<?php echo esc_attr( $custom_model ); ?>"
                placeholder="<?php echo esc_attr( 'Enter your model ID' ); ?>"
            >

            <p class="description">
                <?php
                echo esc_html(
                    'Enter the exact model ID provided by your API provider.'
                );
                ?>
            </p>
        </div>

        <p class="description">
            <?php
            echo esc_html(
                'Choose a predefined model or enter a custom model ID.'
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
            value="<?php echo esc_attr( $api_endpoint ); ?>"
            placeholder="<?php echo esc_attr( 'https://api.example.com/v1/chat/completions' ); ?>"
        >

        <p class="description">
            <?php
            echo esc_html(
                'Optional. Leave blank to use the selected provider default endpoint. For custom providers, enter the full HTTPS Chat Completions endpoint.'
            );
            ?>
        </p>
    </div>
</div>
