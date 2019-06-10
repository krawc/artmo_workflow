<?php
/**
 * WCFM plugin view
 *
 * WCfM Support popup View
 *
 * @author 		WC Lovers
 * @package 	wcfmu/views/support
 * @version   4.0.3
 */
 
global $wp, $WCFM, $WCFMu, $_POST, $wpdb;

$order_id = $_POST['order_id'];
$order_id = str_replace( '#', '', $order_id );

if( !$order_id ) return;


$support_categories    = $WCFMu->wcfmu_support->wcfm_support_categories();
$support_priority_types = $WCFMu->wcfmu_support->wcfm_support_priority_types();

$order                  = wc_get_order( $order_id );
$line_items             = $order->get_items( apply_filters( 'woocommerce_admin_order_item_types', 'line_item' ) );
$product_items          = array();
foreach ( $line_items as $item_id => $item ) {
	$product_items[$item_id] = $item->get_name();
}

?>

<div class="support_form_wrapper_hide">
	<div id="support_form_wrapper" class="wcfm_popup_wrapper">
	  <div style="margin-bottom: 15px;"><h2 style="float: none;"><?php _e( 'Support Ticket', 'wc-frontend-manager-ultimate' ); ?></h2></div>
		<div id="wcfm_support_form_wrapper">
			<form action="" method="post" id="wcfm_support_form" class="support-form" novalidate="">
			
				<?php 
				$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_support_ticket_popup_fields', array( 
																																			"wcfm_support_category" => array( 'label' => __( 'Category', 'wc-frontend-manager-ultimate' ), 'type' => 'select', 'class' => 'wcfm-select wcfm_ele wcfm_popup_input', 'label_class' => 'wcfm_title wcfm_popup_label', 'options' => $support_categories ),
																																			"wcfm_support_priority" => array( 'label' => __( 'Priority', 'wc-frontend-manager-ultimate' ), 'type' => 'select', 'class' => 'wcfm-select wcfm_ele wcfm_popup_input', 'label_class' => 'wcfm_title wcfm_popup_label', 'options' => $support_priority_types ),
																																			"wcfm_support_product" => array( 'label' => __( 'Product', 'wc-frontend-manager-ultimate' ), 'type' => 'select', 'class' => 'wcfm-select wcfm_ele wcfm_popup_input', 'label_class' => 'wcfm_title wcfm_popup_label', 'options' => $product_items )
																																			) ) ) ; 
				
				?>
				
				<p class="wcfm-support-form-query wcfm_popup_label">
					<strong for="comment"><?php _e( 'Issues you are having', 'wc-frontend-manager-ultimate' ); ?> <span class="required">*</span></strong>
				</p>
				<textarea id="wcfm_support_query" name="wcfm_support_query" class="wcfm_popup_input wcfm_popup_textarea"></textarea>
				
				<?php if ( function_exists( 'gglcptch_init' ) ) { ?>
					<div class="wcfm_clearfix"></div>
					<div class="wcfm_gglcptch_wrapper" style="float:right;">
						<?php echo apply_filters( 'gglcptch_display_recaptcha', '', 'wcfm_support_form' ); ?>
					</div>
				<?php } elseif ( class_exists( 'anr_captcha_class' ) && function_exists( 'anr_captcha_form_field' ) ) { ?>
					<div class="wcfm_clearfix"></div>
					<div class="wcfm_gglcptch_wrapper" style="float:right;">
						<?php do_action( 'anr_captcha_form_field' ); ?>
					</div>
				<?php } ?>
				<div class="wcfm_clearfix"></div>
				<div class="wcfm-message" tabindex="-1"></div>
				<div class="wcfm_clearfix"></div><br />
				
				<p class="form-submit">
					<input name="submit" type="submit" id="wcfm_support_submit_button" class="submit wcfm_popup_button" value="<?php _e( 'Submit', 'wc-frontend-manager-ultimate' ); ?>"> 
					<input type="hidden" name="wcfm_support_order_id" value="<?php echo $order_id; ?>" id="wcfm_support_order_id">
				</p>	
			</form>
			<div class="wcfm_clearfix"></div>
		</div>
	</div>
</div>
<div class="wcfm-clearfix"></div>