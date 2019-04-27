<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


add_action('wp_ajax_artmo_create_post', 'artmo_create_post'); // This is for authenticated users
add_action('wp_ajax_nopriv_artmo_create_post', 'artmo_create_post');

function artmo_create_post() {
  if (is_user_logged_in()) {

    parse_str($_POST["form_data"], $post_data);
    $status = $_POST['status'];

    if ( empty( $post_data["post_title"] ) ) {
      echo "Insert title please";
      wp_die();
    }

    if ( empty( $post_data["post_content"] ) ) {
      echo "Insert your content please";
      wp_die();
    }

    $post = array();
    $post['post_title'] = $post_data["post_title"];
    $post['post_content'] = $post_data["post_content"];
    $post['post_status'] = $status;

    wp_insert_post($post);

    echo 'Done!';
    wp_die();

  } else {
    echo 'Log in please!';
    wp_die();
  }

}
