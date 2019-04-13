<?php
global $WCFM, $WCFMu;

$product_id = '';
if( isset($_POST['product']) ) {
	$product_id = $_POST['product'];
	$product = wc_get_product( $product_id );
	$product->get_stock_quantity() === 1 ? $stock = $product->get_stock_quantity() : $stock = '';
	$product->get_status() === 'publish' ? $published = '1' : $published = '';

	if( $product ) {
	  ?>
	  <form id="wcfm_quick_edit_form">
	    <table>
	      <tbody>
		        <tr class="wcfm_quick_edit_form-title">
		          <td class="wcfm_quick_edit_form_label"><?php _e( 'Product', 'wc-frontend-manager-ultimate' ); ?></td>
		          <td><input type="text" name="wcfm_quick_edit_title" value="<?php echo $product->get_title(); ?>" /></td>
		        </tr>
	        <?php if( in_array( $product->get_type(), array('simple', 'external') ) ) { ?>
						<tr>
							<td class="wcfm_quick_edit_form_label"><?php _e( 'Price (EUR)', 'wc-frontend-manager-ultimate' ); ?></td>
							<td><input type="number" name="wcfm_quick_edit_regular_price" value="<?php echo get_post_meta($product_id, '_regular_price', true); ?>" /></td>
						</tr>
	        <?php } ?>
	        <?php if( $product->get_type() == 'simple' && $product->managing_stock() ) { ?>
						<tr>
							<td class="wcfm_quick_edit_form_label"><?php _e( 'Available', 'wc-frontend-manager-ultimate' ); ?></td>
							<td><input type="checkbox" name="wcfm_quick_edit_stock" value="1" <?php checked( $stock, 1, true ); ?>/>
							</td>
						</tr>
						<?php if( current_user_can('manage_vendors', $product_id) ) { ?>
							<tr>
								<td class="wcfm_quick_edit_form_label"><?php _e( 'Approved', 'wc-frontend-manager-ultimate' ); ?></td>
								<td><input type="checkbox" name="wcfm_quick_edit_status" value="1" <?php checked( $published, 1, true ); ?>/>
								</td>
							</tr>
							<tr>
								<td class="wcfm_quick_edit_form_label"><?php _e( 'Note to Vendor', 'wc-frontend-manager-ultimate' ); ?></td>
								<td><textarea name="wcfm_quick_edit_note"><?php echo get_post_meta($product_id, 'note_to_vendor', true); ?></textarea></td>
							</tr>
						<?php } ?>
					<?php } ?>
	      </tbody>
	    </table>
	    <input type="hidden" name="wcfm_quick_edit_product_id" value="<?php echo $product_id; ?>" />
	    <div class="wcfm-message" tabindex="-1"></div>
	    <input type="button" class="wcfm_quick_edit_button" id="wcfm_quick_edit_button" value="Update" />
	  </form>
	  <?php
	}
}
?>
