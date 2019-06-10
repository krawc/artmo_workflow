<?php
/**
 * WCFMu plugin controllers
 *
 * Plugin Products Quick Manage Controller
 *
 * @author 		WC Lovers
 * @package 	wcfm/controllers
 * @version   1.0.0
 */

class WCFMu_Products_Quick_Manage_Controller {

	public function __construct() {
		global $WCFM;

		$this->processing();
	}

	public function processing() {
		global $WCFM, $WCFMu, $wpdb, $_POST;

		$wcfm_quick_edit_form_data = array();
	  parse_str($_POST['wcfm_quick_edit_form'], $wcfm_quick_edit_form_data);
	  $wcfm_products_manage_messages = get_wcfm_products_manager_messages();
	  $has_error = false;

	  if(isset($wcfm_quick_edit_form_data['wcfm_quick_edit_title']) && !empty($wcfm_quick_edit_form_data['wcfm_quick_edit_title'])) {
	  	$product_id = $wcfm_quick_edit_form_data['wcfm_quick_edit_product_id'];
	  	$title = $wcfm_quick_edit_form_data['wcfm_quick_edit_title'];

			$product_obj = wc_get_product( $product_id );
			$author_id = get_post_field( 'post_author', $product_id );
			$url = get_permalink( $product_id );

			$accept_msg = 'ARTWORK APPROVAL: ' . $title . '.  ' . $url . '  is published in COLLECTIONS.';
			$reject_msg = 'ARTWORK REVIEW: ' . $title . '.  ' . $url . ' Some adjustments are necessary. The artwork is a Draft now. See details in EDIT MODE.';

	  	// Update Basic
	  	$update_product['ID'] = $product_id;
	  	$update_product['post_title'] = $title;
	  	$update_product_id = wp_update_post( $update_product, true );

	  	// Update SKU
	  	if(isset($wcfm_quick_edit_form_data['wcfm_quick_edit_sku']) && !empty($wcfm_quick_edit_form_data['wcfm_quick_edit_sku'])) {
				update_post_meta( $product_id, '_sku', $wcfm_quick_edit_form_data['wcfm_quick_edit_sku'] );
				$unique_sku = wc_product_has_unique_sku( $product_id, $wcfm_quick_edit_form_data['wcfm_quick_edit_sku'] );
				if ( ! $unique_sku ) {
					update_post_meta( $product_id, '_sku', '' );
					echo '{"status": false, "message": "' . $wcfm_products_manage_messages['sku_unique'] . '"}';
					$has_error = true;
				}
			} else {
				update_post_meta( $product_id, '_sku', '' );
			}

	  	// Update Price
	  	update_post_meta( $product_id, '_regular_price', wc_format_decimal($wcfm_quick_edit_form_data['wcfm_quick_edit_regular_price']) );
	  	if(isset($wcfm_quick_edit_form_data['wcfm_quick_edit_sale_price']) && !empty($wcfm_quick_edit_form_data['wcfm_quick_edit_sale_price'])) {
				update_post_meta( $product_id, '_sale_price', wc_format_decimal($wcfm_quick_edit_form_data['wcfm_quick_edit_sale_price']) );
				update_post_meta( $product_id, '_price', wc_format_decimal($wcfm_quick_edit_form_data['wcfm_quick_edit_sale_price']) );
			} else {
				update_post_meta( $product_id, '_sale_price', '' );
				update_post_meta( $product_id, '_price', wc_format_decimal($wcfm_quick_edit_form_data['wcfm_quick_edit_regular_price']) );
			}

			if(isset($wcfm_quick_edit_form_data['wcfm_quick_edit_stock']) && !empty ($wcfm_quick_edit_form_data['wcfm_quick_edit_stock'])) {
				$stockValue = '1';
				$stockStatus = 'instock';
			} else {
				$stockValue = '0';
				$stockStatus = 'outofstock';
			}

			// Update Stock
			update_post_meta( $product_id, '_stock', $stockValue );
			update_post_meta( $product_id, '_stock_status', $stockStatus );

			if(isset($wcfm_quick_edit_form_data['wcfm_quick_edit_note'])) {
				update_post_meta( $product_id, 'note_to_vendor', $wcfm_quick_edit_form_data['wcfm_quick_edit_note']);
			}

			if( current_user_can( 'publish_products' ) && apply_filters( 'wcfm_is_allow_publish_products', true ) ) {
				if (isset($wcfm_quick_edit_form_data['wcfm_quick_edit_note']) && !empty($wcfm_quick_edit_form_data['wcfm_quick_edit_note'])) {
						wp_update_post(array(
							'ID'    =>  $product_id,
							'post_status'   =>  'draft'
						));
						artmo_send_private_message( $author_id, 1, $reject_msg );
				} else {
						wp_update_post(array(
							'ID'    =>  $product_id,
							'post_status'   =>  'publish'
						));
						artmo_send_private_message( $author_id, 1, $accept_msg );
				}
			}

			($stockValue === '1') ? update_post_meta( $product_id, '_stock_status', 'instock') : update_post_meta( $product_id, '_stock_status', 'outofstock');

			echo '{"status": true, "message": "' . $wcfm_products_manage_messages['product_saved'] . '"}';
	  } else {
	  	echo '{"status": false, "message": "' . $wcfm_products_manage_messages['no_title'] . '"}';
	  }

	  die;
	}
}
