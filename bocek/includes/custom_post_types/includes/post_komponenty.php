<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function create_post_type_komponenty() {

  // Set UI labels for Custom Post Type
  $labels = array(
      'name'                => "Komponenty šablony",
      'name_admin_bar'      => "Vytvořit komponentu",
      'singular_name'       => "Komponenta",
      'menu_name'           => "Komponenty šablony",
      'parent_item_colon'   => "Nadřazená komponenta:",
      'all_items'           => "Přehled komponent",
      'view_item'           => "Zobrazit komponentu",
      'view_items'          => "Zobrazit komponenty",
      'add_new_item'        => "Vytvořit novou komponentu",
      'add_new'             => "Vytvořit komponentu",
      'edit_item'           => "Upravit komponentu",
      'search_items'        => "Hledat komponenty",
      'not_found'           => "Nebyli nalezeni žádné komponenty.",
      'not_found_in_trash'  => "V koši nebyli nalezené žádné komponenty.",
      'archives'            => "Archivy komponentů",
      'attributes'          => "Vlastnosti komponenty",
      'filter_items_list'   => "Filtrovat seznam komponentů",
      'insert_into_item'    => "Vložit do komponenty",
      'items_list'          => "Seznam komponent",
      'items_list_navigation'=> "Navigace v seznamu komponent",
      'item_link'           => "Odkaz komponenty",
      'item_link_description'=> "Odkaz na komponentu.",
  );
  
  // Set other options for Custom Post Type
  $args = array(
      'label'               => "komponenty",
      'description'         => "Komponenty",
      'labels'              => $labels,
      'menu_icon'           => "dashicons-admin-appearance",
      'supports'            => array( 'title' ),
      'hierarchical'        => false,
      'public'              => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'menu_position'       => 6,
      'can_export'          => true,
      'has_archive'         => true,
      'exclude_from_search' => true,
      'publicly_queryable'  => false,
      'capability_type'     => 'post',
	  'show_in_rest'		=> true,
  );
  
  // Registering your Custom Post Type
  register_post_type( 'komponenty', $args );

}
add_action( 'init', 'create_post_type_komponenty', 0 );