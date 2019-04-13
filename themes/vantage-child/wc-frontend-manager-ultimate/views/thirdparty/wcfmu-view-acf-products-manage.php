<?php
/**
 * WCFM plugin views
 *
 * Plugin Advanced Custom Fields(ACF) Products Manage Views
 *
 * @author 		WC Lovers
 * @package 	wcfmu/views/thirdparty
 * @version   3.0.4
 */
global $wp, $WCFM, $WCFMu;

if( !$wcfm_allow_acf_fields = apply_filters( 'wcfm_is_allow_acf_fields', true ) ) {
	return;
}

$product_id = 0;
$cg_product_id = 1;

if( isset( $wp->query_vars['wcfm-products-manage'] ) && !empty( $wp->query_vars['wcfm-products-manage'] ) ) {
	$product_id = $wp->query_vars['wcfm-products-manage'];
}

$filter = array( 
	'post_id'	=> $cg_product_id, 
	'post_type'	=> 'product' 
);
$product_group_ids = array();
$product_group_ids = apply_filters( 'acf/location/match_field_groups', $product_group_ids, $filter );
//print_r($product_group_ids);
//die;

// Getting Product Category specific field groups

$cat_group_id_map = array(); 
$cat_group_ids = array();
$product_categories   = get_terms( 'product_cat', 'orderby=name&hide_empty=0&parent=0' );
if( !empty( $product_categories ) ) {
	foreach ( $product_categories as $cat ) {
		$cat_group_id_map[$cat->term_id] = apply_filters('acf/location/match_field_groups', array(), array( 'post_id'	=> $cg_product_id, 'post_type'	=> 'product', 'post_category' => array( $cat->term_id ), 'taxonomy' => array( $cat->term_id ) ) );
		$cat_group_ids = array_merge( $cat_group_ids, $cat_group_id_map[$cat->term_id] );
		$product_child_categories   = get_terms( 'product_cat', 'orderby=name&hide_empty=0&parent=' . absint( $cat->term_id ) );
		if ( $product_child_categories ) {
			foreach ( $product_child_categories as $child_cat ) {
				$cat_group_id_map[$cat->term_id] = apply_filters('acf/location/match_field_groups', array(), array( 'post_id'	=> $cg_product_id, 'post_type'	=> 'product', 'post_category' => array( $child_cat->term_id ), 'taxonomy' => array( $child_cat->term_id ) ) );
				$cat_group_ids = array_merge( $cat_group_ids, $cat_group_id_map[$cat->term_id] );
			}
		}
	}
}
wp_localize_script( 'wcfmu_acf_products_manage_js', 'wcfm_cat_based_acf_fields', $cat_group_id_map );
$cat_group_ids = array_unique($cat_group_ids);
//print_r($cat_group_ids);
//print_r($cat_group_id_map);
//die;

$field_groups = apply_filters('acf/get_field_groups', array());
//print_r($field_groups);
//die;

if( !empty( $field_groups )) {
	foreach( $field_groups as $field_group_index => $field_group ) {
		
		if( !in_array( $field_group['id'], $product_group_ids ) && !in_array( $field_group['id'], $cat_group_ids ) ) continue;
		
		$cat_group_class = '';
		if( in_array( $field_group['id'], $cat_group_ids ) ) $cat_group_class = 'wcfm_cat_based_acf_product_manager_fields';
		if( in_array( $field_group['id'], $product_group_ids ) ) $cat_group_class = '';
    
    $wcfm_is_allowed_acf_field_group = apply_filters( 'wcfm_is_allowed_acf_field_group', true, $field_group['id'] );
    if( !$wcfm_is_allowed_acf_field_group ) continue;
    
    $field_group_fields = apply_filters('acf/field_group/get_fields', array(), $field_group['id']);
    //print_r($field_group_fields);
    
		if ( !empty( $field_group_fields ) ) { 
			?>
			<div class="page_collapsible wcfm_acf_products_manage_<?php echo $field_group['id']; ?>_collapsible <?php echo $cat_group_class; ?> simple variable external grouped booking" id="wcfm_products_manage_form_<?php echo sanitize_title($field_group['title']); ?>_head"><label class="fa fa-certificate"></label><?php echo $field_group['title']; ?><span></span></div>
			<div class="wcfm-container wcfm_acf_products_manage_<?php echo $field_group['id']; ?>_container <?php echo $cat_group_class; ?> simple variable external grouped booking">
				<div id="wcfm_products_manage_form_<?php echo sanitize_title($field_group['title']); ?>_expander" class="wcfm-content">
				  <h2><?php echo $field_group['title']; ?></h2>
				  <div class="wcfm_clearfix"></div>
				  <?php
				  if ( !empty( $field_group_fields ) ) {
				  	foreach( $field_group_fields as $field_group_field ) {
				  		$field_value = '';
				  		if( isset( $field_group_field['default_value'] ) ) $field_value = $field_group_field['default_value'];
				  		if( $product_id ) $field_value = get_post_meta( $product_id, $field_group_field['name'], true );
				  		
				  		// Is Required
				  		$custom_attributes = array();
				  		if( isset( $field_group_field['required'] ) && $field_group_field['required'] ) $custom_attributes = array( 'required' => 1 );
				  		
				  		// Hidden ACF Key field
				  		$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_group_field['key'] => array( 'name' => 'acf[_' . $field_group_field['name'] . ']', 'type' => 'hidden', 'value' => $field_group_field['key'] ) ) );
				  		
				  		switch( $field_group_field['type'] ) {
				  			case 'email':
								case 'text':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_group_field['name'] => array( 'label' => $field_group_field['label'], 'custom_attributes' => $custom_attributes, 'placeholder' => $field_group_field['placeholder'], 'hints' => $field_group_field['instructions'], 'name' => 'acf[' . $field_group_field['name'] . ']', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title', 'value' => $field_value ) ) );
								break;
								
								case 'number':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_group_field['name'] => array( 'label' => $field_group_field['label'], 'custom_attributes' => $custom_attributes, 'placeholder' => $field_group_field['placeholder'], 'hints' => $field_group_field['instructions'], 'name' => 'acf[' . $field_group_field['name'] . ']', 'type' => 'number', 'attributes' => array( 'min' => $field_group_field['min'], 'max' => $field_group_field['max'], 'step' => $field_group_field['step'] ), 'class' => 'wcfm-text wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title', 'value' => $field_value ) ) );
								break;
								
								case 'wysiwyg':
								case 'textarea':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_group_field['name'] => array( 'label' => $field_group_field['label'], 'custom_attributes' => $custom_attributes, 'placeholder' => isset( $field_group_field['placeholder'] ) ? $field_group_field['placeholder'] : '', 'hints' => $field_group_field['instructions'], 'name' => 'acf[' . $field_group_field['name'] . ']', 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title', 'value' => $field_value ) ) );
								break;
								
								case 'date_picker':
									$custom_attributes['date_format'] = $field_group_field['date_format'];
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_group_field['name'] => array( 'label' => $field_group_field['label'], 'custom_attributes' => $custom_attributes, 'placeholder' => $field_group_field['display_format'], 'hints' => $field_group_field['instructions'], 'name' => 'acf[' . $field_group_field['name'] . ']', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_datepicker simple variable external grouped booking', 'label_class' => 'wcfm_title', 'value' => $field_value ) ) );
								break;
								
								case 'true_false':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_group_field['name'] => array( 'label' => $field_group_field['label'], 'custom_attributes' => $custom_attributes, 'hints' => $field_group_field['instructions'], 'name' => 'acf[' . $field_group_field['name'] . ']', 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title checkbox-title', 'value' => '1', 'dfvalue' => $field_value ) ) );
								break;
								
								case 'checkbox':
									if( $field_value && !is_array( $field_value ) ) $field_value = array($field_value);
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_group_field['name'] => array( 'label' => $field_group_field['label'], 'custom_attributes' => $custom_attributes, 'hints' => $field_group_field['instructions'] , 'name' => 'acf[' . $field_group_field['name'] . ']', 'type' => 'checklist', 'class' => 'wcfm-checkbox wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title', 'options' => $field_group_field['choices'], 'value' => $field_value ) ) );
							  break;
							  
								case 'radio':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_group_field['name'] => array( 'label' => $field_group_field['label'], 'custom_attributes' => $custom_attributes, 'hints' => $field_group_field['instructions'] , 'name' => 'acf[' . $field_group_field['name'] . ']', 'type' => 'radio', 'class' => 'wcfm-radio wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title', 'options' => $field_group_field['choices'], 'value' => $field_value ) ) );
								break;
								
								case 'select':
									$field_group_field['choices'] = array_merge( array( '' => __( '-Select-', 'wc-frontend-manager-ultimate' ) ), (array) $field_group_field['choices'] );
									if( isset( $field_group_field['multiple'] ) && ( $field_group_field['multiple'] == 1 ) ) {
										$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_group_field['name'] => array( 'label' => $field_group_field['label'], 'custom_attributes' => $custom_attributes, 'hints' => $field_group_field['instructions'] , 'name' => 'acf[' . $field_group_field['name'] . ']', 'type' => 'select', 'class' => 'wcfm-select wcfm-acf-multi-select wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title', 'attributes' => array( 'multiple' => 'multiple', 'style' => 'width: 60%;' ), 'options' => $field_group_field['choices'], 'value' => $field_value ) ) );
									} else {
										$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_group_field['name'] => array( 'label' => $field_group_field['label'], 'custom_attributes' => $custom_attributes, 'hints' => $field_group_field['instructions'] , 'name' => 'acf[' . $field_group_field['name'] . ']', 'type' => 'select', 'class' => 'wcfm-select wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title', 'options' => $field_group_field['choices'], 'value' => $field_value ) ) );
									}
								break;
								
								case 'image':
									if( $product_id && $field_value ) $field_value = wp_get_attachment_url( $field_value );
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_group_field['name'] => array( 'label' => $field_group_field['label'], 'custom_attributes' => $custom_attributes, 'hints' => $field_group_field['instructions'], 'name' => 'acf[' . $field_group_field['name'] . ']', 'type' => 'upload', 'class' => 'wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title', 'value' => $field_value ) ) );
								break;
								
								case 'file':
									if( $product_id && $field_value ) $field_value = wp_get_attachment_url( $field_value );
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_group_field['name'] => array( 'label' => $field_group_field['label'], 'custom_attributes' => $custom_attributes, 'hints' => $field_group_field['instructions'], 'name' => 'acf[' . $field_group_field['name'] . ']', 'type' => 'upload', 'mime' => 'Uploads', 'class' => 'wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title', 'value' => $field_value ) ) );
								break;
							}
				  	}
				  }
				  ?>
				</div>
			</div>
			<?php
		}
	}
}


?>