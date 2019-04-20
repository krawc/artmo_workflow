<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

remove_action( 'um_members_directory_footer', 'um_members_directory_pagination');
add_action( 'um_members_directory_footer', 'artmo_um_members_directory_pagination');

function artmo_um_members_directory_pagination( $args ) {
    $ultimatemember = new UM();
    extract( $args );
    if ( isset( $args['search'] ) && $args['search'] == 1 && isset( $args['must_search'] ) && $args['must_search'] == 1 && !isset( $_REQUEST['um_search'] ) )
        return;
    if ( um_members('total_pages') > 1 ) { // needs pagination
    ?>

    <div class="um-members-pagi um-artmo-pagi uimob340-hide uimob500-hide">
          <?php if ( um_members('page') != um_members('total_pages') ) { ?>
          <a href="<?php echo $ultimatemember->permalinks()->add_query( 'members_page', um_members('page') + 1 ); ?>" class="pagi pagi-arrow um-tip-n" title="<?php _e('Next', 'ultimatemember'); ?>"><?php _e('SEE MORE', 'ultimatemember'); ?></a>
          <?php } else { ?>
          <span class="pagi pagi-arrow disabled"><?php _e('No more pages to show', 'ultimatemember'); ?></span>
          <?php } ?>
    </div>
    <?php
    }
}

add_action( 'um_registration_complete', 'myplugin_registration_save', 10, 2 );

function myplugin_registration_save( $user_id, $args ) {

	$ultimatemember = new UM();
	extract($args);

  $firstName = um_user('first_name');
  $lastName = um_user('last_name');
  $nickName = um_user('nickname');
  $userName = um_user('user_login');

  artmo_update_names($user_id, $firstName, $lastName, $userName, $nickName);

}

add_action('um_after_user_account_updated', 'um_change_names', 10, 1);
add_action( 'wcfm_profile_update', 'um_change_names', 10 );


function um_change_names( $user_id ) {

  $ultimatemember = new UM();
  um_fetch_user( $user_id );

  $firstName = um_user('first_name');
  $lastName = um_user('last_name');
  $nickName = um_user('nickname');
  $userName = um_user('user_login');

  $role = $ultimatemember->user()->get_role();

  if ( $role == 'um_artist' ) {
    $display_name_check = get_user_meta( $user_id, 'display_name_check', true );
    $user_display_name = get_user_meta( $user_id, 'user_display_name', true );

    if ((!empty($display_name_check)) && ($display_name_check == 'yes')) {
      update_user_meta($user_id, 'user_display_name', $user_display_name);
    } else {
      artmo_update_names($user_id, $firstName, $lastName, $userName, $nickName);
    }

  } else {
    artmo_update_names($user_id, $firstName, $lastName, $userName, $nickName);
  }
}


add_filter('um_profile_tabs', 'add_custom_profile_tab', 2000 );
function add_custom_profile_tab( $tabs ) {
	$tabs['main'] = array(
		'name' => 'Profile',
		'icon' => 'um-faicon-user',
	);
	return $tabs;
}

add_filter( 'um_profile_tabs', 'add_custom_friends_tab', 2000 );

function add_custom_friends_tab( $tabs ) {
	$user_id = um_user( 'ID' );
	if ( ! $user_id )
		return $tabs;

	$tabs['friends'] = array(
		'_builtin' => true,
		'name' => __( 'Network', 'um-friends' ),
		'icon' => 'um-faicon-users',
	);

	return $tabs;
}

add_filter('um_profile_tabs', 'add_custom_activity_tab', 2000 );
function add_custom_activity_tab( $tabs ) {
	$tabs['activity'] = array(
		'name' => 'Activity',
		'icon' => 'fa fa-pencil-square',
	);
	return $tabs;
}

add_filter('um_profile_tabs', 'add_custom_photos_tab', 2000 );
function add_custom_photos_tab( $tabs ) {
	$tabs['photos'] = array(
		'name' => 'Photos',
		'icon' => 'fa fa-image',
	);
	return $tabs;
}

add_filter('um_profile_tabs', 'add_custom_messages_tab', 2000 );
function add_custom_messages_tab( $tabs ) {
	$tabs['messages'] = array(
		'name' => 'Messages',
		'icon' => 'fa fa-envelope',
	);
	return $tabs;
}

add_filter( 'um_profile_tabs', 'add_custom_groups_tab', 2002 );

function add_custom_groups_tab( $tabs ) {

  $tabs['groups_list'] = array(
    'name'     => __( 'Groups', 'um-groups' ),
    'icon'     => 'fa fa-comments',
  );
  $enabled_tab = UM()->options()->get( 'profile_tab_groups_list' );
  if( ! $enabled_tab && ! is_admin() ){
    unset( $tabs['groups_list'] );
  }
  return $tabs;
}

add_filter('um_profile_tabs', 'reorder_profile_tabs', 2002 );
function reorder_profile_tabs( $tabs ) {

  //tabs that need a particular order
  $order = array("main", "art_collection", "my_collection", "messages", "friends", "groups_list", "activity", "photos", "following", "followers");
  $ordered_tabs = array();

  //add tabs in particular order
  foreach($order as $key) {
    $ordered_tabs[$key] = $tabs[$key];
    unset($tabs[$key]);
  }

  //add remaining $tabs
  foreach($tabs as $key) {
    $ordered_tabs[$key] = $tabs[$key];
  }

  return $ordered_tabs;

}

add_filter('um_profile_tabs', 'my_collection_tab', 2000 );

function my_collection_tab( $tabs ) {

	$user_id = um_get_requested_user();
  um_fetch_user( $user_id );
  $roles = artmo_get_user_roles_by_id( $user_id );

  $registered = get_user_meta($user_id, 'registered_vendor', true);

  if ( in_array( 'um_artist', $roles ) ) {
  	// Show to profile owners only
  	if ( is_user_logged_in() && get_current_user_id() == $user_id ) {
      if ($registered) {
    		$tabs['my_collection'] = array(
    			'name' => 'Dashboard',
    			'icon' => 'fa fa-dashboard',
    			'custom' => true
    		);
      } else {
        $tabs['my_collection'] = array(
          'name' => 'Sell Artworks',
          'icon' => 'fa fa-dashboard',
          'custom' => true
        );
      }
  	}
  }
  return $tabs;
}

add_filter('um_profile_tabs', 'art_collection_tab', 2001 );

function art_collection_tab( $tabs ) {

	$user_id = um_get_requested_user();
  um_fetch_user( $user_id );
  $roles = artmo_get_user_roles_by_id( $user_id );

  if ( in_array('um_artist', $roles ) ) {

  $args = array(
      'post_type'  => 'product',
      'author'     => $user_id,
  );

  $wp_posts = get_posts($args);

  if (count($wp_posts)) {
    $tabs['art_collection'] = array(
      'name' => "Collection",
      'icon' => 'fa fa-bookmark',
      'custom' => true
    );
  }
}

  return $tabs;
}

add_action( 'um_profile_menu', 'add_header_um_tabs', 10 );

function add_header_um_tabs() {

  if ( isset($_REQUEST['profiletab']) ) {
    $current_tab = $_REQUEST['profiletab'];
    $um_action = $_REQUEST['um_action'];
    $heading = $current_tab;
    if ($current_tab === 'friends') $heading = 'network';
    if ($current_tab === 'groups_list') $heading = 'groups';
    if (($um_action === 'edit') && ($current_tab === 'main')) $heading = 'edit profile';
    echo '<h2 class="um-tabs-single-header">' . $heading . '</h2>';
  } else if ( um_is_core_page( 'user' ) && um_is_user_himself() ) {
    echo '<h2 class="um-tabs-single-header">PROFILE</h2>';
  }
}


add_filter('um_profile_content_my_collection', 'my_collection_tab_content' );
function my_collection_tab_content($args) {

  $user_id = get_current_user_id();
  um_fetch_user($user_id);
  wp_redirect(um_user_profile_url() . '/?collection_link');
  exit;

}

add_filter('um_profile_content_art_collection', 'art_collection_tab_content' );
function art_collection_tab_content($args) {

  $user_id = um_get_requested_user();
  wp_redirect(get_permalink( woocommerce_get_page_id( 'shop' ) ) . '/?swoof=1&artists_cat=artist_' . $user_id);
  exit;

}

add_shortcode('retrieve_genre_tags_artist', 'retrieve_genre_tags_artist');

function retrieve_genre_tags_artist() {

  $user_id = um_get_requested_user();
  um_fetch_user( $user_id );
  $roles = artmo_get_user_roles_by_id( $user_id );

  if ( in_array( 'um_artist', $roles ) ) {
    $args = array(
        'post_type'  => 'product',
        'author'     => $user_id,
    );
    $wp_posts = get_posts($args);
    $tags = array();
    $medium_cats = array();
    $contents = '';

    if (count($wp_posts)) {
        foreach ( $wp_posts as $post ) :
          $post_id = $post->ID;
          $terms = wp_get_post_terms($post_id, 'genre_tag');
          $media = wp_get_post_terms($post_id, 'medium_cat');
          foreach ($terms as $cat) {
             array_push($tags, $cat->name);
          }
          foreach ($media as $cat) {
             array_push($medium_cats, $cat->name);
          }

        endforeach;

        $tags = array_unique($tags);
        $medium_cats = array_unique($medium_cats);

      //return the tags
      $contents .= '<div class="um-profile-genres">';
      $contents .= '<div class="genres-title um-profile um-viewing um-field-label"><label>' . __('GENRES', 'vantage-child') . '</label></div>';
      $contents .= '<div class="um-profile-tags">';
      foreach ($tags as $tag) {
        $contents .= '<span class="um-profile-genre-tag"><a href="'. get_permalink( woocommerce_get_page_id( 'shop' ) ) . '/?swoof=1&genre_tag=' . sanitize_title($tag) .'">'.$tag.'</a></span>';
      }
      foreach ($medium_cats as $cat) {
        $contents .= '<span class="um-profile-genre-tag"><a href="'. get_permalink( woocommerce_get_page_id( 'shop' ) ) . '/?swoof=1&medium_cat=' . sanitize_title($cat) .'">'.$cat.'</a></span>';
      }
      $contents .= '</div>';
      $contents .= '</div>';
      return $contents;
    }
    return false;
  }
}

add_shortcode('retrieve_series_artist', 'retrieve_series_artist');

function retrieve_series_artist() {

  $user_id = um_get_requested_user();
  um_fetch_user( $user_id );
  $roles = artmo_get_user_roles_by_id( $user_id );

  if ( in_array( 'um_artist', $roles ) ) {

      $args = array(
          'post_type'  => 'product',
          'author'     => $user_id,
          'post_status'=> 'publish',
          'posts_per_page' => -1,
      );
      $wp_posts = get_posts($args);

      $contents = '';
      $series = array();

      if (count($wp_posts)) {
          foreach ( $wp_posts as $post ) :
            $post_id = $post->ID;

            $artist_cat_parent = get_term_by('slug', 'artist_' . $user_id, 'artists_cat');
            $artist_cat_id = $artist_cat_parent->term_id;

            $terms = wp_get_post_terms($post_id, 'artists_cat');

            foreach ($terms as $cat) {
              $cat_id = (int)$cat->term_id;
              if (cat_is_ancestor_of($artist_cat_parent, $cat)) {
               array_push($series, $cat->name);
              }
            }

          endforeach;

        $series = array_unique($series);

        if (!empty($series)) {
          $contents .= '<div class="um-profile-genres">';
          $contents .= '<div class="genres-title um-profile um-viewing um-field-label"><label>' . __('SERIES', 'vantage-child') . '</label></div>';
          $contents .= '<div class="um-profile-tags">';
          foreach ($series as $tag) {
            $contents .= '<span class="um-profile-genre-tag"><a href="'. get_permalink( woocommerce_get_page_id( 'shop' ) ) . '/?swoof=1&artists_cat=' . sanitize_title($tag) .'">'.$tag.'</a></span>';
          }
          $contents .= '</div>';
          $contents .= '</div>';
          return $contents;
        }
      }
    }
    return false;
  }


add_action( 'wp_head', function () {
  if(um_is_myprofile() && !((get_query_var( 'profiletab')=="main") || (get_query_var( 'profiletab')=="")) ){
  echo '<style>.um-cover,.um-header,.um-profile-navbar{display:none !important;} .entry-content .um-profile{margin-top:2px;}</style>';
  }
});


add_action( 'pre_user_query', 'my_random_user_query' );

function my_random_user_query( $class ) {
    if( 'rand' == $class->query_vars['orderby'] )
        $class->query_orderby = str_replace( 'user_login', 'RAND()', $class->query_orderby );
    return $class;
}


add_shortcode('artmo_ultimatemember_message_button', 'artmo_ultimatemember_message_button');

function artmo_ultimatemember_message_button( $args = array() ) {

  wp_enqueue_script( 'um-messaging' );
  wp_enqueue_style( 'um-messaging' );

  $defaults = array(
    'user_id' => 0,
    'label' => 'Message'
  );
  $args = wp_parse_args( $args, $defaults );

  /**
   * @var $user_id
   * @var $label
   */
  extract( $args );

  if ( ! UM()->Messaging_API()->api()->can_message( $user_id ) ) {
    return '';
  }

  $current_url = UM()->permalinks()->get_current_url();
  if ( um_get_core_page( 'user' ) ) {
    do_action( "um_messaging_button_in_profile", $current_url, $user_id );
  }

  ob_start();

  if ( ! is_user_logged_in() ) {
    $redirect = um_get_core_page( 'login' );

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
      if ( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
        $redirect = add_query_arg( 'redirect_to', urlencode( $_SERVER['HTTP_REFERER'] ), $redirect );
      }
    } else {
      $redirect = add_query_arg( 'redirect_to', $current_url, $redirect );
    } ?>

    <a href="<?php echo esc_attr( $redirect ) ?>" class="um-login-to-msg-btn um-message-btn um-button" data-message_to="<?php echo esc_attr( $user_id ) ?>">
      <?php _e($label,'um-messaging') ?>
    </a>

  <?php } elseif ( $user_id != get_current_user_id() ) { ?>

    <a href="javascript:void(0);" class="um-message-btn um-button" data-message_to="<?php echo esc_attr( $user_id ) ?>">
      <span><?php _e( $label,'um-messaging' ) ?></span>
    </a>

  <?php }

  $btn = ob_get_clean();
  return $btn;
}

remove_action( 'um_after_profile_header_name_args', 'um_social_links_icons', 50 );
add_action( 'um_after_header_meta', 'artmo_um_social_links_icons', 10, 2 );

function artmo_um_social_links_icons( $userid, $args ) {
  if ( ! empty( $args['show_social_links'] ) ) {

		echo '<div class="um-profile-connect um-member-connect">';
		artmo_show_social_urls();
		echo '</div>';

	}
}

function artmo_show_social_urls() {
  $social = array();

  $fields = UM()->builtin()->all_user_fields;
  foreach ( $fields as $field => $args ) {
    if ( isset( $args['advanced'] ) && $args['advanced'] == 'social' ) {
      $social[ $field ] = $args;
    }
  }


    foreach ( $social as $k => $arr ) {
      if ( um_profile( $k ) ) {
        if (is_user_logged_in()) { ?>

        <a href="<?php echo um_filtered_social_link( $k, $arr['match'] ); ?>"
           style="background: <?php echo $arr['color']; ?>;" target="_blank" class="um-tip-n"
           title="<?php echo $arr['title']; ?>"><i class="<?php echo $arr['icon']; ?>"></i></a>

          <?php
        } else { ?>

        <a href="<?php echo um_get_core_page( 'login' ); ?>"
           style="background: <?php echo $arr['color']; ?>;" target="_blank" class="um-tip-n"
           title="<?php echo $arr['title']; ?>"><i class="<?php echo $arr['icon']; ?>"></i></a>

        <?php
        }
    }
  }
}


add_shortcode('ultimatemember_followers_bar_no_button', 'ultimatemember_followers_bar_no_button');

function ultimatemember_followers_bar_no_button( $args = array() ) {

    wp_enqueue_style( 'um_followers' );
    wp_enqueue_script( 'um_followers' );

    $defaults = array(
      'user_id' => get_current_user_id()
    );
    $args = wp_parse_args( $args, $defaults );

    /**
     * @var $user_id
     */
    extract( $args );

    ob_start();
    $can_view = true;

    if ( ! is_user_logged_in() || get_current_user_id() != $user_id ) {
      $is_private_case_old = UM()->user()->is_private_case( $user_id, __( 'Followers', 'um-followers' ) );
      $is_private_case = UM()->user()->is_private_case( $user_id, 'follower' );
      if ( $is_private_case || $is_private_case_old ) { // only followers can view my profile
        $can_view = false;
      }
      $is_private_case_old = UM()->user()->is_private_case( $user_id, __( 'Only people I follow can view my profile', 'um-followers' ) );
      $is_private_case = UM()->user()->is_private_case( $user_id, 'followed' );
      if ( $is_private_case || $is_private_case_old ) { // only people i follow can view my profile
        $can_view = false;
      }

    } ?>

    <div class="um-followers-rc">
      <?php if ( $can_view ) { ?>
        <a href="<?php echo UM()->Followers_API()->api()->followers_link( $user_id ); ?>" class="<?php if ( isset( $_REQUEST['profiletab'] ) && $_REQUEST['profiletab'] == 'followers' ) { echo 'current'; } ?>"><?php _e('followers','um-followers'); ?><?php echo UM()->Followers_API()->api()->count_followers( $user_id ); ?></a>
      <?php } ?>
    </div>

    <div class="um-followers-rc">
      <?php if ( $can_view ) { ?>
        <a href="<?php echo UM()->Followers_API()->api()->following_link( $user_id ); ?>" class="<?php if ( isset( $_REQUEST['profiletab'] ) && $_REQUEST['profiletab'] == 'following' ) { echo 'current'; } ?>"><?php _e('following','um-followers'); ?><?php echo UM()->Followers_API()->api()->count_following( $user_id ); ?></a>
      <?php } ?>
    </div>

    <?php $output = ob_get_clean();
    return $output;
}

function um_followers_add_profile_bar_no_button() {
	echo do_shortcode('[ultimatemember_followers_bar_no_button user_id="' . um_profile_id() . '" /]');
}

remove_action( 'um_profile_navbar', 'um_followers_add_profile_bar', 4 );
add_action( 'um_profile_navbar', 'um_followers_add_profile_bar_no_button', 3 );

remove_action( 'um_before_profile_main_meta', 'um_friends_add_button' );

function artmo_um_friends_add_button( $args ) {
	if ( $args['cover_enabled'] == 1 ) {
		wp_enqueue_script( 'um_friends' );
		wp_enqueue_style( 'um_friends' );

    echo '<div class="um-coverbtns">';
		$user_id = um_profile_id();
    if ( UM()->Followers_API()->api()->can_follow( $user_id, get_current_user_id() ) ) {
      echo '<div class="um-followers-btn">' . UM()->Followers_API()->api()->follow_button( $user_id, get_current_user_id() ) . '</div>';
    }
		echo '<div class="um-friends-coverbtn">' . UM()->Friends_API()->api()->friend_button( $user_id, get_current_user_id() ) . '</div>';
    echo '</div>';
	}
}
add_action( 'um_before_profile_main_meta', 'artmo_um_friends_add_button' );
