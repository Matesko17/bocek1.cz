<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

function exclude_empty_search($query) {
    if (is_search() && $query->is_main_query() && get_search_query() === '') {
        $query->set('post__in', array(0)); // Set an invalid post ID to ensure no posts are returned
    }
}

if ($dm_toolkit_config["dm_toolkit_disable_empty_string_in_search"] === 1) add_action('pre_get_posts', 'exclude_empty_search');