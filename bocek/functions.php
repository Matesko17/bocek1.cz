<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$GLOBALS['current_theme'] = wp_get_theme();

/* Custom functions ... */
require_once("includes/functions/index.php");

/* THEME FEATURES - after_setup_theme, menus, theme_support, text-domain, thumbnails ...  */
require_once("includes/theme_features/index.php");

/* FRONT END - wp_enqueue_scripts / HTML HEAD / AJAX ... */
require_once("includes/frontend/index.php");

/* Custom Post Types ... */
require_once("includes/custom_post_types/index.php");

/* Advanced Custom Fields ... */
require_once("includes/acf/index.php");

//EXTRA WP PLUGINS
//require_once("plugins_wp/wp_infinite_posts_loading/wp_infinite_posts_loading.php"); //For infinite loading roadshow / fotogalerie