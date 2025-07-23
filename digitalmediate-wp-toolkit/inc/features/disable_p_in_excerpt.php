<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

//disable auto <p> in excerpt
if ($dm_toolkit_config["dm_toolkit_disable_p_in_excerpt"] === 1) remove_filter( 'the_excerpt', 'wpautop' );