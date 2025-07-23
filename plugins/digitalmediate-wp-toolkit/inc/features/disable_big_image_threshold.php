<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

//Zakáže limitaci velkých obrázků při nahrávání ve wordpressu, limit byl do maximální šíře 2560px a došlo vždy k zmenšení na tuto šířku, s tímto lze nahrávat i 4K obrázky bez zásahu wordpressu

function disable_big_image_threshold($threshold, $imagesize, $file, $attachment_id) {
  return false;
}

if ($dm_toolkit_config["dm_toolkit_disable_big_image_treshold"] === 1) add_filter('big_image_size_threshold', 'disable_big_image_threshold', 10, 4);