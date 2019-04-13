<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

//Page Slug Body Class

add_filter( 'body_class', 'add_slug_body_class' );

function add_slug_body_class( $classes ) {
  global $wp, $post;
  if ( isset( $post ) ) {
    $url = home_url( $wp->request );
    $end = end(explode('/', $url));
    $end = array_slice(explode('/', $url), -1)[0];
    $classes[] = $post->post_type . '-' . $post->post_name . ' ' . $end;
  }
  return $classes;
}

//Page Slug Body Class

add_filter( 'body_class', 'add_onboarding_endpoint_body_class' );

function add_onboarding_endpoint_body_class( $classes ) {
  global $wp, $post;
  if ( isset($_REQUEST['vmstep']) ) {
    $classes[] = 'vmstep-' . $_REQUEST['vmstep'];
  }
  return $classes;
}


//Page Slug Body Class

add_filter( 'body_class', 'add_profile_endpoint_body_class' );

function add_profile_endpoint_body_class( $classes ) {
  global $wp, $post;
  if ( isset($_REQUEST['profiletab']) ) {
    $classes[] = 'profiletab-' . $_REQUEST['profiletab'];
  } else {
    $classes[] = 'profiletab-none';
  }
  return $classes;
}

add_filter('body_class','browser_body_class');

function browser_body_class($classes) {
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

	if($is_lynx) $classes[] = 'lynx';
	elseif($is_gecko) $classes[] = 'gecko';
	elseif($is_opera) $classes[] = 'opera';
	elseif($is_NS4) $classes[] = 'ns4';
	elseif($is_safari) $classes[] = 'safari';
	elseif($is_chrome) $classes[] = 'chrome';
	elseif($is_IE) $classes[] = 'ie';
	else $classes[] = 'unknown';
	if($is_iphone) $classes[] = 'iphone';
	return $classes;
}

add_filter('body_class','logged_out_class');
function logged_out_class($classes) {
    if (! ( is_user_logged_in() ) ) {
        $classes[] = 'logged-out';
    }
    return $classes;
}


add_filter('wp_dropdown_users_args', 'assign_subscriber_author_func', 10, 2);
 function assign_subscriber_author_func($query_args, $r){
    $query_arg['who'] = 'um_artist';
    return $query_arg;
}

if( get_role('subscriber') ){
    remove_role( 'subscriber' );
}
