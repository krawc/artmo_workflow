<?php
/**
 * WCFM plugin views
 *
 * Plugin WC Booking Manual Views
 *
 * @author 		WC Lovers
 * @package 	wcfmu/views
 * @version   2.3.9
 */
global $WCFM, $WCFMu, $wc_bookings;

$wcfm_is_allow_manual_booking = apply_filters( 'wcfm_is_allow_manual_booking', true );
if( !current_user_can( 'manage_bookings' ) || !$wcfm_is_allow_manual_booking ) {
	wcfm_restriction_message_show( "Manual Bookings" );
	return;
}

?>
<div class="collapse wcfm-collapse" id="wcfm_bookings_listing">
  <div class="wcfm-page-headig">
		<span class="fa fa-calendar-plus-o"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Create Bookings', 'woocommerce-bookings' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>
	  
	  <div class="wcfm-container wcfm-top-element-container">
			<h2><?php _e( 'Create Bookings Manually', 'wc-frontend-manager-ultimate' ); ?></h2>
			<?php
			if( $allow_wp_admin_view = apply_filters( 'wcfm_allow_wp_admin_view', true ) ) {
				?>
				<a target="_blank" class="wcfm_wp_admin_view text_tip" href="<?php echo admin_url('edit.php?post_type=wc_booking&page=create_booking'); ?>" data-tip="<?php _e( 'WP Admin View', 'wc-frontend-manager-ultimate' ); ?>"><span class="fa fa-wordpress"></span></a>
				<?php
			}
			
			if( $wcfm_is_allow_booking_calendar = apply_filters( 'wcfm_is_allow_booking_calendar', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_bookings_calendar_url().'" data-tip="'. __('Calendar View', 'wc-frontend-manager-ultimate') .'"><span class="fa fa-calendar-o"></span></a>';
			}
			
			echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_bookings_url().'" data-tip="' . __( 'Bookings List', 'wc-frontend-manager-ultimate' ) . '"><span class="fa fa-calendar"></span></a>';
			
			if( $wcfm_is_allow_manage_resource = apply_filters( 'wcfm_is_allow_manage_resource', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_bookings_resources_url().'" data-tip="' . __( 'Manage Resources', 'wc-frontend-manager-ultimate' ) . '"><span class="fa fa-briefcase"></span></a>';
			}
			
			if( apply_filters( 'wcfm_add_new_product_sub_menu', true ) && apply_filters( 'wcfm_is_allow_create_bookable', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_edit_product_url().'" data-tip="' . __('Create Bookable', 'wc-frontend-manager-ultimate') . '"><span class="fa fa-cube"></span></a>';
			}
			?>
			<div class="wcfm-clearfix"></div>
		</div>
	  <div class="wcfm-clearfix"></div><br />
	  
	  <?php do_action( 'before_wcfm_bookings_manual' ); ?>
			
		<div class="wcfm-container">
		  <div id="wwcfm_bookings_listing_expander" class="wcfm-content">
		    <?php
		    include_once( WC_BOOKINGS_ABSPATH . 'includes/admin/class-wc-bookings-admin.php' );
		    require_once( $WCFMu->plugin_path . 'includes/bookings_manual/class-wcfm-bookings-manual.php' );
				$create_manual_view = new WCFM_Create_Bookings_Manual();
				$create_manual_view->output();
		    ?>
		  <div class="wcfm-clearfix"></div>
			</div>
		</div>
		<?php
		do_action( 'after_wcfm_bookings_manual' );
		?>
	</div>
</div>