<?php
/**
 * @package Woo Slideshow Extension
 * @version 1.0
 */
/*
Plugin Name: Woo Slideshow Extension
Description: This plugin adds a slideshow to the woocommerce shop page when using the storefront theme. Requires the Slideshow plugin created by StefanBoonstra
Author: frametagmedia
Version: 1.0
Author URI: https://frametagmedia.com.au
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once("assets/lib/AdminSection.php");
require_once("assets/lib/PluginOptionsPage.php");

$wooSlideshowExtention_path    = plugin_dir_path(__FILE__);
$wooSlideshowExtention_URL     = trailingslashit(get_bloginfo('wpurl')) . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
$wooSlideshowExtention_name    = "woo Slideshow Extention";
$wooSlideshowExtention_alias   = "wooSlideshowExtention";
$wooSlideshowExtention_options = array(
    array(
        "type" => "section",
        "name" => "Plugin Settings",
        "description" => "Note: This plugin depends on <a href='https://wordpress.org/plugins/slideshow-jquery-image-gallery/'>Gallery</a> plugin. Please install, and activate it first."
    ),
    array( "type" => "open" ),
    array(
        "type" => "number",
        "name" => "Slideshow ID",
        "desc" => "The ID of the Slideshow to display.<p>You can find the ID under <strong>Slideshow > Slideshow > [Slideshow name]</strong>, in the <strong>Information</strong> section.</p><p><strong>Example:</strong> [slideshow_deploy id='1331'] = you need to enter '1331' as the ID.</p>",
        "id" => $wooSlideshowExtention_alias . "_slideshow_id",
        "std" => ""
    ),
    array(
        "type" => "text",
        "name" => "Section title",
        "desc" => "Title to show before the slideshow. Leave empty to show none",
        "id" => $wooSlideshowExtention_alias . "_slideshow_title",
        "std" => ""
    ),
    array( "type" => "close" )
);

//--------------------------------
//private:

$wooSlideshowExtentionOptionsPage = new WooSlideShowExtension_PluginOptionsPage(AdminSection::SETTINGS, $wooSlideshowExtention_name, $wooSlideshowExtention_alias, $wooSlideshowExtention_options);
register_activation_hook(__FILE__, array($wooSlideshowExtentionOptionsPage, 'install'));
register_deactivation_hook(__FILE__, array($wooSlideshowExtentionOptionsPage, 'uninstall'));
$wooSlideshowExtentionOptionsPage->menu("Slideshow Extention");

//Plugin part

if ( ! function_exists( 'storefront_homepage_slideshow' ) ):
	function storefront_homepage_slideshow() {
            global $wooSlideshowExtention_alias;
            $slideshow_id =  get_option($wooSlideshowExtention_alias . "_slideshow_id");
            $slideshow_title =  get_option($wooSlideshowExtention_alias . "_slideshow_title");
            echo '<section class="storefront-slideshow-section">';
            if ($slideshow_id):
                if ($slideshow_title != ""):
                    echo '<h2 class="section-title">'.$slideshow_title.'</h2>';
                endif;
                    do_action('slideshow_deploy', $slideshow_id);
            else:
                echo "<p>Slideshow ID is not set. Please define the slideshow ID under <strong>Settings > Slideshow Extention</strong></p>";
            endif;
            echo '</section>';
	}
endif;

if (!function_exists('wooSlideshowExtention_assets')):
    function wooSlideshowExtention_assets()
    {
        global $wooSlideshowExtention_URL;
        wp_enqueue_style('wooSlideshowExtention_style', $wooSlideshowExtention_URL . '/assets/css/style.css');
    }
endif;

add_action( 'homepage', 'storefront_homepage_slideshow',		70 );
add_action('wp_enqueue_scripts', 'wooSlideshowExtention_assets');

?>
