<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function add_svg_to_upload_mimes( $upload_mimes ) {
    $upload_mimes['svg'] = 'image/svg+xml';
    $upload_mimes['svgz'] = 'image/svg+xml';
    return $upload_mimes;
}
if ($dm_toolkit_config["dm_toolkit_svg_support"] === 1) add_filter( 'upload_mimes', 'add_svg_to_upload_mimes', 10, 1 );