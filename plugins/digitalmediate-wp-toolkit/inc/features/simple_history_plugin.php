<?php
if (isset($dm_toolkit_config["dm_toolkit_simple_history_allow_emailing"]) AND $dm_toolkit_config["dm_toolkit_simple_history_allow_emailing"] === 1) {
  add_action('simple_history/log/inserted', function($context, $data_parent_row, $logger) {
    if ($data_parent_row["level"] == "error" OR $data_parent_row["level"] == "critical" OR $data_parent_row["level"] == "emergency") {
      //email only errors

      wp_mail(get_option('admin_email'), "Chyba na webu " . wp_parse_url(site_url(), PHP_URL_HOST), "Byla zaznamenána chyba na vašem webu.\n\nError level: " . $data_parent_row["level"] . "\nError message: " . $data_parent_row["message"] . "\n\n\nTohle je automaticky vygenerovaný e-mail z vašeho webu ".wp_parse_url(site_url(), PHP_URL_HOST));
    }
  }, 10, 3);
}

if (isset($dm_toolkit_config["config_simple_history_days_history"])) {
  $days = intval($dm_toolkit_config["config_simple_history_days_history"]);

  if ($days > 30) {
    add_filter(
      "simple_history/db_purge_days_interval", 
      function( $dayz ) use ($days) {
        return $days;
      }
    );
  }
}