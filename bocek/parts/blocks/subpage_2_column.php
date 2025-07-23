<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $dm_toolkit_config;
?>
<section class="subpage_2_column<?php if(get_sub_field('zrcadlit')) echo ' subpage_2_column--reverse' ?>">
	<div class="container">
		<div class="wrap">
			<div class="left">
				<h2><?php echo get_sub_field('nadpis') ?></h2>
				<div class="offset">
					<?php
					if (!empty($field = get_sub_field('text'))) {
						echo '<p>' . $field . '</p>';
					}

					if (!empty($field = get_sub_field('text_mensi'))) {
						echo '<p class="smaller">' . $field . '</p>';
					}

					if (!empty($field = get_sub_field('tlacitko_cta'))) {
						echo '<a href="' . esc_url($field['url']) . '" target="' . esc_attr($field['target']) . '" class="button button--primary"><span>' . esc_html($field['title']) . '</span><img src="' . get_theme_file_uri("assets/img/svg_sprites/icons/chevron_down.svg") . '" alt=""></a>';
					}
					?>
				</div>
			</div>
			<?php
				$image_id = get_sub_field('obrazek_vpravo');
				if (!empty($image_id)) {
					echo '<div class="right">';
					echo '<img src="' . esc_url( wp_get_attachment_url( $image_id ) ) . '" srcset="' . esc_attr( wp_get_attachment_image_srcset( $image_id, "full" ) ) . '" sizes="800px" alt="">';
					echo '</div>';
				}
			?>
		</div>
	</div>
</section>