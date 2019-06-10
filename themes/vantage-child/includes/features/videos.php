<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


function artmo_get_video_searchform() {

  $categories = get_video_categories();
  $countries = get_option('user_videos_countries');
  $roles = array( 'um_artist' => 'Artist', 'um_gallery' => 'Gallery', 'um_member' => 'Member' );
  $sorting_rules = array( 'newest' => 'Newest', 'most_followed' => 'Most Followed' );

  ?>
  <div class="um-search um-video-search">

    <form method="get" action="" />
      <div class="um-search-filter">
        <select name="category" value="<?php echo $_GET['category'] ?>">
          <?php
          $default = (empty($_GET['category']) ? 'selected' : '');
          $all = (($_GET['category'] == 'all') ? 'selected' : '');
          echo '<option value="" ' . $default . '>ARTMO Picks</option>';
          echo '<option value="all" ' . $all . '>All Videos</option>';
          foreach ( $categories as $key => $value ) {
            $selected = (($key == $_GET['category'] && !empty($_GET['category'])) ? 'selected' : '');
            echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
          }
          ?>
        </select>
      </div>

      <div class="um-search-filter">
        <select name="country" value="<?php echo $_GET['country'] ?>">
          <?php
          $default = (empty($_GET['country']) ? 'selected' : '');
          echo '<option value="" ' . $default . '>Country</option>';
          foreach ( $countries as $key => $value ) {
            $selected = (($key == $_GET['country'] && !empty($_GET['country'])) ? 'selected' : '');
            echo '<option value="' . $value . '" ' . $selected . '>' . $value . '</option>';
          }

          ?>
        </select>
      </div>

      <div class="um-search-filter">
        <select name="role" value="<?php echo $_GET['role'] ?>">
          <?php
          $default = (empty($_GET['role']) ? 'selected' : '');
          echo '<option value="" ' . $default . '>Account Type</option>';
          foreach ( $roles as $key => $value ) {
            $selected = (($key == $_GET['role'] && !empty($_GET['role'])) ? 'selected' : '');
            echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
          }
          ?>
        </select>
      </div>



      <div class="um-search-filter">
        <input placeholder="User Name" name="user_display_name" value="<?php echo $_GET['user_display_name'] ?>"/>
      </div>


      <div class="um-search-filter">
        <select name="sort" value="<?php echo $_GET['sort'] ?>">
          <?php
          $default = (empty($_GET['sort']) ? 'selected' : '');
          echo '<option value="" ' . $default . '>Sort By</option>';
          foreach ( $sorting_rules as $key => $value ) {
            $selected = (($key == $_GET['sort'] && !empty($_GET['sort'])) ? 'selected' : '');
            echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
          }
          ?>
        </select>
      </div>

      <button type="submit">SEARCH</button>
      <a class="videos-reset" href="?">RESET</a>

    </form>
  </div>
  <?php
}

function artmo_get_user_videos() {

  artmo_get_video_searchform();

  ?>
  <div class="youtube-videos-container">

    <div class="youtube-videos-player-container">
      <div class="youtube-videos-player-overlay">
        <i class="ion ion-close"></i>
      </div>
      <div class="youtube-videos-player"></div>
    </div>
    <div class="youtube-videos"></div>
    <div class="youtube-videos-preloader">
      <i class="ion ion-load-d"></i>
    </div>
  </div>
  <?php

}

add_action( 'wp_ajax_artmo_ajax_get_user_videos', 'artmo_ajax_get_user_videos' );
add_action( 'wp_ajax_nopriv_artmo_ajax_get_user_videos', 'artmo_ajax_get_user_videos' );

function get_user_videos_countries($users) {

  $user_videos_countries = array();

  if ( ! empty( $users ) ) {
    // loop through each user
    foreach ( $users as $user ) {
      $country = get_user_meta($user->ID, 'countryField', true);
      $user_videos_countries[$country] = $country;
    }
  } else {
    echo 'No users found';
  }

  ksort($user_videos_countries);

  return $user_videos_countries;

}

function get_user_videos_from_query($users) {

  $user_videos = $video_ids_array = $video_ids_array_vimeo = array();

  if ( ! empty( $users ) ) {
      // loop through each user
      foreach ( $users as $user ) {

          $meta_data = array();

          $YT_user_fields = array('Video-YouTube' => 'Video-YouTube-cat', 'Video-YouTube2' => 'Video-YouTube2-cat' );
          $VM_user_fields = array('Video-vimeo' => 'Video-vimeo-cat', 'Video-vimeo2' => 'Video-vimeo2-cat');

          $user_login = get_user_meta($user->ID, 'user_login', true);
          $user_display_name = get_user_meta($user->ID, 'user_display_name', true);
          $country = get_user_meta($user->ID, 'countryField', true);
          $roles = artmo_get_user_roles_by_id($user->ID);

          $meta_data['user_login'] = $user_login;
          $meta_data['user_display_name'] = $user_display_name;
          $meta_data['country'] = $country;

          if (in_array('um_member', $roles)) {
            $meta_data['role'] = 'um_member';
          } else if (in_array('um_gallery', $roles)) {
            $meta_data['role'] = 'um_gallery';
          } else {
            $meta_data['role'] = 'um_artist';
          }


          foreach($YT_user_fields as $YT_meta_key => $YT_meta_category) {

            $value = get_user_meta($user->ID, $YT_meta_key, true);
            $category = get_user_meta($user->ID, $YT_meta_category, true);

            if (isset($value) && !empty($value)) {
              // get all the user's data
              $video_id = ( strstr( $value, 'http') || strstr( $value, '://' ) ) ? um_youtube_id_from_url( $value ) : $value;
              $meta_data['platform'] = 'youtube';
              $meta_data['category'] = $category;
              $user_videos[$video_id] = $meta_data;
            }
          }


          foreach($VM_user_fields as $VM_meta_key => $VM_meta_category) {

            $value = get_user_meta($user->ID, $VM_meta_key, true);
            $category = get_user_meta($user->ID, $VM_meta_category, true);

            if (isset($value) && !empty($value)) {
              // get all the user's data
              $video_id = ( !is_numeric( $value ) ) ? (int) substr(parse_url($value, PHP_URL_PATH), 1) : $value;
              $meta_data['platform'] = 'vimeo';
              $meta_data['category'] = $category;

              $user_videos[$video_id] = $meta_data;
            }
          }
      }
  } else {
      echo 'No users found';
  }

  return $user_videos;

}



function artmo_ajax_get_user_videos() {

  if ($_POST['videos_sort'] == 'newest') {
    $user_videos = get_option( 'user_videos_newest' );
  } else if ($_POST['videos_sort'] == 'most_followed') {
    $user_videos = get_option( 'user_videos_most_followed' );
  } else {
    $user_videos = get_option( 'user_videos' );
  }

  $response = '';

  $page = $_POST['videos_page'];
  if (!isset( $_POST['videos_page'] )) {
    $page = 0;
  }
  $count = 10;

  $category = $_POST['videos_category'];
  $country = $_POST['videos_country'];
  $role = $_POST['videos_role'];
  $name = $_POST['videos_name'];
  $sort = $_POST['videos_sort'];


  if ((isset($category)) && (!empty($category)) && ($category != 'all')) {
    $user_videos = array_filter( $user_videos, function($element) use ($category){
      if (isset($element['category']) && $element['category'] == $category) {
        return true;
      }
      return false;
    });
  }

  if ((isset($country)) && (!empty($country))) {
    $user_videos = array_filter( $user_videos, function($element) use ($country){
      if (isset($element['country']) && $element['country'] == $country) {
        return true;
      }
      return false;
    });
  }

  if ((isset($role)) && (!empty($role))) {
    $user_videos = array_filter( $user_videos, function($element) use ($role){
      if (isset($element['role']) && $element['role'] == $role) {
        return true;
      }
      return false;
    });
  }


  if ((isset($name)) && (!empty($name))) {
    $user_videos = array_filter( $user_videos, function($element) use ($name){
      if (isset($element['user_display_name']) && strpos(strtolower($element['user_display_name']), strtolower($name)) !== false ) {
        return true;
      }
      return false;
    });
  }

  $user_videos_paged = array_slice($user_videos, $page * $count, $count, true);   // returns "a", "b", and "c"

  //if no category is provided, get artmo picks
  $is_default = ((empty($category)) && (empty($country)) && empty($role) && empty($name) && empty($sort));

  if ($is_default) {
    $response .= get_featured_video_posts($page);
  } else {
    $response .= get_user_video_data( $user_videos_paged );
  }

  echo $response;

  wp_die(); // this is required to terminate immediately and return a proper response

}

function get_featured_video_posts($page) {

  $args = array(
    'post_type'              => array( 'post', ),
    'post_status'            => array( 'publish' ),
    'posts_per_page'         => 30,
    'paged'                  => $page + 1,
    'tax_query' => array(
      array(
        'taxonomy' => 'category',
        'field'    => 'slug',
        'terms'    => 'videos'
      ),
    ),
  );

  $query = new WP_Query($args);

  $result = '';

  if ( $query->have_posts() ) {

    while ( $query->have_posts() ) {
      $query->the_post();
      $post = get_post();

      $result .= '<div class="video-single-featured"><a target="_blank" href="';
      $result .= get_permalink();
      $result .= '">';
      $result .= '<div class="video_thumb"><img src="' . get_the_post_thumbnail_url( $post->ID, array(272,182) ) . '"><i class="ion ion-ios-play"></i></div>';
      $result .= '<div class="video_name">';
      $result .= apply_filters( 'the_title', $post->post_title, $post->ID );
      $result .= '</div></a>';
      $result .= '</div>';

    }

  } else {
    // no posts found
  }

  wp_reset_postdata();

  return $result;

}



  function get_user_video_data($user_videos_paged) {

  // > page
  // > category (if a video field isn't empty AND its category field is a certain value)
  // > country (if country field of the artist is this and that)
  // > user display name

  global $wp_roles;

  $youtube_video_ids = $vimeo_video_ids = array();

  $video_return = array();

  $categories = get_video_categories();

  foreach ( $user_videos_paged as $key => $value ) {

    if ($value['platform'] == 'youtube') {
      $youtube_video_ids[] = $key;
    } else {
      $vimeo_video_ids[] = $key;
    }

  }

  //get 10 thumbnails AND video infos from YouTube (so this is just YT)
  $video_ids_str = implode(',', $youtube_video_ids);

  $url = 'https://www.googleapis.com/youtube/v3/videos?id='. $video_ids_str .'&key=AIzaSyDFe3noolLc9zp53e5165fhIICtuzfIFXY&part=snippet';
  $response = wp_remote_get( esc_url_raw( $url ) );
  $api_response = json_decode( wp_remote_retrieve_body( $response ), true );
  $video_items = $api_response['items'];

  foreach ($vimeo_video_ids as $video_id) {

    $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$video_id.php"));
    $video_items_vimeo[] = $hash[0];

  }

  //get 10 thumbnails AND video infos from YouTube (so this is just YT)


  if (!empty($video_items_vimeo)) {
    foreach ($video_items_vimeo as $item) {
      $vm_video_id = $item['id'];
      $thumb = $item['thumbnail_large'];
      $video_name = $item['title'];
      $video_category = $user_videos_paged[$vm_video_id]['category'];
      $video_category_name = (!empty($video_category) ? '<div class="video_category"><a href="?category=' . $video_category . '">' . $categories[$video_category] . '</a></div> ' : '');
      $profile_login = $user_videos_paged[$vm_video_id]['user_login'];
      $profile_display_name = $user_videos_paged[$vm_video_id]['user_display_name'];
      $profile_link = get_home_url() . '/user/' . $profile_login;
      $role = $user_videos_paged[$vm_video_id]['role'];
      $role_name = $wp_roles->roles[$role]['name'];
      $result = '';

      if (!empty($vm_video_id)) {

        $result .= '<div class="video-single um-vimeo" data-platform="vimeo" data-videoid="' . $vm_video_id . '">';
        $result .= '<div class="video_thumb"><img src="' . $thumb . '"><i class="ion ion-ios-play"></i></div>';
        $result .= '<div class="video_name">';
        $result .= $video_name;
        $result .= '</div>';
        $result .= '<div class="user_name"><a href="';
        $result .= $profile_link;
        $result .= '">';
        $result .= '<span class="video_user_role">' . $role_name . '  </span><span class="video_display_name">' . $profile_display_name . '</span>';
        $result .= '</a></div>';
        $result .= $video_category_name;
        $result .= '</div>';

        $video_return[] = $result;
      }

    }
  }

  if (!empty($video_items)) {
    foreach ($video_items as $item) {
      $yt_video_id = $item['id'];
      $thumb = $item['snippet']['thumbnails']['high']['url'];
      $video_name = $item['snippet']['title'];
      $video_category = $user_videos_paged[$yt_video_id]['category'];
      $video_category_name = (!empty($video_category) ? '<div class="video_category"><a href="?category=' . $video_category . '">' . $categories[$video_category] . '</a></div> ' : '');
      $profile_login = $user_videos_paged[$yt_video_id]['user_login'];
      $profile_display_name = $user_videos_paged[$yt_video_id]['user_display_name'];
      $profile_link = get_home_url() . '/user/' . $profile_login;
      $role = $user_videos_paged[$yt_video_id]['role'];
      $role_name = $wp_roles->roles[$role]['name'];
      $result = '';

      $result .= '<div class="video-single um-youtube" data-platform="youtube" data-videoid="' . $yt_video_id . '">';
      $result .= '<div class="video_thumb"><img src="' . $thumb . '"><i class="ion ion-ios-play"></i></div>';
      $result .= '<div class="video_name">';
      $result .= $video_name;
      $result .= '</div>';
      $result .= '<div class="user_name"><a href="';
      $result .= $profile_link;
      $result .= '">';
      $result .= '<span class="video_user_role">' . $role_name . '  </span><span class="video_display_name">' . $profile_display_name . '</span>';
      $result .= '</a></div>';
      $result .= $video_category_name;
      $result .= '</div>';

      $video_return[] = $result;

    }
  }

  return implode('', $video_return);

}

add_shortcode('artmo_get_user_videos', 'artmo_get_user_videos');
