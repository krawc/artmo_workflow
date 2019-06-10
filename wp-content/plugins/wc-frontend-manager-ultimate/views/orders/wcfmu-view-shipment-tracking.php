<?php
/**
 * WCFM plugin view
 *
 * WCfM Shipment Tracking popup View
 *
 * @author 		WC Lovers
 * @package 	wcfmu/views/orders
 * @version   5.0.1
 */
 
global $wp, $WCFM, $WCFMu, $_POST, $wpdb;

?>

<div id="wcfm_shipping_tracking_form" class="wcfm-collapse-content wcfm_popup_wrapper">
  <div style="margin-bottom: 15px;"><h2 style="float: none;"><?php _e( 'Shipment Tracking Info', 'wc-frontend-manager-ultimate' ); ?></h2></div>
  
  <?php
  $WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_shipment_tracking_fields', array(
																											"wcfm_tracking_code" => array( 'label' => __( 'Tracking Code', 'wc-frontend-manager-ultimate' ), 'type' => 'text', 'class' => 'wcfm-text shipment_tracking_input wcfm_popup_input', 'label_class' => 'shipment_tracking_input wcfm_popup_label', 'custom_attributes' => array( 'required' => true ) ),
																											"wcfm_tracking_url" => array( 'label' => __( 'Tracking URL', 'wc-frontend-manager-ultimate' ), 'type' => 'text', 'class' => 'wcfm-text shipment_tracking_input wcfm_popup_input', 'label_class' => 'shipment_tracking_input wcfm_popup_label', 'custom_attributes' => array( 'required' => true ) ),
																										) ) );
  ?>
  <div class="wcfm-message"></div>
  <input type="submit" id="wcfm_tracking_button" name="wcfm_tracking_button" class="wcfm_submit_button wcfm_popup_button" value="<?php _e( 'Submit', 'wc-frontend-manager' ); ?>" />
</div>