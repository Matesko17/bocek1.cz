<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function custom_remove_wp_version() {
  return '';
}
add_filter('the_generator', 'custom_remove_wp_version');