<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function artmo_show_members_directory($args) {

  wp_enqueue_style('um-followers');

  extract( $args );

  ?>

  <div class="um-members">

  	<div class="um-gutter-sizer"></div>

    <div class="um-members-preloader youtube-videos-preloader">
      <i class="ion ion-load-d"></i>
    </div>

  </div>
  <input type="hidden" name="query_args" value='<?php echo json_encode($args); ?>'/>

<?php

}

function artmo_members_get_args() {
  $args = array(
    "form_id" => "29172",
    "template" => "members",
    "css_profile_card_text" => "#444444",
    "css_card_bordercolor" => "#cccccc",
    "css_img_bordercolor" => "#cccccc",
    "css_card_thickness" => "2px",
    "mode" => "directory",
    "has_profile_photo" => "0",
    "has_cover_photo" => "0",
    "sortby" => "most_followed",
    "has_completed_profile" => "0",
    "show_pm_button" => "0",
    "profile_photo" => "1",
    "cover_photos" => "1",
    "show_name" => "1",
    "show_tagline" => "1",
    "show_userinfo" => "0",
    "userinfo_animate" => "0",
    "show_social" => "1",
    "search" => "1",
    "must_search" => "0",
    "header" => "sorted by most followed",
    "no_users" => "No matches for your search criteria. ",
    "roles" => array( "um_artist" ),
    "tagline_fields" => array( "role_radio", "cityField", "countryField" ),
    "reveal_fields" => array( "text_field" ),
    "search_fields" => array( "artistCountrySearchOptions", "artistCitySearchOptions", "genres", "user_display_name" )
  );
  return $args;
}


function artmo_output_members( $args, $members ) {

    //$args = artmo_members_get_args();

    //echo var_dump($args);

    extract( $args );

    $response = '';

  	$i = 0; foreach( $members as $member) { $i++; um_fetch_user( $member["ID"]);

    $response .= '<div class="um-member masonry-brick um-role-' . um_user( 'role' ) . ' ' . um_user('account_status') . ' ' . 'with-cover' . '">';

    $response .= '<span class="um-member-status ' . ' ' . um_user('account_status') . ' ' . um_user('account_status_name') . '"></span>';

		if ($cover_photos) {
			$sizes = UM()->options()->get('cover_thumb_sizes');
			if ( UM()->mobile()->isTablet() ) {
				$cover_size = $sizes[1];
			} else {
				$cover_size = $sizes[0];
			}

  		$response .= '<div class="um-member-cover" data-ratio="' . UM()->options()->get('profile_cover_ratio') . '">';
  			$response .= '<div class="um-member-cover-e"><a href="' . um_user_profile_url() . '" title="' . esc_attr(um_user('display_name')) . '">' . um_user('cover_photo', $cover_size) . '</a></div>';
  		$response .= '</div>';

		}

  	 if ($profile_photo) {
  			$default_size = str_replace( 'px', '', UM()->options()->get('profile_photosize') );
  			$corner = UM()->options()->get('profile_photocorner');

  	$response .= '<div class="um-member-photo radius-' . $corner . '"><a href="' . um_user_profile_url() . '" title="' . esc_attr(um_user('display_name')) . '">' . get_avatar( um_user('ID'), $default_size ) . '</a></div>';

    }

		  $response .= '<div class="um-member-card">';

  				if ( $show_name ) {
  						$response .= '<div class="um-member-name"><a href="' . um_user_profile_url() . '" title="' . esc_attr(um_user('display_name')) . '">' . um_user('display_name', 'html') . '</a></div>';
  				}

          ob_start();
          do_action( 'um_members_just_after_name', um_user('ID'), $args );
          $contents = ob_get_contents(); // the actions output will now be stored in the variable as a string!
          $response .= $contents;
          ob_end_clean(); // never forget this or you will keep capturing output.

          if ( UM()->roles()->um_current_user_can( 'edit', um_user('ID') ) ) {
						$response .= '<div class="um-members-edit-btn">';
							$response .= '<a href="' . um_edit_profile_url() . '" class="um-edit-profile-btn um-button um-alt">';
								 $response .= __( 'Edit profile','ultimate-member' );
							$response .= '</a>';
						$response .= '</div>';
					}

          ob_start();
          do_action( 'um_members_after_user_name', um_user('ID'), $args );
          $contents = ob_get_contents(); // the actions output will now be stored in the variable as a string!
          $response .= $contents;
          ob_end_clean(); // never forget this or you will keep capturing output.

					if ( $show_tagline && ! empty( $tagline_fields ) && is_array( $tagline_fields ) ) {

            //$response .= 'here';

						um_fetch_user( $member["ID"] );
						foreach( $tagline_fields as $key ) {
							if ( $key /*&& um_filtered_value( $key )*/ ) {
								$value = um_filtered_value( $key );

								if ( ! $value )
									continue;

  				           $response .= '<div class="um-member-tagline um-member-tagline-' . esc_attr( $key ) . '">' . __( $value, 'ultimate-member') . '</div>';

  								} // end if
  							} // end foreach
  						} // end if $show_tagline
  						if ( ! empty( $show_userinfo ) ) {

  							$response .= '<div class="um-member-meta-main">';

  							if ( $userinfo_animate ) {
  								$response .= '<div class="um-member-more"><a href="#"><i class="um-faicon-angle-down"></i></a></div>';
  							}

  							$response .= '<div class="um-member-meta">';

  							um_fetch_user( $member["ID"] );
  								if ( ! empty( $reveal_fields ) && is_array( $reveal_fields ) ) {
  									foreach ( $reveal_fields as $key ) {
  										if ( $key ) {
  											$value = um_filtered_value( $key );
  											if ( ! $value )
  												continue;

  											$response .= '<div class="um-member-metaline um-member-metaline-' . esc_attr( $key ) . '"><span><strong>' . UM()->fields()->get_label( $key ) . ':</strong> ' . __( $value, 'ultimate-member') . '</span></div>';

  										}
  									}
  								}
  								if ( $show_social ) {
  									$response .= '<div class="um-member-connect">';
  										UM()->fields()->show_social_urls();
  									$response .= '</div>';
  								}

  							$response .= '</div>';

  							$response .= '<div class="um-member-less"><a href="#"><i class="um-faicon-angle-up"></i></a></div>';

  						$response .= '</div>';

  						}

  					$response .= '</div>';

  	     $response .= '</div>';

  	um_reset_user_clean();
  	} // end foreach
  	um_reset_user();

    return $response;

}

remove_action( 'um_pre_directory_shortcode', 'um_pre_directory_shortcode' );

remove_action( 'um_members_directory_display', 'um_members_directory_display', 10, 1 );

add_action( 'um_members_directory_display', 'artmo_show_members_directory', 10, 1 );
