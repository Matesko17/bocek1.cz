<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();
?>
    <section class="subpage_hero">
      <?php
        $image_id = get_post_thumbnail_id();
        if (empty($image_id)) $image_id = $dm_toolkit_config["config_zastupny_obrazek"];
      ?>
      <span class="bg" data-lazy-image-loading="<?php echo wp_get_attachment_image_src($image_id, 'medium')[0]; ?>">
        <?php
          echo '<img class="bg" src="' . esc_url( wp_get_attachment_url( $image_id ) ) . '" srcset="' . esc_attr( wp_get_attachment_image_srcset( $image_id, "full" ) ) . '" alt="" loading="lazy">';
        ?>
      </span>
      <div class="container">
        <div class="wrap">
          <h1><?php the_title() ?></h1>
        </div>
      </div>
    </section>

    <section class="subpage_content">
      <div class="container">
        <div class="typography_content">
          <?php the_content() ?>
        </div>
      </div>
    </section>
<?php
get_footer();
?>