<?php
if ( ! defined( 'ABSPATH' ) ) {
  http_response_code(403);
  exit();
}

class digitalmediate_wp_toolkit_AdminPages extends digitalmediate_wp_toolkit {
  public function addAdminPageToMenu() {
    add_menu_page("Digital Mediate - WP Toolkit", "DM WP Toolkit", "manage_options", "dm-toolkit", false, "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iMTkuOTM0IiBoZWlnaHQ9IjE4LjExOSIgdmlld0JveD0iMCAwIDE5LjkzNCAxOC4xMTkiPjxkZWZzPjxjbGlwUGF0aCBpZD0iYSI+PHJlY3Qgd2lkdGg9IjE5LjkzNCIgaGVpZ2h0PSIxOC4xMTkiIGZpbGw9IiNjZWNlY2UiLz48L2NsaXBQYXRoPjwvZGVmcz48ZyBjbGlwLXBhdGg9InVybCgjYSkiPjxwYXRoIGQ9Ik0tNDI3NS41ODItOTE0MC41NDlhMi4wNTEsMi4wNTEsMCwwLDEsMS41ODEtMi40MzRoLjAxMWEyLjAxNywyLjAxNywwLDAsMSwxLjU3MS4zNzksMi4wMDcsMi4wMDcsMCwwLDEsLjc2OSwxLjIyNmMuMDIuMTI2LjAyOC4yNTEuMDM0LjMyMWEyLjA3NCwyLjA3NCwwLDAsMS0xLjY0MSwyLjA1NCwxLjg3NiwxLjg3NiwwLDAsMS0uMzUxLjAzM0EyLjA5LDIuMDksMCwwLDEtNDI3NS41ODItOTE0MC41NDlabS0xMC41NDMsMS40Yy0uMTY3LS4wMzQtLjMzNC0uMDctLjUtLjEwNXMtLjMyLS4xLS40ODEtLjE0NGMtLjEyMi0uMDQ3LS4yNDMtLjA5Mi0uMzY3LS4xMzhsLS4yMTctLjA5Yy0uMDkzLS4wNDctLjE4OS0uMS0uMjg1LS4xNDZzLS4yLS4xLS4zLS4xNTljLS4wNTgtLjAzNC0uMTE1LS4wNjktLjE3NS0uMTA2cy0uMTQzLS4xLS4yMTctLjE1YS41NTcuNTU3LDAsMCwxLS4xMjktLjA4M2wwLDAtLjQ0NC0uMzUzLS4wMSwwYy0uMDctLjA1OC0uMTMxLS4xMjQtLjItLjE4Ni0uMDk0LS4wODktLjE4OS0uMTc1LS4yODMtLjI2MS0uMTM4LS4xNjYtLjI3Ni0uMzMtLjQxNC0uNDkzcy0uMjQ3LS4zMzUtLjM3MS0uNXEtLjIzOS0uNDI4LS40NzQtLjg1NGE3LjA1LDcuMDUsMCwwLDEtLjQ0OS0zLjk4N2MuMDE5LS4xMTIuMDM4LS4yMjcuMDU2LS4zMzguMDg5LS4yODEuMTc1LS41NTguMjY1LS44MzcuMDE2LS4wNDMuMDM4LS4wODguMDU2LS4xMy4wODItLjE3OS4xNjItLjM1OC4yNDQtLjUzN2wuMzQ2LS41NjhjLjA3My0uMTA4LjE0NS0uMjE2LjIxOS0uMzIzLjA1NC0uMDY4LjEtLjE0LjE2MS0uMjA2bC4wMDgtLjAwOC4zMjUtLjM0LjE3OC0uMTg1LjItLjJjLjA1Mi0uMDQ0LjEtLjA4OS4xNTMtLjEzMS4wODctLjA3MS4xNzUtLjE0NC4yNjMtLjIxMnMuMTYtLjExNS4yMzktLjE3NWwuMjgxLS4xNzkuMjcxLS4xNjFjLjE1NS0uMDc4LjMxMS0uMTU4LjQ2OC0uMjM1bC41NDItLjIyMS40ODItLjE0NmMuMTQ1LS4wMzUuMjktLjA2OC40MzYtLjEuMzU2LS4wNDQuNzEtLjA4OSwxLjA2NS0uMTMxbDEuMjIxLjA3YTUuNzE5LDUuNzE5LDAsMCwxLDEuMTk1LjI5M2wuOC4zMjhjLjE4My4xLjM2Ni4yLjU3NC4zMTN2LS45NDRjMC0uMDQsMC0uMDgsMC0uMTJ2LTQuMDQzYzAtLjE1MiwwLS4xNTIuMTUxLS4xNjdhMS4zMTQsMS4zMTQsMCwwLDAsLjE0OS0uMDE5bC40MTItLjA2OGMuMTQtLjAyOC4yNzctLjA1NC40MTQtLjA4MmwuNjc1LS4xMDhjLjIzMS0uMDM1LjQ2NC0uMDc0LjY5NS0uMTExLjItLjAzNS40LS4wNzIuNjA1LS4xMDkuMDkyLS4wMTguMTgzLS4wMzEuMjkxLS4wNXY5LjU0N2MwLC4wMywwLC4wNjMsMCwuMXMwLC4wOCwwLC4xMnY3LjY1NmMwLC4wMzksMCwuMDgsMCwuMTJ2LjMyM2gtMy4xMzNjLS4yNDUuMDA5LS4yLjAxMS0uMjA1LS4yLDAtLjIzNiwwLS40NzgsMC0uNzE2LDAtLjAzOS0uMDA4LS4wNzctLjAxMi0uMTM1LS4xMTEuMDc1LS4yMDcuMTQyLS4zLjIwNi0uMTM1LjA4Ny0uMjcxLjE3Ny0uNDA4LjI2M2E0LjQwOSw0LjQwOSwwLDAsMS0xLjEzOC40OTNjLS4zNC4wNzEtLjY4MS4xNDUtMS4wMjEuMjE1LS4yNzUuMDE1LS41NTIuMDMxLS44MjcuMDQ0LS4xMjQsMC0uMjQ4LDAtLjM3MSwwQy00Mjg1LjY2MS05MTM5LjA4Ni00Mjg1Ljg5NC05MTM5LjExNi00Mjg2LjEyNS05MTM5LjE0NlptLTEuOTU0LTUuNDc4Yy4wODMuMjIuMTY5LjQzOC4yNTUuNjU1bC4zOTMuNjI4Yy4xNjcuMTcuMzM0LjMzNi41LjUwNmEuMTUxLjE1MSwwLDAsMSwuMDIuMDM3Yy4xNzQuMTE3LjM0Ny4yMzYuNTIuMzUxLjIxOS4xLjQzOC4xOTQuNjU3LjI5MWEuMjYzLjI2MywwLDAsMSwuMDQyLjAzMmMuMTE2LjAzLjIzMy4wNjEuMzQ5LjA5NGwuODI3LjA4M2MuMzU0LS4wMzQuNzA5LS4wNjUsMS4wNjQtLjFhMy45MSwzLjkxLDAsMCwwLDEuNTQ3LS42ODFsLjQxMi0uMzU1Yy4wMy0uMDY1LjA4OC0uMTMxLjA4OC0uMnEuMDA2LTIuNDEzLDAtNC44MjZhLjI0OS4yNDksMCwwLDAtLjA2Mi0uMTVjLS4wODItLjA4Ni0uMTc1LS4xNjQtLjI2NS0uMjQzbC0uODI3LS41YTQuNzcyLDQuNzcyLDAsMCwwLTEuMTc1LS4zNSw1LjU1Miw1LjU1MiwwLDAsMC0xLjE5NC0uMDMzbC0uODI5LjE5MmMtLjA3MS4wMTgtLjE0NC4wNDQtLjIxNS4wNjZsLS40MTYuMjE1Yy0uMTYuMTA2LS4zMTcuMjE0LS40NzQuMzItLjE3NS4xNzMtLjM0OS4zNDUtLjUyNC41Mi0uMTI5LjItLjI1OS4zOTItLjM5LjU4Ni0uMDc4LjE4LS4xNTguMzU3LS4yMzcuNTM1LS4wMjYuMDk0LS4wNTIuMTg4LS4wNzQuMjgyLDAsLjAyMS0uMDA5LjAzOS0uMDEzLjA1Ny0uMDIyLjEtLjA0Mi4xOTQtLjA1OC4yOTItLjAwNi4wMzQtLjAxMi4wNjgtLjAxOC4xcy0uMDEyLjA5LS4wMTguMTM2YS4yNTIuMjUyLDAsMCwxLDAsLjAzOWMwLC4wNC0uMDA4LjA4Mi0uMDEyLjEyMnMtLjAwNi4xMDgtLjAwNi4xNjFjMCwuMDI4LDAsLjA1NiwwLC4wODd2LjAyMkMtNDI4OC4xNzItOTE0NS4zLTQyODguMTI3LTkxNDQuOTYzLTQyODguMDc5LTkxNDQuNjIzWiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoNDI5MS41NSA5MTU3LjA5MSkiIGZpbGw9IiNjZWNlY2UiIHN0cm9rZT0icmdiYSgwLDAsMCwwKSIgc3Ryb2tlLXdpZHRoPSIxIi8+PHBhdGggZD0iTTE1My4wMTgsNTI1Ljc2aDBsMC0uMDExLDAsLjAxIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMTQ5LjY4NiAtNTE0LjMxNSkiIGZpbGw9IiNjZWNlY2UiLz48cGF0aCBkPSJNMTkyLjAxNywzOTAuOTRsLS41MjMuNTJoMGwuNTIzLS41MiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTE4Ny4zMjkgLTM4Mi40MzcpIiBmaWxsPSIjY2VjZWNlIi8+PHBhdGggZD0iTTE1My4wMTgsNTI1Ljc2aDBsMC0uMDExLDAsLjAxIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMTQ5LjY4NiAtNTE0LjMxNSkiIGZpbGw9IiNjZWNlY2UiLz48cGF0aCBkPSJNMTkyLjAxNywzOTAuOTRsLS41MjMuNTJoMGwuNTIzLS41MiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTE4Ny4zMjkgLTM4Mi40MzcpIiBmaWxsPSIjY2VjZWNlIi8+PC9nPjwvc3ZnPg==", 90);
    add_submenu_page("dm-toolkit", "Nastavení - Digital Mediate WP Toolkit", "Nastavení", "manage_options", "dm-toolkit", array($this, 'AdminPageContentSettings'));
    //add_submenu_page("dm-toolkit", "Log", "Log", "manage_options", "dm-toolkit-log", array($this, 'AdminPageContentLog'));
  }

  public function AdminPageContentSettings() {
    global $dm_toolkit_config;

    add_settings_error('dm-wp-kit-settings-updated-successfully', 'updated', 'Nastavení bylo úspěšně uloženo.', 'success');
    add_settings_error('dm-wp-kit-settings-updated-error', 'error', 'Při ukládání nastavení došlo k chybě.', 'error');

    if (!empty($_POST) AND isset($_POST['dm_toolkit_form_update_settings'])) {

      if (isset( $_POST['nonce_dm_toolkit_form_update_settings'] ) && wp_verify_nonce( $_POST['nonce_dm_toolkit_form_update_settings'], 'dm_toolkit_form_update_settings' ) ) {

        //reset config first
        if (isset($dm_toolkit_config) AND is_array($dm_toolkit_config)) {
          foreach ($dm_toolkit_config as $key => $value) {
            $dm_toolkit_config[$key] = 0;
          }
        }

        foreach ($_POST as $key => $input) {
          $value = false;
          if (strpos($key, "dm_toolkit_") === 0) {
            if ($input === "1") {
              $value = 1;
            } else {
              $value = 0;
            }
          } elseif (strpos($key, "config_") === 0) {
            $value = wp_unslash($input); //i dont know why, but WP default escape post strings
          }

          if ($value !== false AND isset($dm_toolkit_config) AND is_array($dm_toolkit_config)) {
            $dm_toolkit_config[$key] = $value;
          }
        }

        $this->saveSettingsToConfigFile($dm_toolkit_config);

        settings_errors('dm-wp-kit-settings-updated-successfully');

      } else {
        settings_errors('dm-wp-kit-settings-updated-error');
      }
    }
    ?>
      <div class="wrap">
        <h1>Nastavení - Digital Mediate WP Toolkit</h1>

        <form action="" method="POST">
          <input type="hidden" name="dm_toolkit_form_update_settings" value="1">
          <?php wp_nonce_field( 'dm_toolkit_form_update_settings', 'nonce_dm_toolkit_form_update_settings' ); ?>

          <h2 style="color: #0035d1;">Nastavení (globální)</h2>
          <table class="form-table" role="presentation">
          <?php
            foreach ($this->global_config_inputs as $value) {
              if (isset($value["type"]) AND $value["type"] == "textarea") {
                $this->createTextarea($value["name"], $value["description"]);
              } else {
                $this->createInput($value["name"], $value["description"]);
              }
            }
          ?>
          </table>

          <h2 style="color: #0035d1;">Optimalizace - WP</h2>
          <table class="form-table" role="presentation">
          <?php
            $this->createInputCheckbox("dm_toolkit_disable_jquery_migrate", "Zakázat jQuery migrate", $dm_toolkit_config["dm_toolkit_disable_jquery_migrate"]);
            $this->createInputCheckbox("dm_toolkit_disable_jquery", "Zakázat defaultní jQuery (v head) a vložit ho do patičky webu", $dm_toolkit_config["dm_toolkit_disable_jquery"]);
            $this->createInputCheckbox("dm_toolkit_disable_wp_emoji", "Zakázat WP Emoji", $dm_toolkit_config["dm_toolkit_disable_wp_emoji"]);
            $this->createInputCheckbox("dm_toolkit_disable_wp_styles", "Zakázat defaultní WordPress styly", $dm_toolkit_config["dm_toolkit_disable_wp_styles"]);
          ?>
          </table>

          <h2 style="color: #0035d1;">Optimalizace - WC</h2>
          <table class="form-table" role="presentation">
          <?php
            $this->createInputCheckbox("dm_toolkit_disable_wc_styles", "Zakázat defaultní WooCommerce styly", $dm_toolkit_config["dm_toolkit_disable_wc_styles"]);
          ?>
          </table>

          <h2 style="color: #0035d1;">Média</h2>
          <table class="form-table" role="presentation">
          <?php
            $this->createInputCheckbox("dm_toolkit_svg_support", "SVG podpora", $dm_toolkit_config["dm_toolkit_svg_support"]);
            $this->createInputCheckbox("dm_toolkit_webp", "Konverze do WebP", $dm_toolkit_config["dm_toolkit_webp"]);
            $this->createInputCheckbox("dm_toolkit_disable_big_image_treshold", "Zakázat limit 2560px u šířky obrázků", $dm_toolkit_config["dm_toolkit_disable_big_image_treshold"]);
          ?>
          </table>

          <h2 style="color: #0035d1;">Obsah</h2>
          <table class="form-table" role="presentation">
          <?php
            $this->createInputCheckbox("dm_toolkit_disable_p_in_excerpt", "Zákazat <p> v Excerpt", $dm_toolkit_config["dm_toolkit_disable_p_in_excerpt"]);
            $this->createInputCheckbox("dm_toolkit_disable_empty_string_in_search", "Zákazat prázdný řetězec ve WP vyhledávání", $dm_toolkit_config["dm_toolkit_disable_empty_string_in_search"]);
          ?>
          </table>

          <h2 style="color: #0035d1;">Simple History plugin</h2>
          <table class="form-table" role="presentation">
          <?php
            $this->createInputCheckbox("dm_toolkit_simple_history_allow_emailing", "Posílat chybové logy na e-mail administrátora webu", $dm_toolkit_config["dm_toolkit_simple_history_allow_emailing"]);
          ?>
          </table>

          <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Uložit změny"></p>

        </form>
      </div>
    <?
  }

  function createInputCheckbox($key, $name, $current_value, $info = null) {
    echo '<tr>
      <th scope="row"><label for="'.esc_attr($key).'">'.esc_html($name).'</label></th>
      <td><input type="checkbox" name="'.esc_attr($key).'" id="'.esc_attr($key).'" value="1"'. checked(1, $current_value, false) .'>';
    if (isset($info)) echo '<p class="description">'.esc_html($info).'</p>';
    
    echo '</td></tr>';
  }

  function createInput($key, $name, $info = null) {
    global $dm_toolkit_config;

    echo '<tr>
      <th scope="row"><label for="'.esc_attr($key).'">'.esc_html($name).'</label></th>
      <td><input type="text" name="'.esc_attr($key).'" id="'.esc_attr($key).'" value="'.esc_attr((isset($dm_toolkit_config[$key]) ? $dm_toolkit_config[$key] : "")).'">';
    if (isset($info)) echo '<p class="description">'.esc_html($info).'</p>';
    
    echo '</td></tr>';
  }

  function createTextarea($key, $name) {
    global $dm_toolkit_config;

    echo '<tr>
      <th scope="row"><label for="'.esc_attr($key).'">'.esc_html($name).'</label></th>
      <td><textarea cols="60" rows="8" name="'.esc_attr($key).'" id="'.esc_attr($key).'">'.esc_html((isset($dm_toolkit_config[$key]) ? $dm_toolkit_config[$key] : "")).'</textarea>';
    
    echo '</td></tr>';
  }

  public function saveSettingsToConfigFile(array $data) {
    $phpCode = '<?php $dm_toolkit_config = ' . var_export($data, true) . ';';

    // Write the PHP code to the file
    file_put_contents($this->PATH . '/config.php', $phpCode);
  }

  /*
  public function AdminPageContentLog() {
    ?>
      <div class="wrap">
        <h1>Dynamická dm_toolkit - Log (záznamy)</h1>
        <table class="my-styled-table">
          <?php
          $handle = fopen($this->PATH . "/log/errors.log", "r");
          if ($handle) {
            $result = array();
            while (($line = fgets($handle)) !== false) {
              $cas = explode(";", $line, 2);
              if (!empty($cas[0]) AND ctype_digit($cas[0])) {
                $result[] = '<tr><td>'. wp_date( get_option( 'date_format' ) . " " . get_option( 'time_format' ), $cas[0] ) . '</td><td>'.esc_html($cas[1]).'</td></tr>';
              }
            }

            fclose($handle);

            $result = array_reverse($result);
            foreach ($result as $value) {
              echo $value . PHP_EOL;
            }
          }
          ?>
        </table>
        <style>
          .my-styled-table {
            margin-top: 25px;
          }

          .my-styled-table tr td {
            border-bottom: 1px solid #000;
            padding: 5px;
          }

          .my-styled-table tr td:first-child {
            white-space: nowrap;
            font-weight: bold;
            padding-right: 10px;
          }

          .my-styled-table tr td:last-child {
            word-break: break-all;
          }
        </style>
      </div>
    <?php
  }
  */
}