<?php
/*
    Plugin Name: Zálohování a údržba od Digital Mediate
    Description: Slouží k automatickému zálohování celého webu (soubory i databáze) do složky /wp-content/digitalmediate-zalohovani-backups-files/. Dále provádí automatickou údržbu webu první den v každém měsíci ve 2 ráno.
    Author: Digital Mediate
    Author URI: https://www.digitalmediate.cz/
    Version: 1.2.8
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You Shall Not Pass!' );
}

// Check SSL Mode
if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && ( $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) ) {
	$_SERVER['HTTPS'] = 'on';
}

// Plugin Version
define( 'DIGITALMEDIATE_ZALOHOVANI_VERSION', "1.2.8" );

// Plugin Basename
define( 'DIGITALMEDIATE_ZALOHOVANI_PLUGIN_BASENAME', basename( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . basename( __FILE__ ) );

// Plugin Path
define( 'DIGITALMEDIATE_ZALOHOVANI_PATH', dirname( __FILE__ ) );

// Plugin inc Path
define( 'DIGITALMEDIATE_ZALOHOVANI_INC_PATH', DIGITALMEDIATE_ZALOHOVANI_PATH . DIRECTORY_SEPARATOR . "inc" );

// Wordpress Path
define( 'DIGITALMEDIATE_ZALOHOVANI_WP_PATH', rtrim(ABSPATH, "/\\") );

// Plugin URL
define( 'DIGITALMEDIATE_ZALOHOVANI_URL', plugins_url( '', DIGITALMEDIATE_ZALOHOVANI_PLUGIN_BASENAME ) );

// Backups URL
define( 'DIGITALMEDIATE_ZALOHOVANI_BACKUPS_URL', content_url( 'digitalmediate-zalohovani-backups-files', DIGITALMEDIATE_ZALOHOVANI_PLUGIN_BASENAME ) );

// Backups Path
define( 'DIGITALMEDIATE_ZALOHOVANI_BACKUPS_PATH', str_replace("/", DIRECTORY_SEPARATOR, WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'digitalmediate-zalohovani-backups-files') );

// Themes Path
define( 'DIGITALMEDIATE_ZALOHOVANI_THEMES_PATH', get_theme_root() );

// TIME_OFFSET
define( 'DIGITALMEDIATE_ZALOHOVANI_TIME_OFFSET', current_datetime()->getOffset() );

//Test, delete this
//var_dump(DIGITALMEDIATE_ZALOHOVANI_PLUGIN_BASENAME, DIGITALMEDIATE_ZALOHOVANI_PATH, DIGITALMEDIATE_ZALOHOVANI_URL, DIGITALMEDIATE_ZALOHOVANI_BACKUPS_URL, DIGITALMEDIATE_ZALOHOVANI_BACKUPS_PATH, DIGITALMEDIATE_ZALOHOVANI_THEMES_PATH, DIGITALMEDIATE_ZALOHOVANI_INC_PATH, DIGITALMEDIATE_ZALOHOVANI_TIME_OFFSET);

class DigitalMediateZalohovaniPlugin {
    public $error;
    public $error_ok;

    function __construct() {
      $this->error = "";
      $this->error_ok = "";

      add_action('admin_menu', array($this, 'adminPage'));
      add_action('admin_init', array($this, 'settings'));
      //add_action('init', array($this, 'languages'));

      //Custom Intervals
      add_filter( 'cron_schedules', array($this, 'add_cron_interval'));

      //CRON HOOK
      add_action( 'digitalmediate_zalohovani_cron_hook', array($this, 'zalohuj') );

      //Zalohuj databazi HOOK
      add_action( 'digitalmediate_zalohovani_zalohuj_databazi_hook', array($this, 'zalohuj_databazi') );

      //DELETE HOOK
      add_action( 'digitalmediate_zalohovani_delete_hook', array($this, 'smazatStareZalohy') );

      //CHECK HOOK
      add_action( 'digitalmediate_zalohovani_check_hook', array($this, 'zkontrolujPosledniExport') );

      //UDRZBA HOOK
      add_action( 'digitalmediate_zalohovani_udrzba', array($this, 'udrzbuj') );

      //Deactivate CRON HOOK when plugin is deactivated
      register_deactivation_hook( __FILE__, array($this, 'plugin_deactivated') ); 

      //Plugin is activated
      register_activation_hook( __FILE__, array($this, 'plugin_activated') );

      //Options changed
      add_filter( 'pre_update_option_digitalmediate_zalohovani_time', array($this, 'optionsChanged') );

      //POST
      add_action( 'admin_init', array($this, 'zpracuj_post_data') );

      //Debug
      //add_action( 'init', function() { do_action( 'digitalmediate_zalohovani_cron_hook' ); } );
      //add_action( 'init', function() { do_action( 'digitalmediate_zalohovani_delete_hook' ); } );
      //add_action( 'init', function() { do_action( 'digitalmediate_zalohovani_check_hook' ); } );
      //add_action( 'init', function() { do_action( 'digitalmediate_zalohovani_udrzba' ); } );
    }
  /*
    function languages() {
      load_plugin_textdomain('wcpdomain', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }*/

    function plugin_activated() {
        //Vytvoříme složku pro zálohování
        if (!file_exists(DIGITALMEDIATE_ZALOHOVANI_BACKUPS_PATH)) {
          mkdir(DIGITALMEDIATE_ZALOHOVANI_BACKUPS_PATH, 0777, true);
        }
        //Vložíme .htaccess
        $handle = fopen(DIGITALMEDIATE_ZALOHOVANI_BACKUPS_PATH. DIRECTORY_SEPARATOR . ".htaccess", 'w');
        fwrite($handle, "Order deny,allow\r\nDeny from all");
        fclose($handle);

        //Nastavíme options s autoload=false
        add_option('digitalmediate_zalohovani_doing_now', '0', '', false);
        add_option('digitalmediate_zalohovani_allow', '0', '', false);
        add_option('digitalmediate_zalohovani_every', '3', '', false);
        add_option('digitalmediate_zalohovani_time', '01:00', '', false);
        add_option('digitalmediate_zalohovani_autodelete', '1', '', false);
        add_option('digitalmediate_zalohovani_autodelete_size', '5', '', false);
        add_option('digitalmediate_zalohovani_last_export_file', '', '', false);

        //Set CRON
        if ( ! wp_next_scheduled( 'digitalmediate_zalohovani_cron_hook' ) ) {

          $first_time = strtotime(date("Y-m-d 1:00", time() - DIGITALMEDIATE_ZALOHOVANI_TIME_OFFSET)) - DIGITALMEDIATE_ZALOHOVANI_TIME_OFFSET;
          if ($first_time-60 < time()) {
            //Add one day
            $first_time = $first_time + 86400;
            if ($first_time-60 < time()) {
              //Another one day
              $first_time = $first_time + 86400;
            }
          }
          wp_schedule_event( $first_time, 'two_weeks', 'digitalmediate_zalohovani_cron_hook' );
        }

        //Set single event na udržbu
        $first_time = strtotime(date("Y-m-01 2:00", time() + 3456000 - DIGITALMEDIATE_ZALOHOVANI_TIME_OFFSET)) - DIGITALMEDIATE_ZALOHOVANI_TIME_OFFSET;
        wp_schedule_single_event( $first_time, "digitalmediate_zalohovani_udrzba" );
    }

    function plugin_deactivated() {
        $timestamp = wp_next_scheduled( 'digitalmediate_zalohovani_cron_hook' );
        wp_unschedule_event( $timestamp, 'digitalmediate_zalohovani_cron_hook' );

        $timestamp = wp_next_scheduled( 'digitalmediate_zalohovani_udrzba' );
        wp_unschedule_event( $timestamp, 'digitalmediate_zalohovani_udrzba' );

        //Delete setting options
        delete_option('digitalmediate_zalohovani_doing_now');
        delete_option('digitalmediate_zalohovani_allow');
        delete_option('digitalmediate_zalohovani_every');
        delete_option('digitalmediate_zalohovani_time');
        delete_option('digitalmediate_zalohovani_autodelete');
        delete_option('digitalmediate_zalohovani_autodelete_size');
        delete_option('digitalmediate_zalohovani_last_export_file');
    }

    function add_cron_interval( $schedules ) {
      $schedules['one_hour'] = array(
        'interval' => 3600,
        'display'  => esc_html__( 'Every one hour' )
      );
      $schedules['one_day'] = array(
        'interval' => 86400,
        'display'  => esc_html__( 'Every one day' )
      );
      $schedules['one_week'] = array(
        'interval' => 604800,
        'display'  => esc_html__( 'Every one week' )
      );
      $schedules['two_weeks'] = array(
        'interval' => 1209600,
        'display'  => esc_html__( 'Every two weeks' )
      );
      $schedules['one_month'] = array(
        'interval' => 2592000,
        'display'  => esc_html__( 'Every one month' )
      );
      $schedules['six_months'] = array(
        'interval' => 2592000*6,
        'display'  => esc_html__( 'Every six months' )
      );

      return $schedules;
    }

    function zalohuj($rychle = false) {
        @ini_set('max_execution_time', 300);

        if (!$rychle AND get_option("digitalmediate_zalohovani_allow") == "0") {
          //zálohování není povoleno, ukončíme to
          return false;
        }

        // Include DM_zalohovani_HZip class
        if (!class_exists('DM_zalohovani_HZip')) require_once DIGITALMEDIATE_ZALOHOVANI_INC_PATH . DIRECTORY_SEPARATOR . 'DM_zalohovani_HZip.class.php';

        // Include Mysqldump class
        if (!class_exists('Ifsnop\Mysqldump\Mysqldump')) require_once DIGITALMEDIATE_ZALOHOVANI_INC_PATH . DIRECTORY_SEPARATOR . 'Mysqldump.php';

        //Provádíme zálohování!
        update_option("digitalmediate_zalohovani_doing_now", '1');

        $start_time = time() + DIGITALMEDIATE_ZALOHOVANI_TIME_OFFSET;
        $clean_siteurl = $this->clean_url(get_site_url());

        $new_zip_file = DIGITALMEDIATE_ZALOHOVANI_BACKUPS_PATH . DIRECTORY_SEPARATOR . 'zaloha_'. $clean_siteurl . '__' . date("Y-m-d__H-i", $start_time).'__'. bin2hex(random_bytes(24)) .'.zip';

        //Nastavíme last_export_file
        update_option("digitalmediate_zalohovani_last_export_file", $new_zip_file);

        //Zalohovat soubory
        DM_zalohovani_HZip::zipDir(DIGITALMEDIATE_ZALOHOVANI_WP_PATH, $new_zip_file);

        //README
        $readme_content = "Záloha vytvořena dne ". date("d.m.Y H:i:s", $start_time) . " místního času.";
        $readme_content .= PHP_EOL . PHP_EOL . "SITE Name:\t".get_bloginfo("name");
        $readme_content .= PHP_EOL . "SITE URL:\t".get_site_url();
        $readme_content .= PHP_EOL . "WP version:\t".get_bloginfo("version");
        $readme_content .= PHP_EOL . PHP_EOL . "Automaticky vygenerováno WP pluginem: Zálohování od Digital Mediate " . DIGITALMEDIATE_ZALOHOVANI_VERSION;
        $readme_content .= PHP_EOL . "www.digitalmediate.cz";

        //Zapíšeme info do jiz vytvoreneho .zip
        $zip = new ZipArchive;
        $zip->open($new_zip_file , ZipArchive::CREATE);
        $zip->addFromString('readme.txt', $readme_content);
        $zip->addFromString('abspath.txt', ABSPATH);
        $zip->setArchiveComment("WP backup of ". get_site_url() . " created on " . date("d.m.Y H:i", $start_time) . PHP_EOL . "Generated by WP plugin \"Zalohovani od Digital Mediate\" version ".DIGITALMEDIATE_ZALOHOVANI_VERSION." / Author: www.digitalmediate.cz");
        $zip->close();

        if ($rychle) {
          $this->zalohuj_databazi(true);
        } else {
          //Nastavíme single event na zálohu databáze po 1 minutě
          wp_schedule_single_event( time() + 60, "digitalmediate_zalohovani_zalohuj_databazi_hook" );
        }
    }

    function zalohuj_databazi($rychle = false) {
      @ini_set('max_execution_time', 300);

      if (get_option("digitalmediate_zalohovani_doing_now") == "1") {

        //Nastavíme single event na automatické promazávání záloh za další hodinu
        if (get_option("digitalmediate_zalohovani_autodelete") == "1") {
          wp_schedule_single_event( time() + 3600, "digitalmediate_zalohovani_delete_hook" );
        }

        //Nastavíme single event na kontrolu exportního souboru
        if (!$rychle) wp_schedule_single_event( time() + 360, "digitalmediate_zalohovani_check_hook" );

        // Include Mysqldump class
        if (!class_exists('Ifsnop\Mysqldump\Mysqldump')) require_once DIGITALMEDIATE_ZALOHOVANI_INC_PATH . DIRECTORY_SEPARATOR . 'Mysqldump.php';

        $last_export_file = get_option("digitalmediate_zalohovani_last_export_file");

        //Zalohovat DB do souboru
        $mysql_backup_file = DIGITALMEDIATE_ZALOHOVANI_BACKUPS_PATH . DIRECTORY_SEPARATOR . bin2hex(random_bytes(24)) . '.sql';

        $mysql_dumpSettings = array(
          'default-character-set' => Ifsnop\Mysqldump\Mysqldump::UTF8MB4
        );

        $mysql_dump = new Ifsnop\Mysqldump\Mysqldump('mysql:host='.DB_HOST.';dbname='.DB_NAME.'', DB_USER, DB_PASSWORD, $mysql_dumpSettings);
        $mysql_dump->start($mysql_backup_file);

        //DB soubor do jiz vytvoreneho .zip
        $zip = new ZipArchive;
        $zip->open($last_export_file , ZipArchive::CREATE);
        $zip->addFile($mysql_backup_file, "database.sql");
        $zip->close();

        //Smažeme soubor zálohy DB
        unlink($mysql_backup_file);

        if ($rychle) $this->zkontrolujPosledniExport(true);
      }
    }

    function udrzbuj() {
      @ini_set('max_execution_time', 300);

      if (get_option("digitalmediate_zalohovani_doing_now") == "0") {

        //Nastavíme další single event na příští měsíc
        wp_schedule_single_event( strtotime(date("Y-m-01 2:00", time() + 3456000 - DIGITALMEDIATE_ZALOHOVANI_TIME_OFFSET)) - DIGITALMEDIATE_ZALOHOVANI_TIME_OFFSET, "digitalmediate_zalohovani_udrzba" );

        include_once ABSPATH . "wp-admin/includes/update.php";
        include_once ABSPATH . "wp-admin/includes/plugin.php";
        include_once ABSPATH . "wp-admin/includes/misc.php";
        include_once ABSPATH . "wp-admin/includes/file.php";

        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        wp_cache_flush();

        $current_wp_version = get_bloginfo('version');

        $core_updates = get_core_updates();
        if ( isset( $core_updates[0]->version ) && version_compare( $core_updates[0]->version, $current_wp_version, '>' ) ) {
          $version = $core_updates[0]->version;
          $locale  = $core_updates[0]->locale;
          $update  = find_core_update( $version, $locale );

          //var_dump($update);

          $upgrader = new Core_Upgrader();
          $new_version   = $upgrader->upgrade($update);
        }

        //START UPGRADING PLUGINS
        wp_cache_flush();

        $plugins = get_plugins();

        $plugins_to_upgrade = array();
        foreach ($plugins as $key => $value) {
          $plugins_to_upgrade[] = $key;
        }

        $upgrader = new Plugin_Upgrader( new Bulk_Plugin_Upgrader_Skin() );
        $upgrader->bulk_upgrade( $plugins_to_upgrade );

        $plugins_after_upgrade = get_plugins();

        $plugins_updated = array();
        foreach ($plugins_after_upgrade as $key => $plugin) {
          if ($plugins[$key]['Version'] !== $plugin['Version']) {
            $plugins_updated[] = array($plugin['Name'], $plugins[$key]['Version'], $plugin['Version'], time()); 
          }
        }
        //END UPGRADING PLUGINS

        $message = "Byla provedena automatická údržba webu.\r\nWeb: " . get_bloginfo('url') . "\r\n\r\n";
        if (isset($new_version) AND is_string($new_version)) $message .= 'Aktualizované jádro WordPress:' . "\r\n" . $current_wp_version . ' ➔ ' . $new_version;

        if (count($plugins_updated) > 0) {
          $message .= "Aktualizované pluginy:\r\n";
          foreach ($plugins_updated as $value) {
            $message .= $value[0] . ' | ' . $value[1] . ' ➔ ' . $value[2] . "\r\n";
          }
        }

        $message .= "\r\n(Tento automatický e-mail zaslal plugin: Zálohování a údržba od Digital Mediate. Pokud nechcete dostávat tyto e-maily, tak tento plugin na výše zmíněném webu deaktivujte.)";

        $headers[] = 'Content-Type: text/plain; charset=utf-8';
        $headers[] = 'From: hello@digitalmediate.cz';
        $headers[] = 'Content-Transfer-Encoding: base64';
        $headers[] = 'X-Mailer: PHP/' . phpversion();

        mail(get_bloginfo('admin_email'),
          '=?UTF-8?B?' . base64_encode('Automatická údržba webu: '.get_bloginfo('name')) . '?=',
          base64_encode($message),
          implode("\r\n", $headers)
        );
      } else {
        //zrovna zálohujeme, zkusíme údržbu spustit až za dalších 10 minut
        //Nastavíme single event na údržbu po 10 minutách
        wp_schedule_single_event( time() + 600, "digitalmediate_zalohovani_udrzba" );
      }
    }

    function nactiZalohu($zaloha_file, $mysql_dump_from_special_file_path = false) {
      if (preg_match('/^.+\.zip$/i', $zaloha_file)) {
        //Zkontrolujeme platnost souboru zálohy

        $zaloha_file = DIGITALMEDIATE_ZALOHOVANI_BACKUPS_PATH . DIRECTORY_SEPARATOR .basename($zaloha_file);
        
        if (file_exists($zaloha_file)) {

          //Zkontrolujeme platnost zálohy
          if ($this->zipIntegrityCheck($zaloha_file)) {

            //Vytvoříme maintenance soubor
            $maintenance_file = '<?php http_response_code(503); ?><!doctype html><head><title>Údržba webu</title><meta charset="UTF-8"><link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet"><style>html, body{padding: 0; margin: 0; width: 100%; height: 100%;}*{box-sizing: border-box;}body{text-align: center; padding: 0; background: #d6a03b; color: #fff; font-family: Open Sans;}h1{font-size: 50px; font-weight: 100; text-align: center;}body{font-family: Open Sans; font-weight: 100; font-size: 20px; color: #fff; text-align: center; display: -webkit-box; display: -ms-flexbox; display: flex; -webkit-box-pack: center; -ms-flex-pack: center; justify-content: center; -webkit-box-align: center; -ms-flex-align: center; align-items: center;}article{display: block; width: 700px; padding: 50px; margin: 0 auto;}a{color: #fff; font-weight: bold;}a:hover{text-decoration: none;}svg{width: 75px; margin-top: 1em;}</style></head><article> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 202.24 202.24"><defs><style>.cls-1{fill:#fff;}</style></defs><title>Asset 3</title><g id="Layer_2" data-name="Layer 2"><g id="Capa_1" data-name="Capa 1"><path class="cls-1" d="M101.12,0A101.12,101.12,0,1,0,202.24,101.12,101.12,101.12,0,0,0,101.12,0ZM159,148.76H43.28a11.57,11.57,0,0,1-10-17.34L91.09,31.16a11.57,11.57,0,0,1,20.06,0L169,131.43a11.57,11.57,0,0,1-10,17.34Z"/><path class="cls-1" d="M101.12,36.93h0L43.27,137.21H159L101.13,36.94Zm0,88.7a7.71,7.71,0,1,1,7.71-7.71A7.71,7.71,0,0,1,101.12,125.63Zm7.71-50.13a7.56,7.56,0,0,1-.11,1.3l-3.8,22.49a3.86,3.86,0,0,1-7.61,0l-3.8-22.49a8,8,0,0,1-.11-1.3,7.71,7.71,0,1,1,15.43,0Z"/></g></g></svg> <h1>Za chvíli jsme zpátky!</h1> <div> <p>Omlouváme se ale tyto webové stránky jsou v tuto chvíli nedostupné kvůli plánované údržbě webu. Zkuste tyto webové stránky znovu navštívit za chvíli.</p><p>&mdash; '.esc_html(get_bloginfo("name")).'</p></div></article>';
            $handle = fopen(DIGITALMEDIATE_ZALOHOVANI_WP_PATH . DIRECTORY_SEPARATOR . "digitalmediate_maintenance.php", "w");
            fwrite($handle, $maintenance_file);
            fclose($handle);
            
            //Vytvoříme htaccess soubor
            $htaccess_file = "allow from all".PHP_EOL."RewriteEngine on".PHP_EOL."RewriteRule .* digitalmediate_maintenance.php [L]";
            $handle = fopen(DIGITALMEDIATE_ZALOHOVANI_WP_PATH . DIRECTORY_SEPARATOR . ".htaccess", "w");
            fwrite($handle, $htaccess_file);
            fclose($handle);

            //Extrahujeme všechny soubory ze zipu
            $new_extract_folder = DIGITALMEDIATE_ZALOHOVANI_BACKUPS_PATH . DIRECTORY_SEPARATOR . "zaloha_" . bin2hex(random_bytes(24));
            mkdir($new_extract_folder);

            $zip = new ZipArchive;
            $res = $zip->open($zaloha_file);
            if ($res === TRUE) {
              $zip->extractTo($new_extract_folder);
              $zip->close();
            }

            //Smažeme všechny soubory z domény (kromě složky digitalmediate-zalohovani-backups-files)
            $domain_files = scandir(DIGITALMEDIATE_ZALOHOVANI_WP_PATH);
            foreach ($domain_files as $file) {
              if ($file !== "." AND $file !== ".." AND $file !== "digitalmediate_maintenance.php" AND $file !== ".htaccess") {
                if (is_dir(DIGITALMEDIATE_ZALOHOVANI_WP_PATH . DIRECTORY_SEPARATOR . $file)) {
                  $this->rrmdir(DIGITALMEDIATE_ZALOHOVANI_WP_PATH . DIRECTORY_SEPARATOR . $file);
                } else {
                  unlink(DIGITALMEDIATE_ZALOHOVANI_WP_PATH . DIRECTORY_SEPARATOR . $file);
                }
              }
            }

            //Nakopírujeme nové soubory z extrahované složky
            foreach (
              $iterator = new \RecursiveIteratorIterator(
               new \RecursiveDirectoryIterator($new_extract_folder . DIRECTORY_SEPARATOR . "files", \RecursiveDirectoryIterator::SKIP_DOTS),
               \RecursiveIteratorIterator::SELF_FIRST) as $item
            ) {
               if ($item->isDir()) {
                  @mkdir(DIGITALMEDIATE_ZALOHOVANI_WP_PATH . DIRECTORY_SEPARATOR . $iterator->getSubPathname());
               } else {
                  if ($item->getPathname() == $new_extract_folder . DIRECTORY_SEPARATOR . "files" . DIRECTORY_SEPARATOR . ".htaccess") {
                    $main_htaccess_file = $new_extract_folder . DIRECTORY_SEPARATOR . "files" . DIRECTORY_SEPARATOR . ".htaccess";
                    continue;
                  }

                  if ($item->getPathname() == $new_extract_folder . DIRECTORY_SEPARATOR . "files" . DIRECTORY_SEPARATOR . "wp-config.php") {
                    $wp_config_file = $new_extract_folder . DIRECTORY_SEPARATOR . "files" . DIRECTORY_SEPARATOR . "wp-config.php";
                    continue;
                  }

                  copy($item, DIGITALMEDIATE_ZALOHOVANI_WP_PATH . DIRECTORY_SEPARATOR . $iterator->getSubPathname());
               }
            }



            //Databáze
            /* activate reporting */
            $driver = new mysqli_driver();
            $driver->report_mode = MYSQLI_REPORT_ERROR;

            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

            if ($mysqli->connect_errno) {
              $this->error = 'Nelze se připojit k databázi, chyba: ' . $mysqli->connect_error;
            }

            if ($mysqli) {

              $mysqli->set_charset(DB_CHARSET);

              //Smažeme všechny tabulky
              $mysqli->query('SET foreign_key_checks = 0');
              if ($result = $mysqli->query("SHOW TABLES")) {
                while($row = $result->fetch_array(MYSQLI_NUM)) {
                  $mysqli->query('DROP TABLE IF EXISTS '.$row[0]);
                }
              }
              $mysqli->query('SET foreign_key_checks = 1');

              if ($mysql_dump_from_special_file_path !== false) {
                $database_file_handle = fopen(DIGITALMEDIATE_ZALOHOVANI_BACKUPS_PATH . DIRECTORY_SEPARATOR . basename($mysql_dump_from_special_file_path), "r");
              } else {
                $database_file_handle = fopen($new_extract_folder . DIRECTORY_SEPARATOR . 'database.sql', "r");
              }

              if ($database_file_handle) {
                $templine = "";
                $mysql_errors = "";
                while (($line = fgets($database_file_handle)) !== false) {
                  if (substr($line, 0, 2) == '--' || $line == '') continue;

                  // Add this line to the current segment
                  $templine .= $line;

                  if (substr(trim($line), -1, 1) == ';') {
                    // Perform the query
                    if (!$mysqli->query($templine)) {
                      $mysql_errors .= 'Error performing query \'<strong>' . $templine . '</strong>\': ' . $mysqli->error . '<br /><br />';
                    }
                    // Reset temp variable to empty
                    $templine = '';
                  }
                }

                fclose($database_file_handle);
              } else {
                $this->error = 'Nelze načíst soubor databáze.';
              }

              /* execute multi query */
              //var_dump($mysqli->multi_query(file_get_contents($new_extract_folder . DIRECTORY_SEPARATOR . 'database.sql')));

              $mysqli->close();

            }


            //Zkopírujeme hlavní htaccess
            if (isset($main_htaccess_file) AND file_exists($main_htaccess_file)) {
              copy($main_htaccess_file, DIGITALMEDIATE_ZALOHOVANI_WP_PATH . DIRECTORY_SEPARATOR . ".htaccess");
            }

            //Smažeme maintenance soubor
            unlink(DIGITALMEDIATE_ZALOHOVANI_WP_PATH . DIRECTORY_SEPARATOR . "digitalmediate_maintenance.php");

            //Smazat tmp special file
            if ($mysql_dump_from_special_file_path !== false) {
              unlink(DIGITALMEDIATE_ZALOHOVANI_BACKUPS_PATH . DIRECTORY_SEPARATOR . basename($mysql_dump_from_special_file_path));
            }

            //Zkopírujeme WP_CONFIG
            if (isset($wp_config_file) AND file_exists($wp_config_file)) {
              copy($wp_config_file, DIGITALMEDIATE_ZALOHOVANI_WP_PATH . DIRECTORY_SEPARATOR . "wp-config.php");
            }

            //Smažeme celou složku s nově extrahovanými soubory
            $this->rrmdir($new_extract_folder, true);

            $this->error_ok = 'Záloha ze souboru "'.basename($zaloha_file).'" byla úspěšně načtena.';

            wp_die("Záloha ze souboru byla úspěšně načtena.<br><br>Všeobecné chyby: ". $this->error ."<br><br>Chyby z MySQL:<br><br>" . $mysql_errors);

          } else {
            $this->error = 'Vybraný soubor zálohy má špatný obsah/integritu. Zálohu nelze načíst.';
          }
        } else {
          $this->error = 'Vybraný soubor zálohy nebyl nalezen.';
        }
      } else {
        $this->error = 'Vybrali jste neplatný soubor zálohy.';
      }
    }

    //Kontrola validního zip exportu
    function zipIntegrityCheck($zip_file_path) {
      $zip = new ZipArchive;
      $res = $zip->open($zip_file_path);
      if ($res === TRUE) {
        if ($zip->locateName('readme.txt') !== FALSE AND 
            $zip->locateName('database.sql') !== FALSE AND 
            $zip->locateName('files/wp-admin/') !== FALSE AND 
            $zip->locateName('files/wp-content/') !== FALSE AND 
            $zip->locateName('files/wp-includes/') !== FALSE) {
            
            return true;
        }
        $zip->close();
      }
      return false;
    }

    function smazatStareZalohy() {
      if (get_option("digitalmediate_zalohovani_autodelete") == "1" AND get_option("digitalmediate_zalohovani_autodelete_size") >= 1) {

        $total_file_size = 0;
        foreach ($this->scandir_ordered_by_date(DIGITALMEDIATE_ZALOHOVANI_BACKUPS_PATH) as $file) {
          $file_path = DIGITALMEDIATE_ZALOHOVANI_BACKUPS_PATH . DIRECTORY_SEPARATOR . $file;
          $total_file_size = $total_file_size + filesize($file_path);
          
          //Pokud přesáhneme hranici začneme mazat
          if ($total_file_size > (get_option("digitalmediate_zalohovani_autodelete_size")*1024*1024*1024)) {
            if (is_dir($file_path)) {
              rrmdir($file_path);
            } else {
              unlink($file_path);
            }
          }
        }
      }
    }

    function zkontrolujPosledniExport($rychle = false) {
      $last_export_file = get_option("digitalmediate_zalohovani_last_export_file");
      if ($last_export_file !== "") {
        if (!$this->zipIntegrityCheck($last_export_file)) {
          //Kontrola dopadla špatně, smažeme tento soubor a vytvoříme novou zálohu za hodinu
          unlink($last_export_file);
          if (!$rychle) wp_schedule_single_event( time()+3600, "digitalmediate_zalohovani_cron_hook" );
          if ($rychle) throw new Exception("Kontrola souboru nedopadla v pořádku. Zkuste opakovat zálohování.");
        } else {
          //kontrola proběhla v pořádku, již neprovádíme zálohování
          update_option("digitalmediate_zalohovani_doing_now", '0');
        }
      }
    }

    function optionsChanged($val) {
      //smazat starý cron
      $timestamp = wp_next_scheduled( 'digitalmediate_zalohovani_cron_hook' );
      wp_unschedule_event( $timestamp, 'digitalmediate_zalohovani_cron_hook' );

      //vytvořit nový cron
      switch (get_option('digitalmediate_zalohovani_every')) {
        case '1':
          $interval = "one_day";
          break;

        case '2':
          $interval = "one_week";
          break;
        
        case '4':
          $interval = "one_month";
          break;
        
        case '5':
          $interval = "six_months";
          break;
        
        //Default value '3'
        default:
          $interval = "two_weeks";
          break;
      }

      $first_time = strtotime(date("Y-m-d ".$val, time() - DIGITALMEDIATE_ZALOHOVANI_TIME_OFFSET)) - DIGITALMEDIATE_ZALOHOVANI_TIME_OFFSET;

      if ($first_time-60 < time()) {
        //Add one day
        $first_time = $first_time + 86400;
        if ($first_time-60 < time()) {
          //Another one day
          $first_time = $first_time + 86400;
        }
      }

      wp_schedule_event( $first_time, $interval, 'digitalmediate_zalohovani_cron_hook' );

      return $val;
    }

    function scandir_ordered_by_date($dir) {
      $ignored = array('.', '..', '.svn', '.htaccess');
  
      $files = array();    
      foreach (scandir($dir) as $file) {
          if (in_array($file, $ignored)) continue;
          $files[$file] = filemtime($dir . '/' . $file);
      }
  
      arsort($files);
      $files = array_keys($files);
  
      return ($files) ? $files : false;
    }

    function clean_url($url) {
      $url = preg_replace('/.*\/\//i','',$url);
      $url = str_replace("/","_",$url);
      $url = preg_replace('/[^0-9a-zA-Z_\-\.]/i','',$url);
      return $url;
    }

    function rrmdir($dir, $all = false) {
      if (is_dir($dir)) {
        if (!preg_match('/digitalmediate\-zalohovani\-backups\-files/', $dir) OR $all) {
          $objects = scandir($dir);
          foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
              if (filetype($dir."/".$object) == "dir") {
                $this->rrmdir($dir."/".$object, $all);
              } else {
                unlink ($dir."/".$object);
              }
            }
          }
          reset($objects);
          @rmdir($dir);
        }
      }
    }

    function migrate_database_change_values($mysql_dump, $stara_abspath, $stara_domena, $nova_domena) {
      if (empty($stara_domena) OR empty($nova_domena)) {
        return "Chyba: stará a nová doména nesmí být prázdná!";
      }

      if (empty($stara_abspath)) {
        return "Chyba: ABSPATH v záloze nesmí být prázdný.";
      }

      if (empty($mysql_dump)) {
        return "Chyba: MySQL dump nesmí být prázdný.";
      }

      $pattern = '/(s:\d+:\\\".*?\\\";)/'; //patern pro serialize data

      $callback = function ($matches) use ($stara_abspath, $stara_domena, $nova_domena) {
        //V této funkci budeme měnit obsah v SERIALIZED data

        $matches[1] = str_replace('\\\\', '\\', $matches[1]);
        $matches[1] = str_replace('\\\'', '\'', $matches[1]);
        $matches[1] = str_replace('\\"', '"', $matches[1]);

        $unserializedData = @unserialize($matches[1]);

        if ($unserializedData AND !empty($unserializedData)) {
          //here make some changes
          //napřed přepsat absolutní cesty:
          $unserializedData = str_replace($stara_abspath, ABSPATH, $unserializedData);
          $unserializedData = str_replace(str_replace("/", "\/", $stara_abspath), str_replace("/", "\/", ABSPATH), $unserializedData); //escaped version

          //přepsat veškeré jiné cesty:
          $unserializedData = str_replace($stara_domena, $nova_domena, $unserializedData);
          $unserializedData = str_replace(str_replace(".", "\.", $stara_domena), str_replace(".", "\.", $nova_domena), $unserializedData); //dots is escaped

          $newSerializedData = serialize($unserializedData);
  
          $newSerializedData = str_replace('\\', '\\\\', $newSerializedData);
          $newSerializedData = str_replace('\'', '\\\'', $newSerializedData);
          $newSerializedData = str_replace('"', '\\"', $newSerializedData);
  
          return $newSerializedData;
        }

        return $matches[0];
      };
    
      // Perform the replacement in the MySQL dump text
      $mysql_dump = preg_replace_callback($pattern, $callback, $mysql_dump);

      //napřed přepsat absolutní cesty:
      $mysql_dump = str_replace($stara_abspath, ABSPATH, $mysql_dump);
      $mysql_dump = str_replace(str_replace("/", "\/", $stara_abspath), str_replace("/", "\/", ABSPATH), $mysql_dump); //escaped version
      
      //přepsat veškeré jiné cesty:
      $mysql_dump = str_replace($stara_domena, $nova_domena, $mysql_dump);
      $mysql_dump = str_replace(str_replace(".", "\.", $stara_domena), str_replace(".", "\.", $nova_domena), $mysql_dump); //dots is escaped

      return $mysql_dump;
    }

    function migrate_database_zpracuj_velkou_databazi_a_nahrad_vsechny_texty($sql_soubor_ke_zpracovani, $stara_abspath, $stara_domena, $nova_domena) {
      $database_file_handle = fopen($sql_soubor_ke_zpracovani, "r");

      $new_database_file_name = DIGITALMEDIATE_ZALOHOVANI_BACKUPS_PATH . DIRECTORY_SEPARATOR . 'tmp_new_databaze_migrate__' . wp_date("Y-m-d__H-i").'__'. bin2hex(random_bytes(24)) .'.sql';
      $new_database_file_handle = fopen($new_database_file_name, "w");

      if ($database_file_handle) {
        $templine = "";
        while (($line = fgets($database_file_handle)) !== false) {
          // Add this line to the current segment
          $templine .= $line;

          if (substr(trim($line), -1, 1) == ';') {
            fwrite($new_database_file_handle, $this->migrate_database_change_values($templine, $stara_abspath, $stara_domena, $nova_domena));
            // Reset temp variable to empty
            $templine = '';
          }
        }

        //write the last templine
        fwrite($new_database_file_handle, $this->migrate_database_change_values($templine, $stara_abspath, $stara_domena, $nova_domena));

        fclose($new_database_file_handle);

        return $new_database_file_name;
      } else {
        $this->error = 'Nelze načíst soubor databáze.';
        return false;
      }
    }
  
    function settings() {
      add_settings_section('digitalmediate-zalohovani_first_section', null, null, 'digitalmediate-zalohovani-settings-page');

      add_settings_field('digitalmediate_zalohovani_allow', 'Povolit automatické zálohování', array($this, 'allowHTML'), 'digitalmediate-zalohovani-settings-page', 'digitalmediate-zalohovani_first_section');
      register_setting('digitalmediate-zalohovani', 'digitalmediate_zalohovani_allow', array('sanitize_callback' => 'sanitize_text_field', 'default' => '0'));

      add_settings_field('digitalmediate_zalohovani_every', 'Automaticky zálohovat', array($this, 'everyHTML'), 'digitalmediate-zalohovani-settings-page', 'digitalmediate-zalohovani_first_section');
      register_setting('digitalmediate-zalohovani', 'digitalmediate_zalohovani_every', array('sanitize_callback' => 'sanitize_text_field', 'default' => '3'));
      register_setting('digitalmediate-zalohovani', 'digitalmediate_zalohovani_time', array('sanitize_callback' => array($this, 'sanitizeTime'), 'default' => '01:00'));

      add_settings_field('digitalmediate_zalohovani_autodelete', 'Promazávání', array($this, 'autoDeleteHTML'), 'digitalmediate-zalohovani-settings-page', 'digitalmediate-zalohovani_first_section');
      register_setting('digitalmediate-zalohovani', 'digitalmediate_zalohovani_autodelete', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
      register_setting('digitalmediate-zalohovani', 'digitalmediate_zalohovani_autodelete_size', array('sanitize_callback' => array($this, 'sanitizeVelikost'), 'default' => '5'));

      //Google drive
      //add_settings_section('digitalmediate-zalohovani_sec_section', '<img src="'. DIGITALMEDIATE_ZALOHOVANI_URL . DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'google_drive.svg" alt="Google Drive™" height="32">', function() { echo '<p>Pokud povolíte zálohování na Google Drive™, tak budou na zvolený disk automaticky zasílány zálohy jednou měsíčně. Jedná se o pojistku proti ztrátě záloh uložených na tomto webu.</p>'; }, 'digitalmediate-zalohovani-settings-page');
      //add_settings_field('digitalmediate_google_drive', 'Povolit Google Drive™', array($this, 'checkboxHTML'), 'digitalmediate-zalohovani-settings-page', 'digitalmediate-zalohovani_sec_section', array('label_for' => "digitalmediate_google_drive", 'disabled' => true));
      //register_setting('digitalmediate-zalohovani', 'digitalmediate_google_drive', array('sanitize_callback' => 'sanitize_text_field', 'default' => '0'));


      /*
      add_settings_field('wcp_location', 'Display Location', array($this, 'locationHTML'), 'word-count-settings-page', 'wcp_first_section');
      register_setting('wordcountplugin', 'wcp_location', array('sanitize_callback' => array($this, 'sanitizeLocation'), 'default' => '0'));
  
      add_settings_field('wcp_headline', 'Headline Text', array($this, 'headlineHTML'), 'word-count-settings-page', 'wcp_first_section');
      register_setting('wordcountplugin', 'wcp_headline', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Statistics'));
  
      add_settings_field('wcp_wordcount', 'Word Count', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_wordcount'));
      register_setting('wordcountplugin', 'wcp_wordcount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
  
      add_settings_field('wcp_charactercount', 'Character Count', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_charactercount'));
      register_setting('wordcountplugin', 'wcp_charactercount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
  
      add_settings_field('wcp_readtime', 'Read Time', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_readtime'));
      register_setting('wordcountplugin', 'wcp_readtime', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
      */
    }

    function zpracuj_post_data() {
      if (!empty($_POST) AND isset($_POST["provest-zalohu"])) {
        if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'digitalmediate_form_action_provest-zalohu')) {
          try {
            $this->zalohuj(true);

            $file = get_option("digitalmediate_zalohovani_last_export_file");

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file)); 
            header('Content-Transfer-Encoding: binary');
            header('Connection: Keep-Alive');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));

            readfile($file);
            
            exit;
          } catch(Exception $e) {
            $this->error = 'Při vytváření zálohy došlo k chybě: ' .$e->getMessage();
          }
        } else {
          wp_die('Security check failed.');
        }
      } elseif (!empty($_POST) AND isset($_POST["dokoncit-migraci"])) {
        if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'digitalmediate_form_action_dokoncit-migraci') AND isset($_GET['nonce']) && wp_verify_nonce($_GET['nonce'], 'digitalmediate_form_action_migrate_file')) {
          //Delete tmp database folder
          if (isset($_POST["digitalmediate_migrate_path_tmp_database_folder"])) {
            $this->rrmdir(DIGITALMEDIATE_ZALOHOVANI_BACKUPS_PATH . DIRECTORY_SEPARATOR . basename($_POST["digitalmediate_migrate_path_tmp_database_folder"]), true);
          }

          if (isset($_POST["digitalmediate_migrate_path_tmp_new_databaze_migrate"])) {
            $this->nactiZalohu($_GET["migrate_file"], $_POST["digitalmediate_migrate_path_tmp_new_databaze_migrate"]);
          } else {
            $this->error = 'Nastala chyba při nahrávání souboru.';
          }
        } else {
          wp_die('Security check failed.');
        }
      } elseif (!empty($_POST) AND isset($_POST["digitalmediate_zalohovani_select_zaloha"])) {
        if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'digitalmediate_form_action_nacist_zalohu')) {
          if (isset($_POST["digitalmediate_zalohovani_migrace_ano"]) AND $_POST["digitalmediate_zalohovani_migrace_ano"] == "1") {
            wp_safe_redirect(admin_url('options-general.php?page=digitalmediate-zalohovani-settings-page&migrate_file='.urlencode($_POST["digitalmediate_zalohovani_select_zaloha"]).'&stara='.urlencode($_POST["digitalmediate_zalohovani_migrace_stara_domena"]).'&nova='.urlencode($_POST["digitalmediate_zalohovani_migrace_nova_domena"]).'&nonce='.urlencode(wp_create_nonce('digitalmediate_form_action_migrate_file'))));
            exit;
          } else {
            $this->nactiZalohu($_POST["digitalmediate_zalohovani_select_zaloha"]);
          }
        } else {
          wp_die('Security check failed.');
        }
      } elseif (!empty($_POST) AND isset($_POST["odeslat-zalohu"])) {
        if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'digitalmediate_form_action_odeslat-zalohu')) {
          $uploaded_file = $_FILES["digitalmediate_input_zaloha_ze_souboru"];

          if (isset($uploaded_file["error"]) AND $uploaded_file["error"] === 0) {
            if (isset($uploaded_file["type"]) AND $uploaded_file["type"] === "application/x-zip-compressed") {
              if (preg_match('/^zaloha_(.+)__(\d+)-(\d+)-(\d+)__(\d+)-(\d+)__(.+)\.zip$/i', $uploaded_file["name"], $matches)) {
                $new_filename = "zaloha_".$matches[1]."-nahrano__".$matches[2]."-".$matches[3]."-".$matches[4]."__".$matches[5]."-".$matches[6]."__".$matches[7].".zip";
                if (move_uploaded_file($uploaded_file["tmp_name"], DIGITALMEDIATE_ZALOHOVANI_BACKUPS_PATH . DIRECTORY_SEPARATOR . basename($new_filename))) {
                  $this->error_ok = 'Záloha byla úspěšně nahrána. Pro její načtení ji vyberte ze seznamu.';
                } else {
                  $this->error = 'Při nahrávání zálohy došlo k nečekané chybě.';
                }
              } else {
                $this->error = 'Nahraný soubor zálohy nemá platný název, tento soubor nesmíte přejmenovat!';
              }
            } else {
              $this->error = 'Nahraný soubor zálohy musí být ve formátu .zip!';
            }
          } else {
            $this->error = 'Při nahrávání souboru došlo k nečekané chybě.';
          }
        } else {
          wp_die('Security check failed.');
        }
      }
    }

    function sanitizeVelikost($input) {
      if (!is_numeric($input) OR $input == 0 OR $input > 9999999) {
        add_settings_error('digitalmediate_zalohovani_autodelete', 'digitalmediate-zalohovani_autodelete_size_error', 'Velikost všech záloh musí být celé číslo a větší jak 0');
        return get_option('digitalmediate_zalohovani_autodelete_size');
      }
      return intval($input);
    }

    function sanitizeTime($input) {
      $input = sanitize_text_field($input);
      if (!preg_match('/\d{2}:\d{2}/',$input)) {
        add_settings_error('digitalmediate_zalohovani_time', 'digitalmediate_zalohovani_time_error', 'Zadejte platný čas pro zálohování, např.: 23:55');
        return get_option('digitalmediate_zalohovani_time');
      }
      return $input;
    }
  
    // reusable checkbox function
    function checkboxHTML($args) { ?>
      <input type="checkbox" name="<?php echo $args['label_for'] ?>" id="<?php echo $args['label_for'] ?>" value="1" <?php checked(get_option($args['label_for']), '1') ?><?php if(isset($args['disabled']) AND $args['disabled']) echo ' disabled' ?>>
    <?php }
  
    function headlineHTML() { ?>
      <input type="text" name="wcp_headline" value="<?php echo esc_attr(get_option('wcp_headline')) ?>">
    <?php }

    function allowHTML() { ?>
      <fieldset>
        <label for="digitalmediate_zalohovani_allow">
        <input name="digitalmediate_zalohovani_allow" type="checkbox" id="digitalmediate_zalohovani_allow" value="1" <?php checked(get_option('digitalmediate_zalohovani_allow'), '1') ?>>
      </fieldset>
    <?php }

    function autoDeleteHTML() { ?>
      <fieldset>
        <label for="digitalmediate_zalohovani_autodelete">
        <input name="digitalmediate_zalohovani_autodelete" type="checkbox" id="digitalmediate_zalohovani_autodelete" value="1" <?php checked(get_option('digitalmediate_zalohovani_autodelete'), '1') ?>>
        Automaticky smazat staré zálohy, pokud velikost všech záloh přesáhne </label>
        <label for="digitalmediate_zalohovani_autodelete_size"><input name="digitalmediate_zalohovani_autodelete_size" type="number" min="1" step="1" id="digitalmediate_zalohovani_autodelete_size" value="<?php echo esc_attr(get_option('digitalmediate_zalohovani_autodelete_size')) ?>" class="small-text"> GB</label>
      </fieldset>
    <?php }
  
    function everyHTML() { ?>
      <fieldset>
        <label for="digitalmediate_zalohovani_every">každých <select name="digitalmediate_zalohovani_every" id="digitalmediate_zalohovani_every">
          <option value="1" <?php selected(get_option('digitalmediate_zalohovani_every'), '1') ?>>1 den</option>
          <option value="2" <?php selected(get_option('digitalmediate_zalohovani_every'), '2') ?>>7 dní</option>
          <option value="3" <?php selected(get_option('digitalmediate_zalohovani_every'), '3') ?>>14 dní</option>
          <option value="4" <?php selected(get_option('digitalmediate_zalohovani_every'), '4') ?>>1 měsíc</option>
          <option value="5" <?php selected(get_option('digitalmediate_zalohovani_every'), '5') ?>>6 měsíců</option>
        </select> </label>
        <label for="digitalmediate_zalohovani_time">v čase <input name="digitalmediate_zalohovani_time" id="digitalmediate_zalohovani_time" type="time" value="<?php echo esc_attr(get_option('digitalmediate_zalohovani_time')) ?>"></label>
        <p class="description"><i>Příští záloha webu proběhne: <?php echo date("d.m.Y H:i:s", wp_next_scheduled( 'digitalmediate_zalohovani_cron_hook' ) + DIGITALMEDIATE_ZALOHOVANI_TIME_OFFSET); ?></i></p>
        <p class="description"><i>Příští údržba webu proběhne: <?php echo date("d.m.Y H:i:s", wp_next_scheduled( 'digitalmediate_zalohovani_udrzba' ) + DIGITALMEDIATE_ZALOHOVANI_TIME_OFFSET); ?></i></p>
      </fieldset>
    <?php }
  
    function adminPage() {
      add_options_page('Zálohování od Digital Mediate Nastavení', 'Zálohování od DM', 'manage_options', 'digitalmediate-zalohovani-settings-page', array($this, 'ourHTML'));
    }
  
    function ourHTML() { ?>
      <div class="wrap">
        <?php
        if ($this->error !== "") {
        ?>
        <div class="error notice">
          <p><?php echo $this->error;?></p>
        </div>
        <?php
        } elseif ($this->error_ok !== "") {
        ?>
        <div class="notice notice-success is-dismissible">
          <p><?php echo $this->error_ok;?></p>
        </div>
        <?php
        }
        ?>


        <?php
        if (isset($_GET["migrate_file"]) AND !empty($_GET["migrate_file"])) {
          if (isset($_GET['nonce']) && wp_verify_nonce($_GET['nonce'], 'digitalmediate_form_action_migrate_file')) {
        ?>
        <h2>Provést migraci ze souboru <?=esc_html($_GET["migrate_file"])?></h2>
        <?php
          $zip = new ZipArchive;
          if ($zip->open(DIGITALMEDIATE_ZALOHOVANI_BACKUPS_PATH . DIRECTORY_SEPARATOR . basename($_GET["migrate_file"])) === TRUE) {
            // Check if the file exists in the ZIP
            $index = $zip->locateName('database.sql');
            $index2 = $zip->locateName('abspath.txt');
            if ($index !== false AND $index2 !== false) {
              // Extract the file contents
              $tmp_database_folder = DIGITALMEDIATE_ZALOHOVANI_BACKUPS_PATH . DIRECTORY_SEPARATOR . 'tmp_databaze_migrate__' . wp_date("Y-m-d__H-i").'__'. bin2hex(random_bytes(24));
              $zip->extractTo($tmp_database_folder, 'database.sql');
              $abspath_txt_contents = $zip->getFromIndex($index2);
          
              // Close the ZIP file
              $zip->close();

              $new_database_path = $this->migrate_database_zpracuj_velkou_databazi_a_nahrad_vsechny_texty($tmp_database_folder . DIRECTORY_SEPARATOR . 'database.sql', $abspath_txt_contents, $_GET["stara"], $_GET["nova"]);
        ?>
        <form method="post">
          <?php wp_nonce_field('digitalmediate_form_action_dokoncit-migraci', 'nonce', true, true) ?>
          <input type="hidden" name="digitalmediate_migrate_path_tmp_database_folder" value="<?=esc_attr(basename($tmp_database_folder))?>">
          <input type="hidden" name="digitalmediate_migrate_path_tmp_new_databaze_migrate" value="<?=esc_attr(basename($new_database_path))?>">
          <p>Běž ve FTP do <b><?=esc_url($new_database_path)?></b>, otevři v editoru a pro jistotu zkontroluj zda-li nikde nezůstala stará doména. Soubor ulož a pokračuj.</p>
          <p>Starý ABSPATH: <?=esc_html($abspath_txt_contents)?></p>
          <p>Nový ABSPATH: <?=esc_html(ABSPATH)?></p>
          <p>Starý web: <?=esc_html($_GET["stara"])?></p>
          <p>Nový web: <?=esc_html($_GET["nova"])?></p>
        <?php
          submit_button("Dokončit migraci", "large", "dokoncit-migraci");
        ?>
        </form>
                    <?php
                } else {
                    echo "Chyba: Soubor database.sql nebo abspath.txt nebyl v ZIP nalezen.";
                }
            } else {
                echo "Chyba: Nelze otevřít ZIP soubor.";
            }
          } else {
            wp_die('Security check failed.');
          }
        }
        ?>

        <h1>Zálohování od Digital Mediate Nastavení</h1>
        <form action="options.php" method="POST">
        <?php
          settings_fields('digitalmediate-zalohovani');
          do_settings_sections('digitalmediate-zalohovani-settings-page');
          submit_button();
        ?>
        </form>

        <h2>Okamžitá záloha</h2>
        <form method="POST">
          <?php wp_nonce_field('digitalmediate_form_action_provest-zalohu', 'nonce', true, true) ?>
          <?php
            submit_button("Provést okamžitou zálohu a stáhnout", "large", "provest-zalohu");
          ?>
        </form>

        <h2>Načíst zálohu</h2>
        <form method="POST" onsubmit="submitNacistZalohu(); return false" id="digitalmediate_zalohovani_form_nacist_zalohu">
          <?php wp_nonce_field('digitalmediate_form_action_nacist_zalohu', 'nonce', true, true) ?>
          <select name="digitalmediate_zalohovani_select_zaloha" required>
            <?php
            $files = scandir(DIGITALMEDIATE_ZALOHOVANI_BACKUPS_PATH);
            $files = array_diff( $files, [".", "..", ".htaccess"] );

            if (count($files) > 0) {
              echo '<option value="" selected disabled style="display: none">Vyberte zálohu ze seznamu</option>';
              foreach ($files as $file) {
                if (preg_match('/^zaloha_(.+)__(\d+)-(\d+)-(\d+)__(\d+)-(\d+)__(.+)\.zip$/i', $file, $matches)) {
                  echo '<option value="'.esc_attr($file).'">'.esc_html($matches[4].".".$matches[3].".".$matches[2]." ".$matches[5].":".$matches[6]." - ".$matches[1]).'</option>';
                }
              }
            } else {
              echo '<option value="" selected disabled style="display: none">Doposud nebyla vytvořena žádná záloha</option>';
            }
            ?>
          </select>
          <label>
            <input type="checkbox" name="digitalmediate_zalohovani_migrace_ano" value="1" id="">
            <input type="hidden" name="digitalmediate_zalohovani_migrace_stara_domena" value="">
            <input type="hidden" name="digitalmediate_zalohovani_migrace_nova_domena" value="">
            Provést migraci
          </label>
          <?php
            submit_button("Načíst vybranou zálohu", "secondary", "nacist-zalohu", false);
          ?>
          <p class="description"><i>Načtení zálohy může trvat delší dobu, vyčkejte prosím. V žádném případě nepřerušujte načítání webu, jinak hrozí fatalní chyba a destrukce celého webu.</i></p>
        </form>
        <script>
          function submitNacistZalohu() {
            let vybrana_zaloha = document.querySelector('form#digitalmediate_zalohovani_form_nacist_zalohu select');
            if (confirm("Opravdu si přejete načíst zálohu "+ vybrana_zaloha.options[vybrana_zaloha.selectedIndex].text +"? Můžete přijít o data a akci nelze vrátit zpět.") == true) {

              if (document.getElementsByName("digitalmediate_zalohovani_migrace_ano")[0].checked) {
                let stara_domena = prompt("Zadejte název STARÉ domény (např.: neco.dmtest.cz):");
                document.getElementsByName("digitalmediate_zalohovani_migrace_stara_domena")[0].value = stara_domena;

                let nova_domena = prompt("Zadejte název NOVÉ domény (např.: www.digitalmediate.cz):");
                document.getElementsByName("digitalmediate_zalohovani_migrace_nova_domena")[0].value = nova_domena;
              }

              document.getElementById("digitalmediate_zalohovani_form_nacist_zalohu").submit();
            } else {
              return false;
            }
          }
        </script>

        <h2>Nahrát zálohu ze souboru</h2>
        <form method="POST" enctype="multipart/form-data">
          <?php wp_nonce_field('digitalmediate_form_action_odeslat-zalohu', 'nonce', true, true) ?>
          <input type="file" name="digitalmediate_input_zaloha_ze_souboru" accept=".zip" required>
          <?php
            submit_button("Odeslat", "small", "odeslat-zalohu", false);
          ?>
        </form>
      </div>
    <?php }
  }
  
  $digitalMediateZalohovaniPlugin = new DigitalMediateZalohovaniPlugin();