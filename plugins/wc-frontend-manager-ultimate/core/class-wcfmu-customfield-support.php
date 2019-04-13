<?php
/**
 * WCFM plugin core
 *
 * Custom Field Support Controller
 *
 * @author 		WC Lovers
 * @package 	wcfm/core
 * @version   3.5.5
 */
 
class WCFMu_Custom_Field_Support {

	public function __construct() {
		global $WCFM;
		
		// Custom fields visibility options
		add_filter( 'wcfm_product_custom_visibility_options', array( &$this, 'wcfm_product_custom_visibility_options' ) );
		
    // Product custom fields display
    add_action( 'woocommerce_single_product_summary',	array( &$this, 'wcfm_custom_field_display_after_title' ),  7 );
    add_action( 'woocommerce_single_product_summary',	array( &$this, 'wcfm_custom_field_display_after_price' ),  12 );
    add_action( 'woocommerce_single_product_summary',	array( &$this, 'wcfm_custom_field_display_with_summery' ),  25 );
    add_action( 'woocommerce_product_meta_end',	array( &$this, 'wcfm_custom_field_display_with_meta' ),  25 );
    add_action( 'the_content',	array( &$this, 'wcfm_custom_field_display_with_description' ),  25 );
    
  }
  
  /**
   * Custom fileds visibility options
   */
  function wcfm_product_custom_visibility_options( $visibility_options ) {
  	$more_visibility_options = array( 'after_title' => __( 'After product title', 'wc-frontend-manager-ultimate' ), 'after_price' => __( 'After product price', 'wc-frontend-manager-ultimate' ), 'with_summery' => __( 'With product summery', 'wc-frontend-manager-ultimate' ), 'with_meta' => __( 'With product meta', 'wc-frontend-manager-ultimate' ), 'with_desctiption' => __( 'With product description', 'wc-frontend-manager-ultimate' ) );
  	//, 'new_tab' => __( 'As new tab', 'wc-frontend-manager-ultimate' )
  	$visibility_options = array_merge( $visibility_options, $more_visibility_options );
  	return $visibility_options;
  }
  
	/**
	 * product custom field display
	 */
	function get_wcfm_custom_field_display_data( $product_id, $wcfm_product_custom_field ) {
		global $WCFM, $product, $post;
		
		$display_data = '';
		$block_name = !empty( $wcfm_product_custom_field['block_name'] ) ? $wcfm_product_custom_field['block_name'] : '';
		if( !$block_name ) return '';
		$exclude_product_types = isset( $wcfm_product_custom_field['exclude_product_types'] ) ? $wcfm_product_custom_field['exclude_product_types'] : array();
		$is_group = !empty( $wcfm_product_custom_field['group_name'] ) ? 'yes' : 'no';
		$is_group = !empty( $wcfm_product_custom_field['is_group'] ) ? 'yes' : 'no';
		$group_name = $wcfm_product_custom_field['group_name'];
		$group_value = array();
		$group_value = (array) get_post_meta( $product_id, $group_name, true );		
		$group_value = apply_filters( 'wcfm_custom_field_group_data_value', $group_value, $group_name );
		
		$product = wc_get_product( $product_id );
		$product_type = $product->get_type();
		
		$wcfm_product_custom_block_fields = $wcfm_product_custom_field['wcfm_product_custom_block_fields'];
		if( !empty( $wcfm_product_custom_block_fields ) && !in_array( $product_type, $exclude_product_types ) ) {
			
			$vendor_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $product_id );
			if( $vendor_id ) {
				if( !$WCFM->wcfm_vendor_support->wcfm_vendor_allowed_element_capability( $vendor_id, 'allowed_custom_fields', sanitize_title($block_name) ) ) return;
			}
			
			$display_data .= '<div class="wcfm_custom_field_display wcfm_custom_field_display_'.sanitize_title($block_name).'">';
			if( $block_name && apply_filters( 'wcfm_is_allow_custom_field_block_name_display', true ) ) {
				$display_data .= "<h4 class='wcfm_custom_field_display_heading'>" . __( $block_name, 'wc-frontend-manager' ) . "</h4>";
			}
			foreach( $wcfm_product_custom_block_fields as $wcfm_product_custom_block_field ) {
				if( !$wcfm_product_custom_block_field['name'] ) continue;
				$field_value = '';
				$field_name = $wcfm_product_custom_block_field['name'];
				if( $is_group == 'yes' ) {
					$field_name = $group_name . '[' . $wcfm_product_custom_block_field['name'] . ']';
					if( $product_id ) {
						if( $wcfm_product_custom_block_field['type'] == 'checkbox' ) {
							$field_value = isset( $group_value[$wcfm_product_custom_block_field['name']] ) ? 'yes' : 'no';
						} elseif( $wcfm_product_custom_block_field['type'] == 'upload' ) {
							if( isset( $group_value[$wcfm_product_custom_block_field['name']] ) ) {
								$field_value = '<a href="'.$group_value[$wcfm_product_custom_block_field['name']].'" target="_blank">' . __( 'Check here', 'wc-frontend-manager-ultimate' ) . '</a>';
							}
						} else {
							if( isset( $group_value[$wcfm_product_custom_block_field['name']] )) {
								$field_value = $group_value[$wcfm_product_custom_block_field['name']];
							}
						}
					}
				} else {
					if( $product_id ) {
						if( $wcfm_product_custom_block_field['type'] == 'checkbox' ) {
							$field_value = get_post_meta( $product_id, $field_name, true ) ? get_post_meta( $product_id, $field_name, true ) : 'no';
						} elseif( $wcfm_product_custom_block_field['type'] == 'upload' ) {
							if( get_post_meta( $product_id, $field_name, true ) ) {
								$field_value = '<a href="'.get_post_meta( $product_id, $field_name, true ).'" target="_blank">' . __( 'Check here', 'wc-frontend-manager-ultimate' ) . '</a>';
							}
						} else {
							$field_value = get_post_meta( $product_id, $field_name, true );
						}
					}
				}
				
				if( !$field_value ) continue;
				
				$display_data .= "<label class='wcfm_custom_field_display_label'>" . __( $wcfm_product_custom_block_field['label'], 'wc-frontend-manager') . "</label>: <span class='wcfm_custom_field_display_value'>" . $field_value . "</span><br />";
			}
			$display_data .= '</div>';
		}
		return $display_data;
	}
	
	/**
	 * product custom field display after title
	 */
	function wcfm_custom_field_display_after_title() {
		global $WCFM, $product, $post;
	
		$product_id = 0;
		if ( is_object( $product ) ) { 
			$product_id   		= $product->get_id(); 
		} else if ( is_product() ) {
			$product_id   		= $post->ID;
		}
			
		if( $product_id ) {
			$wcfm_product_custom_fields = (array) get_option( 'wcfm_product_custom_fields' );
			if( $wcfm_product_custom_fields && is_array( $wcfm_product_custom_fields ) && !empty( $wcfm_product_custom_fields ) ) {
				foreach( $wcfm_product_custom_fields as $wpcf_index => $wcfm_product_custom_field ) {
					if( !isset( $wcfm_product_custom_field['enable'] ) ) continue;
					
					$visibility = isset( $wcfm_product_custom_field['visibility'] ) ? $wcfm_product_custom_field['visibility'] : '';
					if( !$visibility ) continue;
					if( $visibility != 'after_title' ) continue;
					
					$display_data = $this->get_wcfm_custom_field_display_data( $product_id, $wcfm_product_custom_field );
					echo $display_data;
				}
			}
		}
	}
	
	/**
	 * product custom field display after price
	 */
	function wcfm_custom_field_display_after_price() {
		global $WCFM, $product, $post;
	
		$product_id = 0;
		if ( is_object( $product ) ) { 
			$product_id   		= $product->get_id(); 
		} else if ( is_product() ) {
			$product_id   		= $post->ID;
		}
			
		if( $product_id ) {
			$wcfm_product_custom_fields = (array) get_option( 'wcfm_product_custom_fields' );
			if( $wcfm_product_custom_fields && is_array( $wcfm_product_custom_fields ) && !empty( $wcfm_product_custom_fields ) ) {
				foreach( $wcfm_product_custom_fields as $wpcf_index => $wcfm_product_custom_field ) {
					if( !isset( $wcfm_product_custom_field['enable'] ) ) continue;
					
					$visibility = isset( $wcfm_product_custom_field['visibility'] ) ? $wcfm_product_custom_field['visibility'] : '';
					if( !$visibility ) continue;
					if( $visibility != 'after_price' ) continue;
					
					$display_data = $this->get_wcfm_custom_field_display_data( $product_id, $wcfm_product_custom_field );
					echo $display_data;
				}
			}
		}
	}
	
	/**
	 * product custom field display with summery
	 */
	function wcfm_custom_field_display_with_summery() {
		global $WCFM, $product, $post;
	
		$product_id = 0;
		if ( is_object( $product ) ) { 
			$product_id   		= $product->get_id(); 
		} else if ( is_product() ) {
			$product_id   		= $post->ID;
		}
			
		if( $product_id ) {
			$wcfm_product_custom_fields = (array) get_option( 'wcfm_product_custom_fields' );
			if( $wcfm_product_custom_fields && is_array( $wcfm_product_custom_fields ) && !empty( $wcfm_product_custom_fields ) ) {
				foreach( $wcfm_product_custom_fields as $wpcf_index => $wcfm_product_custom_field ) {
					if( !isset( $wcfm_product_custom_field['enable'] ) ) continue;
					
					$visibility = isset( $wcfm_product_custom_field['visibility'] ) ? $wcfm_product_custom_field['visibility'] : '';
					if( !$visibility ) continue;
					if( $visibility != 'with_summery' ) continue;
					
					$display_data = $this->get_wcfm_custom_field_display_data( $product_id, $wcfm_product_custom_field );
					echo $display_data;
				}
			}
		}
	}
	
	/**
	 * product custom field display with summery
	 */
	function wcfm_custom_field_display_with_meta() {
		global $WCFM, $product, $post;
	
		$product_id = 0;
		if ( is_object( $product ) ) { 
			$product_id   		= $product->get_id(); 
		} else if ( is_product() ) {
			$product_id   		= $post->ID;
		}
			
		if( $product_id ) {
			$wcfm_product_custom_fields = (array) get_option( 'wcfm_product_custom_fields' );
			if( $wcfm_product_custom_fields && is_array( $wcfm_product_custom_fields ) && !empty( $wcfm_product_custom_fields ) ) {
				foreach( $wcfm_product_custom_fields as $wpcf_index => $wcfm_product_custom_field ) {
					if( !isset( $wcfm_product_custom_field['enable'] ) ) continue;
					
					$visibility = isset( $wcfm_product_custom_field['visibility'] ) ? $wcfm_product_custom_field['visibility'] : '';
					if( !$visibility ) continue;
					if( $visibility != 'with_meta' ) continue;
					
					$display_data = $this->get_wcfm_custom_field_display_data( $product_id, $wcfm_product_custom_field );
					echo $display_data;
				}
			}
		}
	}
	
	/**
	 * product custom field display with description
	 */
	function wcfm_custom_field_display_with_description( $description ) {
		global $WCFM, $product, $post;
	
		$product_id = 0;
		if ( is_object( $product ) ) { 
			$product_id   		= $product->get_id(); 
		} else if ( is_product() ) {
			$product_id   		= $post->ID;
		}
			
		if( $product_id ) {
			$wcfm_product_custom_fields = (array) get_option( 'wcfm_product_custom_fields' );
			if( $wcfm_product_custom_fields && is_array( $wcfm_product_custom_fields ) && !empty( $wcfm_product_custom_fields ) ) {
				foreach( $wcfm_product_custom_fields as $wpcf_index => $wcfm_product_custom_field ) {
					if( !isset( $wcfm_product_custom_field['enable'] ) ) continue;
					
					$visibility = isset( $wcfm_product_custom_field['visibility'] ) ? $wcfm_product_custom_field['visibility'] : '';
					if( !$visibility ) continue;
					if( $visibility != 'with_desctiption' ) continue;
					
					$display_data = $this->get_wcfm_custom_field_display_data( $product_id, $wcfm_product_custom_field );
					$description .= $display_data;
				}
			}
		}
		
		return $description;
	}
}