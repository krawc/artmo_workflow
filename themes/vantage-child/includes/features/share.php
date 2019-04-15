<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


function artmo_get_share_btns($url) {

  $buttons = array(
      'share-btn_1' => '<span class="share-btn btnFb"><a href="https://www.facebook.com/sharer/sharer.php?u='.$url.'" target="_blank" ><i class="ion ion-social-facebook"></i></a></span>',
      'share-btn_2' => '<span class="share-btn btnTw"><a href="https://twitter.com/home?status='.$url.'" target="_blank" ><i class="ion ion-social-twitter"></i></a></span>',
      'share-btn_3' => '<span class="share-btn btnWa"><a href="whatsapp://send?text='.$url.'" data-action="share/whatsapp/share" target="_blank" ><i class="ion ion-social-whatsapp"></i></a></span>',
      'share-btn_4' => '<span class="share-btn btnPi"><a href="https://pinterest.com/pin/create/button/?url=&media=&description='.$url.'" target="_blank" ><i class="ion ion-social-pinterest"></i></a></span>',
      'share-btn_5' => '<span class="share-btn btnLi"><a href="https://www.linkedin.com/shareArticle?mini=true&url='.$url.'&title=&summary=&source=" target="_blank" ><i class="ion ion-social-linkedin"></i></a></span>',
      'share-btn_6' => '<span class="share-btn btnEm"><a href="mailto:?to=&body='.$url.'" ><i class="ion ion-ios-email"></i></a></span>',
  );

  $buttons_markup = '<div class="share-btns">';

  foreach ($buttons as $button => $markup) {
    $buttons_markup .= $markup;
  }

  $buttons_markup .= '</div>';
  return $buttons_markup;

}

function artmo_get_share_post_component($url = '') {

  $response = '';
  if (empty($url)) {
    $url = get_permalink();
  }

  $response .= '<div class="share-btns-container share-btns-post">';
  $response .= '<div class="share-btns-trig"><i class="ion ion-android-share-alt"></i> Share</div>';
  $response .= artmo_get_share_btns(urlencode($url));
  $response .= '</div>';

  return $response;

}

function artmo_display_share_post_content_profile() {

  $user_id = um_profile_id();
  $um_user = um_fetch_user($user_id);
  $profile_url = um_user_profile_url();

  echo artmo_get_share_post_component($profile_url);

}

function artmo_the_share_post_component() {
  echo artmo_get_share_post_component();
}

function artmo_share_post_shortcode( $atts ) {

  $a = shortcode_atts( array(
  		'url' => ''
  	 ), $atts );

  return artmo_get_share_post_component( $a['url'] );

}

add_shortcode('artmo_share_post_shortcode', 'artmo_share_post_shortcode');

add_action('um_profile_navbar', 'artmo_display_share_post_content_profile', 4, 1);
add_action('woocommerce_after_add_to_cart_button', 'artmo_the_share_post_component');
