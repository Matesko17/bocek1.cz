<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function theme_features_theme_support() {
  add_theme_support( 'title-tag' );
  add_theme_support( 'post-thumbnails' );

  //Add page-templates support
  add_theme_support( 'page-templates' );

  //WC
  //add_theme_support('woocommerce');
}

add_action('after_setup_theme', "theme_features_theme_support");


/**
 * Add excerpt support to pages
 */
function wpdocs_custom_init() {
	add_post_type_support( 'page', 'excerpt' );
}

add_action('init', 'wpdocs_custom_init');