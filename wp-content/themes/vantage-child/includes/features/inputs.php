<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function artmo_country_input($field) {

  global $country_arr;

  $field['class'] 		= isset( $field['class'] ) ? $field['class'] : '';
  $field['value'] 		= isset( $field['value'] ) ? $field['value'] : '';
  $field['name'] 			= isset( $field['name'] ) ? $field['name'] : $field['id'];
  $field['placeholder_value'] = isset( $field['placeholder_value'] ) ? $field['placeholder_value'] : '--Select Country--';

  // Custom attribute handling
  $custom_attributes = array();

  if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
    foreach ( $field['custom_attributes'] as $attribute => $value ) {
      $custom_attributes[] = 'data-' . esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';

      // Required Option
      if( $attribute == 'required' ) {
        if( !isset( $field['custom_attributes']['required_message'] ) ) {
          $custom_attributes[] = 'data-required_message="' . esc_attr( $field['label'] ) . ': ' . __( 'This field is required.', 'wc-frontend-manager' ) . '"';
        }
        $field['label'] .= '<span class="required">*</span>';
      }
    }
  }

  // attribute handling
  $attributes = array();
  $is_multiple = false;
  if ( ! empty( $field['attributes'] ) && is_array( $field['attributes'] ) ) {
    foreach ( $field['attributes'] as $attribute => $value ) {
      $attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
    }
  }

  $options = '<option value="">' . __( $field['placeholder_value'], 'wc-frontend-manager' ) . '</option><optgroup label="-------------------------------------">';
  foreach ( $country_arr as $key => $value ) {
    if( $is_multiple || is_array( $field['value'] ) ) {
      $options .=  '<option value="' . esc_attr( $key ) . '"' . selected( in_array( $key, (array)$field['value'] ), true, false ) . '>' . esc_html( $value ) . '</option>';
    } else {
      $options .= '<option value="' . esc_attr( $key ) . '" ' . selected( esc_attr( $field['value'] ), esc_attr( $key ), false ) . '>' . esc_html($value) . '</option>';
    }
  }
  $options .= '</optgroup>';


  printf(
      '<select id="%s" name="%s" class="country_select %s" %s %s>%s</select>',
      esc_attr($field['id']),
      esc_attr($field['name']),
      esc_attr($field['class']),
      implode( ' ', $custom_attributes ),
      implode( ' ', $attributes ),
      $options
  );

}
