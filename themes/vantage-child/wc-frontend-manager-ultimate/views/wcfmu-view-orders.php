<?php
add_action( 'before_wcfm_orders', 'wcfmu_orders_menu' );

function wcfmu_orders_menu() {
	global $WCFM, $WCFMu, $wpdb, $wp_locale;
	
	$wcfmu_orders_menus = apply_filters( 'wcfmu_orders_menus', array( 'all' => __( 'All', 'wc-frontend-manager-ultimate'), 
																																		'processing' => __( 'Processing', 'wc-frontend-manager-ultimate'),
																																		'on-hold' => __( 'On Hold', 'wc-frontend-manager-ultimate'),
																																		'completed' => __( 'Completed', 'wc-frontend-manager-ultimate'),
																																		'cancelled' => __( 'Cancelled', 'wc-frontend-manager-ultimate'),
																																		'refunded' => __( 'Refunded', 'wc-frontend-manager-ultimate'),
																																		'failed' => __( 'Failed', 'wc-frontend-manager-ultimate')
																																	) );
	
	$order_status = ! empty( $_GET['order_status'] ) ? sanitize_text_field( $_GET['order_status'] ) : 'all';
	
	?>
	<ul class="wcfm_orders_menus">
		<?php
		$is_first = true;
		foreach( $wcfmu_orders_menus as $wcfmu_orders_menu_key => $wcfmu_orders_menu) {
			?>
			<li class="wcfm_orders_menu_item">
				<?php
				if($is_first) $is_first = false;
				else echo " | ";
				?>
				<a class="<?php echo ( $wcfmu_orders_menu_key == $order_status ) ? 'active' : ''; ?>" href="<?php echo get_wcfm_orders_url( $wcfmu_orders_menu_key ); ?>"><?php echo $wcfmu_orders_menu; ?></a>
			</li>
			<?php
		}
		?>
	</ul>
	<?php
	if( !wcfm_is_vendor() ) {
		$months = $wpdb->get_results( $wpdb->prepare( '
				SELECT DISTINCT YEAR( shop_orders.post_date ) AS year, MONTH( shop_orders.post_date ) AS month
				FROM ' . $wpdb->prefix . 'posts AS shop_orders
				WHERE shop_orders.post_type = %s
				ORDER BY shop_orders.post_date DESC
			', 'shop_order' ) );
	
		$month_count = count( $months );
	
		if ( ! $month_count || ( 1 === $month_count && 0 === $months[0]->month ) ) {
			return;
		}
	
		$m = isset( $_REQUEST['m'] ) ? (int) $_REQUEST['m'] : 0;
		?>
	
		<div class="wcfm_orders_filter_wrap wcfm_filters_wrap">
			<select name="m" id="filter-by-date" style="width: 150px;">
				<option<?php selected( $m, 0 ); ?> value='0'><?php esc_html_e( 'Show all dates', 'wc-frontend-manager-ultimate' ); ?></option>
				<?php
				foreach ( $months as $arc_row ) {
					if ( 0 === $arc_row->year ) {
						continue;
					}
	
					$month = zeroise( $arc_row->month, 2 );
					$year  = $arc_row->year;
	
					if ( '00' === $month || '0' === $year ) {
						continue;
					}
	
					printf( "<option %s value='%s'>%s</option>\n",
						selected( $m, $year . $month, false ),
						esc_attr( $arc_row->year . $month ),
						/* translators: 1: month name, 2: 4-digit year */
						sprintf( __( '%1$s %2$d', 'wc-frontend-manager-ultimate' ), $wp_locale->get_month( $month ), $year )
					);
				}
				?>
			</select>
		</div>
		<?php
	}
}
?>