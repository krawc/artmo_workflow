<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function artmo_user_post_editor() {

  $response = '';

  $settings = array(
    'textarea_name' => 'artmocreateposteditor',
    'teeny' => true
  );

  $response .= '<div id="create-post-container">';
  $response .= '<form action="" method="post" name="artmo_create_post">';
  $response .= '<div class="wp-editor-container">';
  $response .= wp_editor( 'Hello World!', 'artmocreateposteditor', $settings);
  $response .= '</div>';
  $response .= '<input name="post_title" class="create-post-title" placeholder="Title">';
  $response .= '<textarea name="post_content" class="create-post-content"></textarea>';
  $response .= '<div class="create-post-buttons">';
  $response .= '<button type="submit" id="create_post_draft">DRAFT</button>';
  $response .= '<button type="submit" id="create_post_publish">PUBLISH</button>';
  $response .= '</div>';
  $response .= '</form>';
  $response .= '</div>';

  return $response;
}

add_shortcode('artmo_user_post_editor', 'artmo_user_post_editor');
