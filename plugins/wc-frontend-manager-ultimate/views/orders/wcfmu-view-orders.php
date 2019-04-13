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
}
?>