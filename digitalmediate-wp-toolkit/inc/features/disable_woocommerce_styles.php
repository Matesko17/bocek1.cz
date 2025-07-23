<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

function conditionally_remove_wc_assets() {
	// remove WC generator tag
	remove_filter( 'get_the_generator_html', 'wc_generator_tag', 10, 2 );
	remove_filter( 'get_the_generator_xhtml', 'wc_generator_tag', 10, 2 );

	// unload WC scripts
	remove_action( 'wp_enqueue_scripts', [ WC_Frontend_Scripts::class, 'load_scripts' ] );
	remove_action( 'wp_print_scripts', [ WC_Frontend_Scripts::class, 'localize_printed_scripts' ], 5 );
	remove_action( 'wp_print_footer_scripts', [ WC_Frontend_Scripts::class, 'localize_printed_scripts' ], 5 );

	// remove "Show the gallery if JS is disabled"
	remove_action( 'wp_head', 'wc_gallery_noscript' );

	// remove WC body class
	remove_filter( 'body_class', 'wc_body_class' );
}

if ($dm_toolkit_config["dm_toolkit_disable_wc_styles"] === 1) add_action( 'template_redirect', 'conditionally_remove_wc_assets' );

function conditionally_woocommerce_enqueue_styles( $enqueue_styles ) {
	return array();
}

if ($dm_toolkit_config["dm_toolkit_disable_wc_styles"] === 1) add_filter( 'woocommerce_enqueue_styles', 'conditionally_woocommerce_enqueue_styles' );

function conditionally_wp_enqueue_scripts() {
	wp_dequeue_style( 'woocommerce-inline' );
  wp_dequeue_style( 'wc-blocks-style' );
  wp_dequeue_style( 'wc-blocks-style-active-filters' );
  wp_dequeue_style( 'wc-blocks-style-add-to-cart-form' );
  wp_dequeue_style( 'wc-blocks-packages-style' );
  wp_dequeue_style( 'wc-blocks-style-all-products' );
  wp_dequeue_style( 'wc-blocks-style-all-reviews' );
  wp_dequeue_style( 'wc-blocks-style-attribute-filter' );
  wp_dequeue_style( 'wc-blocks-style-breadcrumbs' );
  wp_dequeue_style( 'wc-blocks-style-catalog-sorting' );
  wp_dequeue_style( 'wc-blocks-style-customer-account' );
  wp_dequeue_style( 'wc-blocks-style-featured-category' );
  wp_dequeue_style( 'wc-blocks-style-featured-product' );
  wp_dequeue_style( 'wc-blocks-style-mini-cart' );
  wp_dequeue_style( 'wc-blocks-style-price-filter' );
  wp_dequeue_style( 'wc-blocks-style-product-add-to-cart' );
  wp_dequeue_style( 'wc-blocks-style-product-button' );
  wp_dequeue_style( 'wc-blocks-style-product-categories' );
  wp_dequeue_style( 'wc-blocks-style-product-image' );
  wp_dequeue_style( 'wc-blocks-style-product-image-gallery' );
  wp_dequeue_style( 'wc-blocks-style-product-query' );
  wp_dequeue_style( 'wc-blocks-style-product-results-count' );
  wp_dequeue_style( 'wc-blocks-style-product-reviews' );
  wp_dequeue_style( 'wc-blocks-style-product-sale-badge' );
  wp_dequeue_style( 'wc-blocks-style-product-search' );
  wp_dequeue_style( 'wc-blocks-style-product-sku' );
  wp_dequeue_style( 'wc-blocks-style-product-stock-indicator' );
  wp_dequeue_style( 'wc-blocks-style-product-summary' );
  wp_dequeue_style( 'wc-blocks-style-product-title' );
  wp_dequeue_style( 'wc-blocks-style-rating-filter' );
  wp_dequeue_style( 'wc-blocks-style-reviews-by-category' );
  wp_dequeue_style( 'wc-blocks-style-reviews-by-product' );
  wp_dequeue_style( 'wc-blocks-style-product-details' );
  wp_dequeue_style( 'wc-blocks-style-single-product' );
  wp_dequeue_style( 'wc-blocks-style-stock-filter' );
  wp_dequeue_style( 'wc-blocks-style-cart' );
  wp_dequeue_style( 'wc-blocks-style-checkout' );
  wp_dequeue_style( 'wc-blocks-style-mini-cart' );
  wp_dequeue_style( 'wc-blocks-style-mini-cart-contents' );
}

if ($dm_toolkit_config["dm_toolkit_disable_wc_styles"] === 1) add_action( 'wp_enqueue_scripts', 'conditionally_wp_enqueue_scripts' );

function remove_wc_custom_action(){
	remove_action( 'wp_head', 'wc_gallery_noscript' );
}

if ($dm_toolkit_config["dm_toolkit_disable_wc_styles"] === 1) add_action( 'init', 'remove_wc_custom_action' );

if ($dm_toolkit_config["dm_toolkit_disable_wc_styles"] === 1) add_filter( 'woocommerce_enqueue_styles' , '__return_empty_array' );