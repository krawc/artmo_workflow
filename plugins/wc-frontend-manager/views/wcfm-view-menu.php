<?php

global $wp, $WCFM, $WCFM_Query;

$wcfm_options = get_option('wcfm_options');

$is_menu_disabled = isset( $wcfm_options['menu_disabled'] ) ? $wcfm_options['menu_disabled'] : 'no';
if( $is_menu_disabled == 'yes' ) return;

$wcfm_menus = $WCFM->get_wcfm_menus();

$current_endpoint = $WCFM_Query->get_current_endpoint();

$menu_active_dependent_list = apply_filters( 'wcfm_menu_dependancy_map', array(
																																			'wcfm-articles-manage'     => 'wcfm-articles',
																																			'wcfm-products-manage'     => 'wcfm-products',
																																			'wcfm-stock-manage'        => 'wcfm-products',
																																			'wcfm-products-export'     => 'wcfm-products',
																																			'wcfm-products-import'     => 'wcfm-products',
																																			'wcfm-coupons-manage'      => 'wcfm-coupons',
																																			'wcfm-orders-details'      => 'wcfm-orders',
																																			'wcfm-vendors-manage'      => 'wcfm-vendors',
																																			'wcfm-customers-details'   => 'wcfm-customers',
																																			'wcfm-customers-manage'    => 'wcfm-customers',
																																			'wcfm-capability'          => 'wcfm-settings',
																																			'wcfm-withdrawal'          => 'wcfm-payments',
																																			'wcfm-bookings'                    => 'wcfm-bookings-dashboard',
																																			'wcfm-bookings-resources'          => 'wcfm-bookings-dashboard',
																																			'wcfm-bookings-resources-manage'   => 'wcfm-bookings-dashboard',
																																			'wcfm-bookings-manual'             => 'wcfm-bookings-dashboard',
																																			'wcfm-bookings-calendar'           => 'wcfm-bookings-dashboard',
																																			'wcfm-bookings-details'            => 'wcfm-bookings-dashboard',
																																			'wcfm-bookings-settings'           => 'wcfm-bookings-dashboard',
																																			'wcfm-appointments'                => 'wcfm-appointments-dashboard',
																																			'wcfm-appointments-staffs'         => 'wcfm-appointments-dashboard',
																																			'wcfm-appointments-staffs-manage'  => 'wcfm-appointments-dashboard',
																																			'wcfm-appointments-manual'         => 'wcfm-appointments-dashboard',
																																			'wcfm-appointments-calendar'       => 'wcfm-appointments-dashboard',
																																			'wcfm-appointments-details'        => 'wcfm-appointments-dashboard',
																																			'wcfm-appointments-settings'       => 'wcfm-appointments-dashboard',
																																			'wcfm-reports-sales-by-date'    => 'wcfm-reports',
																																			'wcfm-reports-out-of-stock'     => 'wcfm-reports',
																																			'wcfm-reports-sales-by-product' => 'wcfm-reports',
																																			'wcfm-reports-coupons-by-date'  => 'wcfm-reports',
																																			'wcfm-reports-low-in-stock'     => 'wcfm-reports',
																																			'wcfm-rental-quote-details'     => 'wcfm-rental-quote'
																																			) );

$logo = ( get_option( 'wcfm_site_logo' ) ) ? get_option( 'wcfm_site_logo' ) : '';
$logo_image_url = wp_get_attachment_image_src( $logo, 'thumbnail' );

if ( !empty( $logo_image_url ) ) {
	$logo_image_url = $logo_image_url[0];
} else {
	$logo_image_url = $WCFM->plugin_url . 'assets/images/your-logo-here.png';
}

$store_logo = apply_filters( 'wcfm_store_logo', $logo_image_url );
$store_name = apply_filters( 'wcfm_store_name', get_bloginfo() );
?>
<div id="wcfm_menu">


  <div class="wcfm_menu_items wcfm_menu_home">
    <a class="wcfm_menu_item <?php if( !$current_endpoint ) echo 'active'; ?>" href="<?php echo apply_filters( 'wcfm_dashboard_home', get_wcfm_page() ); ?>">
      <span class="ion-ios-glasses-outline"></span>
      <span class="text"><?php _e( 'DASHBOARD', 'wc-frontend-manager' ); ?></span>
    </a>
  </div>

  <?php
  if( !empty($wcfm_menus) ) {
  	foreach( $wcfm_menus as $wcfm_menu_key => $wcfm_menu_data ) {
  		if( !isset( $wcfm_menu_data['capability'] ) || empty( $wcfm_menu_data['capability'] ) || apply_filters( $wcfm_menu_data['capability'], true ) ) {
  			$is_active = false;
  			if( isset( $wp->query_vars[$wcfm_menu_key] ) ) $is_active = true;
  			if( !$is_active && $current_endpoint && isset( $menu_active_dependent_list[$current_endpoint] ) && ( $menu_active_dependent_list[$current_endpoint] == $wcfm_menu_key ) ) $is_active = true;
  		?>
				<div class="wcfm_menu_items wcfm_menu_<?php echo $wcfm_menu_key; ?>">
					<a class="wcfm_menu_item <?php if( $is_active ) echo 'active'; ?>" href="<?php echo $wcfm_menu_data['url']; ?>">
					  <span class="ion-<?php echo $wcfm_menu_data['icon']; ?>"></span>
					  <span class="text"><?php echo $wcfm_menu_data['label']; ?></span>
					</a>
					<?php if( apply_filters( 'wcfm_is_pref_hover_submenu', true ) ) { ?>
						<?php if( !isset( $wcfm_menu_data['submenu_capability'] ) || empty( $wcfm_menu_data['submenu_capability'] ) || apply_filters( $wcfm_menu_data['submenu_capability'], true ) ) { ?>
							<?php if( isset( $wcfm_menu_data['has_new'] ) ) { ?>
								<span class="wcfm_sub_menu_items <?php echo $wcfm_menu_data['new_class']; ?> moz_class">
									<a href="<?php echo $wcfm_menu_data['new_url']; ?>"><?php _e( 'Add New', 'wc-frontend-manager' ); ?></a>
								</span>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				</div>
			<?php
	 		}
	 	}
	 }
	?>
</div>
