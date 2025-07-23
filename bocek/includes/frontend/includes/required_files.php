<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function required_frontend_files() {
    //Main JS Scripts
    wp_enqueue_script("theme-main", get_theme_file_uri('build/main.js'), array(), $GLOBALS['current_theme']->get( 'Version' ), array(
      'in_footer' => true,
    ));

    //Main JS variables
    wp_localize_script( 
      'theme-main', 
      'theme_vars', 
      array(
        'url_ajax' => admin_url( 'admin-ajax.php' ),
        'url_root' => site_url(),
        'url_theme' => get_theme_file_uri(),
        'contact_form_vars' => array(
          'contact_form_element' => '.mainpage_contact_form form',
          'contact_form_folder' => get_theme_file_uri('assets/plugins/contact_form/')
        ),
      )
    );

    //Sync JS Scripts
    wp_enqueue_script("theme-main-sync", get_theme_file_uri('build/main_sync.js'), array(), $GLOBALS['current_theme']->get( 'Version' ), array(
      'in_footer' => false,
    ));

    //CSS
    wp_enqueue_style("theme-main-styles", get_theme_file_uri('build/build.css'), array(), $GLOBALS['current_theme']->get( 'Version' ));
}

add_action("wp_enqueue_scripts", "required_frontend_files");