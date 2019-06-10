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
			echo apply_filters( 'wpo_wcpdf_invoice_title', __( 'Packing Slip', 'woocommerce-pdf-invoices-packing-slips' ) );
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
<?php if( $document->get_header_logo_id() ) echo apply_filters( 'wpo_wcpdf_invoice_title', __( 'Packing Slip', 'woocommerce-pdf-invoices-packing-slips' ) ); ?>
</h1>

<?php do_action( 'wpo_wcpdf_after_document_label', $document_type, $order ); ?>

<table class="order-data-addresses">
	<tr>
		<td class="address billing-address">
			<!-- <h3><?php _e( 'Billing Address:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3> -->
			<?php echo wp_kses( $order->get_formatted_billing_address(), array( 'br' => array() ) ); ?>
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
				<tr class="order-number">
					<th><?php _e( 'Order Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php echo $order_id; ?></td>
				</tr>
				<tr class="order-date">
					<th><?php _e( 'Order Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php echo date_i18n( 'Y-m-d', strtotime( $post->post_date ) ); ?> @<?php echo date_i18n( 'H', strtotime( $post->post_date ) ); ?>:<?php echo date_i18n( 'i', strtotime( $post->post_date ) ); ?></td>
				</tr>
				<tr class="payment-method">
					<th><?php _e( 'Shipping Method:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php _e( $order->get_shipping_method(), 'woocommerce' ); ?></td>
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
			<th class="quantity"><?php _e('Quantity', 'wpo_wcpdf '); ?></th>
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
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php do_action( 'wpo_wcpdf_after_order_details', $document_type, $order ); ?>

<?php if ( $document->get_footer() ): ?>
<div id="footer">
	<?php $document->footer(); ?>
</div><!-- #letter-footer -->
<?php endif; ?>
<?php do_action( 'wpo_wcpdf_after_document', $document_type, $order ); ?>
