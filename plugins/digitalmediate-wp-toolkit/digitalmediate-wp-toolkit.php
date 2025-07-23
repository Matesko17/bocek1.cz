<?php
/*
    Plugin Name: Digital Mediate - WP Toolkit
    Description: Základní balíček úprav, nástrojů a vizuálních vylepšení pro zlepšení a optimalizaci WordPress.
    Author: Digital Mediate
    Author URI: https://www.digitalmediate.cz/
    Version: 1.6.1
*/

if ( ! defined( 'ABSPATH' ) ) {
  http_response_code(403);
  exit();
}

require_once(__DIR__ . "/config_list.php");
require_once(__DIR__ . "/config.php");

require_once(__DIR__ . "/inc/AdminPages.php");

require_once(__DIR__ . "/inc/features/disable_wordpress_styles.php");
require_once(__DIR__ . "/inc/features/disable_woocommerce_styles.php");
require_once(__DIR__ . "/inc/features/svg_support.php");
require_once(__DIR__ . "/inc/features/media_to_webp_convertor.php");
require_once(__DIR__ . "/inc/features/hide_meta_wp_generator.php");
require_once(__DIR__ . "/inc/features/disable_p_in_excerpt.php");
require_once(__DIR__ . "/inc/features/disable_empty_string_in_search.php");
require_once(__DIR__ . "/inc/features/disable_big_image_threshold.php");
require_once(__DIR__ . "/inc/features/simple_history_plugin.php");

//Visual
require_once(__DIR__ . "/inc/features_visual/custom_login_logo.php");

class digitalmediate_wp_toolkit {
  const VERSION =       '1.6.1';
  const TEXT_DOMAIN =   'digitalmediate-wp-toolkit';

  public $PATH;
  public $URL;
  public $WP_PATH;
  public $WP_URL;
  public $WP_THEME_PATH;
  public $global_config_inputs;

  public function __construct() {
    global $dm_toolkit_global_config_inputs;
    
    $this->PATH = __DIR__;
    $this->URL = plugins_url( '', __FILE__ );
    $this->WP_PATH = rtrim(ABSPATH, "/\\");
    $this->WP_URL = get_bloginfo("url");
    $this->WP_THEME_PATH = get_theme_root();
    $this->global_config_inputs = $dm_toolkit_global_config_inputs;
  }

  public function main_init() {
    add_action( 'admin_init', array($this, 'admin_init') );

    $digitalmediate_wp_toolkit_AdminPages = new digitalmediate_wp_toolkit_AdminPages();

    //Admin page
    add_action( 'admin_menu', array($digitalmediate_wp_toolkit_AdminPages, 'addAdminPageToMenu') );

    //Deactivate CRON HOOK when plugin is deactivated
    register_deactivation_hook( __FILE__, array($this, 'plugin_deactivated') ); 

    //Plugin is activated
    register_activation_hook( __FILE__, array($this, 'plugin_activated') );
  }

  public function plugin_activated() {
    //
  }

  public function plugin_deactivated() {
    //
  }

  public function admin_init() {
    //
  }
}

global $digitalmediate_wp_toolkit;
$digitalmediate_wp_toolkit = new digitalmediate_wp_toolkit();
$digitalmediate_wp_toolkit->main_init();