<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

function theme_features_menus() {
  register_nav_menu("main_menu", "Header - Main Menu Location");
  register_nav_menu("footer_menu_left", "Footer - Menu Left Location");
  register_nav_menu("footer_menu_right", "Footer - Menu Right Location");
  register_nav_menu("footer_menu_bottom", "Footer - Menu Bottom Location");
}

add_action('after_setup_theme', "theme_features_menus");