<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


//change user roles allowing to be a vendor
add_filter('wcfm_allwoed_user_rols', 'return_user_roles_wcfm');

function return_user_roles_wcfm() {
  return array( 'administrator', 'shop_manager', 'um_artist' );
}


add_action('wcfm_before_dashboard_welcome_box', 'artmo_display_notice_box_dashboard');

function artmo_display_notice_box_dashboard() {
  if (get_theme_mod('vendor_notice')){
    ?>
  <div class="wcfm_vendor_notice">
    <p class="wcfm_vendor_notice_text"><?php echo get_theme_mod('vendor_notice'); ?></p>
  </div>
  <?php
  }
}

add_filter('wcfm_allowed_membership_user_roles', 'artmo_get_membership_user_roles');

function artmo_get_membership_user_roles () {
  return array('um_artist', 'wc_product_vendors_admin_vendor', 'wcfm_vendor');
}


add_filter('wcfm_is_allow_extend_membership', false);

add_filter('wcfm_membership_registration_steps', 'artmo_wcfm_membership_registration_steps');

function artmo_wcfm_membership_registration_steps ($steps) {
  if (wcfm_get_membership()) {
    unset($steps['registration']);
  }
  return $steps;
}
