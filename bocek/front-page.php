<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();
?>
    <section class="mainpage_hero">
      <span class="bg" data-lazy-image-loading="<?php echo get_theme_file_uri("assets/img/mainpage/hero_thumbnail.webp"); ?>">
      <img src="<?php echo get_theme_file_uri("assets/img/mainpage/hero_1x.webp"); ?>" 
           srcset="<?php echo get_theme_file_uri("assets/img/mainpage/hero_2x.webp"); ?> 3840w, 
                   <?php echo get_theme_file_uri("assets/img/mainpage/hero_1_5x.webp"); ?> 2880w, 
                   <?php echo get_theme_file_uri("assets/img/mainpage/hero_1x.webp"); ?> 1920w" 
           alt="" 
           loading="lazy">
      </span>
      <div class="container">
        <div class="wrap">
          <h1><?php echo get_field("hero_nadpis_h1") ?></h1>
          <div class="col-2">
            <img src="<?php echo get_theme_file_uri("assets/img/svg_sprites/icons/heart.svg"); ?>" alt="">
            <p><?php echo get_field("hero_text_vedle_srdce") ?></p>
          </div>
          <div class="col-2">
            <img src="<?php echo get_theme_file_uri("assets/img/svg_sprites/icons/30.svg"); ?>" alt="">
            <p><?php echo get_field("hero_text_vedle_30") ?></p>
          </div>
          <a href="#more" class="button button--primary"><span><?php echo esc_html(get_field("hero_text_tlacitka_zjistit_vice")) ?></span><img src="<?php echo get_theme_file_uri("assets/img/svg_sprites/icons/chevron_down.svg"); ?>" alt=""></a>
        </div>
      </div>
    </section>

    <a id="more" class="anchor"></a>
    <section class="mainpage_questions">
      <div class="container">
        <div class="wrap">
          <img src="<?php echo get_theme_file_uri("assets/img/mainpage/010101.png"); ?>" alt="" class="bg">
          <div class="fly_circle"></div>
          <h2><?php echo get_field("zni_nadpis") ?></h2>
          <div class="cards">
            <div>
              <?php
              $blok = get_field("zni_blok_1");
              $image_id = $blok["pozadi"];

              if (empty($image_id)) $image_id = $dm_toolkit_config["config_zastupny_obrazek"];
              echo '<img src="' . esc_url( wp_get_attachment_url( $image_id ) ) . '" srcset="' . esc_attr( wp_get_attachment_image_srcset( $image_id, "full" ) ) . '" sizes="500px" alt="">';
              ?>
              <h3><?php echo $blok["text"] ?> <span><?php echo $blok["text_na_konci_cervene"] ?></span></h3>
            </div>

            <div>
              <?php
              $blok = get_field("zni_blok_2");
              $image_id = $blok["pozadi"];

              if (empty($image_id)) $image_id = $dm_toolkit_config["config_zastupny_obrazek"];
              echo '<img src="' . esc_url( wp_get_attachment_url( $image_id ) ) . '" srcset="' . esc_attr( wp_get_attachment_image_srcset( $image_id, "full" ) ) . '" sizes="500px" alt="">';
              ?>
              <h3><?php echo $blok["text"] ?> <span><?php echo $blok["text_na_konci_cervene"] ?></span></h3>
            </div>

            <div>
              <?php
              $blok = get_field("zni_blok_3");
              $image_id = $blok["pozadi"];

              if (empty($image_id)) $image_id = $dm_toolkit_config["config_zastupny_obrazek"];
              echo '<img src="' . esc_url( wp_get_attachment_url( $image_id ) ) . '" srcset="' . esc_attr( wp_get_attachment_image_srcset( $image_id, "full" ) ) . '" sizes="500px" alt="">';
              ?>
              <h3><?php echo $blok["text"] ?> <span><?php echo $blok["text_na_konci_cervene"] ?></span></h3>
            </div>

          </div>
        </div>
      </div>
    </section>

    <section class="mainpage_why_us">
      <div class="container">
        <div class="wrap">
          <img src="<?php echo get_theme_file_uri("assets/img/mainpage/010101.png"); ?>" alt="" class="fly">
          <div class="fly_circle"></div>
          <div class="left">
            <h2><?php echo get_field("proc_nadpis") ?></h2>
            <div class="offset">
              <p><?php echo get_field("proc_podnadpis") ?></p>
              <p class="smaller"><?php echo get_field("proc_doprovodny_text") ?></p>
              <?php
              $tlacitko = get_field("proc_tlacitko");
              ?>
              <a href="<?php echo esc_url($tlacitko["url"]) ?>" class="button button--primary"><span><?php echo $tlacitko["title"] ?></span><img src="<?php echo get_theme_file_uri("assets/img/svg_sprites/icons/chevron_down.svg"); ?>" alt=""></a>
            </div>
          </div>
          <div class="right">
            <div>
              <?php
                $blok = get_field("proc_blok_1");
                $image_id = $blok["ikona"];

                if (empty($image_id)) $image_id = $dm_toolkit_config["config_zastupny_obrazek"];
                echo '<img src="' . esc_url( wp_get_attachment_url( $image_id ) ) . '" srcset="' . esc_attr( wp_get_attachment_image_srcset( $image_id, "full" ) ) . '" sizes="100px" alt="">';
              ?>
              <h3><?php echo $blok["nadpis"] ?></h3>
            </div>
            <div>
              <?php
                $blok = get_field("proc_blok_2");
                $image_id = $blok["ikona"];

                if (empty($image_id)) $image_id = $dm_toolkit_config["config_zastupny_obrazek"];
                echo '<img src="' . esc_url( wp_get_attachment_url( $image_id ) ) . '" srcset="' . esc_attr( wp_get_attachment_image_srcset( $image_id, "full" ) ) . '" sizes="100px" alt="">';
              ?>
              <h3><?php echo $blok["nadpis"] ?></h3>
            </div>
            <div>
              <?php
                $blok = get_field("proc_blok_3");
                $image_id = $blok["ikona"];

                if (empty($image_id)) $image_id = $dm_toolkit_config["config_zastupny_obrazek"];
                echo '<img src="' . esc_url( wp_get_attachment_url( $image_id ) ) . '" srcset="' . esc_attr( wp_get_attachment_image_srcset( $image_id, "full" ) ) . '" sizes="100px" alt="">';
              ?>
              <h3><?php echo $blok["nadpis"] ?></h3>
            </div>
            <div>
              <?php
                $blok = get_field("proc_blok_4");
                $image_id = $blok["ikona"];

                if (empty($image_id)) $image_id = $dm_toolkit_config["config_zastupny_obrazek"];
                echo '<img src="' . esc_url( wp_get_attachment_url( $image_id ) ) . '" srcset="' . esc_attr( wp_get_attachment_image_srcset( $image_id, "full" ) ) . '" sizes="100px" alt="">';
              ?>
              <h3><?php echo $blok["nadpis"] ?></h3>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="mainpage_our_services">
      <div class="container">
        <div class="wrap">
          <img src="<?php echo get_theme_file_uri("assets/img/mainpage/010101.png"); ?>" alt="" class="fly">
          <img src="<?php echo get_theme_file_uri("assets/img/mainpage/010101.png"); ?>" alt="" class="fly fly2">
          <div class="fly_circle"></div>
          <h2><?php echo get_field("nase_nadpis") ?></h2>
          <div class="cards">
            <?php
              $blok = get_field("nase_blok_1");
            ?>
            <a href="<?php echo esc_url($blok["odkaz"]) ?>">
              <?php
                $image_id = $blok["pozadi"];
                if (empty($image_id)) $image_id = $dm_toolkit_config["config_zastupny_obrazek"];
                echo '<img src="' . esc_url( wp_get_attachment_url( $image_id ) ) . '" srcset="' . esc_attr( wp_get_attachment_image_srcset( $image_id, "full" ) ) . '" sizes="600px" alt="" class="bg">';
              ?>
              <div class="bottom">
                <h3><?php echo $blok["nadpis"] ?></h3>
                <p><?php echo ($blok["text"] ?? "") ?></p>
              </div>
              <?php echo svg_from_sprite("assets/img/svg_sprites/icons/arrow_red.svg", 68, 68, "", "arrow") ?>
            </a>

            <?php
              $blok = get_field("nase_blok_2");   
            ?>
            <a href="<?php echo esc_url($blok["odkaz"]) ?>">
              <?php
                $image_id = $blok["pozadi"];

                if (empty($image_id)) $image_id = $dm_toolkit_config["config_zastupny_obrazek"];
                echo '<img src="' . esc_url( wp_get_attachment_url( $image_id ) ) . '" srcset="' . esc_attr( wp_get_attachment_image_srcset( $image_id, "full" ) ) . '" sizes="600px" alt="" class="bg">';
              ?>
              <div class="bottom">
                <h3><?php echo $blok["nadpis"] ?></h3>
                <p><?php echo $blok["text"] ?? "" ?></p>
              </div>
              <?php echo svg_from_sprite("assets/img/svg_sprites/icons/arrow_red.svg", 68, 68, "", "arrow") ?>
            </a>

            <?php
              $blok = get_field("nase_blok_3");   
            ?>
            <a href="<?php echo esc_url($blok["odkaz"]) ?>">
              <?php
                $image_id = $blok["pozadi"];

                if (empty($image_id)) $image_id = $dm_toolkit_config["config_zastupny_obrazek"];
                echo '<img src="' . esc_url( wp_get_attachment_url( $image_id ) ) . '" srcset="' . esc_attr( wp_get_attachment_image_srcset( $image_id, "full" ) ) . '" sizes="600px" alt="" class="bg">';
              ?>
              <div class="bottom">
                <h3><?php echo $blok["nadpis"] ?></h3>
                <p><?php echo ($blok["text"] ?? "") ?></p>
              </div>
              <?php echo svg_from_sprite("assets/img/svg_sprites/icons/arrow_red.svg", 68, 68, "", "arrow") ?>
            </a>
          </div>
        </div>
      </div>
    </section>

    <section class="mainpage_how">
      <div class="container">
        <div class="wrap">
          <h2><?php echo get_field("jak_nadpis") ?></h2>
          <p><?php echo get_field("jak_podnadpis") ?></strong></p>

          <div class="cards">
            <?php echo svg_from_sprite("assets/img/svg_sprites/mainpage/spoje_fly.svg", 521, 449, "", "fly") ?>
            <?php echo svg_from_sprite("assets/img/svg_sprites/mainpage/spoje_fly_right.svg", 470, 449, "", "fly fly2") ?>
            <span class="fly_circle"></span>
            <div>
              <?php
                $blok = get_field("jak_blok_1");
              ?>
              <div class="nr"><?php echo svg_from_sprite("assets/img/svg_sprites/mainpage/cisla/01.svg", 67, 52, "01") ?></div>
              <h3><?php echo $blok["nadpis"] ?></h3>
              <p><?php echo $blok["text"] ?></p>
            </div>
            <div>
              <?php
                $blok = get_field("jak_blok_2");
              ?>
              <div class="nr"><?php echo svg_from_sprite("assets/img/svg_sprites/mainpage/cisla/02.svg", 77, 52, "02") ?></div>
              <h3><?php echo $blok["nadpis"] ?></h3>
              <p><?php echo $blok["text"] ?></p>
            </div>
            <div>
              <?php
                $blok = get_field("jak_blok_3");
              ?>
              <div class="nr"><?php echo svg_from_sprite("assets/img/svg_sprites/mainpage/cisla/03.svg", 78, 52, "03") ?></div>
              <h3><?php echo $blok["nadpis"] ?></h3>
              <p><?php echo $blok["text"] ?></p>
            </div>
            <div>
              <?php
                $blok = get_field("jak_blok_4");
              ?>
              <div class="nr"><?php echo svg_from_sprite("assets/img/svg_sprites/mainpage/cisla/04.svg", 79, 52, "04") ?></div>
              <h3><?php echo $blok["nadpis"] ?></h3>
              <p><?php echo $blok["text"] ?></p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="mainpage_cta">
      <div class="container">
        <div class="wrap">
          <p><?php echo get_field("text") ?></p>
          <a href="#contact" class="button button--primary"><span><?php echo esc_html(get_field("text_tlacitka")) ?></span><img src="<?php echo get_theme_file_uri("assets/img/svg_sprites/icons/chevron_down.svg"); ?>" alt=""></a>
        </div>
      </div>
    </section>
<?php
get_footer();
?>