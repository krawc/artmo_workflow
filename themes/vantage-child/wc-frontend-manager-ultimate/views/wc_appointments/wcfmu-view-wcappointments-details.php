<?php
/**
 * WCFM plugin view
 *
 * WCFM Appointments Details View
 *
 * @author 		WC Lovers
 * @package 	wcfmu/view
 * @version   2.4.0
 */
 
global $wp, $WCFM, $WCFMu, $theappointment, $wpdb;

if( !current_user_can( 'manage_appointments' ) || !apply_filters( 'wcfm_is_allow_appointment_list', true ) ) {
	wcfm_restriction_message_show( "Appointments" );
	return;
}

if ( ! is_object( $theappointment ) ) {
	if( isset( $wp->query_vars['wcfm-appointments-details'] ) && !empty( $wp->query_vars['wcfm-appointments-details'] ) ) {
		$theappointment = get_wc_appointment( $wp->query_vars['wcfm-appointments-details'] );
	}
}

$appointment_id = $wp->query_vars['wcfm-appointments-details'];
$post = get_post($appointment_id);
$appointment = new WC_Appointment( $post->ID );
$order             = $appointment->get_order();
$product_id        = $appointment->get_product_id( 'edit' );
$customer_id       = $appointment->get_customer_id( 'edit' );
$product           = $appointment->get_product( $product_id );
$customer          = $appointment->get_customer();
$statuses          = array_unique( array_merge( get_wc_appointment_statuses( null, true ), get_wc_appointment_statuses( 'user', true ), get_wc_appointment_statuses( 'cancel', true ) ) );

do_action( 'before_wcfm_appointments_details' );
?>

<div class="collapse wcfm-collapse" id="wcfm_appointment_details">

  <div class="wcfm-page-headig">
		<span class="fa fa-calendar-check-o"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Appointment Details', 'wc-frontend-manager-ultimate' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
		<div id="wcfm_page_load"></div>
		
		<div class="wcfm-container wcfm-top-element-container">
			<h2><?php _e( 'Appointment #', 'wc-frontend-manager-ultimate' ); echo $appointment_id; ?></h2>
			
			<?php
			if( $allow_wp_admin_view = apply_filters( 'wcfm_allow_wp_admin_view', true ) ) {
				?>
				<a target="_blank" class="wcfm_wp_admin_view text_tip" href="<?php echo admin_url('post.php?post='.$appointment_id.'&action=edit'); ?>" data-tip="<?php _e( 'WP Admin View', 'wc-frontend-manager-ultimate' ); ?>"><span class="fa fa-wordpress"></span></a>
				<?php
			}
			
			if( $wcfm_is_allow_appointment_calendar = apply_filters( 'wcfm_is_allow_appointment_calendar', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_appointments_calendar_url().'" data-tip="'. __('Calendar View', 'wc-frontend-manager-ultimate') .'"><span class="fa fa-calendar-o"></span></a>';
			}
			echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_appointments_url().'" data-tip="' . __( 'Appointments List', 'wc-frontend-manager-ultimate' ) . '"><span class="fa fa-calendar"></span></a>';
			if( $wcfm_is_allow_manage_staff = apply_filters( 'wcfm_is_allow_manage_staff', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_appointments_staffs_url().'" data-tip="' . __( 'Manage Staffs', 'wc-frontend-manager-ultimate' ) . '"><span class="fa fa-group"></span></a>';
			}
			
			if( $has_new = apply_filters( 'wcfm_add_new_product_sub_menu', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_edit_product_url().'" data-tip="' . __('Create Appointmentable', 'wc-frontend-manager-ultimate') . '"><span class="fa fa-cube"></span></a>';
			}
			?>
			<div class="wcfm_clearfix"></div>
		</div>
	  <div class="wcfm-clearfix"></div><br />
	  
	  <?php do_action( 'begin_wcfm_appointments_details' ); ?>
		
		<!-- collapsible -->
		<div class="page_collapsible appointments_details_general" id="wcfm_general_options">
			<?php _e('Overview', 'wc-frontend-manager-ultimate'); ?><span></span>
		</div>
		<div class="wcfm-container">
			<div id="appointments_details_general_expander" class="wcfm-content">
			
				<?php if ( $order ) { do_action( 'begin_wcfm_appointments_details_overview', $appointment_id, $order->get_order_number() ); } ?>
				
				<p class="form-field form-field-wide">
					<label for="appointment_date"><?php _e( 'Appointment Created:', 'wc-frontend-manager-ultimate' ) ?></label>
					<?php echo date_i18n( wc_date_format() . ' @' . wc_time_format(), $appointment->get_date_created() ); ?>
				</p>
				
				<p class="form-field form-field-wide">
					<label for="appointment_date"><?php _e( 'Order Number:', 'wc-frontend-manager-ultimate' ) ?></label>
					<?php
					if ( $order ) {
						if( apply_filters( 'wcfm_is_allow_order_details', true ) && $WCFM->wcfm_vendor_support->wcfm_is_order_for_vendor( $order->get_order_number() ) ) {
							echo '<span class="appointment-orderno"><a href="' . get_wcfm_view_order_url( $order->get_order_number(), $order ) . '">#' . $order->get_order_number() . '</a></span> &ndash; ' . esc_html( wc_get_order_status_name( $order->get_status() ) ) . '(' . date_i18n( wc_date_format(), strtotime( $order->get_date_created() ) ) . ')';
						} else {
							echo '<span class="appointment-orderno">#' . $order->get_order_number() . ' - ' . esc_html( wc_get_order_status_name( $order->get_status() ) ) . '</span>';
						}
					} else {
						echo '-';
					}
					?>
				</p>
				
				<?php if( apply_filters( 'wcfm_is_allow_appointment_status_update', true ) ) { ?>
					<p class="form-field form-field-wide">
						<label for="wcfm_appointment_status"><?php _e( 'Appointment Status:', 'woocommerce-appointments' ); ?></label>
						<select id="wcfm_appointment_status" name="appointment_status">
							<?php
								foreach ( $statuses as $key => $value ) {
									echo '<option value="' . esc_attr( $key ) . '" ' . selected( $key, $post->post_status, false ) . '>' . esc_html__( $value, 'woocommerce-appointments' ) . '</option>';
								}
							?>
						</select>
						<button class="wcfm_modify_appointment_status button" id="wcfm_modify_appointment_status" data-appointmentid="<?php echo $appointment_id; ?>"><?php _e( 'Update', 'wc-frontend-manager-ultimate' ); ?></button>
					</p>
				<?php } ?>
			</div>
		</div>
		<div class="wcfm_clearfix"></div>
		<br />
		<!-- collapsible End -->
		
		<!-- collapsible -->
		<div class="page_collapsible appointments_details_appointment" id="wcfm_appointment_options">
			<?php _e('Appointment', 'wc-frontend-manager-ultimate'); ?><span></span>
		</div>
		<div class="wcfm-container">
			<div id="appointments_details_appointment_expander" class="wcfm-content">
				
				<p class="form-field form-field-wide">
					<label for="Appointmented_product"><?php _e( 'Product:', 'woocommerce-appointments' ) ?></label>
					<?php
					
					if ( $product ) {
						$product_post = get_post($product->get_ID());
						echo '<a href="' . get_permalink($product->get_ID()) . '" target="_blank">' . $product_post->post_title . '</a>';
					} else {
						echo '-';
					}
					?>
				</p>
				
				<?php 
				$product_staffs		= $appointment->get_staff_ids( 'edit' );
				if( $product_staffs ) { 
				?>
					<p class="form-field form-field-wide">
						<label for="Appointmented_product"><?php _e( 'Staff:', 'woocommerce-appointments' ) ?></label>
						<?php
						  foreach ( $product_staffs as $staff_id ) {
						  	$staff            = new WC_Product_Appointment_Staff( $staff_id );
						  	echo $staff->display_name;
							}
						?>
					</p>
				<?php } ?>
				
				<p class="form-field form-field-wide">
					<label for="appointment_date"><?php _e( 'Start Date:', 'woocommerce-appointments' ) ?></label>
					<?php echo date_i18n( wc_date_format() . ' ' . wc_time_format(), $appointment->get_start( 'edit' ) ); ?>
				</p>
				
				<p class="form-field form-field-wide">
					<label for="appointment_date"><?php _e( 'End Date:', 'woocommerce-appointments' ) ?></label>
					<?php echo date_i18n( wc_date_format() . ' ' . wc_time_format(), $appointment->get_end( 'edit' ) ); ?>
				</p>
				<p class="form-field form-field-wide">
					<label for="appointment_date"><?php _e( 'All day:', 'woocommerce-appointments' ) ?></label>
					<?php echo $appointment->get_all_day( 'edit' ) ? 'YES' : 'NO'; ?>
				</p>
		 </div>
		</div>
		<div class="wcfm_clearfix"></div>
		<br />
		<!-- collapsible End -->
		
		<?php if ( $order ) { do_action( 'before_wcfm_appointments_customer_details', $appointment_id, $order->get_order_number() ); } ?>
		
		<!-- collapsible -->
		<div class="page_collapsible appointments_details_customer" id="wcfm_customer_options">
			<?php _e('Customer', 'woocommerce-appointments'); ?><span></span>
		</div>
		<div class="wcfm-container">
			<div id="appointments_details_customer_expander" class="wcfm-content">
				<?php
				$order_id    = $post->post_parent;
				$has_data    = false;
		
				echo '<table class="appointment-customer-details">';
		
				if ( $customer && $customer->full_name ) {
					echo '<tr>';
						echo '<th>' . __( 'Name:', 'woocommerce-appointments' ) . '</th>';
						echo '<td>';
						echo $customer->full_name;
						echo '</td>';
					echo '</tr>';
					
					if( apply_filters( 'wcfm_allow_view_customer_email', true ) ) {
						echo '<tr>';
							echo '<th>' . __( 'User Email:', 'woocommerce-appointments' ) . '</th>';
							echo '<td>';
							echo '<a href="mailto:' . esc_attr( $customer->email ) . '">' . esc_html( $customer->email ) . '</a>';
							echo '</td>';
						echo '</tr>';
					}
			
					$has_data = true;
				}
		
				if ( $order_id && ( $order = wc_get_order( $order_id ) ) ) {
					if( apply_filters( 'wcfm_allow_customer_billing_details', true ) ) {
						echo '<tr>';
							echo '<th>' . __( 'Address:', 'woocommerce-appointments' ) . '</th>';
							echo '<td>';
							if ( $order->get_formatted_billing_address() ) {
								echo wp_kses( $order->get_formatted_billing_address(), array( 'br' => array() ) );
							} else {
								echo __( 'No billing address set.', 'woocommerce-appointments' );
							}
							echo '</td>';
						echo '</tr>';
					}
					
					if( apply_filters( 'wcfm_allow_view_customer_email', true ) ) {
						echo '<tr>';
							echo '<th>' . __( 'Billing Email:', 'wc-frontend-manager-ultimate' ) . '</th>';
							echo '<td>';
							echo '<a href="mailto:' . esc_attr( $order->get_billing_email() ) . '">' . esc_html( $order->get_billing_email() ) . '</a>';
							echo '</td>';
						echo '</tr>';
						echo '<tr>';                                    
							echo '<th>' . __( 'Billing Phone:', 'wc-frontend-manager-ultimate' ) . '</th>';
							echo '<td>';
							echo esc_html( $order->get_billing_phone() );
							echo '</td>';
						echo '</tr>';
					}
					
					if( apply_filters( 'wcfm_is_allow_order_details', true ) && $WCFM->wcfm_vendor_support->wcfm_is_order_for_vendor( $order_id ) ) {
						echo '<tr class="view">';
							echo '<th>&nbsp;</th>';
							echo '<td>';
							echo '<a class="button" target="_blank" href="' . get_wcfm_view_order_url( $order_id ) . '">' . __( 'View Order', 'woocommerce-appointments' ) . '</a>';
							echo '</td>';
						echo '</tr>';
					}
		
					$has_data = true;
				}
		
				if ( ! $has_data ) {
					echo '<tr>';
						echo '<td colspan="2">' . __( 'N/A', 'woocommerce-appointments' ) . '</td>';
					echo '</tr>';
				}
				
				if ( $order ) { do_action( 'end_wcfm_appointments_details', $appointment_id, $order->get_order_number() ); }
				
				echo '</table>';
				?>
			</div>
		</div>
		<?php if ( $order ) { do_action( 'after_wcfm_appointments_details', $appointment_id, $order->get_order_number() ); } ?>
	</div>
</div>