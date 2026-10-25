
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
            <option
                value="openai"
                <?php selected( $settings['api_provider'], 'openai' ); ?>
            >
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

        <?php
        $stored_api_key = get_option( 'htr_ai_chatbot_api_key', '' );

        if ( '' === $stored_api_key ) {
            $legacy_settings = get_option( 'htr_ai_chatbot_settings', array() );

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
                <?php selected( $settings['api_model'], 'gpt-4o-mini' ); ?>
            >
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
            value="<?php echo esc_attr( $settings['api_endpoint'] ); ?>"
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
