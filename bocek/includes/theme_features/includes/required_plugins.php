<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//requirements plugins for this theme
function admin_notice_plugin_not_found($plugin_func, $plugin_name) {
  if (!function_exists($plugin_func)) {
      add_action( 'admin_notices', function() use ($plugin_name) {
          echo '<div class="notice notice-error"><p><b>Chyba šablony:</b> zvolená šablona vyžaduje aktivní plugin &quot;<b>'.esc_html($plugin_name).'</b>&quot; pro správnou funkčnost.</p></div>';
      });
  }
}

admin_notice_plugin_not_found("get_field", "Advanced Custom Fields");
admin_notice_plugin_not_found("custom_login_logo", "Digital Mediate - WP Toolkit");