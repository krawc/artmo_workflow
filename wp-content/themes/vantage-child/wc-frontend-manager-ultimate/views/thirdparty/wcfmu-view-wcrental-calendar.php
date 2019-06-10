<?php
/**
 * WCFM plugin views
 *
 * Plugin WC Rental Calendar Views
 *
 * @author 		WC Lovers
 * @package 	wcfmu/views/thirdparty
 * @version   2.3.10
 */
global $WCFM, $WCFMu;

if( !$wcfm_allow_rental = apply_filters( 'wcfm_is_allow_rental', true ) ) {
	wcfm_restriction_message_show( "Rental" );
	return;
}

$args = array(
	'post_type' => 'shop_order',
	'post_status' => 'any',
	'posts_per_page' => -1,
);

$orders = get_posts($args);


$fullcalendar = array();

if(isset($orders) && !empty($orders)) {
	foreach($orders as $o) {

		$order_id = $o->ID;
		$order = wc_get_order($order_id);
		
		$line_items = $order->get_items();
		$line_items = apply_filters( 'wcfm_valid_line_items', $line_items, $order->get_id() );
		if( empty( $line_items ) ) continue;

		foreach( $line_items as $item ) {

			$item_meta_array = $item['item_meta_array'];

			foreach ($item_meta_array as $item_meta) {

				$fullcalendar[$order_id]['post_status'] = $o->post_status;
				$fullcalendar[$order_id]['title'] = $item['name'];
				$fullcalendar[$order_id]['link'] = get_the_permalink($item_meta->value);
				$fullcalendar[$order_id]['id'] = $item_meta->value;
				$fullcalendar[$order_id]['description'] = '<table cellspacing="0" class="redq-rental-display-meta"><tbody><tr><th>Order ID:</th><td>#'.$order_id.'</td></tr>';

				foreach ($item_meta_array as $order_item ) {
					if( $order_item->key !== '_qty'
						&& $order_item->key !== '_tax_class'
						&& $order_item->key !== '_product_id'
						&& $order_item->key !== '_variation_id'
						&& $order_item->key !== '_line_subtotal'
						&& $order_item->key !== '_line_total'
						&& $order_item->key !== '_line_subtotal_tax'
						&& $order_item->key !== '_line_tax'
						&& $order_item->key !== '_line_tax_data'
						&& $order_item->key !== 'pickup_hidden_datetime'
						&& $order_item->key !== 'return_hidden_datetime'
						&& $order_item->key !== 'return_hidden_days'
						&& $order_item->key !== 'redq_google_cal_sync_id'
						&& $order_item->key !== '_vendor_id'
					) {
						// Skip serialised meta
						if ( is_array( $order_item->value ) ) {
							continue;
						}
						$fullcalendar[$order_id]['description'] .= '<tr><th>'. wp_kses_post( rawurldecode( $order_item->key ) ).'</th><td>' . wp_kses_post( wpautop( make_clickable( rawurldecode( $order_item->value ) ) ) ) . '</td></tr>';
					}

					$product_id = $item['product_id'];
					$euro_date_format = get_post_meta($product_id, 'redq_choose_european_date_format', true);

					if( $order_item->key === 'pickup_hidden_datetime' ) {

						$pickup_date_time = explode('at', $order_item->value);

						if($euro_date_format == 'no'){
							$start = new DateTime($pickup_date_time[0]);
						} else {
							$starting = date('m/d/Y', strtotime(str_replace('/' , '.' , $pickup_date_time[0])));
							$start = new DateTime($starting);
						}
						$fullcalendar[$order_id]['start'] = $start->format('Y-m-d');
					}

					if( $order_item->key === 'return_hidden_days' ) {
						$start_day = $fullcalendar[$order_id]['start'];
						$end_day = new DateTime($start_day.' + '.$order_item->value.' day');
						$fullcalendar[$order_id]['end'] = $end_day->format('Y-m-d');
					}
					
					if( apply_filters( 'wcfm_is_allow_order_details', true ) && $WCFM->wcfm_vendor_support->wcfm_is_order_for_vendor( $order->get_id() ) ) {
						$fullcalendar[$order_id]['url'] = get_wcfm_view_order_url( absint( $order->get_id() ) );
					} else {
						$fullcalendar[$order_id]['url'] = '#';
					}
				}

				$order_total = $order->get_formatted_order_total();
				//$fullcalendar[$order_id]['description'] .= '<tr><th>'.esc_html__('Order Total', 'redq-rental').'</th><td>'.$order_total.'</td>';
				$fullcalendar[$order_id]['description'] .= '</tbody></table>';

			}
		}
	}
}

$calendar_data = array();
foreach ($fullcalendar as $key => $value) {
	if(array_key_exists('start', $value) && array_key_exists('end', $value)){
		$calendar_data[$key] = $value;
	}
}
			
wp_localize_script( 'wcfmu_rental_calendar_js', 'WCFMRENTALFULLCALENDER', $calendar_data );

?>
<div class="collapse wcfm-collapse" id="wcfm_wcrental_listing">
  <div class="wcfm-page-headig">
		<span class="fa fa-calendar-o"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Rental Calender', 'wc-frontend-manager-ultimate' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>
	  
	  <div class="wcfm-container wcfm-top-element-container">
			<h2><?php _e( 'Calender View', 'wc-frontend-manager-ultimate' ); ?></h2>
			
			<?php
			if( $allow_wp_admin_view = apply_filters( 'wcfm_allow_wp_admin_view', true ) ) {
				?>
				<a target="_blank" class="wcfm_wp_admin_view text_tip" href="<?php echo admin_url('admin.php?page=rnb_admin'); ?>" data-tip="<?php _e( 'WP Admin View', 'wc-frontend-manager-ultimate' ); ?>"><span class="fa fa-wordpress"></span></a>
				<?php
			}
			
			echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_rental_quote_url().'" data-tip="' . __('Quote Requests', 'wc-frontend-manager-ultimate') . '"><span class="fa fa-snowflake-o"></span></a>';
			
			if( $has_new = apply_filters( 'wcfm_add_new_product_sub_menu', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_edit_product_url().'" data-tip="' . __('Add New Product', 'wc-frontend-manager-ultimate') . '"><span class="fa fa-cube"></span><span class="text">' . __( 'Add New', 'wc-frontend-manager') . '</span></a>';
			}
			?>
			<div class="wcfm-clearfix"></div>
		</div>
	  <div class="wcfm-clearfix"></div><br />
	  
	  <?php do_action( 'before_wcfm_wcrental_calendar' ); ?>
			
		<div class="wcfm-container">
		  <div id="wwcfm_wcrental_listing_expander" class="wcfm-content">

				<div class="wrap">
					<div id="wcfm-rental-calendar"></div>
				</div>
				
				<div id="eventContent" class="popup-modal white-popup-block mfp-hide">
					<div class="white-popup">
						<h2 style="color: #00897b; font-weight: 600;"><span id="eventProduct"></span></h2>
						<strong><?php esc_html_e('Start:', 'redq-rental') ?></strong> <span id="startTime"></span><br>
						<strong><?php esc_html_e('End:', 'redq-rental') ?></strong> <span id="endTime"></span><br><br>
						<div id="eventInfo"></div>
						<p><strong><a id="eventLink" href="" target="_blank"><?php esc_html_e('View Order', 'redq-rental') ?></a></strong></p>
					</div>
				</div>
				
			<div class="wcfm-clearfix"></div>
			</div>
		</div>
		<?php
		do_action( 'after_wcfm_wcrental_calendar' );
		?>
	</div>
</div>