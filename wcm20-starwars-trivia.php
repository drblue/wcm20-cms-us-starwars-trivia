<?php
/**
 * Plugin Name:	WCM20 StarWars Trivia
 * Description:	This plugin adds various methods for showing StarWars Trivia
 * Version:		0.1
 * Author:		Johan Nordström
 * Author URI:	https://www.thehiveresistance.com
 * Text Domain:	wst
 * Domain Path:	/languages
 */

define('WST_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WST_SHORTCODE_TAG_PEOPLE', 'starwars-people');
define('WST_SHORTCODE_TAG_PERSON', 'starwars-person');

/**
 * Include dependencies.
 */
require_once(WST_PLUGIN_DIR . 'includes/functions.php');
require_once(WST_PLUGIN_DIR . 'includes/shortcodes.php');
require_once(WST_PLUGIN_DIR . 'includes/swapi.php');
require_once(WST_PLUGIN_DIR . 'includes/widgets.php');
