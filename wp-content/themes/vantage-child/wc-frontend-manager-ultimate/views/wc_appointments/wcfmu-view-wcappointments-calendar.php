<?php
/**
 * WCFM plugin views
 *
 * Plugin WC Appointment Calendar Views
 *
 * @author 		WC Lovers
 * @package 	wcfmu/views
 * @version   2.4.0
 */
global $WCFM, $WCFMu, $wc_appointments;

$wcfm_is_allow_appointment_calendar = apply_filters( 'wcfm_is_allow_appointment_calendar', true );
if( !current_user_can( 'manage_appointments' ) || !$wcfm_is_allow_appointment_calendar ) {
	wcfm_restriction_message_show( "Appointments Calendar" );
	return;
}

$wc_appointments->delete_appointment_dr_transients();
$view           = isset( $_REQUEST['view'] ) ? ucfirst( $_REQUEST['view'] ) : 'Month';
$view           = __( $view, 'wc-frontend-manager-ultimate' ); 
?>
<div class="collapse wcfm-collapse" id="wcfm_appointments_listing">
  <div class="wcfm-page-headig">
		<span class="fa fa-calendar-o"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Appointments Calender', 'wc-frontend-manager-ultimate' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>
	  
	  <div class="wcfm-container wcfm-top-element-container">
			<h2><?php printf( __( 'Appointments by %s', 'wc-frontend-manager-ultimate' ), $view ); ?></h2>
			<?php
			if( $allow_wp_admin_view = apply_filters( 'wcfm_allow_wp_admin_view', true ) ) {
				?>
				<a target="_blank" class="wcfm_wp_admin_view text_tip" href="<?php echo admin_url('edit.php?post_type=wc_appointment&page=appointment_calendar'); ?>" data-tip="<?php _e( 'WP Admin View', 'wc-frontend-manager-ultimate' ); ?>"><span class="fa fa-wordpress"></span></a>
				<?php
			}
			
			if( $wcfm_is_allow_manual_appointment = apply_filters( 'wcfm_is_allow_manual_appointment', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_create_appointments_url().'" data-tip="' . __( 'Create Appointment', 'wc-frontend-manager-ultimate' ) . '"><span class="fa fa-calendar-plus-o"></span></a>';
			}
			
			if( $wcfm_is_allow_manage_staff = apply_filters( 'wcfm_is_allow_manage_staff', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_appointments_staffs_url().'" data-tip="' . __( 'Manage Staffs', 'wc-frontend-manager-ultimate' ) . '"><span class="fa fa-group"></span></a>';
			}
			
			if( $has_new = apply_filters( 'wcfm_add_new_product_sub_menu', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_edit_product_url().'" data-tip="' . __('Create Appointable', 'wc-frontend-manager-ultimate') . '"><span class="fa fa-cube"></span></a>';
			}
			echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_appointments_url().'" data-tip="' . __( 'Appointments List', 'wc-frontend-manager-ultimate' ) . '"><span class="fa fa-calendar"></span></a>';
			?>
			<div class="wcfm-clearfix"></div>
		</div>
	  <div class="wcfm-clearfix"></div><br />
	  
	  <?php do_action( 'before_wcfm_appointments_calendar' ); ?>
			
		<div class="wcfm-container">
		  <div id="wwcfm_appointments_listing_expander" class="wcfm-content">
		    <?php
		    include_once( WC_APPOINTMENTS_ABSPATH . 'includes/admin/class-wc-appointments-admin.php' );
		    require_once( $WCFMu->plugin_path . 'includes/appointments_calendar/class-wcfm-appointments-calendar.php' );
				$calendar_view = new WCFM_Appointments_Calendar();
				$calendar_view->output();
		    ?>
		  <div class="wcfm-clearfix"></div>
			</div>
		</div>
		<?php
		do_action( 'after_wcfm_appointments_calendar' );
		?>
	</div>
</div>