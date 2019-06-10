<?php
global $wp, $WCFM, $WCFMu, $wp_query;

$order_id = 0;
if( isset( $wp->query_vars['wcfm-orders-details'] ) && !empty( $wp->query_vars['wcfm-orders-details'] ) ) {
	$order_id = absint($wp->query_vars['wcfm-orders-details']);
} else {
	return;
}

$order = wc_get_order( $order_id );

$order_status = sanitize_title( $order->get_status() );
if( apply_filters( 'wcfm_is_pref_shipment_tracking', true ) && apply_filters( 'wcfm_is_allow_shipping_tracking', true ) && !in_array( $order_status, apply_filters( 'wcfm_shipment_disable_order_status', array( 'failed', 'cancelled', 'refunded', 'pending' ) ) ) ) {
	$needs_shipping_tracking = false; 
	?>
	<div class="wcfm-clearfix"></div>
	<br />
	<!-- collapsible -->
	<div class="page_collapsible orders_details_shipment" id="wcfm_order_shipment_options"><?php _e('Shipment Tracking', 'wc-frontend-manager-ultimate'); ?><span></span></div>
	<div class="wcfm-container orders_details_shipment_expander_container">
		<div id="orders_details_shipment_expander" class="wcfm-content">
		  <h2><?php _e( 'Mark item(s) as shipped and provide tracking information', 'wc-frontend-manager-ultimate' ); ?></h2>
		  <div class="wcfm-clearfix"></div>
		  <table cellpadding="0" cellspacing="0" class="woocommerce_order_items">
				<tbody id="order_line_items">
				<?php
				  $line_items          = $order->get_items( apply_filters( 'woocommerce_admin_order_item_types', 'line_item' ) );
					$line_items = apply_filters( 'wcfm_valid_line_items', $line_items, $order->get_id() );
					
					$shipped_action = 'wcfm_wcvendors_order_mark_shipped';
					$is_marketplace = wcfm_is_marketplace();
					if( $is_marketplace == 'wcvendors' ) $shipped_action = 'wcfm_wcvendors_order_mark_shipped';
					elseif( $is_marketplace == 'wcpvendors' ) $shipped_action = 'wcfm_wcpvendors_order_mark_fulfilled';
					elseif( $is_marketplace == 'wcmarketplace' ) $shipped_action = 'wcfm_wcmarketplace_order_mark_shipped';
					elseif( $is_marketplace == 'wcfmmarketplace' ) $shipped_action = 'wcfm_wcfmmarketplace_order_mark_shipped';
					elseif( $is_marketplace == 'dokan' ) $shipped_action = 'wcfm_dokan_order_mark_shipped';
					
					foreach ( $line_items as $item_id => $item ) {
						$_product  = $item->get_product();
						
						$needs_shipping = $WCFM->frontend->is_wcfm_needs_shipping( $_product );
						$shipped = true;
						if( $needs_shipping ) {
							$shipped = false;
							foreach ( $item->get_formatted_meta_data() as $meta_id => $meta ) {
								if( $meta->key == 'wcfm_tracking_url' ) {
									$shipped = true;
									break;
								}
							}
						}
						
						if( $shipped ) continue;
						$needs_shipping_tracking = true;
		
						if( current_user_can( 'edit_published_products' ) && apply_filters( 'wcfm_is_allow_edit_products', true ) && apply_filters( 'wcfm_is_allow_edit_specific_products', true, $item->get_product_id() ) ) {
							$product_link  = $_product ? get_wcfm_edit_product_url( $item->get_product_id(), $_product ) : '';
						} else {
							$product_link  = $_product ? get_permalink( $item->get_product_id() ) : '';
						}
						?>
						<tr class="item <?php echo apply_filters( 'woocommerce_admin_html_order_item_class', ( ! empty( $class ) ? $class : '' ), $item, $order ); ?>" data-order_item_id="<?php echo $item_id; ?>">
							<td class="name" data-sort-value="<?php echo esc_attr( $item->get_name() ); ?>">
								<?php
									echo $product_link ? '<a href="' . esc_url( $product_link ) . '" class="wc-order-item-name">' .  esc_html( $item->get_name() ) . '</a>' : '<div "class="wc-order-item-name"">' . esc_html( $item->get_name() ) . '</div>';
						
									if ( $_product && $_product->get_sku() ) {
										echo '<div class="wc-order-item-sku"><strong>' . __( 'SKU:', 'wc-frontend-manager' ) . '</strong> ' . esc_html( $_product->get_sku() ) . '</div>';
									}
						
									if ( ! empty( $item->get_variation_id() ) ) {
										echo '<div class="wc-order-item-variation"><strong>' . __( 'Variation ID:', 'wc-frontend-manager' ) . '</strong> ';
										if ( ! empty( $item->get_variation_id() ) && 'product_variation' === get_post_type( $item->get_variation_id() ) ) {
											echo esc_html( $item->get_variation_id() );
										} elseif ( ! empty( $item->get_variation_id() ) ) {
											echo esc_html( $item->get_variation_id() ) . ' (' . __( 'No longer exists', 'wc-frontend-manager' ) . ')';
										}
										echo '</div>';
									}
								?>
							</td>
							<td>
							  <a class="wcfm_order_mark_shipped" href="#" data-shipped_action="<?php echo $shipped_action; ?>" data-productid="<?php echo $_product->get_id(); ?>" data-orderitemid="<?php echo $item->get_id(); ?>" data-orderid="<?php echo $order_id; ?>"><span class="fa fa-truck text_tip" data-tip="<?php echo esc_attr__( 'Mark Shipped', 'wc-frontend-manager-ultimate' ); ?>"></span></a>
							</td>
						</tr>
						<?php
					}
					?>
				</tbody>
		  </table>
		</div>
	</div>
	<?php
	if( !$needs_shipping_tracking ) {
		?>
		<style>
		#wcfm_order_shipment_options, .orders_details_shipment_expander_container, #orders_details_shipment_expander { display: none; }
		</style>
		<?php
	}
}

if( apply_filters( 'wcfm_allow_order_notes', true ) ) {
	?>
	<div class="wcfm-clearfix"></div>
	<br />
	<!-- collapsible -->
	<div class="page_collapsible orders_details_notes" id="wcfm_order_notes_options"><?php _e('Order Notes', 'wc-frontend-manager-ultimate'); ?><span></span></div>
	<div class="wcfm-container">
		<div id="orders_details_notes_expander" class="wcfm-content">
			<?php
				if( $view_view_order_notes = apply_filters( 'wcfm_view_order_notes', true ) ) {
					$args = array(
						'post_id'   => $wp->query_vars['wcfm-orders-details'],
						'orderby'   => 'comment_ID',
						'order'     => 'DESC',
						'approve'   => 'approve',
						'type'      => 'order_note'
					);
			
					remove_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ), 10, 1 );
			
					$notes = apply_filters( 'wcfm_order_notes', get_comments( $args ), $wp->query_vars['wcfm-orders-details'] );
			
					add_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ), 10, 1 );
			
					echo '<table id="notes_holder"><tbody>';
			
					if ( $notes ) {
			
						foreach( $notes as $note ) {
			
							$note_classes   = array( 'note' );
							$note_classes[] = get_comment_meta( $note->comment_ID, 'is_customer_note', true ) ? 'customer-note' : '';
							$note_classes[] = $note->comment_author === __( 'WooCommerce', 'wc-frontend-manager-ultimate' ) ? 'system-note' : '';
							$note_classes   = apply_filters( 'woocommerce_order_note_class', array_filter( $note_classes ), $note );
							?>
							<tr class="<?php echo esc_attr( implode( ' ', $note_classes ) ); ?>">
								<td>
									<?php echo wpautop( wptexturize( wp_kses_post( $note->comment_content ) ) ); ?>
								</td>
								<td>
									<abbr class="exact-date" title="<?php echo $note->comment_date; ?>"><?php printf( __( 'added on %1$s at %2$s', 'wc-frontend-manager-ultimate' ), date_i18n( wc_date_format(), strtotime( $note->comment_date ) ), date_i18n( wc_time_format(), strtotime( $note->comment_date ) ) ); ?></abbr>
									<?php if ( $note->comment_author !== __( 'WooCommerce', 'wc-frontend-manager-ultimate' ) ) printf( ' ' . __( 'by %s', 'wc-frontend-manager-ultimate' ), $note->comment_author ); ?>
								</td>
							</tr>
							<?php
						}
			
					} else {
						//echo '<li>' . __( 'There are no notes yet.', 'wc-frontend-manager-ultimate' ) . '</li>';
					}
			
					echo '</tbody></table>';
				}
			?>
			
			<?php if( $view_add_order_notes = apply_filters( 'wcfm_add_order_notes', true ) ) { ?>
				<div class="add_note">
					<h4><?php _e( 'Add note', 'wc-frontend-manager-ultimate' ); ?> <span class="img_tip" data-tip="<?php _e( 'Add a note for your reference, or add a customer note (the user will be notified).', 'wc-frontend-manager-ultimate' ); ?>"></span></h4>
					<p>
						<textarea type="text" name="order_note" id="add_order_note" class="input-text" cols="20" rows="5"></textarea>
					</p>
					<p>
						<select name="order_note_type" id="order_note_type" class="wcfm-select">
							<option value=""><?php _e( 'Private note', 'wc-frontend-manager-ultimate' ); ?></option>
							<option value="customer"><?php _e( 'Note to customer', 'wc-frontend-manager-ultimate' ); ?></option>
						</select>
						<div class="wcfm-clearfix"></div>
						<a href="#" class="add_note button" id="wcfm_add_order_note" data-orderid="<?php echo $wp->query_vars['wcfm-orders-details']; ?>"><?php _e( 'Add', 'wc-frontend-manager-ultimate' ); ?></a>
						<div class="wcfm-clearfix"></div>
					</p>
				</div>
			<?php } ?>
		</div>
	</div>
	<!-- end collapsible -->
	<?php
}
?>