<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Vytvoří menu ve stylu: ul > li > a
 */
function dm_create_wp_menu($menu_name, $very_simple = false) {
  global $wp;

  $locations = get_nav_menu_locations($menu_name);

  $return = "";

  if ($locations AND isset($locations[$menu_name])) {
    $menu = get_term( $locations[$menu_name], 'nav_menu' );

    if (is_wp_error($menu)) {
      return "";
    }

    $menu_items = wp_get_nav_menu_items($menu->term_id);

    $current_url = home_url($_SERVER['REQUEST_URI']);


    // Initialize an empty array to store the rearranged items
    $rearrangedArray = array();
    
    // Iterate through the original array to group items by parent
    foreach ($menu_items as $post) {
        // Check if the item has a parent
        if ($post->menu_item_parent) {
            // Add the child item to its parent's array
            $parentID = intval($post->menu_item_parent);
            if (!isset($rearrangedArray[$parentID])) {
                $rearrangedArray[$parentID] = array();
            }
            $rearrangedArray[$parentID]["children"][] = $post;
        } else {
            // Add the parent item directly to the rearranged array
            $rearrangedArray[$post->ID]["item"] = $post;
        }
    }

    if (is_single()) {
      $post_type = get_post_type();
      $archive_url = get_post_type_archive_link($post_type);

      if ($archive_url) {
        $current_url = $archive_url;
      }
    }

    if (!$very_simple) $return .= "<ul>" . PHP_EOL;
    foreach ($rearrangedArray as $value) {

      //Prepare for list of children url
      $children_urls = array();
      if (isset($value["children"]) AND is_array($value["children"])) {
        $children_urls = array_column($value["children"], 'url');
      }

      if (!$very_simple) $return .= '<li'.($current_url === $value["item"]->url || in_array($current_url, $children_urls) ? ' class="active"' : '').'>'. PHP_EOL;
      if (!preg_match('/http/', $value["item"]->url)) $value["item"]->url = home_url() . "/" . $value["item"]->url;
      $return .= '<a href="'.esc_attr($value["item"]->url).'"><span>'.esc_html($value["item"]->title).'</span>' . (isset($value["children"]) ? svg_from_sprite("assets/img/svg_sprites/icons/chevron_down_white.svg", 21, 20) : '') . '</a>' . PHP_EOL;

      if (isset($value["children"]) AND !$very_simple) {
        $return .= '<ul>' . PHP_EOL;
        foreach ($value["children"] as $value2) {
          if (!preg_match('/http/', $value2->url)) $value2->url = home_url() . "/" . $value2->url;
          $return .= '<li><a href="'.esc_attr($value2->url).'">'.esc_html($value2->title).'</a></li>' . PHP_EOL;
        }
        $return .= '</ul>' . PHP_EOL;
      }

      if (!$very_simple) $return .= '</li>' . PHP_EOL;
    }
    if (!$very_simple) $return .= "</ul>" . PHP_EOL;
  }

  return $return;
}

/**
 * Převede obyčejný text do paragrafů (nahradí \r\n\r\n za <p>) a vypíše ho (echo), dvě odřádkování (new line) = nový paragraf
 */
function dm_text_into_paragraphs(string $text) {
  $text = nl2br(esc_html($text), false);
  $paragraphs = explode("<br>\r\n<br>", $text);
  foreach ($paragraphs as $p) {
    echo '<p>'. trim($p) .'</p>';
  }
}

/**
 * Vytvoří odkaz <a> z předaného ACF typ=odkaz
 */
function dm_make_link_from_acf_link(array $acf_link, string $class = "") {
  return '<a href="'. esc_attr($acf_link["url"]) .'"'. (!empty($acf_link["target"]) ? ' target="'.esc_attr($acf_link["target"]).'"' : '') . (!empty($class) ? ' class="'.esc_attr($class).'"' : '') .'>'. (!empty($acf_link["title"]) ? esc_html($acf_link["title"]) : esc_html($acf_link["url"])) .'</a>';
}

/**
 * Vytvoří <svg> z hlavního vygenerovaného svg_sprite souboru
 * @return string
 */
function svg_from_sprite(string $file, int $width = 0, int $height = 0, string $alt = "", string $class = "") {
  $file = str_replace("assets/img/svg_sprites/", "", $file);
  $file = str_replace("/", "-", $file);
  $file = explode(".", $file);

  $theme_uri_parts = parse_url(get_theme_file_uri());

  return '<svg class="svg_sprite'.($class !== "" ? " ".$class : "").'"'. ($width !== 0 ? ' width="'.$width.'"' : '') . ($height !== 0 ? ' height="'.$height.'"' : '') .'>'.($alt !== "" ? '<title>'.esc_html($alt).'</title>' : '').'<use href="'.$theme_uri_parts['path'].'/assets/img/svg_sprite.svg?v='.$GLOBALS['current_theme']->get( 'Version' ).'#'.$file[0].'"></use></svg>';
}

function dm_get_price_with_standart_tax($price_without_tax) {
  $tax_rates = WC_Tax::get_rates();

  if (!empty($tax_rates)) {
      $tax_rate = reset($tax_rates); // Get the first tax rate (assuming there's only one).
      $tax_amount = $price_without_tax * ($tax_rate['rate'] / 100);
      $price_with_tax = $price_without_tax + $tax_amount;
      return $price_with_tax;
  }

  return $price_without_tax;
}

/**
 * Vytvoří breadcrumb v <ul>
 * @return void
 */
function custom_breadcrumb() {
  echo '<ul>';

  // Home link
  echo '<li><a href="' . home_url() . '">' . get_the_title(get_option('page_on_front')) . '</a></li>';

  if (is_page()) {
      $post = get_post();

      // Check if the current page has a parent
      if ($post->post_parent) {
          $parent_id  = $post->post_parent;
          $breadcrumbs = array();

          while ($parent_id) {
              $page = get_page($parent_id);
              $breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
              $parent_id  = $page->post_parent;
          }

          $breadcrumbs = array_reverse($breadcrumbs);

          foreach ($breadcrumbs as $crumb) {
              echo $crumb;
          }
      }

      // Current page
      echo '<li>' . get_the_title() . '</li>';
  } elseif (is_single()) {
    // Single page
    echo '<li><a href="' . get_the_permalink(get_option('page_for_posts')) . '">' . get_the_title(get_option('page_for_posts')) . '</a></li>';
    echo '<li>' . get_the_title() . '</li>';
  } elseif (is_home()) {
    // Blog page
    echo '<li>' . get_the_title(get_option('page_for_posts')) . '</li>';
  } elseif (is_category()) {
    // Category archive
    $category = get_queried_object();
    echo '<li>' . $category->name . '</li>';
  } elseif (is_tag()) {
    // Tag archive
    $tag = get_queried_object();
    echo '<li>' . $tag->name . '</li>';
  } elseif (is_search()) {
    // Search results
    echo '<li>Výsledky vyhledávání „' . get_search_query() . '“</li>';
  } elseif (is_404()) {
    // 404 page
    echo '<li>Chyba 404</li>';
  } elseif (is_archive()) {
    // Other archives
    echo '<li>' . get_the_archive_title() . '</li>';
  }

  echo '</ul>';
}