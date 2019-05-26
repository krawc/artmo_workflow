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


add_action('wp_ajax_artmo_get_members_from_query', 'artmo_get_members_from_query'); // This is for authenticated users
add_action('wp_ajax_nopriv_artmo_get_members_from_query', 'artmo_get_members_from_query');

function artmo_get_members_from_query() {

  $page = ($_POST['page'] ? $_POST['page'] : 0);
  $step = 10;
  $start = $page * $step;
  $end = $start + $step;

  $all_users = get_option('all_users');

  $city = $_POST['city'];
  $country = $_POST['country'];
  $genres = $_POST['genres'];
  $name = $_POST['name'];
  $args = $_POST['args'];
  $args_string = stripslashes($args);
  $args_obj = json_decode($args_string, true);

  $roles = $args_obj['roles']; //generally this is gonna be one role, but who knows lol
  $show_these_users = $args_obj['show_these_users'];

  if ( isset($_POST['role']) && !empty($_POST['role']) ) {
    $roles = array($_POST['role']);
  }

  if (isset($show_these_users) && !empty($show_these_users)) {
    $ids = array();
    foreach ($show_these_users as $username) {
      $curr_user = get_user_by('login', $username);
      $ids[] = $curr_user->ID;
    }
    $all_users = array_filter( $all_users, function($user) use ($ids){
      if (isset($user['ID']) && in_array($user['ID'], $ids)) {
        return true;
      }
      return false;
    });
  }

  if ((isset($city)) && (!empty($city)) && ($city != 'all')) {
    $all_users = array_filter( $all_users, function($user) use ($city){
      if (isset($user['city']) && $user['city'] == $city) {
        return true;
      }
      return false;
    });
  }

  if ((isset($country)) && (!empty($country))) {
    $all_users = array_filter( $all_users, function($user) use ($country){
      if (isset($user['country']) && $user['country'] == $country) {
        return true;
      }
      return false;
    });
  }

  if ((isset($genres)) && (!empty($genres))) {
    $all_users = array_filter( $all_users, function($user) use ($genres){
      if ($user['genres'][0]) {
        if (isset($user['genres']) && in_array($genres, $user['genres'][0])) {
          return true;
        }
      }
      return false;
    });
  }


  if ((isset($name)) && (!empty($name))) {
    $all_users = array_filter( $all_users, function($user) use ($name){
      if (isset($user['name']) && strpos(strtolower($user['name']), strtolower($name)) !== false ) {
        return true;
      }
      return false;
    });
  }

  if ((isset($roles)) && (!empty($roles))) {
    $all_users = array_filter( $all_users, function($user) use ($roles){
      if ($user['roles']) {
        $matches = array_intersect($roles, $user['roles']);
        if (isset($user['roles']) && (count($matches) > 0)) {
          return true;
        }
      }
      return false;
    });
  }

  if (in_array('um_artist', $roles)) {
    array_multisort(array_map(function($element) {
        return $element['um_last_login'];
    }, $all_users), SORT_DESC, $all_users);
  } else {
    array_multisort(array_map(function($element) {
        return $element['connections'];
    }, $all_users), SORT_DESC, $all_users);
  }

  $members = array_slice($all_users, $start, $step);

  //echo print_r($args_obj);
  echo artmo_output_members( $args_obj, $members );

  wp_die();

}


function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
    $sort_col = array();
    foreach ($arr as $key=> $row) {
        $sort_col[$key] = $row[$col];
    }

    array_multisort($sort_col, $dir, $arr);
}
