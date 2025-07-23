<?php
function custom_webp_quality($quality, $mime_type) {
  if ($mime_type === 'image/webp') {
    return 90;
  }
  return $quality;
}
if ($dm_toolkit_config["dm_toolkit_webp"] === 1) add_filter('wp_editor_set_quality', 'custom_webp_quality', 10, 2);

function convert_media_files_to_webp($file) {
  //Only if PNG or JPG
  if (isset($file['type']) AND ($file['type'] === 'image/png' OR $file['type'] === 'image/jpeg') AND isset($file['error']) AND $file['error'] === 0 AND extension_loaded('gd') AND extension_loaded('imagick')) {
    $filename_without_extension = pathinfo($file['name'], PATHINFO_FILENAME);

    if ($file['type'] === 'image/jpeg') {
      //check quality and convert only quality jpg images
      $img = new Imagick($file['tmp_name']);
      $quality = $img->getImageCompressionQuality();
      $img->destroy();

      if ($quality > 80) {
        $image = imagecreatefromjpeg($file['tmp_name']);
      }
    } elseif ($file['type'] === 'image/png') {
      //converting only truecolor 24bit png images
      if (is_png_true_color($file['tmp_name'])) {
        $image = imagecreatefrompng($file['tmp_name']);
        imagepalettetotruecolor($image);

        if (is_png_alpha_transparency($file['tmp_name'])) {
          imagealphablending($image, true);
          imagesavealpha($image, true);
        }
      }
    }


    if (isset($image) AND $image !== false) {
      //quality 90% for best results
      imagewebp($image, $file['tmp_name'], 90);
      imagedestroy($image);

      $file['name'] = $filename_without_extension . ".webp";
      $file['full_path'] = $filename_without_extension . ".webp";
      $file['type'] = 'image/webp';
      $file['size'] = filesize($file['tmp_name']);
    }
  }

  return $file;
}
if ($dm_toolkit_config["dm_toolkit_webp"] === 1) add_filter('wp_handle_upload_prefilter', 'convert_media_files_to_webp');

//Check if PNG is TRUE COLOR type
function is_png_true_color($file_path) {
  $image = imagecreatefrompng($file_path);

  if ($image === false) {
      return false;
  }

  $width = imagesx($image);
  $height = imagesy($image);

  $unique_colors = [];

  // Loop through all pixels and collect unique colors
  for ($x = 0; $x < $width; $x++) {
      for ($y = 0; $y < $height; $y++) {
          $color = imagecolorat($image, $x, $y);
          $unique_colors[$color] = true;
      }
  }

  imagedestroy($image);

  // If there are more than 256 unique colors, it's likely a true color image
  $num_unique_colors = count($unique_colors);
  if ($num_unique_colors > 256) {
    return true;
  }

  return false;
}

//Check if PNG has ALPHA TRANSPARENCY COLORS
function is_png_alpha_transparency($file_path) {
  // Create an Imagick instance
  $img = new Imagick($file_path);

  // Set the format to PNG (to preserve transparency information)
  $img->setImageFormat('png');
  $return = $img->getImageAlphaChannel();

  $img->clear();

  // Get the image's alpha channel
  return $return;
}