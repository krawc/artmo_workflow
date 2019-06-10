<?php
/**
 * WCFMu plugin core
 *
 * Plugin Shipment Tracking Controler
 *
 * @author 		WC Lovers
 * @package 	wcfmu/core
 * @version   4.1.8
 */
 
class WCFMu_Shipment_Tracking {
	
	public function __construct() {
		// WC Vendors Mark as Shipped
    add_action( 'wp_ajax_wcfm_wcvendors_order_mark_shipped', array( &$this, 'wcfm_wcvendors_order_mark_shipped' ) );
    
    // WC Product Vendors Mark as Fulfilled
    add_action( 'wp_ajax_wcfm_wcpvendors_order_mark_fulfilled', array( &$this, 'wcfm_wcpvendors_order_mark_fulfilled' ) );
    
    // WC Marketplace Mark as Shipped
    add_action( 'wp_ajax_wcfm_wcmarketplace_order_mark_shipped', array( &$this, 'wcfm_wcmarketplace_order_mark_shipped' ) );
    
    // WCfM Marketplace Mark as Shipped
    add_action( 'wp_ajax_wcfm_wcfmmarketplace_order_mark_shipped', array( &$this, 'wcfm_wcfmmarketplace_order_mark_shipped' ) );
    
    // Dokan Mark as Shipped
    add_action( 'wp_ajax_wcfm_dokan_order_mark_shipped', array( &$this, 'wcfm_dokan_order_mark_shipped' ) );
    
    // WCFM Mark as Received
    add_action( 'wp_ajax_wcfm_mark_as_recived', array( &$this, 'wcfm_mark_as_recived' ) );
    
    if( apply_filters( 'wcfm_is_allow_shipping_tracking', true ) ) {
			if( !wcfm_is_vendor() ) {
				add_filter( 'wcfm_orders_actions', array( &$this, 'wcfmu_shipping_tracking_orders_actions' ), 20, 3 );
			} else {
				add_filter( 'dokan_orders_actions', array( &$this, 'wcfmu_dokan_shipment_tracking_orders_actions' ), 20, 3 );
				add_filter( 'wcmarketplace_orders_actions', array( &$this, 'wcfmu_wcmarketplace_shipping_tracking_orders_actions' ), 20, 4 );
				add_filter( 'wcfmmarketplace_orders_actions', array( &$this, 'wcfmu_wcfmmarketplace_shipping_tracking_orders_actions' ), 20, 4 );
				add_filter( 'wcvendors_orders_actions', array( &$this, 'wcfmu_wcvendors_shipment_tracking_orders_actions' ), 20, 4 );
				add_filter( 'wcpvendors_orders_actions', array( &$this, 'wcfmu_wcpvendors_shipment_tracking_orders_actions' ), 20, 4 );
			}
		}
		
		// Vendor Order Shippment Tracking
		add_filter( 'woocommerce_order_item_display_meta_key', array( &$this, 'wcfm_tracking_url_display_label' ) );
		add_action( 'woocommerce_order_item_meta_end', array( &$this, 'wcfm_order_tracking_response' ), 20, 3 );
    
    // Shipment Tracking message type
		add_filter( 'wcfm_message_types', array( &$this, 'wcfm_shipment_tracking_message_types' ), 75 );
		
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
			
			$tracking_url = apply_filters( 'wcfm_tracking_url', $tracking_url, $tracking_code, $order_id );
			
			if( wcfm_is_vendor() ) {
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
					//$mails = $woocommerce->mailer()->get_emails();
					//if ( !empty( $mails ) ) {
					//	$mails[ 'WC_Email_Notify_Shipped' ]->trigger( $order_id, $user_id );
					//}
					//do_action('wcvendors_vendor_ship', $order_id, $user_id);
					_e( 'Order marked shipped.', 'wc-frontend-manager-ultimate' );
				} elseif ( false != ( $key = array_search( $user_id, $shippers) ) ) {
					unset( $shippers[$key] ); // Remove user from the shippers array
				}
				
				$shop_name =  $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( absint($user_id) );
				$wcfm_messages = sprintf( __( 'Vendor <b>%s</b> has shipped <b>%s</b> to customer.<br/>Tracking Code : %s <br/>Tracking URL : <a class="wcfm_dashboard_item_title" target="_blank" href="%s">%s</a>', 'wc-frontend-manager-ultimate' ), $shop_name, get_the_title( $product_id ), $tracking_code, $tracking_url, $tracking_url );
				$WCFM->wcfm_notification->wcfm_send_direct_message( $user_id, 0, 0, 1, $wcfm_messages, 'shipment_tracking' );
				$comment_id = $order->add_order_note( $wcfm_messages, '1');
				
				update_post_meta( $order_id, 'wc_pv_shipped', $shippers );
			} else {
				$comment_id = $order->add_order_note( sprintf( __( 'Product <b>%s</b> has been shipped to customer.<br/>Tracking Code : %s <br/>Tracking URL : <a href="%s">%s</a>', 'wc-frontend-manager-ultimate' ), get_the_title( $product_id ), $tracking_code, $tracking_url, $tracking_url ), '1');
			}
			
			// Update Shipping Tracking Info
			$this->updateShippingTrackingInfo( $order_id, $order_item_id, $tracking_code, $tracking_url, $product_id );
			
			do_action( 'wcfm_after_order_mark_shipped', $order_id, $order_item_id, $tracking_code, $tracking_url, $product_id );
		}
	}
	
	/**
	 * Mark WC Product Vendors order as Fulfilled
	 */
	function wcfm_wcpvendors_order_mark_fulfilled() {
		global $WCFM, $WCFMu, $woocommerce, $wpdb;
		
		$user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		
		if ( !empty( $_POST['orderid'] ) ) {
			$order_id = $_POST['orderid'];
			$product_id = $_POST['productid'];
			$order_item_id = $_POST['orderitemid'];
			$tracking_url  = $_POST['tracking_url'];
			$tracking_code  = $_POST['tracking_code'];
			$order = wc_get_order( $order_id );
			
			$tracking_url = apply_filters( 'wcfm_tracking_url', $tracking_url, $tracking_code, $order_id );
			
			if( $order_item_id ) {
				if( wcfm_is_vendor() ) {
					$vendor_data = WC_Product_Vendors_Utils::get_vendor_data_from_user();
					
					WC_Product_Vendors_Utils::set_fulfillment_status( absint( $order_item_id ), 'fulfilled' );
			
					WC_Product_Vendors_Utils::send_fulfill_status_email( $vendor_data, 'fulfilled', $order_item_id );
					
					WC_Product_Vendors_Utils::clear_reports_transients();
					
					$shop_name = ! empty( $vendor_data['shop_name'] ) ? $vendor_data['shop_name'] : '';
					$wcfm_messages = sprintf( __( 'Vendor <b>%s</b> has shipped <b>%s</b> to customer.<br/>Tracking Code : %s <br/>Tracking URL : <a class="wcfm_dashboard_item_title" target="_blank" href="%s">%s</a>', 'wc-frontend-manager-ultimate' ), $shop_name, get_the_title( $product_id ), $tracking_code, $tracking_url, $tracking_url );
					$WCFM->wcfm_notification->wcfm_send_direct_message( $user_id, 0, 0, 1, $wcfm_messages, 'shipment_tracking' );
					$comment_id = $order->add_order_note( $wcfm_messages, '1');
				} else {
					$comment_id = $order->add_order_note( sprintf( __( 'Product <b>%s</b> has been shipped to customer.<br/>Tracking Code : %s <br/>Tracking URL : <a href="%s">%s</a>', 'wc-frontend-manager-ultimate' ), get_the_title( $product_id ), $tracking_code, $tracking_url, $tracking_url ), '1');
				}
				
				// Update Shipping Tracking Info
				$this->updateShippingTrackingInfo( $order_id, $order_item_id, $tracking_code, $tracking_url, $product_id );
				
				do_action( 'wcfm_after_order_mark_shipped', $order_id, $order_item_id, $tracking_code, $tracking_url, $product_id );
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
		
		if ( !empty( $_POST['orderid'] ) ) {
			$order_id = $_POST['orderid'];                   
			$order = wc_get_order( $order_id );
			$product_id = absint( $_POST['productid'] );
			$tracking_url = $_POST['tracking_url'];
			$tracking_code  = $_POST['tracking_code'];
      $order_item_id = $_POST['orderitemid'];
      
      $tracking_url = apply_filters( 'wcfm_tracking_url', $tracking_url, $tracking_code, $order_id );
      
      if( wcfm_is_vendor() ) {
      	$vendor = get_wcmp_vendor($user_id);
				$user_id = apply_filters('wcmp_mark_as_shipped_vendor', $user_id);
				$shippers = (array) get_post_meta($order_id, 'dc_pv_shipped', true);
				
				if (!in_array($user_id, $shippers)) {
					$shippers[] = $user_id;
					//$mails = WC()->mailer()->emails['WC_Email_Notify_Shipped'];
					//if (!empty($mails)) {
						//$customer_email = get_post_meta($order_id, '_billing_email', true);
						//$mails->trigger($order_id, $customer_email, $vendor->term_id, array( 'tracking_code' => $tracking_code, 'tracking_url' => $tracking_url ) );
					//}
					do_action('wcmp_vendors_vendor_ship', $order_id, $vendor->term_id);
					array_push($shippers, $user_id);
				}
					
				$wpdb->query("UPDATE {$wpdb->prefix}wcmp_vendor_orders SET shipping_status = '1' WHERE order_id = $order_id and vendor_id = $user_id and order_item_id = $order_item_id");
				$shop_name =  $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( absint($user_id) );
				$wcfm_messages = sprintf( __( 'Vendor <b>%s</b> has shipped <b>%s</b> to customer.<br/>Tracking Code : %s <br/>Tracking URL : <a class="wcfm_dashboard_item_title" target="_blank" href="%s">%s</a>', 'wc-frontend-manager-ultimate' ), $shop_name, get_the_title( $product_id ), $tracking_code, $tracking_url, $tracking_url );
      	$WCFM->wcfm_notification->wcfm_send_direct_message( $user_id, 0, 0, 1, $wcfm_messages, 'shipment_tracking' );
      	$comment_id = $order->add_order_note( $wcfm_messages, '1');
				add_comment_meta( $comment_id, '_vendor_id', $user_id );
				
				update_post_meta($order_id, 'dc_pv_shipped', $shippers);
			} else {
				$comment_id = $order->add_order_note( sprintf( __( 'Product <b>%s</b> has been shipped to customer.<br/>Tracking Code : %s <br/>Tracking URL : <a href="%s">%s</a>', 'wc-frontend-manager-ultimate' ), get_the_title( $product_id ), $tracking_code, $tracking_url, $tracking_url ), '1');
			}
			
			// Update Shipping Tracking Info
			$this->updateShippingTrackingInfo( $order_id, $order_item_id, $tracking_code, $tracking_url, $product_id );
			
			do_action( 'wcfm_after_order_mark_shipped', $order_id, $order_item_id, $tracking_code, $tracking_url, $product_id );
		}
		die;
	}
	
	/**
	 * Mark WCfM Marketplace order as Shipped
	 */
	function wcfm_wcfmmarketplace_order_mark_shipped() {
		global $WCFM, $WCFMu, $woocommerce, $wpdb;
		
		$user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		
		if ( !empty( $_POST['orderid'] ) ) {
			$order_id = $_POST['orderid'];                   
			$order = wc_get_order( $order_id );
			$product_id = absint( $_POST['productid'] );
			$tracking_url = $_POST['tracking_url'];
			$tracking_code  = $_POST['tracking_code'];
      $order_item_id = $_POST['orderitemid'];
      
      $tracking_url = apply_filters( 'wcfm_tracking_url', $tracking_url, $tracking_code, $order_id );
      
      if( wcfm_is_vendor() ) {
				$wpdb->query("UPDATE {$wpdb->prefix}wcfm_marketplace_orders SET commission_status = 'shipped', shipping_status = 'shipped' WHERE order_id = $order_id and vendor_id = $user_id and item_id = $order_item_id");
				$shop_name =  $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( absint($user_id) );
				$wcfm_messages = sprintf( __( 'Vendor <b>%s</b> has shipped <b>%s</b> to customer.<br/>Tracking Code : %s <br/>Tracking URL : <a class="wcfm_dashboard_item_title" target="_blank" href="%s">%s</a>', 'wc-frontend-manager-ultimate' ), $shop_name, get_the_title( $product_id ), $tracking_code, $tracking_url, $tracking_url );
      	$WCFM->wcfm_notification->wcfm_send_direct_message( $user_id, 0, 0, 1, $wcfm_messages, 'shipment_tracking' );
      	$comment_id = $order->add_order_note( $wcfm_messages, '1');
				add_comment_meta( $comment_id, '_vendor_id', $user_id );
			} else {
				$comment_id = $order->add_order_note( sprintf( __( 'Product <b>%s</b> has been shipped to customer.<br/>Tracking Code : %s <br/>Tracking URL : <a href="%s">%s</a>', 'wc-frontend-manager-ultimate' ), get_the_title( $product_id ), $tracking_code, $tracking_url, $tracking_url ), '1');
			}
			
			// Update Shipping Tracking Info
			$this->updateShippingTrackingInfo( $order_id, $order_item_id, $tracking_code, $tracking_url, $product_id );
			
			do_action( 'wcfm_after_order_mark_shipped', $order_id, $order_item_id, $tracking_code, $tracking_url, $product_id );
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
      
      $tracking_url = apply_filters( 'wcfm_tracking_url', $tracking_url, $tracking_code, $order_id );
			
      if( wcfm_is_vendor() ) {
      	$shop_name = $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( absint($user_id) );
      	$wcfm_messages = sprintf( __( 'Vendor <b>%s</b> has shipped <b>%s</b> to customer.<br/>Tracking Code : %s <br/>Tracking URL : <a class="wcfm_dashboard_item_title" target="_blank" href="%s">%s</a>', 'wc-frontend-manager-ultimate' ), $shop_name, get_the_title( $product_id ), $tracking_code, $tracking_url, $tracking_url );
      	$WCFM->wcfm_notification->wcfm_send_direct_message( $user_id, 0, 0, 1, $wcfm_messages, 'shipment_tracking' );
      	$comment_id = $order->add_order_note( $wcfm_messages, '1');
      } else {
      	$comment_id = $order->add_order_note( sprintf( __( 'Product <b>%s</b> has been shipped to customer.<br/>Tracking Code : %s <br/>Tracking URL : <a href="%s">%s</a>', 'wc-frontend-manager-ultimate' ), get_the_title( $product_id ), $tracking_code, $tracking_url, $tracking_url ), '1');
      }
			
			// Update Shipping Tracking Info
			$this->updateShippingTrackingInfo( $order_id, $order_item_id, $tracking_code, $tracking_url, $product_id );
			
			do_action( 'wcfm_after_order_mark_shipped', $order_id, $order_item_id, $tracking_code, $tracking_url, $product_id );
		}
		die;
	}
	
	function updateShippingTrackingInfo( $order_id, $order_item_id, $tracking_code, $tracking_url, $product_id ) {
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
		
		// Shipment Tracking Notification to Customer
		if( apply_filters( 'wcfm_is_allow_shipment_tracking_customer_email', true ) ) {
			if( !defined( 'DOING_WCFM_EMAIL' ) ) 
				define( 'DOING_WCFM_EMAIL', true );
			
			$shipment_message = sprintf( __( 'Product <b>%s</b> has been shipped to you.<br/>Tracking Code : %s <br/>Tracking URL : <a target="_blank" href="%s">%s</a>', 'wc-frontend-manager-ultimate' ), get_the_title( $product_id ), $tracking_code, $tracking_url, $tracking_url );
			$notificaton_mail_subject = "{site_name}: " . __( "Shipment Tracking Update", "wc-frontend-manager-ultimate" ) . " - {product_title}";
			$notification_mail_body =  '<br/>' . __( 'Hi {customer_name}', 'wc-frontend-manager' ) .
																 ',<br/><br/>' . 
																 __( 'Product Shipment update:', 'wc-frontend-manager-ultimate' ) .
																 '<br/><br/>' .
																 '{shipment_message}' .
																 '<br/><br/>' .
																 sprintf( __( 'Track your package %shere%s.', 'wc-frontend-manager-ultimate' ), '<a href="{tracking_url}">', '</a>' ) .
																 '<br /><br/>' . __( 'Thank You', 'wc-frontend-manager' ) .
																 '<br/><br/>';
													 
			$subject = str_replace( '{site_name}', get_bloginfo( 'name' ), $notificaton_mail_subject );
			$subject = str_replace( '{product_title}', get_the_title( $product_id ), $subject );
			$message = str_replace( '{shipment_message}', $shipment_message, $notification_mail_body );
			$message = str_replace( '{tracking_url}', $tracking_url, $message );
			$message = str_replace( '{customer_name}', get_post_meta( $order_id, '_billing_first_name', true ), $message );
			$message = apply_filters( 'wcfm_email_content_wrapper', $message, __( "Shipment Tracking Update", "wc-frontend-manager-ultimate" ) );
			
			$customer_email = get_post_meta( $order_id, '_billing_email', true );
			if( $customer_email ) {
				wp_mail( $customer_email, $subject, $message );
			}
		}
		
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
      
      //$comment_id = $order->add_order_note( sprintf( __( 'Item(s) <b>%s</b> received by customer.', 'wc-frontend-manager-ultimate' ), get_the_title( $product_id ) ), '1');
      
      // Keep Tracking URL as Order Item Meta
			$sql = "INSERT INTO {$wpdb->prefix}woocommerce_order_itemmeta";
			$sql .= ' ( `meta_key`, `meta_value`, `order_item_id` )';
			$sql .= ' VALUES ( %s, %s, %s )';
			
			$confirm_message = __( 'YES', 'wc-frontend-manager-ultimate' );
	
			$wpdb->get_var( $wpdb->prepare( $sql, 'wcfm_mark_as_recived', $confirm_message, $order_item_id  ) );
			
			$vendor_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $product_id );
			
			// WCfM Marketplace Table Update
			if( $vendor_id  && (wcfm_is_marketplace() == 'wcfmmarketplace') ) {
				$wpdb->query("UPDATE {$wpdb->prefix}wcfm_marketplace_orders SET shipping_status = 'completed' WHERE order_id = $order_id and vendor_id = $vendor_id and item_id = $order_item_id");
			}
			
			// Notification
			$wcfm_messages = sprintf( __( 'Customer marked <b>%s</b> received.', 'wc-frontend-manager-ultimate' ), get_the_title( $product_id ) );
			$WCFM->wcfm_notification->wcfm_send_direct_message( -1, 0, 0, 1, $wcfm_messages, 'shipment_received' );
			
			// Vendor Notification
			if( $vendor_id ) {
				$WCFM->wcfm_notification->wcfm_send_direct_message( -2, $vendor_id, 0, 1, $wcfm_messages, 'shipment_received' );
			}
			
			// WC Order Note
			$comment_id = $order->add_order_note( $wcfm_messages, '1');
			
			do_action( 'wcfm_after_order_mark_received', $order_id, $order_item_id, $product_id );
    }
    die;
	}
	
  public function wcfmu_shipping_tracking_orders_actions( $actions, $user_id, $order ) {
  	global $WCFM, $WCFMu;
  	
  	$order_status = sanitize_title( $order->get_status() );
		if( in_array( $order_status, apply_filters( 'wcfm_shipment_disable_order_status', array( 'failed', 'cancelled', 'refunded', 'pending' ) ) ) ) return $actions;
  	
		$items = $order->get_items();
		$order_item_id = 0;
		foreach ( $items as $item_id => $item ) {
			$shipped = false;
			$needs_shipping = $WCFM->frontend->is_wcfm_needs_shipping( $item->get_product() );
			if ( $needs_shipping ) {
				foreach ( $item->get_formatted_meta_data() as $meta_id => $meta ) {
					if( $meta->key == 'wcfm_tracking_url' ) {
						$shipped = true;
					}
				}
			}
		}

		if ( $shipped ) {
			//$actions .= '<a class="wcfm-action-icon" href="#"><span class="fa fa-ship text_tip" data-tip="' . esc_attr__( 'Shipped', 'wc-frontend-manager-ultimate' ) . '"></span></a>';
		} elseif ( $needs_shipping ) {
			$actions .= '<a class="wcfm-action-icon" href="' . get_wcfm_view_order_url($order->get_id()) . '#wcfm_order_shipment_options"><span class="fa fa-truck text_tip" data-tip="' . esc_attr__( 'Mark Shipped', 'wc-frontend-manager-ultimate' ) . '"></span></a>';
		} 
		
  	
  	return $actions;
  }
  
  public function wcfmu_dokan_shipment_tracking_orders_actions( $actions, $user_id, $order ) {
  	global $WCFM, $WCFMu;
  	
  	$order_status = sanitize_title( $order->get_status() );
		if( in_array( $order_status, apply_filters( 'wcfm_shipment_disable_order_status', array( 'failed', 'cancelled', 'refunded', 'pending' ) ) ) ) return $actions;
  	
		$needs_shipping = true;
		
		$items = $order->get_items();
		$order_item_id = 0;
		foreach ( $items as $item_id => $item ) {
			$shipped = false;
			$needs_shipping = $WCFM->frontend->is_wcfm_needs_shipping( $item->get_product() );
			if ( $needs_shipping ) {
				foreach ( $item->get_formatted_meta_data() as $meta_id => $meta ) {
					if( $meta->key == 'wcfm_tracking_url' ) {
						$shipped = true;
					}
				}
			}
		}

		if ( $shipped ) {
			//$actions .= '<a class="wcfm-action-icon" href="#"><span class="fa fa-ship text_tip" data-tip="' . esc_attr__( 'Shipped', 'wc-frontend-manager-ultimate' ) . '"></span></a>';
		} elseif ( $needs_shipping ) {
			$actions .= '<a class="wcfm-action-icon" href="' . get_wcfm_view_order_url($order->get_id()) . '#wcfm_order_shipment_options"><span class="fa fa-truck text_tip" data-tip="' . esc_attr__( 'Mark Shipped', 'wc-frontend-manager-ultimate' ) . '"></span></a>';
		}
  	
  	return $actions;
  }
  
  public function wcfmu_wcmarketplace_shipping_tracking_orders_actions( $actions, $user_id, $order, $the_order ) {
  	global $WCFM, $WCFMu;
  	
		$needs_shipping = true; 
		if( !$order->product_id ) return $actions;
		
		$order_status = sanitize_title( $the_order->get_status() );
		if( in_array( $order_status, apply_filters( 'wcfm_shipment_disable_order_status', array( 'failed', 'cancelled', 'refunded', 'pending' ) ) ) ) return $actions;
		
		// See if product needs shipping 
		$shipped = $order->shipping_status;
		$product = wc_get_product( $order->product_id ); 
		$needs_shipping = $WCFM->frontend->is_wcfm_needs_shipping( $product ); 

		if ( $shipped ) {
			//$actions .= '<a class="wcfm-action-icon" href="#"><span class="fa fa-ship text_tip" data-tip="' . esc_attr__( 'Shipped', 'wc-frontend-manager-ultimate' ) . '"></span></a>';
		} elseif ( $needs_shipping ) {
			$actions .= '<a class="wcfm_wcmarketplace_order_mark_shipped wcfm-action-icon" href="#" data-productid="' . $order->product_id . '" data-orderitemid="' . $order->order_item_id . '" data-orderid="' . $order->order_id . '"><span class="fa fa-truck text_tip" data-tip="' . esc_attr__( 'Mark Shipped', 'wc-frontend-manager-ultimate' ) . '"></span></a>';
		} 
		
  	
  	return $actions;
  }
  
  public function wcfmu_wcfmmarketplace_shipping_tracking_orders_actions( $actions, $user_id, $order, $the_order ) {
  	global $WCFM, $WCFMu;
  	
		$needs_shipping = true; 
		if( !$order->product_id ) return $actions;
		if( $order->refund_status   == 'requested' ) return $actions;
		if( $order->is_refunded ) return $actions;
		
		$order_status = sanitize_title( $the_order->get_status() );
		if( in_array( $order_status, apply_filters( 'wcfm_shipment_disable_order_status', array( 'failed', 'cancelled', 'refunded', 'pending' ) ) ) ) return $actions;
		
		// See if product needs shipping 
		$shipped = $order->shipping_status;
		$product = wc_get_product( $order->product_id ); 
		$needs_shipping = $WCFM->frontend->is_wcfm_needs_shipping( $product ); 

		if ( $needs_shipping && ( $shipped == 'pending' ) ) {
			$actions .= '<a class="wcfm_wcfmmarketplace_order_mark_shipped wcfm-action-icon" href="#" data-productid="' . $order->product_id . '" data-orderitemid="' . $order->item_id . '" data-orderid="' . $order->order_id . '"><span class="fa fa-truck text_tip" data-tip="' . esc_attr__( 'Mark Shipped', 'wc-frontend-manager-ultimate' ) . '"></span></a>';
		} 
		
  	
  	return $actions;
  }
  
  public function wcfmu_wcvendors_shipment_tracking_orders_actions( $actions, $user_id, $the_order, $product_id ) {
  	global $WCFM, $WCFMu;
  	
		$needs_shipping = true;
		$shipped = false;
		
		// See if product needs shipping 
		$product = wc_get_product( $product_id ); 
		$needs_shipping = $WCFM->frontend->is_wcfm_needs_shipping( $product );
			
		if( $needs_shipping ) {
			$items = $the_order->get_items();
			$order_item_id = 0;
			foreach ( $items as $item_id => $item ) {
				if ( ( $item->get_variation_id() == $product_id ) || ( $item->get_product_id() == $product_id ) ) {
					$order_item_id = $item_id;
					foreach ( $item->get_formatted_meta_data() as $meta_id => $meta ) {
						if( $meta->key == 'wcfm_tracking_url' ) {
							$shipped = true;
						}
					}
				}
			}
		}

		//$shippers = (array) get_post_meta( $the_order->get_id(), 'wc_pv_shipped', true );
		//$shipped = in_array($user_id, $shippers);
  	
		if ( $shipped ) {
			//$actions .= '<a class="wcfm-action-icon" href="#"><span class="fa fa-ship text_tip" data-tip="' . esc_attr__( 'Shipped', 'wc-frontend-manager-ultimate' ) . '"></span></a>';
		} elseif ( $needs_shipping ) {
			$actions .= '<a class="wcfm_wcvendors_order_mark_shipped wcfm-action-icon" href="#" data-productid="' . $product_id . '" data-orderitemid="' . $order_item_id . '" data-orderid="' . $the_order->get_id() . '"><span class="fa fa-truck text_tip" data-tip="' . esc_attr__( 'Mark Shipped', 'wc-frontend-manager-ultimate' ) . '"></span></a>';
		} 
		
  	
  	return $actions;
  }
  
  public function wcfmu_wcpvendors_shipment_tracking_orders_actions( $actions, $user_id, $the_order, $order ) {
  	global $WCFM, $WCFMu, $wpdb;
  	
  	$vendor_id   = $this->vendor_id;
		$valid_items = array();
  	
		// See if product needs shipping 
		$needs_shipping = true; 

		$status = WC_Product_Vendors_Utils::get_fulfillment_status( $order->order_item_id );
		$product = wc_get_product( $order->product_id );
		$needs_shipping = $WCFM->frontend->is_wcfm_needs_shipping( $product );
  	
		if ( $status && 'fulfilled' === $status ) {
			//$actions .= '<a class="wcfm-action-icon" href="#"><span class="fa fa-ship text_tip" data-tip="' . esc_attr__( 'Fulfilled', 'wc-frontend-manager-ultimate' ) . '"></span></a>';
		} elseif ( $needs_shipping ) {
			$actions .= '<a class="wcfm_wcpvendors_order_mark_fulfilled wcfm-action-icon" href="#" data-productid="' . $order->product_id . '" data-orderid="' . $order->order_id . '" data-orderitemid="' . $order->order_item_id . '"><span class="fa fa-truck text_tip" data-tip="' . esc_attr__( 'Mark Fulfilled', 'wc-frontend-manager-ultimate' ) . '"></span></a>';
		} 
		
  	
  	return $actions;
  }
  
  // Order item meta Tracking URL label
	function wcfm_tracking_url_display_label( $display_key ) {
		global $WCFM, $WCFMu;
		
		if( $display_key == 'wcfm_tracking_code' ) {
			$display_key = __( 'Tracking Code', 'wc-frontend-manager-ultimate' );
		}
		
		if( $display_key == 'wcfm_tracking_url' ) {
			$display_key = __( 'Tracking URL', 'wc-frontend-manager-ultimate' );
		}
		
		if( $display_key == 'wcfm_mark_as_recived' ) {
			$display_key = __( 'Item(s) Received', 'wc-frontend-manager-ultimate' );
		}
		
		return $display_key;
	}
	
	// Order Tracking reponse at View Order by Customer
	function wcfm_order_tracking_response( $item_id, $item, $order ) {
		global $WCFM, $WCFMu;
		
		// See if product needs shipping 
		$product = $item->get_product(); 
		$needs_shipping = $WCFM->frontend->is_wcfm_needs_shipping( $product ); 
		
		if( $WCFMu->is_marketplace ) {
			if( $WCFMu->is_marketplace == 'wcvendors' ) {
				if( version_compare( WCV_VERSION, '2.0.0', '<' ) ) {
					if( !WC_Vendors::$pv_options->get_option( 'give_shipping' ) ) $needs_shipping = false;
				} else {
					if( !get_option('wcvendors_vendor_give_shipping') ) $needs_shipping = false;
				}
			} elseif( $WCFMu->is_marketplace == 'wcmarketplace' ) {
				global $WCMp;
				if( !$WCMp->vendor_caps->vendor_payment_settings('give_shipping') ) $needs_shipping = false;
			}
		}
		
		if( $needs_shipping ) {
			$traking_added = false;
			$package_received = false;
			foreach ( $item->get_formatted_meta_data() as $meta_id => $meta ) {
				if( $meta->key == 'wcfm_tracking_url' ) {
					$traking_added = true;
				}
				if( $meta->key == 'wcfm_mark_as_recived' ) {
					$package_received = true;
				}
			}
			echo "<p>";
			printf( __( 'Shipment Tracking: ', 'wc-frontend-manager-ultimate' ) );
			if( $package_received ) {
				printf( __( 'Item(s) already received.', 'wc-frontend-manager-ultimate' ) );
			} elseif( $traking_added ) {
				?>
				<a href="#" class="wcfm_mark_as_recived" data-orderitemid="<?php echo $item_id; ?>" data-orderid="<?php echo $order->get_id(); ?>" data-productid="<?php echo $item->get_product_id(); ?>"><?php printf( __( 'Mark as Received', 'wc-frontend-manager-ultimate' ) ); ?></a>
				<?php
			} else {
				printf( __( 'Item(s) will be shipped soon.', 'wc-frontend-manager-ultimate' ) );
			}
			echo "</p>";
		}
	}
	
	function wcfm_shipment_tracking_message_types( $message_types ) {
		$message_types['shipment_tracking'] = __( 'Shipment Tracking', 'wc-frontend-manager-ultimate' );
		$message_types['shipment_received'] = __( 'Shipment Received', 'wc-frontend-manager-ultimate' );
		return $message_types;
	}
}