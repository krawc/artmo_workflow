<?php
/**
 * WCFMu plugin core
 *
 * Plugin Ajax Controler
 *
 * @author 		WC Lovers
 * @package 	wcfmu/core
 * @version   1.0.0
 */
 
class WCFMu_Ajax {
	
	public $controllers_path;

	public function __construct() {
		global $WCFM, $WCFMu;
		
		$this->controllers_path = $WCFMu->plugin_path . 'controllers/';
		
		add_action( 'after_wcfm_ajax_controller', array( &$this, 'wcfmu_ajax_controller' ) );
		
		// Coupon Delete
		add_action( 'wp_ajax_delete_wcfm_coupon', array( &$this, 'delete_wcfm_coupon' ) );
    
    // Order Delete
		add_action( 'wp_ajax_delete_wcfm_order', array( &$this, 'delete_wcfm_order' ) );
    
    // Order Add Note
		add_action( 'wp_ajax_wcfm_add_order_note', array( &$this, 'wcfm_add_order_note' ) );
    
    // Add Taxonomy Attribute Term
    add_action('wp_ajax_wcfmu_add_attribute_term', array( &$this, 'wcfmu_add_attribute_term' ) );
    
    // Generate Duplicate Product - 3.0.1
    add_action('wp_ajax_wcfmu_product_featured', array( &$this, 'wcfmu_product_featured' ) );
    
    // Generate Duplicate Product - 2.5.2
    add_action('wp_ajax_wcfmu_duplicate_product', array( &$this, 'wcfmu_duplicate_product' ) );
    
    // Generate Quick Edit Html
    add_action('wp_ajax_wcfmu_quick_edit_html', array( &$this, 'wcfmu_quick_edit_html' ) );
    
    // WC Vendors Mark as Shipped
    add_action( 'wp_ajax_wcfm_wcvendors_order_mark_shipped', array( &$this, 'wcfm_wcvendors_order_mark_shipped' ) );
    
    // WC Product Vendors Mark as Fulfilled
    add_action( 'wp_ajax_wcfm_wcpvendors_order_mark_fulfilled', array( &$this, 'wcfm_wcpvendors_order_mark_fulfilled' ) );
    
    // WC Marketplace Mark as Shipped
    add_action( 'wp_ajax_wcfm_wcmarketplace_order_mark_shipped', array( &$this, 'wcfm_wcmarketplace_order_mark_shipped' ) );
    
    // Dokan Mark as Shipped
    add_action( 'wp_ajax_wcfm_dokan_order_mark_shipped', array( &$this, 'wcfm_dokan_order_mark_shipped' ) );
    
    // WCFM Mark as Received
    add_action( 'wp_ajax_wcfm_mark_as_recived', array( &$this, 'wcfm_mark_as_recived' ) );
    
    // Generate Screen Manager Html
    add_action('wp_ajax_wcfmu_screen_manager_html', array( &$this, 'wcfmu_screen_manager_html' ) );
    
    // Product Importer
    add_action('wp_ajax_wcfmu_ajax_product_import', array( &$this, 'wcfmu_ajax_product_import' ) );
  }
  
  /**
   * WCFM Ultimate Ajax Controllers
   */
  public function wcfmu_ajax_controller() {
  	global $WCFM, $WCFMu;
  	
  	$controller = '';
  	if( isset( $_POST['controller'] ) ) {
  		$controller = $_POST['controller'];
  		
  		switch( $controller ) {
	  	
				case 'wc-products':
				case 'wcfm-products':
					require_once( $this->controllers_path . 'wcfmu-controller-products.php' );
					new WCFMu_Products_Controller();
				break;
				
				case 'wcfm-products-manage':
					require_once( $this->controllers_path . 'wcfmu-controller-products-manage.php' );
					new WCFMu_Products_Manage_Controller();
				break;
				
				case 'wcfm-products-quick-manage':
					require_once( $this->controllers_path . 'wcfmu-controller-products-quick-manage.php' );
					new WCFMu_Products_Quick_Manage_Controller();
				break;
				
				case 'wcfm-stock-manage':
					require_once( $this->controllers_path . 'wcfmu-controller-stock-manage.php' );
					new WCFMu_Stock_Manage_Controller();
				break;
				
				case 'wcfm-stock-manage-update':
					require_once( $this->controllers_path . 'wcfmu-controller-stock-manage.php' );
					new WCFMu_Stock_Manage_Update_Controller();
				break;
					
			  case 'wcfm-coupons':
					require_once( $this->controllers_path . 'wcfmu-controller-coupons.php' );
					new WCFMu_Coupons_Controller();
				break;
				
				case 'wcfm-coupons-manage':
					require_once( $this->controllers_path . 'wcfmu-controller-coupons-manage.php' );
					new WCFMu_Coupons_Manage_Controller();
				break;
				
				case 'wcfm-orders':
					require_once( $this->controllers_path . 'wcfmu-controller-orders.php' );
					new WCFMu_Orders_Controller();
				break;
				
				case 'wcfm-reports-out-of-stock':
					require_once( $this->controllers_path . 'wcfmu-controller-reports-out-of-stock.php' );
					new WCFMu_Reports_Out_Of_Stock_Controller();
				break;
				
				case 'wcfm-reports-low-in-stock':
					require_once( $this->controllers_path . 'wcfmu-controller-reports-low-in-stock.php' );
					new WCFMu_Reports_Low_In_Stock_Controller();
				break;
				
				case 'wcfm-settings':
					require_once( $this->controllers_path . 'wcfmu-controller-settings.php' );
					new WCFMu_Settings_Controller();
				break;
				
				case 'wcfm-screen-manage':
					require_once( $this->controllers_path . 'wcfmu-controller-screen-manage.php' );
					new WCFMu_Screen_Manage_Controller();
				break;
			}
  	}
  	return;
  }
  
  /**
   * Handle Coupon Delete
   */
  public function delete_wcfm_coupon() {
  	global $WCFM, $WCFMu;
  	
  	$couponid = $_POST['couponid'];
		
		if($couponid) {
			if(wp_delete_post($couponid)) {
				echo 'success';
				die;
			}
			die;
		}
  }
  
  /**
   * Handle Order Delete
   */
  public function delete_wcfm_order() {
  	global $WCFM, $WCFMu;
  	
  	$orderid = $_POST['orderid'];
		
		if($orderid) {
			if(wp_delete_post($orderid)) {
				echo 'success';
				die;
			}
			die;
		}
  }
  
  /**
   * Handle Order Note Add
   */
  public function wcfm_add_order_note() {
  	global $WCFM, $WCFMu, $woocommerce;
  	
  	$user = wp_get_current_user();
		$user = $user->ID;
  	
  	$order_id   = absint( $_POST['order_id'] );
		$note      = wp_kses_post( trim( stripslashes( $_POST['note'] ) ) );
		$note_type = $_POST['note_type'];

		$is_customer_note = $note_type == 'customer' ? 1 : 0;
		$note_class = '';
		if($is_customer_note) $note_class = 'customer-note';

		if ( $order_id > 0 ) {
			$order      = wc_get_order( $order_id );
			
			// WC Vendor association
			if( wcfm_is_vendor() ) add_filter( 'woocommerce_new_order_note_data', array( $WCFMu->wcfmu_marketplace, 'filter_wcfm_vendors_comment' ), 10, 2 );
			
			$comment_id = $order->add_order_note( $note, $is_customer_note, true );
			
			// WC Vendor association
			if( wcfm_is_vendor() ) remove_filter( 'woocommerce_new_order_note_data', array( $WCFMu->wcfmu_marketplace, 'filter_wcfm_vendors_comment' ), 10, 2 );
			
			echo '<tr class="' . $note_class . '"><td>';
			echo wpautop( wptexturize( $note ) );
			echo '</td><td>Just Now';
			echo '</td></tr>';
		}

		die();
	}
	
	/**
	 * Add new attribute term
	 */
	function wcfmu_add_attribute_term() {
		global $WCFM, $WCFMu, $_POST;
		
		$taxonomy = esc_attr( $_POST['taxonomy'] );
		$term     = wc_clean( $_POST['term'] );

		if ( taxonomy_exists( $taxonomy ) ) {

			$result = wp_insert_term( $term, $taxonomy );

			if ( is_wp_error( $result ) ) {
				wp_send_json( array(
					'error' => $result->get_error_message(),
				) );
			} else {
				$term = get_term_by( 'id', $result['term_id'], $taxonomy );
				wp_send_json( array(
					'term_id' => $term->term_id,
					'name'    => $term->name,
					'slug'    => $term->slug,
				) );
			}
		}
		wp_die( -1 );
	}
	
	/**
	 * WCFM Mark/Un-mark Product as Featured
	 */
	public function wcfmu_product_featured() {
		global $WCFM, $WCFMu, $_POST;
		
		if( isset( $_POST['proid'] ) && !empty( $_POST['proid'] ) ) {
			$product_id = $_POST['proid'];
			$is_featured = $_POST['featured'];
			
			if( $is_featured == 'featured' ) {
				wp_set_object_terms( $product_id, 'featured', 'product_visibility' );
			} elseif( $is_featured == 'nofeatured' ) {
				wp_remove_object_terms( $product_id, 'featured', 'product_visibility' );
			}
		}
	}
	
	/**
	 * WCFM Generate Duplicate Product
	 */
	public function wcfmu_duplicate_product() {
		global $WCFM, $WCFMu, $_POST;
		
		include( WC_ABSPATH . 'includes/admin/class-wc-admin-duplicate-product.php' );
		$WC_Admin_Duplicate_Product = new WC_Admin_Duplicate_Product();
		
		if ( empty( $_POST['proid'] ) ) {
			echo '{"status": false, "message": "' .  __( 'No product to duplicate has been supplied!', 'woocommerce' ) . '"}';
		}

		$product_id = isset( $_POST['proid'] ) ? absint( $_POST['proid'] ) : '';

		//check_admin_referer( 'woocommerce-duplicate-product_' . $product_id );

		$product = wc_get_product( $product_id );

		if ( false === $product ) {
			/* translators: %s: product id */
			echo '{"status": false, "message": "' . sprintf( __( 'Product creation failed, could not find original product: %s', 'woocommerce' ), $product_id ) . '" }';
		}

		$duplicate = $WC_Admin_Duplicate_Product->product_duplicate( $product );
		
		update_post_meta( $duplicate->get_id(), '_wcfm_product_views', 0 );

		// Hook rename to match other woocommerce_product_* hooks, and to move away from depending on a response from the wp_posts table.
		do_action( 'woocommerce_product_duplicate', $duplicate, $product );
		do_action( 'after_wcfm_product_duplicate', $duplicate->get_id(), $product );

		// Redirect to the edit screen for the new draft page
		echo '{"status": true, "redirect": "' . get_wcfm_edit_product_url( $duplicate->get_id() ) . '", "id": "' . $duplicate->get_id() . '"}';
		
		die;
	}
	
	/**
	 * Generate Product Quick Edit HTMl
	 */
	function wcfmu_quick_edit_html() {
		global $WCFM, $WCFMu;
		
		require_once( $WCFMu->library->views_path . 'wcfmu-view-products-quick-manage.php' );
		die;
	}
	
	/**
	 * Mark WC Vendors order as Shipped
	 */
	function wcfm_wcvendors_order_mark_shipped() {
		global $WCFM, $WCFMu, $woocommerce, $wpdb;
		
		$user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		
		if ( !empty( $_POST['orderid'] ) ) {
			$order_id = $_POST['orderid'];
			$product_id = $_POST['productid'];
			$order_item_id = $_POST['orderitemid'];
			$tracking_url  = $_POST['tracking_url'];
			$tracking_code  = $_POST['tracking_code'];
			$order = wc_get_order( $order_id );
			$vendors = WCV_Vendors::get_vendors_from_order( $order );
			$vendor_ids = array_keys( $vendors );
			if ( !in_array( $user_id, $vendor_ids ) ) {
				_e( 'You are not allowed to modify this order.', 'wc-frontend-manager-ultimate' );
				die; 
			}
			$shippers = (array) get_post_meta( $order_id, 'wc_pv_shipped', true );

			// If not in the shippers array mark as shipped otherwise do nothing. 
			if( !in_array($user_id, $shippers)) {
				$shippers[] = $user_id;
				$mails = $woocommerce->mailer()->get_emails();
				if ( !empty( $mails ) ) {
					$mails[ 'WC_Email_Notify_Shipped' ]->trigger( $order_id, $user_id );
				}
				do_action('wcvendors_vendor_ship', $order_id, $user_id);
				_e( 'Order marked shipped.', 'wc-frontend-manager-ultimate' );
			} elseif ( false != ( $key = array_search( $user_id, $shippers) ) ) {
				unset( $shippers[$key] ); // Remove user from the shippers array
 			}
 			
 			$shop_name =  $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( absint($user_id) );
			$comment_id = $order->add_order_note( sprintf( __( 'Vendor <b>%s</b> has shipped <b>%s</b> to customer.<br/>Tracking Code : %s <br/>Tracking URL : <a href="%s">%s</a>', 'wc-frontend-manager-ultimate' ), $shop_name, get_the_title( $product_id ), $tracking_code, $tracking_url, $tracking_url ), '1');
			
			// Update Shipping Tracking Info
			$this->updateShippingTrackingInfo( $order_item_id, $tracking_code, $tracking_url );

			update_post_meta( $order_id, 'wc_pv_shipped', $shippers );
		}
	}
	
	/**
	 * Mark WC Product Vendors order as Fulfilled
	 */
	function wcfm_wcpvendors_order_mark_fulfilled() {
		global $WCFM, $WCFMu, $woocommerce, $wpdb;
		
		if ( !empty( $_POST['orderid'] ) ) {
			$order_id = $_POST['orderid'];
			$product_id = $_POST['productid'];
			$order_item_id = $_POST['orderitemid'];
			$tracking_url  = $_POST['tracking_url'];
			$tracking_code  = $_POST['tracking_code'];
			$order = wc_get_order( $order_id );
			
			if( $order_item_id ) {
				$vendor_data = WC_Product_Vendors_Utils::get_vendor_data_from_user();
				
				WC_Product_Vendors_Utils::set_fulfillment_status( absint( $order_item_id ), 'fulfilled' );
		
				WC_Product_Vendors_Utils::send_fulfill_status_email( $vendor_data, 'fulfilled', $order_item_id );
				
				WC_Product_Vendors_Utils::clear_reports_transients();
				
				$shop_name = ! empty( $vendor_data['shop_name'] ) ? $vendor_data['shop_name'] : '';
				$comment_id = $order->add_order_note( sprintf( __( 'Vendor <b>%s</b> has shipped <b>%s</b> to customer.<br/>Tracking Code : %s <br/>Tracking URL : <a href="%s">%s</a>', 'wc-frontend-manager-ultimate' ), $shop_name, get_the_title( $product_id ), $tracking_code, $tracking_url, $tracking_url ), '1');
				
				// Update Shipping Tracking Info
				$this->updateShippingTrackingInfo( $order_item_id, $tracking_code, $tracking_url );
			}
		}
		
		echo "complete";
		die;
	}
	
	/**
	 * Mark WC Marketplace order as Shipped
	 */
	function wcfm_wcmarketplace_order_mark_shipped() {
		global $WCFM, $WCFMu, $woocommerce, $wpdb;
		
		$user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		$vendor = get_wcmp_vendor($user_id);
		
		if ( !empty( $_POST['orderid'] ) ) {
			$order_id = $_POST['orderid'];                   
			$order = wc_get_order( $order_id );
			$product_id = $_POST['productid'];
			$tracking_url = $_POST['tracking_url'];
			$tracking_code  = $_POST['tracking_code'];
      $order_item_id = $_POST['orderitemid'];
      $user_id = apply_filters('wcmp_mark_as_shipped_vendor', $user_id);
			$shippers = (array) get_post_meta($order_id, 'dc_pv_shipped', true);
			
			if (!in_array($user_id, $shippers)) {
				$shippers[] = $user_id;
				$mails = WC()->mailer()->emails['WC_Email_Notify_Shipped'];
				if (!empty($mails)) {
					$customer_email = get_post_meta($order_id, '_billing_email', true);
					$mails->trigger($order_id, $customer_email, $vendor->term_id, array( 'tracking_code' => $tracking_code, 'tracking_url' => $tracking_url ) );
				}
				do_action('wcmp_vendors_vendor_ship', $order_id, $vendor->term_id);
				array_push($shippers, $user_id);
			}
				
			$wpdb->query("UPDATE {$wpdb->prefix}wcmp_vendor_orders SET shipping_status = '1' WHERE order_id = $order_id and vendor_id = $user_id and order_item_id = $order_item_id");
			$shop_name =  $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( absint($user_id) );
			$comment_id = $order->add_order_note( sprintf( __( 'Vendor <b>%s</b> has shipped <b>%s</b> to customer.<br/>Tracking Code : %s <br/>Tracking URL : <a href="%s">%s</a>', 'wc-frontend-manager-ultimate' ), $shop_name, get_the_title( $product_id ), $tracking_code, $tracking_url, $tracking_url ), '1');
			add_comment_meta( $comment_id, '_vendor_id', $user_id );
			
			// Update Shipping Tracking Info
			$this->updateShippingTrackingInfo( $order_item_id, $tracking_code, $tracking_url );
			
			update_post_meta($order_id, 'dc_pv_shipped', $shippers);
		}
		die;
	}
	
	/**
	 * Mark Dokan order as Shipped
	 */
	function wcfm_dokan_order_mark_shipped() {
		global $WCFM, $WCFMu, $woocommerce, $wpdb;
		
		$user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		
		if ( !empty( $_POST['orderid'] ) ) {
			$order_id = $_POST['orderid'];                   
			$order = wc_get_order( $order_id );
			$product_id = $_POST['productid'];
			$tracking_url = $_POST['tracking_url'];
			$tracking_code  = $_POST['tracking_code'];
      $order_item_id = $_POST['orderitemid'];
				
			$shop_name = $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( absint($user_id) );
			$comment_id = $order->add_order_note( sprintf( __( 'Vendor <b>%s</b> has shipped <b>%s</b> to customer.<br/>Tracking Code : %s <br/>Tracking URL : <a href="%s">%s</a>', 'wc-frontend-manager-ultimate' ), $shop_name, get_the_title( $product_id ), $tracking_code, $tracking_url, $tracking_url ), '1');
			
			// Update Shipping Tracking Info
			$this->updateShippingTrackingInfo( $order_item_id, $tracking_code, $tracking_url );
		}
		die;
	}
	
	function updateShippingTrackingInfo( $order_item_id, $tracking_code, $tracking_url ) {
		global $WCFM, $WCFMu, $woocommerce, $wpdb;
		
		// Keep Tracking Code as Order Item Meta
		$sql = "INSERT INTO {$wpdb->prefix}woocommerce_order_itemmeta";
		$sql .= ' ( `meta_key`, `meta_value`, `order_item_id` )';
		$sql .= ' VALUES ( %s, %s, %s )';

		$wpdb->get_var( $wpdb->prepare( $sql, 'wcfm_tracking_code', $tracking_code, $order_item_id  ) );
		
		// Keep Tracking URL as Order Item Meta
		$sql = "INSERT INTO {$wpdb->prefix}woocommerce_order_itemmeta";
		$sql .= ' ( `meta_key`, `meta_value`, `order_item_id` )';
		$sql .= ' VALUES ( %s, %s, %s )';

		$wpdb->get_var( $wpdb->prepare( $sql, 'wcfm_tracking_url', $tracking_url, $order_item_id  ) );
		
		return;
	}
	
	/**
	 * Mark Order item as Received
	 */
	function wcfm_mark_as_recived() {
		global $WCFM, $WCFMu, $woocommerce, $wpdb;
		
		if ( !empty( $_POST['orderitemid'] ) ) {
      $order_id = $_POST['orderid'];                   
			$order = wc_get_order( $order_id );
			$product_id = $_POST['productid'];
      $order_item_id = $_POST['orderitemid'];
      
      $comment_id = $order->add_order_note( sprintf( __( 'Item(s) <b>%s</b> received by customer.', 'wc-frontend-manager-ultimate' ), get_the_title( $product_id ) ), '1');
      
      // Keep Tracking URL as Order Item Meta
			$sql = "INSERT INTO {$wpdb->prefix}woocommerce_order_itemmeta";
			$sql .= ' ( `meta_key`, `meta_value`, `order_item_id` )';
			$sql .= ' VALUES ( %s, %s, %s )';
			
			$confirm_message = __( 'YES', 'wc-frontend-manager-ultimate' );
	
			$wpdb->get_var( $wpdb->prepare( $sql, 'wcfm_mark_as_recived', $confirm_message, $order_item_id  ) );
    }
    die;
	}
	
	/**
	 * Generate Screen Manager HTMl
	 */
	function wcfmu_screen_manager_html() {
		global $WCFM, $WCFMu;
		
		require_once( $WCFMu->library->views_path . 'wcfmu-view-screen-manager.php' );
		die;
	}
	
	/**
	 * Ajax callback for importing one batch of products from a CSV.
	 */
	public function wcfmu_ajax_product_import() {
		global $WCFM, $WCFMu, $wpdb;

		check_ajax_referer( 'wcfm-product-import', 'security' );

		if ( ! current_user_can( 'edit_products' ) || ! isset( $_POST['file'] ) ) {
			wp_die( -1 );
		}

		include_once( $WCFMu->plugin_path . 'includes/product_importer/class-wcfm-product-csv-importer-controller.php' );
		include_once( WC_ABSPATH . 'includes/import/class-wc-product-csv-importer.php' );

		$file   = wc_clean( $_POST['file'] );
		$params = array(
			'delimiter'       => ! empty( $_POST['delimiter'] ) ? wc_clean( $_POST['delimiter'] ) : ',',
			'start_pos'       => isset( $_POST['position'] ) ? absint( $_POST['position'] ) : 0,
			'mapping'         => isset( $_POST['mapping'] ) ? (array) $_POST['mapping'] : array(),
			'update_existing' => isset( $_POST['update_existing'] ) ? (bool) $_POST['update_existing'] : false,
			'lines'           => apply_filters( 'woocommerce_product_import_batch_size', 30 ),
			'parse'           => true,
		);

		// Log failures.
		if ( 0 !== $params['start_pos'] ) {
			$error_log = array_filter( (array) get_user_option( 'product_import_error_log' ) );
		} else {
			$error_log = array();
		}

		$importer         = WCFM_Product_CSV_Importer_Controller::get_importer( $file, $params );
		$results          = $importer->import();
		$percent_complete = $importer->get_percent_complete();
		$error_log        = array_merge( $error_log, $results['failed'], $results['skipped'] );
		$current_user     = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		
		update_user_option( $current_user, 'product_import_error_log', $error_log );

		if ( 100 === $percent_complete ) {
			// Clear temp meta.
			$wpdb->delete( $wpdb->postmeta, array( 'meta_key' => '_original_id' ) );
			$wpdb->delete( $wpdb->posts, array( 'post_status' => 'importing', 'post_type' => 'product' ) );
			$wpdb->delete( $wpdb->posts, array( 'post_status' => 'importing', 'post_type' => 'product_variation' ) );

			// Send success.
			wp_send_json_success( array(
				'position'   => 'done',
				'percentage' => 100,
				'url'        => get_wcfm_import_product_url( 'done' ),
				'imported'   => count( $results['imported'] ),
				'failed'     => count( $results['failed'] ),
				'updated'    => count( $results['updated'] ),
				'skipped'    => count( $results['skipped'] ),
			) );
		} else {
			wp_send_json_success( array(
				'position'   => $importer->get_file_position(),
				'percentage' => $percent_complete,
				'imported'   => count( $results['imported'] ),
				'failed'     => count( $results['failed'] ),
				'updated'    => count( $results['updated'] ),
				'skipped'    => count( $results['skipped'] ),
			) );
		}
	}
}