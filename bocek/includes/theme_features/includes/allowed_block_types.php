<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

function wpcc_allowed_block_types() {
	return array(
		'core/paragraph',
    'core/image',
    'core/list',
    'core/list-item',
    'core/heading',
    'core/html',
	);
}
add_filter( 'allowed_block_types_all', 'wpcc_allowed_block_types' );