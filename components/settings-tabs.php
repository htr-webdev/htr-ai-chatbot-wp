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
    <?php
    $tabs = array(
        'api'        => 'API Settings',
        'chatbot'    => 'Chatbot',
        'appearance' => 'Appearance',
        'behavior'   => 'Behavior',
        'test'       => 'Test',
    );

    foreach ( $tabs as $key => $label ) :
        $active = 'api' === $key;
        ?>
        <button
            type="button"
            class="htr-settings-tab<?php echo $active ? ' is-active' : ''; ?>"
            id="htr-tab-<?php echo esc_attr( $key ); ?>"
            role="tab"
            aria-selected="<?php echo $active ? 'true' : 'false'; ?>"
            aria-controls="htr-panel-<?php echo esc_attr( $key ); ?>"
            data-tab="<?php echo esc_attr( $key ); ?>"
            tabindex="<?php echo $active ? '0' : '-1'; ?>"
        >
            <?php echo esc_html( $label ); ?>
        </button>
    <?php endforeach; ?>
</nav>