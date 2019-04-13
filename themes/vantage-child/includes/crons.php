<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


if(!wp_next_scheduled('my_hourly_events'))
  wp_schedule_event(time(), 'hourly', 'my_hourly_events');

add_action('my_hourly_events', 'the_function_to_run');

function the_function_to_run(){

    $user_query = get_users( array( 'fields' => array( 'ID' ) ) );

    $countries = array();
    $cities = array();

    $countriesGalleries = array();
    $citiesGalleries = array();

    $countriesArtists = array();
    $citiesArtists = array();

    $countriesUniversities = array();
    $citiesUniversties = array();
    $genres = array();

    if ( ! empty( $user_query ) ) {
    	foreach ( $user_query as $user ) {

        $country = get_user_meta($user->ID, 'countryField', true);
        $city = get_user_meta($user->ID, 'cityField', true);

        update_user_meta($user->ID, 'countrySearchOptions', $country);
        update_user_meta($user->ID, 'citySearchOptions', $city);

        $countries[$country] = $country;
        $cities[$city] = $city;
        $role = artmo_get_user_roles_by_id( $user->ID );

        if ( in_array('um_gallery', $role) ) {
            update_user_meta($user->ID, 'galleryCountrySearchOptions', $country);
            update_user_meta($user->ID, 'galleryCitySearchOptions', $city);
            $countriesGalleries[$country] = $country;
            $citiesGalleries[$city] = $city;
        }

        if ( in_array('um_artist', $role) ) {
            update_user_meta($user->ID, 'artistCountrySearchOptions', $country);
            update_user_meta($user->ID, 'artistCitySearchOptions', $city);
            $countriesArtists[$country] = $country;
            $citiesArtists[$city] = $city;
            //get artist's products
            $args = array(
                'post_type'  => 'product',
                'author'     => $user->ID,
            );
            $wp_posts = get_posts($args);
            $contents = '';
            $user_tags = array();
            //if has products, add tags as user meta
            if (count($wp_posts)) {
              foreach ( $wp_posts as $post ) :
                $post_id = $post->ID;
                $genreTags = wp_get_post_terms($post_id, 'genre_tag');
                $media = wp_get_post_terms($post_id, 'medium_cat');
                foreach ($genreTags as $cat) {
                  $genres[$cat->name] = $cat->name;
                  $user_tags[$cat->name] = $cat->name;
                }
                foreach ($media as $cat) {
                  $genres[$cat->name] = $cat->name;
                  $user_tags[$cat->name] = $cat->name;
                }
              endforeach;
              update_user_meta($user->ID, 'genres', $user_tags);
            } else {
              update_user_meta($user->ID, 'genres', array());
            }
        }

        if ( in_array('um_university', $role) ) {
            update_user_meta($user->ID, 'universityCountrySearchOptions', $country);
            update_user_meta($user->ID, 'universityCitySearchOptions', $city);
            $countriesUniversities[$country] = $country;
            $citiesUniversities[$city] = $city;
        }
      }
      ksort($genres);
      ksort($countries);
      ksort($cities);
      ksort($countriesGalleries);
      ksort($citiesGalleries);
      ksort($countriesArtists);
      ksort($citiesArtists);
      ksort($countriesUniversities);
      ksort($citiesUniversities);
    } else {
      echo 'No users to show.';
    }

   update_option( 'used_genres', $genres);
   update_option( 'used_genres', $genres);

   update_option( 'used_countries', $countries);
   update_option( 'used_cities', $cities);

   update_option( 'used_countries_galleries', $countriesGalleries);
   update_option( 'used_cities_galleries', $citiesGalleries);

   update_option( 'used_countries_artists', $countriesArtists);
   update_option( 'used_cities_artists', $citiesArtists);

   update_option( 'used_countries_universities', $countriesUniversities);
   update_option( 'used_cities_universities', $citiesUniversities);

}



if(!wp_next_scheduled('check_user_videos_hourly'))
  wp_schedule_event(time(), 'hourly', 'check_user_videos_hourly');

add_action('check_user_videos_hourly', 'artmo_cron_user_videos');

function artmo_cron_user_videos() {
	global $wpdb; // this is how you get access to the database

  $args = array (
    'role__in'       => array('um_artist', 'um_gallery', 'um_member'),
    'order'      => 'DESC',
    'orderby'    => 'rand',
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key'     => 'Video-vimeo',
            'value'   => '',
            'compare' => '!='
        ),
        array(
            'key'     => 'Video-YouTube',
            'value'   => '',
            'compare' => '!='
        )
      )
  );

  // Create the WP_User_Query object
  $wp_user_query = new WP_User_Query( $args );
  $users = $wp_user_query->get_results();


  $args_newest = array (
    'role__in'       => array('um_artist', 'um_gallery', 'um_member'),
    'order'      => 'DESC',
    'orderby'    => 'user_registered',
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key'     => 'Video-vimeo',
            'value'   => '',
            'compare' => '!='
        ),
        array(
            'key'     => 'Video-YouTube',
            'value'   => '',
            'compare' => '!='
        )
      )
  );


  // Create the WP_User_Query object
  $wp_user_query_newest = new WP_User_Query( $args_newest );
  $users_newest = $wp_user_query_newest->get_results();


  $args_most_followed = array (
    'role__in'       => array('um_artist', 'um_gallery', 'um_member'),
    'order'      => 'DESC',
    'orderby'    => 'followers',
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key'     => 'Video-vimeo',
            'value'   => '',
            'compare' => '!='
        ),
        array(
            'key'     => 'Video-YouTube',
            'value'   => '',
            'compare' => '!='
        )
      )
  );


  // Create the WP_User_Query object
  $wp_user_query_most_followed = new WP_User_Query( $args_most_followed );
  $users_most_followed = $wp_user_query_most_followed->get_results();


  //get users from both queries
  $user_videos = get_user_videos_from_query($users);
  $user_videos_newest = get_user_videos_from_query($users_newest);
  $user_videos_most_followed = get_user_videos_from_query($users_most_followed);

  update_option('user_videos', $user_videos);
  update_option('user_videos_newest', $user_videos_newest);
  update_option('user_videos_most_followed', $user_videos_most_followed);

  $user_videos_countries = get_user_videos_countries($users);
  update_option('user_videos_countries', $user_videos_countries);

}
