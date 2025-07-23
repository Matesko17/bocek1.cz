<?php
/*
Template Name: Podstránka univerzální
*/

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
					<?php
					$field = get_field("hero");
					?>
          <h1><?php echo $field["nadpis"] ?></h1>
					<?php
						if (!empty($field["podnadpis"])) {
							echo '<p>' . $field["podnadpis"] . '</p>';
						}
					?>
        </div>
      </div>
    </section>

		<?php
		if (have_rows("bloky")) {
			while (have_rows("bloky")) {
				the_row();
				
				if (get_row_layout() === "2_sloupce") {
					get_template_part("parts/blocks/subpage_2_column");
				} elseif (get_row_layout() === "3_bloky") {
					get_template_part("parts/blocks/subpage_3_blocks");
				} elseif (get_row_layout() === "mozaika") {
					get_template_part("parts/blocks/subpage_mozaika");
				}
			}
		}
		?>
<?php
get_footer();
?>