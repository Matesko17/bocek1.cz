<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

function remove_jquery_migrate( $scripts ) {
	if ( empty( $scripts->registered['jquery'] ) || is_admin() ) {
		return;
	}

	$deps = & $scripts->registered['jquery']->deps;
	$deps = array_diff( $deps, [ 'jquery-migrate' ] );
}

if ($dm_toolkit_config["dm_toolkit_disable_jquery_migrate"] === 1) add_filter( 'wp_default_scripts', 'remove_jquery_migrate' );

function enqueue_jquery_in_footer() {
  if ( is_admin() ) return;
  
  // Deregister the default jQuery included with WordPress (optional)
  wp_deregister_script('jquery');

  // Enqueue jQuery from Google CDN
  wp_enqueue_script('jquery', get_theme_file_uri('assets/js/jquery-3.6.4.min.js'), array(), '3.6.4', true);
}

if ($dm_toolkit_config["dm_toolkit_disable_jquery"] === 1) add_action('wp_enqueue_scripts', 'enqueue_jquery_in_footer');

// Disable WP-Emoji styles and scripts
function disable_wp_emoji() {
	if ( is_admin() ) return;

  // Remove emoji scripts and styles
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_action('admin_print_styles', 'print_emoji_styles');

  // Remove emoji inline CSS
  add_filter('emoji_svg_url', '__return_false');
}
if ($dm_toolkit_config["dm_toolkit_disable_wp_emoji"] === 1) add_action('init', 'disable_wp_emoji');

// Remove REST API classic theme styles inline CSS
function remove_global_styles_inline_css() {
  if ( is_admin() ) return;

  wp_dequeue_style('global-styles');
  wp_dequeue_style('classic-theme-styles');
  wp_dequeue_style('classic-editor-styles');
}
if ($dm_toolkit_config["dm_toolkit_disable_wp_styles"] === 1) add_action('wp_enqueue_scripts', 'remove_global_styles_inline_css', 100);

// Remove wp-block-library styles
function remove_block_library_styles() {
  if ( is_admin() ) return;

  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('wp-block-library-theme');
}
if ($dm_toolkit_config["dm_toolkit_disable_wp_styles"] === 1) add_action('wp_enqueue_scripts', 'remove_block_library_styles', 100);