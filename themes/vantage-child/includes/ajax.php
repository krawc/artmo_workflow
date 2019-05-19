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

    if ( empty( $post_data["artmocreateposteditor"] ) ) {
      echo "Insert your content please";
      wp_die();
    }

    if ( empty( $post_data["image_url"] ) ) {
      echo "Insert image please";
      wp_die();
    }

    $cat = array();
    array_push($cat, $post_data["cat"]);
    //allow only 1 category - else reject
    if (strpos($post_data["cat"], ',') !== false) {
      echo "Pick 1 category only.";
      wp_die();
    }

    $post = array();

    $post['post_title'] = $post_data["post_title"];
    $post['post_content'] = $post_data["artmocreateposteditor"];
    $post['post_category'] = $cat;

    wp_insert_post($post);

    $attachment_id = $post_data["image_url"];

    set_post_thumbnail($post->ID, $attachment_id);

    echo 'Done!';
    wp_die();

  } else {
    echo 'Log in please!';
    wp_die();
  }

}

/******FILE UPLOAD*****************/
function upload_user_file( $file = array() ) {
    require_once( ABSPATH . 'wp-admin/includes/admin.php' );
    $file_return = wp_handle_upload( $file, array('test_form' => false ) );
    if( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
        return false;
    } else {
        $filename = $file_return['file'];
        $attachment = array(
            'post_mime_type' => $file_return['type'],
            'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
            'post_content' => '',
            'post_status' => 'inherit',
            'guid' => $file_return['url']
        );
        $attachment_id = wp_insert_attachment( $attachment, $file_return['url'] );
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
        wp_update_attachment_metadata( $attachment_id, $attachment_data );
        if( 0 < intval( $attachment_id ) ) {
          return $attachment_id;
        }
    }
    return false;
}
