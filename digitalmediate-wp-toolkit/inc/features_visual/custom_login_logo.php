<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

function custom_login_logo() {
  $logo_file_url = get_stylesheet_directory_uri() . '/assets/img/logo-login.svg';
  $logo_file_path = get_stylesheet_directory() . '/assets/img/logo-login.svg';

  if (!file_exists($logo_file_path)) return;

  echo '<style type="text/css">
      #login h1 a, .login h1 a {
        background-image: url('. $logo_file_url .');
        height: 80px;
        width: 250px;
        background-size: contain;
      }
  </style>';
}
add_action('login_head', 'custom_login_logo');

function custom_login_logo_url() {
  return home_url();
}
add_filter('login_headerurl', 'custom_login_logo_url');


class digitalmediate_footer_modifier extends digitalmediate_wp_toolkit {
  public function custom_login_footer_digitalmediate() {
    echo '<a href="https://www.digitalmediate.cz/" target="_blank" id="modified_by_dm"><span>Web vytvořil</span><img src="'.$this->URL.'/assets/digital_mediate.svg" width="112" height="18"></div>';
    echo '<style type="text/css">
        #modified_by_dm {
          display: flex;
          flex-direction: column;
          gap: 3px;
          align-items: center;
          width: 320px;
          margin-inline: auto;
          text-decoration: none;
          color: #3c434a;
        }

        #modified_by_dm span {
          display: block;
          font-size: 11px;
        }

        #modified_by_dm img {
          display: block;
        }
    </style>';
  }

  public function __construct() {
    parent::__construct();
    add_action('login_footer', array($this, 'custom_login_footer_digitalmediate'));
  }
}

new digitalmediate_footer_modifier();