<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$lang_switcher_html = "";
$currnet_lang = pll_current_language('locale');
switch ($currnet_lang) {
    case 'cs_CZ':
        $header_post_group = 125; // Czech
        break;
    case 'en_GB':
        $header_post_group = 540; // English
        break;
    default:
        $header_post_group = 125; // Default to Czech
}
if (function_exists('pll_the_languages')) {
    $languages = pll_the_languages(array('raw' => 1));
    $lang_switcher_html .= '<div class="language-wrapper">';
    $lang_switcher_html .= '<select class="language-switcher" onchange="location = this.value;">';
    foreach ($languages as $lang) {
        $lang_switcher_html .= '<option value="' . esc_url($lang['url']) . '" ' . ($lang['current_lang'] ? 'selected' : '') . '>';
        $lang_switcher_html .= esc_html($lang['name']);
        $lang_switcher_html .= '</option>';
    }
    $lang_switcher_html .= '</select>';
    $lang_switcher_html .= '</div>';
}
?>
<!DOCTYPE html>
<html <?php language_attributes() ?>>
<head>
    <?php
    wp_head();
    ?>
</head>
<body <? body_class(); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5RM8X7DZ"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

<header id="header_handler">
    <a href="<?php echo get_home_url() ?>" class="logo">
        <?php
        $image_id = get_field("logo", $header_post_group);
        if (empty($image_id)) $image_id = $dm_toolkit_config["config_zastupny_obrazek"];
        echo '<img src="' . esc_url(wp_get_attachment_url($image_id)) . '" srcset="' . esc_attr(wp_get_attachment_image_srcset($image_id, "full")) . '" sizes="400px" alt="">';
        ?>
    </a>
    <nav>
        <div class="nav_inner">
            <?php
            echo dm_create_wp_menu("main_menu");
            ?>
            <div class="lang_extra_mobile">
                <?php echo $lang_switcher_html ?>
            </div>
        </div>
    </nav>
    <div class="right">
        <a href="#contact" class="button button--red"><img
                    src="<?php echo get_theme_file_uri("assets/img/svg_sprites/icons/mail_white.svg"); ?>"
                    alt=""><span><?php echo esc_html(get_field("text_tlacitka_pro_kontaktni_formular", $header_post_group)) ?></span></a>
        <?php echo $lang_switcher_html ?>
    </div>
    <a href="#" class="mobile_nav_button"
       onclick="document.getElementById('header_handler').classList.toggle('open'); return false">
        <span></span>
        <span></span>
        <span></span>
    </a>
</header>
<main>