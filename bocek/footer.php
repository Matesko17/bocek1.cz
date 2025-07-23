<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$currnet_lang = pll_current_language('locale');
switch ($currnet_lang) {
    case 'cs_CZ':
        $footer_post_group = 112; // Czech
        $contact_form_post_group = 95; // Czech
        break;
    case 'en_GB':
        $footer_post_group = 541; // English
        $contact_form_post_group = 476; // English
        break;
    default:
        $footer_post_group = 112; // Default to Czech
        $contact_form_post_group = 95; // English
}

?>
    <a id="contact" class="anchor"></a>
    <section class="mainpage_contact_form">
      <div class="container">
        <div class="wrap">
          <img src="<?php echo get_theme_file_uri("assets/img/mainpage/010101.png"); ?>" alt="" class="fly">
          <img src="<?php echo get_theme_file_uri("assets/img/mainpage/contact/bg.webp"); ?>" alt="" class="bg">
          <div class="left">
            <p><?php echo get_field("nadnadpis", $contact_form_post_group) ?></p>
            <h2><?php echo get_field("nadpis", $contact_form_post_group) ?></h2>
            <form action="" method="post">
              <div id="contact_form_success_message">
                <svg class="contact_form_checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="contact_form_checkmark__circle" cx="26" cy="26" r="25" fill="none"/><path class="contact_form_checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"></svg>
                <p><?php echo esc_html(get_field("text:_e-mail_byl_uspesne_odeslan", $contact_form_post_group)) ?></p>
              </div>
              <div id="contact_form_error_message">
                <svg enable-background="new 0 0 50 50" version="1.1" viewBox="0 0 50 50" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" width="56" height="56">
                  <circle cx="25" cy="25" r="25" fill="#D75A4A"/>
                  <polyline points="16 34 25 25 34 16" fill="none" stroke="#fff" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/>
                  <polyline points="16 16 25 25 34 34" fill="none" stroke="#fff" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/>
                </svg>
                <p><?php echo esc_html(get_field("text:_e-mail_nebyl_uspesne_odeslan", $contact_form_post_group)) ?></p>
              </div>
              <div id="contact_form_loading">
                <div class="lds-dual-ring"></div>
              </div>

              <div class="obsah">
                <div class="group">
                  <input type="text" placeholder="<?php echo __("Jméno a Příjmení", "bocek") ?>" name="name" id="input_jmeno">
                  <input type="email" placeholder="<?php echo __("E-mail *", "bocek") ?>" name="email" id="input_email" required>
                </div>
                <input type="tel" placeholder="<?php echo __("Telefon", "bocek") ?>" name="phone" id="input_telefon">
                <textarea placeholder="<?php echo __("Váš vzkaz *", "bocek") ?>" name="message" id="input_zprava" required></textarea>
                
                <div class="col-2">
                  <?php
                    $tlacitko = get_field("odkaz_na_zzou", $contact_form_post_group);
                  ?>
                  <p><?php echo __("O vaše data je u nás postaráno. Přečtěte si naše", "bocek") ?> <a href="<?php echo esc_url($tlacitko["url"]) ?>" target="_blank"><?php echo esc_html($tlacitko["title"]) ?></a></p>
                  <button type="submit" class="button button--gray"><span><?php echo __("Odeslat", "bocek") ?></span><img src="<?php echo get_theme_file_uri("assets/img/svg_sprites/icons/send.svg"); ?>" alt=""></button>
                </div>
              </div>
            </form>
          </div>
          <div class="right">
            <div class="mr_bocek_himself">
              <img src="<?php echo get_theme_file_uri("assets/img/mainpage/contact/mr_bocek_himself.webp"); ?>" alt="">
              <div class="card">
                <h3><?php echo get_field("kontakt_-_jmeno", $contact_form_post_group) ?></h3>
                <div class="contact">
                  <div class="col-2">
                    <img src="<?php echo get_theme_file_uri("assets/img/svg_sprites/icons/phone.svg"); ?>" alt="">
                    <a href="tel:<?php echo esc_attr(str_replace(" ", "", get_field("kontakt_-_telefonni_cislo", $contact_form_post_group))) ?>"><?php echo get_field("kontakt_-_telefonni_cislo", $contact_form_post_group) ?></a>
                  </div>
                  <div class="col-2">
                    <img src="<?php echo get_theme_file_uri("assets/img/svg_sprites/icons/mail.svg"); ?>" alt="">
                    <a href="mailto:<?php echo esc_attr(get_field("kontakt_-_e-mail", $contact_form_post_group)) ?>"><?php echo get_field("kontakt_-_e-mail", $contact_form_post_group) ?></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </main>

  <footer>
    <div class="cols-3">
      <div class="left">
        <?php
          $image_id = get_field("logo", $footer_post_group);
          if (empty($image_id)) $image_id = $dm_toolkit_config["config_zastupny_obrazek"];
          echo '<img src="' . esc_url( wp_get_attachment_url( $image_id ) ) . '" srcset="' . esc_attr( wp_get_attachment_image_srcset( $image_id, "full" ) ) . '" sizes="400px" alt="">';
        ?>
        <div>
          <p><?php echo nl2br(get_field("udaje_o_firme", $footer_post_group)) ?></p>
        </div>
      </div>
      <div class="center">
        <?php
          echo dm_create_wp_menu("footer_menu_left");
        ?>
        <?php
          echo dm_create_wp_menu("footer_menu_right");
        ?>
      </div>
      <div class="right">
        <p><?php echo get_field("nadpis_sledujte_me", $footer_post_group) ?></p>
        <ul>
          <?php
          foreach (get_field("socialni_site", $footer_post_group) as $row) {
            echo '<li><a href="'. esc_url($row["odkaz"]) .'" target="_blank"><img src="'. esc_attr($row["ikonka"]) .'" alt="'. esc_attr($row["alternativni_popisek"]) .'"></a></li>';
          }
          ?>
        </ul>
      </div>
    </div>

    <div class="line"></div>

    <div class="last">
      <p><?php echo get_field("copyright_claim", $footer_post_group) ?></p>
      <div>
        <?php
          echo dm_create_wp_menu("footer_menu_bottom", true);
        ?>
      </div>
      <a href="https://www.digitalmediate.cz/" target="_blank" class="plugin_created_by_dm">
        <video id="js-footer-video-created-by" preload="auto" muted disablepictureinpicture disableremoteplayback playsinline width="170" height="30" title="<?php echo __("Web vytvořil digital mediate.", "bocek") ?>">
          <source src="<?=get_theme_file_uri("assets/img/by_digitalmediate.webm")?>" type="video/webm">
        </video>
        <?php echo svg_from_sprite("assets/img/svg_sprites/by_digitalmediate.svg", 170, 30, __("Web vytvořil digital mediate.", "bocek")) ?>
      </a>
    </div>
  </footer>
<?php
wp_footer();
?>
</body>
</html>