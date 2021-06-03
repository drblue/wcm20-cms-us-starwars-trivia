<?php

/**
 * Include widget class(es)
 */
require(WST_PLUGIN_DIR . 'includes/class.StarWarsTriviaFilmsWidget.php');

/**
 * Register widget(s)
 */
function wst_widgets_init() {
	register_widget('StarWarsTriviaFilmsWidget');
}
add_action('widgets_init', 'wst_widgets_init');
