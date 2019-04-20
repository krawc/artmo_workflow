<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

add_shortcode('artmo_profiles_carousel', 'artmo_profiles_carousel');

function artmo_profiles_carousel() {

  $arguments_artists = array (
      'role__in' => array('um_artist'),
      'number' => '20',
      'order' => 'DESC',
      'orderby' => 'meta_value_num',
      'meta_key' => '_um_last_login',
      'meta_query' => array(
        'relation' => 'AND',
        array(
          'key' => 'cover_photo',
          'value' => '',
          'compare' => '!='
        ),
        array(
          'key' => 'profile_photo',
          'value' => '',
          'compare' => '!='
        ),
        array(
          'key' => 'countryField',
          'value' => '',
          'compare' => '!='
        ),
        array(
          'key' => 'cityField',
          'value' => '',
          'compare' => '!='
        ),
      )
  );

  $artists_query = new WP_User_Query($arguments_artists);
  $artists = $artists_query->get_results();


  $arguments_non_artists = array (
      'role__not_in' => array('administrator', 'um_artist'),
      'number' => '20',
      'order' => 'DESC',
      'orderby' => 'meta_value_num',
      'meta_key' => '_um_last_login',
      'meta_query' => array(
        'relation' => 'AND',
        array(
          'key' => 'cover_photo',
          'value' => '',
          'compare' => '!='
        ),
        array(
          'key' => 'profile_photo',
          'value' => '',
          'compare' => '!='
        ),
        array(
          'key' => 'countryField',
          'value' => '',
          'compare' => '!='
        ),
        array(
          'key' => 'cityField',
          'value' => '',
          'compare' => '!='
        ),
      )
  );

  $non_artists_query = new WP_User_Query($arguments_non_artists);
  $non_artists = $non_artists_query->get_results();

  $allUsers = array_merge($artists, $non_artists);

  shuffle($allUsers);


  global $wp_roles;

  // Check for results
  if (!empty($allUsers)) {
    $ultimatemember = new UM(); ?>

    <div class="members-slider">
      <h1 class="members-slider-header"><?php _e('THE ART NETWORK', 'vantage-child'); ?></h1>
    <div class="members-slider-container">

    <?php
    // loop through each user
    foreach ($allUsers as $user)
    {

      $user_id = $user->ID;
      $i++; um_fetch_user( $user_id );
      $role_slug = um_user('role');
      $role = $wp_roles->roles[$role_slug]['name'];
      $artistCountry = get_user_meta($user_id, 'countryField', true);
      $artistCity = get_user_meta($user_id, 'cityField', true);

      if (!empty($artistCountry) && !empty($artistCity)) {
        $location = '<span class="artist-city">' . $artistCity . '</span><span class="artist-country"> | ' . $artistCountry . '</span>';
      } else {
        $location = $artistCity . $artistCountry;
      }

      ?>

      <div class="um-member um-member-slider um-role-<?php echo um_user('role'); ?> <?php echo um_user('account_status'); ?>">

        <span class="um-member-status <?php echo um_user('account_status'); ?>"><?php echo um_user('account_status_name'); ?></span>

        <?php
          $sizes = um_get_option('cover_thumb_sizes');
          if (!isMobile()):
        ?>
          <div class="um-member-cover" data-ratio="<?php echo um_get_option('profile_cover_ratio'); ?>">
            <div class="um-member-cover-e"><a href="<?php echo um_user_profile_url(); ?>" title="<?php echo esc_attr(um_user('display_name')); ?>"><img src="<?php echo um_get_cover_uri( um_profile( 'cover_photo' ), 300 );?>" width="300" height="136" /></a></div>
          </div>
          <?php
        endif;
          $default_size = str_replace( 'px', '', um_get_option('profile_photosize') );
          $corner = um_get_option('profile_photocorner');
        ?>
        <div class="um-member-photo radius"><a href="<?php echo um_user_profile_url(); ?>" title="<?php echo esc_attr(um_user('display_name')); ?>"><?php echo get_avatar( um_user('ID'), 50 ); ?></a></div>
          <div class="um-member-card">
            <div class="um-member-name"><a href="<?php echo um_user_profile_url(); ?>" title="<?php echo esc_attr(um_user('display_name')); ?>"><?php echo um_user('display_name', 'html'); ?></a></div>
              <div class="um-member-meta">
                <p><?php echo $role; ?></p>
                <p><?php echo $location; ?></p>
              </div>
              <div class="um-member-follow">
                <?php echo UM()->Followers_API()->api()->follow_button( $user_id, get_current_user_id() ); ?>
              </div>
          </div>
      </div>
      <?php

      //echo $user_id;
       um_reset_user_clean();
     } ?>
      </div>
      <div class="members-slider-nav">
        <div class="slider-nav-bck"><i class="ion ion-chevron-left"></i></div>
        <div class="slider-nav-fwd"><i class="ion ion-chevron-right"></i></div>
      </div>
      </div>

      <?php
      um_reset_user();
  } else {
  }
}
