<?php
/**
 * Settings navigation tabs.
 *
 * @package HTR_AI_Chatbot
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<nav class="htr-settings-tabs" aria-label="Settings sections" role="tablist">
    <button
        type="button"
        class="htr-settings-tab is-active"
        id="htr-tab-api"
        role="tab"
        aria-selected="true"
        aria-controls="htr-panel-api"
        data-tab="api"
    >
        API Settings
    </button>

    <button
        type="button"
        class="htr-settings-tab"
        id="htr-tab-chatbot"
        role="tab"
        aria-selected="false"
        aria-controls="htr-panel-chatbot"
        data-tab="chatbot"
        tabindex="-1"
    >
        Chatbot
    </button>

    <button
        type="button"
        class="htr-settings-tab"
        id="htr-tab-appearance"
        role="tab"
        aria-selected="false"
        aria-controls="htr-panel-appearance"
        data-tab="appearance"
        tabindex="-1"
    >
        Appearance
    </button>

    <button
        type="button"
        class="htr-settings-tab"
        id="htr-tab-behavior"
        role="tab"
        aria-selected="false"
        aria-controls="htr-panel-behavior"
        data-tab="behavior"
        tabindex="-1"
    >
        Behavior
    </button>
</nav>