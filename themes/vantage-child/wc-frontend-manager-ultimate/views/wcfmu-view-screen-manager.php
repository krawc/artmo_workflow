<?php
/**
 * WCFM plugin view
 *
 * WCFM ScreenManager View
 *
 * @author 		WC Lovers
 * @package 	wcfmu/view
 * @version   2.3.7
 */
global $WCFM, $WCFMu;

$screen = '';
if( isset($_POST['screen']) ) {
	$screen = $_POST['screen'];
	$wcfm_screen_manager = (array) get_option( 'wcfm_screen_manager' );
	$wcfm_screen_manager_data = array();
	$screen_manager_options = array();
	if( $screen) {
		switch( $screen ) {
		  case 'product':
			  $screen_manager_options = array( 1  => __( 'Image', 'wc-frontend-manager-ultimate' ),
			  																 2  => __( 'Name', 'wc-frontend-manager-ultimate' ),
			  																 3  => __( 'SKU', 'wc-frontend-manager-ultimate' ),
			  																 4  => __( 'Status', 'wc-frontend-manager-ultimate' ),
			  																 5  => __( 'Stock', 'wc-frontend-manager-ultimate' ),
			  																 6  => __( 'Price', 'wc-frontend-manager-ultimate' ),
			  																 7  => __( 'Type', 'wc-frontend-manager-ultimate' ),
			  																 8  => __( 'Views', 'wc-frontend-manager-ultimate' ),
			  																 9  => __( 'Date', 'wc-frontend-manager-ultimate' ),
			  																 10 => __( 'Store', 'wc-frontend-manager' ),
			  																 11 => __( apply_filters( 'wcfm_products_additional_info_column_label', __( 'Additional Info', 'wc-frontend-manager' ) ) ),
			  																 12 => __( 'Actions', 'wc-frontend-manager-ultimate' ),
			  																);
		  break;
		  
		  case 'coupon':
			  $screen_manager_options = array( 0 => __( 'Code', 'wc-frontend-manager-ultimate' ),
			  																 1 => __( 'Type', 'wc-frontend-manager-ultimate' ),
			  																 2 => __( 'Amt', 'wc-frontend-manager-ultimate' ),
			  																 3 => __( 'Usage Limit', 'wc-frontend-manager-ultimate' ),
			  																 4 => __( 'Expiry date', 'wc-frontend-manager-ultimate' ),
			  																 5 => __( 'Actions', 'wc-frontend-manager-ultimate' ),
			  																);
		  break;
		  
		  case 'order':
			  $screen_manager_options = array( 0 => __( 'Status', 'wc-frontend-manager-ultimate' ),
			  																 1 => __( 'Order', 'wc-frontend-manager-ultimate' ),
			  																 2 => __( 'Purchased', 'wc-frontend-manager-ultimate' ),
			  																 3 => __( 'Gross Sales', 'wc-frontend-manager-ultimate' ),
			  																 4 => __( 'Commission', 'wc-frontend-manager-ultimate' ),
			  																 5 => __( 'Date', 'wc-frontend-manager-ultimate' ),
			  																 6 => __( apply_filters( 'wcfm_orders_additional_info_column_label', __( 'Additional Info', 'wc-frontend-manager' ) ) ),
			  																 7 => __( 'Actions', 'wc-frontend-manager-ultimate' ),
			  																);
		  break;
		  
		  case 'booking':
			  $screen_manager_options = array( 0 => __( 'Status', 'wc-frontend-manager-ultimate' ),
			  																 1 => __( 'Booking', 'wc-frontend-manager-ultimate' ),
			  																 2 => __( 'Product', 'wc-frontend-manager-ultimate' ),
			  																 3 => __( 'Order', 'wc-frontend-manager-ultimate' ),
			  																 4 => __( 'Start Date', 'wc-frontend-manager-ultimate' ),
			  																 5 => __( 'End Date', 'wc-frontend-manager-ultimate' ),
			  																 6 => __( apply_filters( 'wcfm_bookings_additional_info_column_label', __( 'Additional Info', 'wc-frontend-manager' ) ) ),
			  																 7 => __( 'Actions', 'wc-frontend-manager-ultimate' ),
			  																);
			 break;
			 
			 case 'appointment':
			  $screen_manager_options = array( 0 => __( 'Status', 'wc-frontend-manager-ultimate' ),
			  																 1 => __( 'Appointment', 'wc-frontend-manager-ultimate' ),
			  																 2 => __( 'Product', 'wc-frontend-manager-ultimate' ),
			  																 3 => __( 'Order', 'wc-frontend-manager-ultimate' ),
			  																 4 => __( 'Start Date', 'wc-frontend-manager-ultimate' ),
			  																 5 => __( 'End Date', 'wc-frontend-manager-ultimate' ),
			  																 6 => __( apply_filters( 'wcfm_appointments_additional_info_column_label', __( 'Additional Info', 'wc-frontend-manager' ) ) ),
			  																 7 => __( 'Actions', 'wc-frontend-manager-ultimate' ),
			  																);
		  break;
		  
		  case 'listing':
			  $screen_manager_options = array( 0 => __( 'Listing', 'wc-frontend-manager' ),
			  																 1 => __( 'Status', 'wc-frontend-manager-ultimate' ),
			  																 2 => __( 'Filled?', 'wp-job-manager' ),
			  																 3 => __( 'Views', 'wp-job-manager' ),
			  																 4 => __( 'Date Posted', 'wp-job-manager' ),
			  																 5 => __( 'Listing Expires', 'wp-job-manager' ),
			  																 6 => __( 'Actions', 'wc-frontend-manager-ultimate' ),
			  																);
		}
		if( isset( $wcfm_screen_manager[$screen] ) ) $wcfm_screen_manager_data = $wcfm_screen_manager[$screen];
		if( !isset( $wcfm_screen_manager_data['admin'] ) ) {
			$wcfm_screen_manager_data['admin'] = $wcfm_screen_manager_data;
			$wcfm_screen_manager_data['vendor'] = $wcfm_screen_manager_data;
		}
	}
	
	
	
	if( !empty( $screen_manager_options ) ) {
	  ?>
	  <form id="wcfm_screen_manager_form">
	    <p><?php _e( 'Choose columns want to hide.', 'wc-frontend-manager-ultimate' ); ?></p>
	    <table>
	      <thead>
	        <tr>
	          <th><?php _e( 'Columns', 'wc-frontend-manager-ultimate' ); ?></th>
	          <th><?php _e( 'Admin', 'wc-frontend-manager-ultimate' ); ?></th>
	          <?php if( wcfm_is_marketplace() ) { ?>
	            <th><?php _e( 'Vendor', 'wc-frontend-manager-ultimate' ); ?></th>
	          <?php } ?>
	        </tr>
	      </thead>
	      <tbody>
	        <?php foreach( $screen_manager_options as $screen_manager_option_index => $screen_manager_option ) { ?>
						<tr>
							<td class="wcfm_screen_manager_form_label"><?php echo $screen_manager_option; ?></td>
							<td><input type="checkbox" <?php if( in_array( $screen_manager_option_index, $wcfm_screen_manager_data['admin'] ) ) echo 'checked="checked"'; ?> name="wcfm_screen_manager[<?php echo $screen; ?>][admin][<?php echo $screen_manager_option_index; ?>]" value="<?php echo $screen_manager_option_index; ?>" /></td>
							<?php if( wcfm_is_marketplace() ) { ?>
								<td><input type="checkbox" <?php if( in_array( $screen_manager_option_index, $wcfm_screen_manager_data['vendor'] ) ) echo 'checked="checked"'; ?> name="wcfm_screen_manager[<?php echo $screen; ?>][vendor][<?php echo $screen_manager_option_index; ?>]" value="<?php echo $screen_manager_option_index; ?>" /></td>
						  <?php } ?>
						</tr>
					<?php } ?>
	      </tbody>
	    </table>
	    <input type="hidden" name="wcfm_screen" value="<?php echo $screen; ?>" />
	    <div class="wcfm-message" tabindex="-1"></div>
	    <input type="button" class="wcfm_screen_manager_button" id="wcfm_screen_manager_button" value="Update" />
	  </form>
	  <?php
	}
}