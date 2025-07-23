<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $dm_toolkit_config;
?>
		<section class="subpage_3_blocks">
			<div class="container">
				<div class="wrap">
					<div class="top">
						<h2><?php echo get_sub_field('nadpis') ?></h2>
						<?php
							if (!empty($field = get_sub_field('tlacitko_cta'))) {
								echo '<a href="' . esc_url($field['url']) . '" target="' . esc_attr($field['target']) . '" class="button button--primary"><span>' . esc_html($field['title']) . '</span><img src="' . get_theme_file_uri("assets/img/svg_sprites/icons/chevron_down.svg") . '" alt=""></a>';
							}
						?>
					</div>

					<div class="blocks">
						<div class="group">
							<div class="group">
								
								<?php
								$field = get_sub_field('dlazdice_vlevo_nahore');
								?>
								<a href="<?php echo esc_url($field["odkaz"]) ?>" class="block">
									<?php
										$image_id = $field["pozadi"];
										if (empty($image_id)) $image_id = $dm_toolkit_config["config_zastupny_obrazek"];
										echo '<img src="' . esc_url( wp_get_attachment_url( $image_id ) ) . '" srcset="' . esc_attr( wp_get_attachment_image_srcset( $image_id, "full" ) ) . '" sizes="800px" alt="">';
									?>
									<h2><?php echo $field["nadpis"] ?></h2>
								</a>

								<?php
								$field = get_sub_field('dlazdice_vlevo_dole');
								?>
								<a href="<?php echo esc_url($field["odkaz"]) ?>" class="block">
									<?php
										$image_id = $field["pozadi"];
										if (empty($image_id)) $image_id = $dm_toolkit_config["config_zastupny_obrazek"];
										echo '<img src="' . esc_url( wp_get_attachment_url( $image_id ) ) . '" srcset="' . esc_attr( wp_get_attachment_image_srcset( $image_id, "full" ) ) . '" sizes="800px" alt="">';
									?>
									<h2><?php echo $field["nadpis"] ?></h2>
								</a>

							</div>

							<?php
							$field = get_sub_field('dlazdice_vpravo');
							?>
							<a href="<?php echo esc_url($field["odkaz"]) ?>" class="block">
								<?php
									$image_id = $field["pozadi"];
									if (empty($image_id)) $image_id = $dm_toolkit_config["config_zastupny_obrazek"];
									echo '<img src="' . esc_url( wp_get_attachment_url( $image_id ) ) . '" srcset="' . esc_attr( wp_get_attachment_image_srcset( $image_id, "full" ) ) . '" sizes="800px" alt="">';
								?>
								<h2><?php echo $field["nadpis"] ?></h2>
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>