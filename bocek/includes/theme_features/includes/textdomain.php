<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function theme_features_textdomain() {
  //Add text-domain support
  load_theme_textdomain( get_template(), get_template_directory() . '/languages' );
}

add_action('after_setup_theme', "theme_features_textdomain");