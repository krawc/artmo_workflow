<?php 
global $WCFM, $WCFMu, $wpo_wcpdf, $order, $order_id, $document, $document_type; 

$post = get_post($order_id);

if ( WC()->payment_gateways() ) {
	$payment_gateways = WC()->payment_gateways->payment_gateways();
} else {
	$payment_gateways = array();
}

$payment_method = ! empty( $order->get_payment_method() ) ? $order->get_payment_method() : '';

$order_type_object = get_post_type_object( $post->post_type );

// Get line items
$line_items          = $order->get_items( apply_filters( 'woocommerce_admin_order_item_types', 'line_item' ) );
$line_items_fee      = $order->get_items( 'fee' );
$line_items_shipping = $order->get_items( 'shipping' );

if( $is_wcfm_order_details_tax_line_item = apply_filters( 'wcfm_order_details_tax_line_item', true ) ) {
	if ( wc_tax_enabled() ) {
		$order_taxes         = $order->get_taxes();
		$tax_classes         = WC_Tax::get_tax_classes();
		$classes_options     = array();
		$classes_options[''] = __( 'Standard', 'wc-frontend-manager-ultimate' );
	
		if ( ! empty( $tax_classes ) ) {
			foreach ( $tax_classes as $class ) {
				$classes_options[ sanitize_title( $class ) ] = $class;
			}
		}
	
		// Older orders won't have line taxes so we need to handle them differently :(
		$tax_data = '';
		if ( $line_items ) {
			$check_item = current( $line_items );
			$tax_data   = maybe_unserialize( isset( $check_item['line_tax_data'] ) ? $check_item['line_tax_data'] : '' );
		} elseif ( $line_items_shipping ) {
			$check_item = current( $line_items_shipping );
			$tax_data = maybe_unserialize( isset( $check_item['taxes'] ) ? $check_item['taxes'] : '' );
		} elseif ( $line_items_fee ) {
			$check_item = current( $line_items_fee );
			$tax_data   = maybe_unserialize( isset( $check_item['line_tax_data'] ) ? $check_item['line_tax_data'] : '' );
		}
	
		$legacy_order     = ! empty( $order_taxes ) && empty( $tax_data ) && ! is_array( $tax_data );
		$show_tax_columns = ! $legacy_order || sizeof( $order_taxes ) === 1;
	}
}

// Marketplace Filters
$line_items = apply_filters( 'wcfm_valid_line_items', $line_items, $order->get_id() );

?>
<?php do_action( 'wpo_wcpdf_before_document', $document_type, $order ); ?>

<table class="head container">
	<tr>
		<td class="header">
		<?php
		if( $document->get_header_logo_id() ) {
			$document->header_logo();
		} else {
			echo apply_filters( 'wpo_wcpdf_invoice_title', __( 'Invoice', 'woocommerce-pdf-invoices-packing-slips' ) );
		}
		?>
		</td>
		<td class="shop-info">
			<div class="shop-name"><h3><?php $document->shop_name(); ?></h3></div>
			<div class="shop-address"><?php $document->shop_address(); ?></div>
		</td>
	</tr>
</table>

<h1 class="document-type-label">
<?php if( $document->get_header_logo_id() ) echo apply_filters( 'wpo_wcpdf_invoice_title', __( 'Invoice', 'woocommerce-pdf-invoices-packing-slips' ) ); ?>
</h1>

<?php do_action( 'wpo_wcpdf_after_document_label', $document_type, $order ); ?>

<table class="order-data-addresses">
	<tr>
		<td class="address billing-address">
			<!-- <h3><?php _e( 'Billing Address:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3> -->
			<?php echo wp_kses( $order->get_formatted_billing_address(), array( 'br' => array() ) ); ?>
			<?php if ( isset($document->settings->template_settings['invoice_email']) ) { ?>
			<div class="billing-email"><?php $document->billing_email(); ?></div>
			<?php } ?>
			<?php if ( isset($document->settings->template_settings['invoice_phone']) ) { ?>
			<div class="billing-phone"><?php $document->billing_phone(); ?></div>
			<?php } ?>
		</td>
		<td class="address shipping-address">
			<?php if ( isset($document->settings->template_settings['invoice_shipping_address']) && $order->get_formatted_shipping_address() ) { ?>
			<h3><?php _e( 'Ship To:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
			<?php echo wp_kses( $order->get_formatted_shipping_address(), array( 'br' => array() ) ); ?>
			<?php } ?>
		</td>
		<td class="order-data">
			<table>
				<?php do_action( 'wpo_wcpdf_before_order_data', $document_type, $order ); ?>
				<?php if ( isset($document->settings->template_settings['display_number']) && $document->settings->template_settings['display_number'] == 'invoice_number') { ?>
				<tr class="invoice-number">
					<th><?php _e( 'Invoice Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $document->invoice_number(); ?></td>
				</tr>
				<?php } ?>
				<?php if ( isset($document->settings->template_settings['display_date']) && $document->settings->template_settings['display_date'] == 'invoice_date') { ?>
				<tr class="invoice-date">
					<th><?php _e( 'Invoice Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $document->invoice_date(); ?></td>
				</tr>
				<?php } ?>
				<tr class="order-number">
					<th><?php _e( 'Order Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php echo $order_id; ?></td>
				</tr>
				<tr class="order-date">
					<th><?php _e( 'Order Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php echo date_i18n( 'Y-m-d', strtotime( $post->post_date ) ); ?> @<?php echo date_i18n( 'H', strtotime( $post->post_date ) ); ?>:<?php echo date_i18n( 'i', strtotime( $post->post_date ) ); ?></td>
				</tr>
				<tr class="payment-method">
					<th><?php _e( 'Payment Method:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php printf( __( '%s', 'wc-frontend-manager-ultimate' ), ( isset( $payment_gateways[ $payment_method ] ) ? esc_html( $payment_gateways[ $payment_method ]->get_title() ) : esc_html( $payment_method ) ) ); ?></td>
				</tr>
				<?php do_action( 'wpo_wcpdf_after_order_data', $document_type, $order ); ?>
			</table>			
		</td>
	</tr>
</table>

<?php do_action( 'wpo_wcpdf_before_order_details', $document_type, $order ); ?>

<table class="order-details">
	<thead>
		<tr>
			<th class="product"><?php _e('Product', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
			<th class="price"><?php _e('Price', 'wc-frontend-manager-ultimate' ); ?></th>
			<th class="quantity"><?php _e('Quantity', 'wpo_wcpdf '); ?></th>
			<?php if( $is_wcfm_order_details_line_total_head = apply_filters( 'wcfm_order_details_line_total_head', true ) ) { ?>
				<th class="line_cost"><?php _e( 'Total', 'wc-frontend-manager-ultimate' ); ?></th>
			<?php } ?>
			<?php do_action( 'wcfm_order_details_after_line_total_head', $order ); ?>
			<?php if( $is_wcfm_order_details_tax_line_item = apply_filters( 'wcfm_order_details_tax_line_item', true ) ) { ?>
				<?php
					if ( empty( $legacy_order ) && ! empty( $order_taxes ) ) :
						foreach ( $order_taxes as $tax_id => $tax_item ) :
							$tax_class      = wc_get_tax_class_by_tax_id( $tax_item['rate_id'] );
							$tax_class_name = isset( $classes_options[ $tax_class ] ) ? $classes_options[ $tax_class ] : __( 'Tax', 'wc-frontend-manager' );
							$column_label   = ! empty( $tax_item['label'] ) ? $tax_item['label'] : __( 'Tax', 'wc-frontend-manager' );
							$column_tip     = $tax_item['name'] . ' (' . $tax_class_name . ')';
							?>
							<th class="line_tax text_tip" data-tip="<?php echo esc_attr( $column_tip ); ?>">
								<?php echo esc_attr( $column_label ); ?>
								<input type="hidden" class="order-tax-id" name="order_taxes[<?php echo $tax_id; ?>]" value="<?php echo esc_attr( $tax_item['rate_id'] ); ?>">
								<a class="delete-order-tax" href="#" data-rate_id="<?php echo $tax_id; ?>"></a>
							</th>
							<?php
						endforeach;
					endif;
				?>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $line_items as $item_id => $item ) : $_product  = $item->get_product(); ?>
		<tr class="<?php echo apply_filters( 'wpo_wcpdf_item_row_class', $item_id, $document_type, $order, $item_id ); ?>">
			<td class="product">
				<?php $description_label = __( 'Description', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>
				<span class="item-name"><?php echo esc_html( $item->get_name() ); ?></span>
				<?php do_action( 'wpo_wcpdf_before_item_meta', $document_type, $item, $order  ); ?>
				<span class="item-meta">
				  <?php
				  if ( ! empty( $item->get_variation_id() ) ) {
							echo '<div class="wc-order-item-variation"><strong>' . __( 'Variation ID:', 'wc-frontend-manager-ultimate' ) . '</strong> ';
							if ( ! empty( $item->get_variation_id() ) && 'product_variation' === get_post_type( $item->get_variation_id() ) ) {
								echo esc_html( $item->get_variation_id() );
							} elseif ( ! empty( $item->get_variation_id() ) ) {
								echo esc_html( $item->get_variation_id() ) . ' (' . __( 'No longer exists', 'wc-frontend-manager-ultimate' ) . ')';
							}
							echo '</div>';
						}
					?>
			
					<?php do_action( 'woocommerce_before_order_itemmeta', $item_id, $item, $_product ) ?>
					
					<div class="view">
						<?php
							global $wpdb;
					
							if ( $metadata = $item->get_formatted_meta_data( '' ) ) {
								echo '<table cellspacing="0" class="display_meta">';
								foreach ( $metadata as $meta_id => $meta ) {
					
									// Skip hidden core fields
									if ( in_array( $meta->key, apply_filters( 'woocommerce_hidden_order_itemmeta', array(
										'_qty',
										'_tax_class',
										'_product_id',
										'_variation_id',
										'_line_subtotal',
										'_line_subtotal_tax',
										'_line_total',
										'_line_tax',
										'method_id',
										'_vendor_id',
										'_fulfillment_status',
										'_commission_status',
										'cost'
									) ) ) ) {
										continue;
									}
					
									// Skip serialised meta
									if ( is_serialized( $meta->display_key ) ) {
										continue;
									}
					
									echo '<tr><td style="vertical-align: middle;" class="no-borders">' . wp_kses_post( rawurldecode( $meta->display_key ) ) . ':</td><td style="border: 0px; vertical-align: middle;">' . wp_kses_post( wpautop( make_clickable( rawurldecode( $meta->value ) ) ) ) . '</td></tr>';
								}
								echo '</table>';
							}
						?>
					</div>
				</span>
				<dl class="meta">
					<?php $description_label = __( 'SKU', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>
					<?php if( $_product && $_product->get_sku() ) : ?><dt class="sku"><?php _e( 'SKU:', 'woocommerce-pdf-invoices-packing-slips' ); ?></dt><dd class="sku"><?php echo esc_html( $_product->get_sku() ); ?></dd><?php endif; ?>
				</dl>
				<?php do_action( 'wpo_wcpdf_after_item_meta', $document_type, $item, $order  ); ?>
			</td>
			<td class="price">
			  <?php
					if ( $item->get_total() ) {
						echo wc_price( $order->get_item_total( $item, false, true ), array( 'currency' => $order->get_currency() ) );
	
						if ( $item->get_subtotal() != $item->get_total() ) {
							echo '<span class="wc-order-item-discount">-' . wc_price( wc_format_decimal( $order->get_item_subtotal( $item, false, false ) - $order->get_item_total( $item, false, false ), '' ), array( 'currency' => $order->get_currency() ) ) . '</span>';
						}
					}
				?>
			</td>
			<td class="quantity">
			  <div class="view">
					<?php
						echo '<small class="times">&times;</small> ' . ( $item->get_quantity() ? esc_html( $item->get_quantity() ) : '1' );
		
						if ( $refunded_qty = $order->get_qty_refunded_for_item( $item_id ) ) {
							echo '<small class="refunded">' . ( $refunded_qty * -1 ) . '</small>';
						}
					?>
				</div>
			</td>
			<?php if( $is_wcfm_order_details_line_total = apply_filters( 'wcfm_order_details_line_total', true ) ) { ?>
				<td class="line_cost" data-sort-value="<?php echo esc_attr( ( $item->get_total() ) ? $item->get_total() : '' ); ?>">
					<div class="view">
						<?php
							if ( $item->get_total() ) {
								echo wc_price( $item->get_total(), array( 'currency' => $order->get_currency() ) );
							}
			
							if ( $item->get_subtotal() !== $item->get_total() ) {
								echo '<span class="wc-order-item-discount">-' . wc_price( wc_format_decimal( $item->get_subtotal() - $item->get_total(), '' ), array( 'currency' => $order->get_currency() ) ) . '</span>';
							}
			
							if ( $refunded = $order->get_total_refunded_for_item( $item_id ) ) {
								echo '<small class="refunded">' . wc_price( $refunded, array( 'currency' => $order->get_currency() ) ) . '</small>';
							}
						?>
					</div>
				</td>
			<?php } ?>
			<?php do_action( 'wcfm_after_order_details_line_total', $item, $order ); ?>
			
			<?php if( $is_wcfm_order_details_tax_line_item = apply_filters( 'wcfm_order_details_tax_line_item', true ) ) { ?>
				<?php
				if ( wc_tax_enabled() ) {
						if ( ! empty( $tax_data ) ) {
							foreach ( $order_taxes as $tax_item ) {
								$tax_item_id       = $tax_item['rate_id'];
								$tax_item_total    = isset( $tax_data['total'][ $tax_item_id ] ) ? $tax_data['total'][ $tax_item_id ] : '';
								$tax_item_subtotal = isset( $tax_data['subtotal'][ $tax_item_id ] ) ? $tax_data['subtotal'][ $tax_item_id ] : '';
								?>
								<td class="line_tax">
									<div class="view">
										<?php
											if ( '' != $tax_item_total ) {
												echo wc_price( wc_round_tax_total( $tax_item_total ), array( 'currency' => $order->get_currency() ) );
											} else {
												echo '&ndash;';
											}
				
											if ( $item->get_subtotal() !== $item->get_total() ) {
												echo '<span class="wc-order-item-discount">-' . wc_price( wc_round_tax_total( $tax_item_subtotal - $tax_item_total ), array( 'currency' => $order->get_currency() ) ) . '</span>';
											}
				
											if ( $refunded = $order->get_tax_refunded_for_item( $item_id, $tax_item_id ) ) {
												echo '<small class="refunded">' . wc_price( $refunded, array( 'currency' => $order->get_currency() ) ) . '</small>';
											}
										?>
									</div>
								</td>
								<?php
							}
						}
					}
				?>
			<?php } ?>
								
		</tr>
		<?php endforeach; ?>
	</tbody>
	
	
	<?php if( $is_wcfm_order_details_shipping_line_item = apply_filters( 'wcfm_order_details_shipping_line_item', true ) ) { ?>
	<tbody id="order_shipping_line_items">
	<?php
		$shipping_methods = WC()->shipping() ? WC()->shipping->load_shipping_methods() : array();
		foreach ( $line_items_shipping as $item_id => $item ) {
			?>
			<tr class="shipping <?php echo ( ! empty( $class ) ) ? $class : ''; ?>" data-order_item_id="<?php echo $item_id; ?>">
				<td class="name">
					<div class="view">
						<?php echo ! empty( $item->get_name() ) ? wc_clean( $item->get_name() ) : __( 'Shipping', 'wc-frontend-manager-ultimate' ); ?>
					</div>
			
					<?php do_action( 'woocommerce_before_order_itemmeta', $item_id, $item, null ) ?>
					<div class="view">
						<?php
							global $wpdb;
					
							if ( $metadata = $item->get_formatted_meta_data( '' ) ) {
								echo '<table cellspacing="0" class="display_meta">';
								foreach ( $metadata as $meta_id => $meta ) {
					
									// Skip hidden core fields
									if ( in_array( $meta->key, apply_filters( 'woocommerce_hidden_order_itemmeta', array(
										'_qty',
										'_tax_class',
										'_product_id',
										'_variation_id',
										'_line_subtotal',
										'_line_subtotal_tax',
										'_line_total',
										'_line_tax',
										'method_id',
										'_fulfillment_status',
										'_commission_status',
										'cost'
									) ) ) ) {
										continue;
									}
					
									// Skip serialised meta
									if ( is_serialized( $meta->display_key ) ) {
										continue;
									}
					
									echo '<tr><td style="vertical-align: middle;" class="no-borders">' . wp_kses_post( rawurldecode( $meta->display_key ) ) . ':</td><td style="border: 0px; vertical-align: middle;">' . wp_kses_post( wpautop( make_clickable( rawurldecode( $meta->value ) ) ) ) . '</td></tr>';
								}
								echo '</table>';
							}
						?>
					</div>
					<?php do_action( 'woocommerce_after_order_itemmeta', $item_id, $item, null ) ?>
				</td>
			
				<?php do_action( 'woocommerce_admin_order_item_values', null, $item, absint( $item_id ) ); ?>
			
				<td class="item_cost no_mob" >&nbsp;</td>
				<td class="quantity no_mob" >&nbsp;</td>
			
				<td class="line_cost">
					<div class="view">
						<?php
							echo ( isset( $item['cost'] ) ) ? wc_price( wc_round_tax_total( $item['cost'] ), array( 'currency' => $order->get_currency() ) ) : '';
			
							if ( $refunded = $order->get_total_refunded_for_item( $item_id, 'shipping' ) ) {
								echo '<small class="refunded">-' . wc_price( $refunded, array( 'currency' => $order->get_currency() ) ) . '</small>';
							}
						?>
					</div>
				</td>
			
				<?php if( $is_wcfm_order_details_tax_line_item = apply_filters( 'wcfm_order_details_tax_line_item', true ) ) { ?>
					<?php
						if ( ( $tax_data = $item->get_taxes() ) && wc_tax_enabled() ) {
							foreach ( $order_taxes as $tax_item ) {
								$tax_item_id    = $tax_item->get_rate_id();
								$tax_item_total = isset( $tax_data['total'][ $tax_item_id ] ) ? $tax_data['total'][ $tax_item_id ] : '';
								?>
									<td class="line_tax no_ipad no_mob" >
										<div class="view">
											<?php
												echo ( '' != $tax_item_total ) ? wc_price( wc_round_tax_total( $tax_item_total ), array( 'currency' => $order->get_currency() ) ) : '&ndash;';
				
												if ( $refunded = $order->get_tax_refunded_for_item( $item_id, $tax_item_id, 'shipping' ) ) {
													echo '<small class="refunded">-' . wc_price( $refunded, array( 'currency' => $order->get_currency() ) ) . '</small>';
												}
											?>
										</div>
									</td>
				
								<?php
							}
						}
					?>
				<?php } ?>
			
			</tr>
			<?php
		}
		do_action( 'woocommerce_admin_order_items_after_shipping', $order->get_id() );
	?>
	</tbody>
	<?php } ?>
	
	<?php if( $is_wcfm_order_details_fee_line_item = apply_filters( 'wcfm_order_details_fee_line_item', true ) ) { ?>
	<tbody id="order_fee_line_items">
	<?php
		foreach ( $line_items_fee as $item_id => $item ) {
			?>
			<tr class="fee <?php echo ( ! empty( $class ) ) ? $class : ''; ?>" data-order_item_id="<?php echo $item_id; ?>">
				<td class="name">
					<div class="view">
						<?php echo ! empty( $item->get_name() ) ? esc_html( $item->get_name() ) : __( 'Fee', 'wc-frontend-manager-ultimate' ); ?>
					</div>
				</td>
			
				<?php do_action( 'woocommerce_admin_order_item_values', null, $item, absint( $item_id ) ); ?>
			
				<td class="item_cost no_mob">&nbsp;</td>
				<td class="quantity no_mob" >&nbsp;</td>
			
				<td class="line_cost">
					<div class="view">
						<?php
							echo ( $item->get_total() ) ? wc_price( wc_round_tax_total( $item->get_total() ), array( 'currency' => $order->get_currency() ) ) : '';
			
							if ( $refunded = $order->get_total_refunded_for_item( $item_id, 'fee' ) ) {
								echo '<small class="refunded">-' . wc_price( $refunded, array( 'currency' => $order->get_currency() ) ) . '</small>';
							}
						?>
					</div>
				</td>
			
				<?php if( $is_wcfm_order_details_tax_line_item = apply_filters( 'wcfm_order_details_tax_line_item', true ) ) { ?>
					<?php
						if ( empty( $legacy_order ) && wc_tax_enabled() ) :
							$line_tax_data = isset( $item['line_tax_data'] ) ? $item['line_tax_data'] : '';
							$tax_data      = maybe_unserialize( $line_tax_data );
				
							foreach ( $order_taxes as $tax_item ) :
								$tax_item_id       = $tax_item['rate_id'];
								$tax_item_total    = isset( $tax_data['total'][ $tax_item_id ] ) ? $tax_data['total'][ $tax_item_id ] : '';
								?>
									<td class="line_tax no_ipad no_mob" >
										<div class="view">
											<?php
												echo ( '' != $tax_item_total ) ? wc_price( wc_round_tax_total( $tax_item_total ), array( 'currency' => $order->get_currency() ) ) : '&ndash;';
				
												if ( $refunded = $order->get_tax_refunded_for_item( $item_id, $tax_item_id, 'fee' ) ) {
													echo '<small class="refunded">-' . wc_price( $refunded, array( 'currency' => $order->get_currency() ) ) . '</small>';
												}
											?>
										</div>
									</td>
				
								<?php
							endforeach;
						endif;
					?>
				<?php } ?>
			
			</tr>
			<?php
		}
		do_action( 'woocommerce_admin_order_items_after_fees', $order->get_id() );
	?>
	</tbody>
	<?php } ?>
	
	<?php if( $is_wcfm_order_details_refund_line_item = apply_filters( 'wcfm_order_details_refund_line_item', true ) ) { ?>
	<tbody id="order_refunds">
	<?php
		if ( $refunds = $order->get_refunds() ) {
			foreach ( $refunds as $refund ) {
			/**
			 * @var object $refund The refund object.
			 */
			$who_refunded = new WP_User( $refund->get_refunded_by() );
			?>
			<tr class="refund <?php echo ( ! empty( $class ) ) ? $class : ''; ?>" data-order_refund_id="<?php echo $refund->get_id(); ?>">
				<td class="name">
					<?php
						/* translators: 1: refund id 2: date */
						printf( __( 'Refund #%1$s - %2$s', 'woocommerce' ), $refund->get_id(), wc_format_datetime( $order->get_date_created(), get_option( 'date_format' ) . ', ' . get_option( 'time_format' ) ) );
			
						if ( $who_refunded->exists() ) {
							echo ' ' . esc_attr_x( 'by', 'Ex: Refund - $date >by< $username', 'woocommerce' ) . ' ' . '<abbr class="refund_by" title="' . sprintf( esc_attr__( 'ID: %d', 'woocommerce' ), absint( $who_refunded->ID ) ) . '">' . esc_attr( $who_refunded->display_name ) . '</abbr>' ;
						}
					?>
					<?php if ( $refund->get_reason() ) : ?>
						<p class="description"><?php echo esc_html( $refund->get_reason() ); ?></p>
					<?php endif; ?>
					<input type="hidden" class="order_refund_id" name="order_refund_id[]" value="<?php echo esc_attr( $refund->get_id() ); ?>" />
				</td>
			
				<?php do_action( 'woocommerce_admin_order_item_values', null, $refund, $refund->get_id() ); ?>
			
				<td class="item_cost no_mob" >&nbsp;</td>
				<td class="quantity no_mob" >&nbsp;</td>
			
				<td class="line_cost" >
					<div class="view">
						<?php echo wc_price( '-' . $refund->get_amount() ); ?>
					</div>
				</td>
			
				<?php if ( wc_tax_enabled() ) : $total_taxes = count( $order_taxes ); ?>
					<?php for ( $i = 0;  $i < $total_taxes; $i++ ) : ?>
						<td class="line_tax no_ipad no_mob" ></td>
					<?php endfor; ?>
				<?php endif; ?>
				<?php
			}
			do_action( 'woocommerce_admin_order_items_after_refunds', $order->get_id() );
		}
	?>
	</tbody>
	<?php } ?>
	
	<tfoot>
		<tr class="no-borders">
			<td class="no-borders">
				<div class="customer-notes">
					<?php do_action( 'wpo_wcpdf_before_customer_notes', $document_type, $order ); ?>
					<?php if ( $document->get_shipping_notes() ) : ?>
						<h3><?php _e( 'Customer Notes', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
						<?php //$document->shipping_notes(); ?>
					<?php endif; ?>
					<?php do_action( 'wpo_wcpdf_after_customer_notes', $document_type, $order ); ?>
				</div>				
			</td>
			<td class="no-borders"></td>
			<?php 
			if(wcfm_is_vendor()) {
				if( $is_wcfm_order_details_shipping_line_item_invoice = apply_filters( 'wcfm_order_details_shipping_line_item_invoice', true ) ) {
					echo '<td class="no-borders"></td>';
				}
				if( $is_wcfm_order_details_tax_line_item_invoice = apply_filters( 'wcfm_order_details_tax_line_item_invoice', true ) ) {
					echo '<td class="no-borders"></td>';
					echo '<td class="no-borders"></td>';
				}
		  } 
		  
		  if ( empty( $legacy_order ) && ! empty( $order_taxes ) ) :
				foreach ( $order_taxes as $tax_id => $tax_item ) :
				  echo '<td class="no-borders"></td>';
				endforeach;
			endif;
		  
		  ?>
		  
			<td class="no-borders" colspan="<?php if(wcfm_is_vendor()) { echo apply_filters( 'wcfm_invoice_order_total_column_width', '3' ); } else { echo '2'; } ?>">
				<table class="totals">
					<tfoot>
					  <?php if( $is_wcfm_order_details_coupon_line_item = apply_filters( 'wcfm_order_details_coupon_line_item', true ) ) { ?>
							<tr>
							  <td class="no-borders"></td>
								<td class="label description"><span class="img_tip" data-tip="<?php _e( 'This is the total discount. Discounts are defined per line item.', 'wc-frontend-manager-ultimate' ) ; ?>"></span> <?php _e( 'Discount', 'wc-frontend-manager-ultimate' ); ?>:</td>
								<td class=""></td>
								<td class="total price">
									<?php echo wc_price( $order->get_total_discount(), array( 'currency' => $order->get_currency() ) ); ?>
								</td>
							</tr>
						<?php } ?>
				
						<?php //do_action( 'woocommerce_admin_order_totals_after_discount', $order->get_id() ); ?>
				
						<?php if( $is_wcfm_order_details_shipping_line_item = apply_filters( 'wcfm_order_details_shipping_line_item', true ) ) { ?>
							<tr>
							  <td class="no-borders"></td>
								<td class="label description"><span class="img_tip" data-tip="<?php _e( 'This is the shipping and handling total costs for the order.', 'wc-frontend-manager-ultimate' ) ; ?>"></span> <?php _e( 'Shipping', 'wc-frontend-manager-ultimate' ); ?>:</td>
								<td class=""></td>
								<td class="total price"><?php
									if ( ( $refunded = $order->get_total_shipping_refunded() ) > 0 ) {
										echo '<del>' . strip_tags( wc_price( $order->get_total_shipping(), array( 'currency' => $order->get_currency() ) ) ) . '</del> <ins>' . wc_price( $order->get_total_shipping() - $refunded, array( 'currency' => $order->get_currency() ) ) . '</ins>';
									} else {
										echo wc_price( $order->get_total_shipping(), array( 'currency' => $order->get_currency() ) );
									}
								?></td>
							</tr>
						<?php } ?>
				
						<?php //do_action( 'woocommerce_admin_order_totals_after_shipping', $order->get_id() ); ?>
				
						<?php if( $is_wcfm_order_details_tax_total = apply_filters( 'wcfm_order_details_tax_total', true ) ) { ?>
							<?php if ( wc_tax_enabled() ) : ?>
								<?php foreach ( $order->get_tax_totals() as $code => $tax ) : ?>
									<tr>
									  <td class="no-borders"></td>
										<td class="label description"><?php echo $tax->label; ?>:</td>
										<td class=""></td>
										<td class="total price"><?php
											if ( ( $refunded = $order->get_total_tax_refunded_by_rate_id( $tax->rate_id ) ) > 0 ) {
												echo '<del>' . strip_tags( $tax->formatted_amount ) . '</del> <ins>' . wc_price( WC_Tax::round( $tax->amount, wc_get_price_decimals() ) - WC_Tax::round( $refunded, wc_get_price_decimals() ), array( 'currency' => $order->get_currency() ) ) . '</ins>';
											} else {
												echo $tax->formatted_amount;
											}
										?></td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php } ?>
				
						<?php //do_action( 'woocommerce_admin_order_totals_after_tax', $order->get_id() ); ?>
				
						<?php if( $is_wcfm_order_details_total = apply_filters( 'wcfm_order_details_total', true ) ) { ?>
						<tr>
						  <td class="no-borders"></td>
							<td class="label description"><?php _e( 'Order Total', 'wc-frontend-manager-ultimate' ); ?>:</td>
							<td class=""></td>
							<td class="total price">
								<div class="view"><?php echo $order->get_formatted_order_total(); ?></div>
							</td>
						</tr>
						<?php } ?>
				
						<?php do_action( 'wcfm_order_totals_after_total', $order->get_id() ); ?>
				
						<?php if( $is_wcfm_order_details_refund_line_item = apply_filters( 'wcfm_order_details_refund_line_item', true ) ) { ?>
							<?php if ( $order->get_total_refunded() ) : ?>
								<tr>
								  <td class="no-borders"></td>
									<td class="label refunded-total description"><?php _e( 'Refunded', 'wc-frontend-manager-ultimate' ); ?>:</td>
									<td class=""></td>
									<td class="total refunded-total price">-<?php echo wc_price( $order->get_total_refunded(), array( 'currency' => $order->get_currency() ) ); ?></td>
								</tr>
							<?php endif; ?>
						<?php } ?>
						
					</tfoot>
				</table>
			</td>
		</tr>
	</tfoot>
</table>

<?php do_action( 'wpo_wcpdf_after_order_details', $document_type, $order ); ?>

<?php if ( $document->get_footer() ): ?>
<div id="footer">
	<?php $document->footer(); ?>
</div><!-- #letter-footer -->
<?php endif; ?>
<?php do_action( 'wpo_wcpdf_after_document', $document_type, $order ); ?>
