<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function theme_head_custom_html() {
  echo '<!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({\'gtm.start\':
  new Date().getTime(),event:\'gtm.js\'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!=\'dataLayer\'?\'&l=\'+l:\'\';j.async=true;j.src=
  \'https://www.googletagmanager.com/gtm.js?id=\'+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,\'script\',\'dataLayer\',\'GTM-5RM8X7DZ\');</script>
  <!-- End Google Tag Manager -->

  <meta charset="'.get_bloginfo('charset').'">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="digitalmediate.cz">

  <link rel="icon" type="image/png" href="/wp-content/themes/bocek/assets/img/favicons/favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/svg+xml" href="/wp-content/themes/bocek/assets/img/favicons/favicon.svg" />
  <link rel="shortcut icon" href="/wp-content/themes/bocek/assets/img/favicons/favicon.ico" />
  <link rel="apple-touch-icon" sizes="180x180" href="/wp-content/themes/bocek/assets/img/favicons/apple-touch-icon.png" />
  <link rel="manifest" href="/wp-content/themes/bocek/assets/img/favicons/site.webmanifest" />
  ';
}

add_action('wp_head', "theme_head_custom_html", 0);