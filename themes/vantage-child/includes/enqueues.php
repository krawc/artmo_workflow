<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array(  ) );
    }
endif;
//enqueue child theme css
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_child_css', 10000 );

// END ENQUEUE PARENT ACTION
function chld_thm_cfg_child_css() {

  wp_enqueue_style( 'child_css_theme', get_stylesheet_directory_uri() . '/css/theme.css', array(  ) );
  wp_enqueue_style( 'child_css_so', get_stylesheet_directory_uri() . '/css/so.css', array(  ) );

  if (function_exists('is_woocommerce')) {
    if (is_woocommerce() || is_page('cart') || is_page('checkout')) {
      wp_enqueue_style( 'child_css_woocommerce', get_stylesheet_directory_uri() . '/css/woocommerce.css', array(  ) );
    }
  }

  // if(function_exists('is_ultimatemember')) {
  //   if (is_ultimatemember() || is_page('artists')) {
      wp_enqueue_style( 'child_css_um', get_stylesheet_directory_uri() . '/css/um.css', array(  ) );

  //   }
  // }

  wp_enqueue_style('um_followers');
  wp_enqueue_script('um_followers');
  wp_enqueue_style('um_friends');
  wp_enqueue_script('um_friends');

  if(function_exists('is_wcfm_page')) {
    if (is_wcfm_page() || is_page('vendor-membership')) {
      wp_enqueue_style( 'child_css_wcfm', get_stylesheet_directory_uri() . '/css/wcfm.css', array(  ) );
    }
  }

  wp_enqueue_style( 'artmo_ionicons_master', get_stylesheet_directory_uri() . '/css/ionicons.min.css', array(  ) );
}
//enqueue child theme JS
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_child_script', 500 );

function chld_thm_cfg_child_script() {
  wp_enqueue_script( 'infinitescroll', "https://cdnjs.cloudflare.com/ajax/libs/jquery-infinitescroll/2.0b2.120519/jquery.infinitescroll.min.js", array(), '20151215', true );
  wp_enqueue_script( 'masonry', "https://cdnjs.cloudflare.com/ajax/libs/masonry/3.1.2/masonry.pkgd.js", array(), '20151215', true );
  wp_enqueue_script( 'imagesloaded', "https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.0.4/jquery.imagesloaded.min.js", array(), '20151215', true );
  wp_enqueue_script( 'artmo-jquery-ui', get_stylesheet_directory_uri() . '/js/jquery-ui/jquery-ui.min.js', array(), '20151215', true );
  wp_enqueue_script( 'artmo-jquery-tagthis', get_stylesheet_directory_uri() . '/js/jquery.tagthis.js', array(), '20151215', true );

  wp_register_script( 'vantage-child-js', get_stylesheet_directory_uri() . '/js/script.js' );
  $translation_array = array(
    'ajax_url' => admin_url( 'admin-ajax.php' ),
  	'finishedMsg' => __( "No more artworks to load.", 'vantage-child' ),
    'msgText' => __( "loading artworks...", 'vantage-child' ),
    'seeOnWishlist' => __( "See on Wishlist", 'vantage-child' ),
    'wishlist' => __( 'Wishlist', 'vantage-child' ),
    'added' => __( 'Added!', 'vantage-child' ),
  );
  wp_localize_script( 'vantage-child-js', 'trans_object', $translation_array );
  wp_enqueue_script( 'vantage-child-js' );
}



function wpb_add_google_fonts() {

wp_enqueue_style( 'wpb-google-fonts', 'https://fonts.googleapis.com/css?family=Montserrat:400,700,300', false );
}
