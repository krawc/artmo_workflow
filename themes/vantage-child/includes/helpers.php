<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function artmo_array_contains($array, $value) {
  foreach ($array as $item) {
      foreach ($item as $key => $value) {
          if ($array[$key] = $value) {
            return true;
          }
      }
      return false;
  }
}

function artmo_get_user_roles_by_id( $id )
{
    $user = new WP_User( $id );
    if ( empty ( $user->roles ) or ! is_array( $user->roles ) )
        return array ();
    $wp_roles = new WP_Roles;
    $names    = $wp_roles->get_names();
    $out      = array ();
    $i        = 0;
    foreach ( $user->roles as $role )
    {
      $out[] = $role;
    }
    return $out;
}

function get_all_genres() {
  $allGenres = get_option('used_genres');
  return $allGenres;
}

function get_all_countries() {
  $allCountries = get_option('used_countries');
  return $allCountries;
}

function get_all_cities() {
  $allCities = get_option('used_cities');
  return $allCities;
}

function get_galleries_countries() {
  $allCountriesGalleries = get_option('used_countries_galleries');
  return $allCountriesGalleries;
}

function get_galleries_cities() {
  $allCitiesGalleries = get_option('used_cities_galleries');
  return $allCitiesGalleries;
}

function get_artists_countries() {
  $allCountriesArtists = get_option('used_countries_artists');
  return $allCountriesArtists;
}

function get_artists_cities() {
  $allCitiesArtists = get_option('used_cities_artists');
  return $allCitiesArtists;
}

function get_universities_countries() {
  $allCountriesUniversities = get_option('used_countries_universities');
  return $allCountriesUniversities;
}

function get_universities_cities() {
  $allCitiesUniversities = get_option('used_cities_universities');
  return $allCitiesUniversities;
}

function wc_get_product_term_list( $product_id, $term, $sep = ', ', $before = '', $after = '' ) {
    return get_the_term_list( $product_id, $term, $before, $sep, $after );
}


//Manually update names (to be deprecated)

add_action( 'init', 'artmo_set_user_display_name');

function artmo_set_user_display_name() {

    if (isset( $_GET['do_user_display_names'] )) {
    $args = array (
      'orderby' => 'registered',
      'order' => 'ASC'
    );
    $wp_user_query = new WP_User_Query( $args );
    $authors = $wp_user_query->get_results();

    if ( ! empty( $authors ) ) {
        foreach ( $authors as $author ) {
            $author_info = get_userdata( $author->ID );
            $firstName = $author_info->first_name;
            $lastName = $author_info->last_name;
            $nickName = $author_info->nickname;
            artmo_update_names($user_id, $firstName, $lastName, $userName, $nickName);
        }
    } else {
        echo 'No authors found';
    }
  }
}


function artmo_update_names($user_id, $firstName, $lastName, $userName, $nickName) {
  if (( !empty( $firstName ) ) && ( !empty( $lastName ) )) {
      $full_name = $firstName . ' ' .  $lastName;
      update_user_meta($user_id, 'user_display_name', $full_name);
      artmo_update_vendor_categories($user_id, $full_name);
  } else if ( !empty( $firstName ) ) {
      update_user_meta($user_id, 'user_display_name', $firstName);
      artmo_update_vendor_categories($user_id, $firstName);
  } else if ( !empty( $userName ) ) {
      update_user_meta($user_id, 'user_display_name', $userName);
      artmo_update_vendor_categories($user_id, $userName);
  } else if ( !empty( $nickName ) ) {
      update_user_meta($user_id, 'user_display_name', $nickName);
      artmo_update_vendor_categories($user_id, $nickName);
  } else {
      update_user_meta($user_id, 'user_display_name', 'ARTMO User');
  }
}

function artmo_update_vendor_categories($user_id, $name) {

		$author_cat = 'artist_'.$user_id;
		$author_cat_obj = get_term_by('slug', $author_cat, 'artists_cat');
		$parent_id = $author_cat_obj->term_id;

    $term_exists = term_exists( $author_cat, 'artists_cat');

    if ( $term_exists !== 0 && $term_exists !== null ) {

    		$term_id = $term_exists['term_id'];
    		$cat_ids = array($term_id, $parent_id);
    		$cat_ids = array_map( 'intval', $cat_ids );
    		$cat_ids = array_unique( $cat_ids );

        $update = wp_update_term( $parent_id, 'artists_cat', array(
            'name' => $name
        ) );

        if ( ! is_wp_error( $update ) ) {
            echo 'Success!';
        }
    }
}


function artmo_send_private_message($to, $from, $message){
  //We do not want to message ourselves
  if ($from==$to) return;
  // Create conversation and add message
  $_POST['content']  = $message;
  $conversation_id = UM()->Messaging_API()->api()->create_conversation( $to, $from );;
  $_POST['content'] = "";
  do_action('um_after_new_message', $to, $from, $conversation_id );
}


function randomGen($min, $max, $quantity) {
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
}


function get_onboarding_steps() {
  return array('profile', 'identification', 'complete');
}


function get_current_user_completeness() {
  $result = UM()->Profile_Completeness_API()->shortcode()->profile_progress( get_current_user_id() );
  if ($result['progress'] < $result['req_progress']) {
    return false;
  }
  return true;
}


function artmo_get_query_cats() {
  return array('artists_cat', 'medium_cat', 'genre_tag', 'country_cat');
}


function artmo_flatten_arr(array $array) {
    $return = array();
    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
    return $return;
}


function artmo_inches2feet($inches)
{
     //$inches = $cm/2.54;
     $feet = intval($inches/12);
     $inches = $inches%12;
     return sprintf('%d\'%d"', $feet, $inches);
}



function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function get_video_categories() {
  $categories = array( 'artist_at_work' => 'Artist at Work', 'artworks' => 'Artworks', 'interview' => 'Interview', 'introduction' => 'Introduction', 'exhibition' => 'Exhibition', 'experimental' => 'Experimental', 'performance' => 'Performance', 'street_art' => 'Street Art', 'video_art' => 'Video Art', 'other' => 'Other');
  return $categories;
}
