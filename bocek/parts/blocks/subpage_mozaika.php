<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $dm_toolkit_config;
?>
		<section class="subpage_mozaika">
			<div class="container">
				<div class="wrap">
					<h2><?php echo get_sub_field('nadpis') ?></h2>
					<?php
						if (!empty($field = get_sub_field('podnadpis'))) {
							echo '<p>' . $field . '</p>';
						}
					?>
					<div class="blocks">
						<?php
						$bloky = get_sub_field('bloky');
						if (!empty($bloky)) {
							foreach (get_sub_field('bloky') as $field) {
								$card_type = !empty($field["card_type"]) ? $field["card_type"] : 'default';
								$css_class = 'block-item block-' . $card_type;
								
								echo (!empty($field["odkaz"])) ? '<a href="' . esc_url($field["odkaz"]) . '" class="' . $css_class . '">' : '<div class="' . $css_class . '">';
								?>
									<?php if ($card_type === 'default'): ?>
										<?php
											$image_id = $field["ikona"];
											if (!empty($image_id)) {
												echo '<img src="' . esc_url( wp_get_attachment_url( $image_id ) ) . '" srcset="' . esc_attr( wp_get_attachment_image_srcset( $image_id, "full" ) ) . '" sizes="100px" alt="">';
											}
										?>
										<h3><?php echo $field["nadpis"] ?></h3>
										<?php echo svg_from_sprite("assets/img/svg_sprites/icons/arrow_red.svg", 68, 68, "", "arrow") ?>
										<div class="overlay_white"></div>
									
									<?php elseif ($card_type === 'landscape'): ?>
										<div class="card-landscape">
											<div class="image-section">
												<?php
													$image_id = $field["main_image"];
													if (!empty($image_id)) {
														echo wp_get_attachment_image($image_id, 'large', false, array(
															'alt' => $field["nadpis"],
															'loading' => 'lazy'
														));
													}
												?>
											</div>
											<div class="content-section">
												<h3><?php echo $field["nadpis"] ?></h3>
											</div>
											<?php echo svg_from_sprite("assets/img/svg_sprites/icons/arrow_red.svg", 68, 68, "", "arrow") ?>
											<div class="overlay_white"></div>
										</div>
									
									<?php elseif ($card_type === 'portrait'): ?>
										<div class="card-portrait">
											<div class="image-section">
												<?php
													$image_id = $field["main_image"];
													if (!empty($image_id)) {
														echo wp_get_attachment_image($image_id, 'large', false, array(
															'alt' => $field["nadpis"],
															'loading' => 'lazy'
														));
													}
												?>
											</div>
											<div class="content-section">
												<h3><?php echo $field["nadpis"] ?></h3>
											</div>
											<?php echo svg_from_sprite("assets/img/svg_sprites/icons/arrow_red.svg", 68, 68, "", "arrow") ?>
											<div class="overlay_white"></div>
										</div>
									
									<?php endif; ?>
								<?php
								echo (!empty($field["odkaz"])) ? '</a>' : '</div>';
							}
						}
						?>
					</div>
				</div>
			</div>
		</section>