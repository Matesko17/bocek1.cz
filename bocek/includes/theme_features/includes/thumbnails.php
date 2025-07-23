<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function theme_features_thumbnails() {
  //Custom thumbnail
  add_image_size( 'thumb-ultra-small', 30 );
}

add_action('after_setup_theme', "theme_features_thumbnails");