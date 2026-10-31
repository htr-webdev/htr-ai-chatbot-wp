<?php
/**
 * API connection test and widget logs component.
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

<div class="htr-settings-card">
    <div class="htr-settings-card__header">
        <h2>Widget Logs</h2>
        <p>
            View the latest requests sent through the frontend chat widget.
        </p>
    </div>

    <div class="htr-settings-field">
        <p class="description">
            Widget logs are collected automatically when visitors send messages.
            Fetching logs replaces the currently displayed log data.
        </p>

        <button
            type="button"
            class="button button-primary"
            id="htr-fetch-widget-logs"
        >
            Fetch Logs
        </button>

        <span
            id="htr-widget-logs-status"
            role="status"
            aria-live="polite"
        ></span>
    </div>

    <div class="htr-settings-field">
        <label for="htr-widget-logs-output">Latest Widget Logs</label>

        <pre
            id="htr-widget-logs-output"
            class="htr-widget-logs-output"
            aria-live="polite"
        >No widget logs have been fetched yet.</pre>
    </div>
</div>