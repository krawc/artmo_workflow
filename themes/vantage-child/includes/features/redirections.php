<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

//Collection link - redirects to different pages depending on the user status / role

add_action('template_redirect', 'collection_link');

function collection_link(){
  if (isset($_GET["collection_link"])) {
    $user_id = get_current_user_id();
    $registered = get_user_meta($user_id, 'wcfm_membership', true);
    $um_user = um_fetch_user($user_id);
    $profile_url = um_user_profile_url();
    if(empty($registered)) {
      ?>
      <script type="text/javascript">
        window.location.href = "<?php echo $profile_url;?>/vendor-membership"
      </script>
      <?php
    } else {
      ?>
      <script type="text/javascript">
        window.location.href = "<?php echo get_home_url(); ?>/wcfm"
      </script>
      <?php
    }
  }
}

add_shortcode('get_collection_menu_link', 'get_collection_menu_link');

function get_collection_menu_link() {
  $ultimatemember = new UM();
  um_fetch_user( $user_id );
  $roles = artmo_get_user_roles_by_id( $user_id );
  if ( in_array('um_artist', $roles) ) {
    return '<a class="collection_menu_link" href="/?collection_link">' . __('COLLECTION', 'vantage-child') . '</a>';
  }
}


add_action('template_redirect', 'add_new_vendor_account');

function add_new_vendor_account(){

if (isset($_GET["add_artist_collection"])) {
  $user_id = get_current_user_id();
  $registered = get_user_meta($user_id, 'registered_vendor', true);

  if(empty($registered)) {

      $ultimatemember = new UM();

      um_fetch_user( $user_id );
      $role = artmo_get_user_roles_by_id( $user_id );
      $display_name = um_user('display_name');
      $user_login = um_user('user_login');


      if (empty(um_user('user_login'))) {
        $display_name = $user_id;
        $user_login = $user_id;
      }

      if ( in_array( 'um_artist', $role ) ) {
        $term = wp_insert_term( $user_login, WC_PRODUCT_VENDORS_TAXONOMY );

          if ( ! is_wp_error( $term ) ) {
            $user = get_userdata( $user_id );
            WC_Product_Vendors_Utils::set_new_pending_vendor( $user_id );
            // add user to term meta
            $vendor_data = array();
            $vendor_data['admins']               = $user_id;
            $vendor_data['email']                = $user->user_email;
            $vendor_data['per_product_shipping'] = 'yes';
            $vendor_data['commission_type']      = 'percentage';

            update_term_meta( $term['term_id'], 'vendor_data', apply_filters( 'wcpv_registration_default_vendor_data', $vendor_data ) );

            wp_insert_term(
              $display_name,   // the term
              'artists_cat', // the taxonomy
              array(
                'description' => '',
                'slug'        => 'artist_'.$user_id
              )
            );

            // Getting the WP_User object
            $user->add_role( 'wc_product_vendors_admin_vendor' );

            add_user_meta($user_id, 'registered_vendor', 'true', true);

            ?>
            <script type="text/javascript">
                window.location.href='<?php echo get_home_url();?>/wcfm';
            </script>
          <?php
        } else {
          echo '<pre>'.var_dump($term->get_error_messages()).'</pre>';
        }
      }
    } else {
      ?>
      <script type="text/javascript">
          window.location.href='<?php echo get_home_url();?>/wcfm';
      </script>
      <?php
    }
  }
}

add_filter( 'um_profile_menu_link_collection', 'collection_profile_link' );

function collection_profile_link() {
  $user_id = get_current_user_id();
  $registered = get_user_meta($user_id, 'registered_vendor', true);
  if(empty($registered)) {
    return 'https://www.artmo.com/user?modal-link=terms-and-conditions-form';
  } else {
    return 'https://www.artmo.com/wcfm';
  }
}
