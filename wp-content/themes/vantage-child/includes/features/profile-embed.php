<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


function get_profile_embed() {

  $response = '';
  $username = $_REQUEST['username'];
  $color = $_REQUEST['color'];
  $type = $_REQUEST['type'];
  $size = $_REQUEST['size'];
  $types_table = get_embed_types();

  $response .= '<!DOCTYPE html><html><head></head><body style="margin:0;">';
  if (isset($username) && !empty($username)) {
    $response .= '<a target="_blank" href="' . get_home_url() . '/user/' . $username .'">';
    $response .= '<img width="' . $types_table[$type][$size]['width'] . '" height="' . $types_table[$type][$size]['height'] . '" class="profile-embed" src="' . get_theme_mod( 'profile_embed_image_' . $color . '_' . $type ) . '"/>';
    $response .= '</a>';
  } else {
    $response .= '<p>No User Found.</p>';
  }
  $response .= '</body></html>';
  return $response;
}

function get_profile_embed_link($username, $color, $type, $size) {

  $types_table = get_embed_types();
  if (isset($username) && !empty($username)) {
    return '<iframe class="profile-embed" src="' . get_home_url() . '/profile-embed/?username=' . $username . '&color=' . $color . '&type=' . $type . '&size=' . $size . '" style="width: ' . $types_table[$type][$size]['width'] . 'px; height: ' . $types_table[$type][$size]['height'] . 'px;" scrolling="no"></iframe>';
  }
  return 'Username error';
}

function profile_embed_link_shortcode($atts) {

  $a = shortcode_atts( array(
    'username' => 'none',
    'color' => 'black',
    'type'  => 'square',
    'size'  => 'small'
  ), $atts );

  return get_profile_embed_link($a['username'], $a['color'], $a['type'], $a['size']);

}

add_shortcode('profile_embed_link_shortcode', 'profile_embed_link_shortcode');

function get_embed_types() {

  $types_table = array(
    'square' => array(
      'large' => array('width' => 200, 'height' => 147),
      'small' => array('width' => 150, 'height' => 110)
    ),
    'banner' => array(
      'large' => array('width' => 150, 'height' => 44),
      'small' => array('width' => 120, 'height' => 35)
    ),
    'logo' => array(
      'large' => array('width' => 111, 'height' => 44),
      'small' => array('width' => 88, 'height' => 35)
    ),
    'icon' => array(
      'large' => array('width' => 40, 'height' => 40),
      'small' => array('width' => 25, 'height' => 25)
    ),
  );

  return $types_table;

}

function get_profile_embed_generator() {

  $current_user = wp_get_current_user();
  $username = $current_user->user_login;
  $colors = array('black', 'white');
  $types = get_embed_types();
  $size = 'small';
  $response = '';

  $response .= '<div class="embed-container">';

  $response .= '<div class="embed-generators">';

  foreach ($types as $type => $values) {
    $response .= '<div class="embed-generator ' . $type . '">';
    $response .= '<div class="embed-preview">';
    $response .= '<div class="embed-preview-stage">';
    foreach ($colors as $color) {
      foreach ($values as $size => $dimensions) {
        $iframe = get_profile_embed_link($username, $color, $type, $size);
        $response .= '<div class="embed-iframe ' . $color . ' ' . $type . ' ' . $size . '" style="width: ' . $dimensions['width'] . 'px; height: ' . $dimensions['height'] . 'px;">' . $iframe . '</div>';
      }
    }
    $response .= '</div>';
    $response .= '</div>';
    $response .= '<div class="embed-options um-search">';

    $response .= '<h4>Step 1: Pick a color:</h4>';
    $response .= '<div class="um-search-filter um-field-area embed-color">';
    foreach ($colors as $color) {
      $checked = ($color == 'black' ? 'checked' : '');
      $active = ($color == 'black' ? 'active' : '');
      $icon = ($color == 'black' ? 'um-icon-android-radio-button-on' : 'um-icon-android-radio-button-off');
      $response .= '<label class="um-field-radio um-field-half right ' . $active . '">';
      $response .= '<input type="radio" name="color" value="' . $color . '" ' . $checked . '><span class="um-field-radio-option">' . $color . '</span>';
      $response .= '<span class="um-field-radio-state"><i class="' . $icon . '"></i></span>';
      $response .= '</label>';
    }
    $response .= '</div>';

    $response .= '<h4>Step 2: Pick a size:</h4>';
    $response .= '<div class="um-search-filter um-field-area embed-size">';
    foreach ($types[$type] as $size => $dimensions) {
      $checked = ($size == 'large' ? 'checked' : '');
      $active = ($size == 'large' ? 'active' : '');
      $icon = ($size == 'large' ? 'um-icon-android-radio-button-on' : 'um-icon-android-radio-button-off');
      $response .= '<label class="um-field-radio um-field-half right ' . $active . '">';
      $response .= '<input type="radio" name="size" value="' . $size . '" ' . $checked . '><span class="um-field-radio-option">' . $size . '</span>';
      $response .= '<span class="um-field-radio-state"><i class="' . $icon . '"></i></span>';
      $response .= '</label>';
    }
    $response .= '</div>';
    $response .= '<h4>Step 3: Copy and paste code on your website:</h4>';
    foreach ($colors as $color) {
      foreach ($values as $size => $dimensions) {
        $iframe = get_profile_embed_link($username, $color, $type, $size);
        $response .= '<div class="um-search-filter embed-copy-code ' . $color . ' ' . $type . ' ' . $size . '"><input type="text" value="'. htmlspecialchars($iframe) .'" class="embed-input"></input></div>';
      }
    }
    $response .= '<button class="embed-copy-btn">Copy the code</button>';
    $response .= do_shortcode('[artmo_ultimatemember_message_button user_id=1 label="HELP"]');
    $response .= '</div>';
    $response .= '</div>';
  }
  $response .= '</div>';
  return $response;

}

add_shortcode('get_profile_embed_generator', 'get_profile_embed_generator');
