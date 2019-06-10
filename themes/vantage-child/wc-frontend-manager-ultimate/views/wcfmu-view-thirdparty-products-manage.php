<?php
/**
 * WCFM plugin views
 *
 * Plugin WC Booking Products Manage Views
 *
 * @author 		WC Lovers
 * @package 	wcfm/views
 * @version   2.1.0
 */
global $wp, $WCFM, $WCFMu;

// WP Job Manage Support
$listings = array();

if( isset( $wp->query_vars['wcfm-products-manage'] ) && !empty( $wp->query_vars['wcfm-products-manage'] ) ) {
	$product_id = $wp->query_vars['wcfm-products-manage'];
	if( $product_id ) {
		// WP Job Manage Support
		$wpjm_listings = get_post_meta( $product_id, '_wpjm_listings', true );
	}
}

?>

  <?php 
  if( $allow_listings = apply_filters( 'wcfm_is_allow_associate_listings', true ) ) {
  	if ( WCFM_Dependencies::wcfm_wp_job_manager_plugin_active_check() ) {
		  $wcfm_capability_options = get_option( 'wcfm_capability_options' );
			$associate_listings = ( isset( $wcfm_capability_options['associate_listings'] ) ) ? $wcfm_capability_options['associate_listings'] : 'no';
			if( !wcfm_is_vendor() || ( wcfm_is_vendor() && 'no' == $associate_listings ) ) { 
				$args = array(
					'posts_per_page'   => -1,
					'offset'           => 0,
					'category'         => '',
					'category_name'    => '',
					'orderby'          => 'date',
					'order'            => 'DESC',
					'include'          => '',
					'exclude'          => '',
					'meta_key'         => '',
					'meta_value'       => '',
					'post_type'        => 'job_listing',
					'post_mime_type'   => '',
					'post_parent'      => '',
					//'author'	   => get_current_user_id(),
					'post_status'      => array('publish'),
					'suppress_filters' => 0 
				);
				$args = apply_filters( 'wcfm_products_args', $args );
				
				$listings_objs = get_posts( $args );
				$wpjm_listings_array = array();
				if( !empty($listings_objs) ) {
					foreach( $listings_objs as $listings_obj ) {
						$wpjm_listings_array[esc_attr( $listings_obj->ID )] = esc_html( $listings_obj->post_title );
					}
				}
      ?>
				<!-- collapsible 15 - WP Job Manage Support -->
				<div class="page_collapsible products_manage_wpjm_listings simple variable grouped external booking" id="wcfm_products_manage_form_wpjm_listings_head"><label class="fa fa-list-ul"></label><?php _e('Listings', 'wc-frontend-manager-ultimate'); ?><span></span></div>
				<div class="wcfm-container simple variable external grouped booking">
					<div id="wcfm_products_manage_form_wpjm_listings_expander" class="wcfm-content">
						<?php
						$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'product_manage_fields_wpjm_listings', array(  
																																																"wpjm_listings" => array('label' => __('Listings', 'wc-frontend-manager-ultimate') , 'type' => 'select', 'attributes' => array( 'multiple' => 'multiple', 'style' => 'width: 60%;' ), 'class' => 'wcfm-select wcfm_ele simple variable grouped external booking', 'label_class' => 'wcfm_title wcfm_ele simple variable grouped external booking', 'options' => $wpjm_listings_array, 'value' => $wpjm_listings, 'hints' => __( 'Associate this product with your Listings.', 'wc-frontend-manager-ultimate' ))
																																											)) );
						?>
					</div>
				</div>
				<!-- end collapsible -->
				<div class="wcfm_clearfix"></div>
			<?php 
			} 
		}
	}
	?>