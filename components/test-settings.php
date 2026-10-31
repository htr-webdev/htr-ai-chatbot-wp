<?php
/**
 * API connection test component.
 *
 * @package HTR_AI_Chatbot
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="htr-settings-card">
    <div class="htr-settings-card__header">
        <h2>API Connection Test</h2>
        <p>Send a test request using your currently saved API configuration.</p>
    </div>

    <div class="htr-settings-field">
        <p class="description">
            Save your API settings before running the test. The test uses a fixed message and does not modify your settings.
        </p>

        <button
            type="button"
            class="button button-primary"
            id="htr-test-api"
        >
            Test API Connection
        </button>

        <span id="htr-test-status" role="status" aria-live="polite"></span>
    </div>

    <div class="htr-settings-field">
        <label for="htr-test-log">Diagnostic Log</label>

        <pre id="htr-test-log" class="htr-test-log" aria-live="polite">No test has been run yet.</pre>
    </div>
</div>