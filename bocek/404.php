<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();
?>
    <section class="subpage_hero">
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
          <h1><?php echo __("Chyba 404", "bocek") ?></h1>
        </div>
      </div>
    </section>

    <section class="subpage_content">
      <div class="container">
        <div class="typography_content">
          <h2><?php echo __("Požadovaná stránka nebyla nalezena", "bocek") ?></h2>
        </div>
      </div>
    </section>
<?php
get_footer();
?>