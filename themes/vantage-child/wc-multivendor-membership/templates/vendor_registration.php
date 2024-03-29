<?php
/**
 * WCFM plugin view
 *
 * WCFMgs Memberships Template
 *
 * @author 		WC Lovers
 * @package 	wcfmvm/templates
 * @version   1.0.0
 */

global $WCFM, $WCFMvm;

$user_id = 0;
$user_name = '';
$user_email = '';
$first_name = '';
$last_name = '';
$store_name = '';
$wcfmvm_static_infos = array();
$wcfmvm_custom_infos = array();

if( is_user_logged_in() ) {
	$user_id = get_current_user_id();
	$current_user = get_userdata( $user_id );
	// Fetching User Data
	if( $current_user && !empty( $current_user ) ) {
		$user_name  = $current_user->user_login;
		$user_email = $current_user->user_email;
		$first_name = $current_user->first_name;
		$last_name  = $current_user->last_name;
		$store_name = get_user_meta( $user_id, 'store_name', true );
		$wcfmvm_static_infos = (array) get_user_meta( $user_id, 'wcfmvm_static_infos', true );
		$wcfmvm_custom_infos = (array) get_user_meta( $user_id, 'wcfmvm_custom_infos', true );
	}
}

if( apply_filters( 'wcfm_is_allow_email_verification', true ) ) {
	$wcfm_membership_options = get_option( 'wcfm_membership_options', array() );
	$membership_type_settings = array();
	if( isset( $wcfm_membership_options['membership_type_settings'] ) ) $membership_type_settings = $wcfm_membership_options['membership_type_settings'];
	$email_verification = isset( $membership_type_settings['email_verification'] ) ? 'yes' : '';
}

$wcfmvm_registration_static_fields = get_option( 'wcfmvm_registration_static_fields', array() );

$wcfmvm_registration_custom_fields = get_option( 'wcfmvm_registration_custom_fields', array() );

?>

<div id="wcfm_membership_container">
  <form id="wcfm_membership_registration_form" class="wcfm">
		<div class="wcfm-container">
	    <div id="wcfm_membership_registration_form_expander" class="wcfm-content">
				<?php
				do_action( 'begin_wcfm_membership_registration_form' );
				 if( $user_id ) {
				 	 $registratio_user_fields = array( 
																						"user_name" => array( 'label' => __('Username', 'wc-multivendor-membership') , 'type' => 'text', 'custom_attributes' => array( 'required' => 1 ), 'attributes' => array( 'readonly' => true ), 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_name ),
																						"user_email" => array( 'label' => __('Email', 'wc-multivendor-membership') , 'type' => 'text', 'custom_attributes' => array( 'required' => 1 ), 'attributes' => array( 'readonly' => true ), 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_email ),
																						"member_id" => array( 'type' => 'hidden', 'value' => $user_id )
																						);
					} else {
						$registratio_user_fields = array( 
																							"user_name" => array( 'label' => __('Username', 'wc-multivendor-membership') , 'type' => 'text', 'custom_attributes' => array( 'required' => 1 ), 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_name ),
																							"user_email" => array( 'label' => __('Email', 'wc-multivendor-membership') , 'type' => 'text', 'custom_attributes' => array( 'required' => 1 ), 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_email )
																							);
					}
					
					$is_user_name = isset( $wcfmvm_registration_static_fields['user_name'] ) ? 'yes' : '';
					if( !$is_user_name ) unset( $registratio_user_fields['user_name'] );
					
					$WCFM->wcfm_fields->wcfm_generate_form_field( $registratio_user_fields );
					
					if( apply_filters( 'wcfm_is_allow_email_verification', true ) ) {
						if( $email_verification ) {
							$email_verified = false;
							if( $user_id ) {
								$email_verified = get_user_meta( $user_id, '_wcfm_email_verified', true );
								$wcfm_email_verified_for = get_user_meta( $user_id, '_wcfm_email_verified_for', true );
								if( $user_email != $wcfm_email_verified_for ) $email_verified = false;
							}
							
							if( $user_id && $email_verified ) {
								?>
								<div class="wcfm_email_verified">
									<span class="fa fa-envelope wcfm_email_verified_icon">
										<span class="wcfm_email_verified_text"><?php _e( 'Email already verified', 'wc-multivendor-membership' ); ?></span>
										<input type="hidden" name="email_verified" value="true" />
									</span>
								</div>
								<div class="wcfm_clearfix"></div>
								<?php
							} else {
								?>
								<div class="wcfm_email_verified">
									<input type="number" name="wcfm_email_verified_input" data-required="1" data-required_message="<?php _e( 'Email Verification Code: ', 'wc-multivendor-membership' ) . _e( 'This field is required.', 'wc-frontend-manager' ); ?>" class="wcfm-text wcfm_email_verified_input" placeholder="<?php _e( 'Verification Code', 'wc-multivendor-membership' ); ?>" value="" />
									<input type="button" name="wcfm_email_verified_button" class="wcfm-text wcfm_submit_button wcfm_email_verified_button" value="<?php _e( 'Re-send Code', 'wc-multivendor-membership' ); ?>" />
								</div>
								<div class="wcfm_clearfix"></div>
								<?php
							}
						}
					}
					
					$is_first_name = isset( $wcfmvm_registration_static_fields['first_name'] ) ? 'yes' : '';
					$is_last_name = isset( $wcfmvm_registration_static_fields['last_name'] ) ? 'yes' : '';
					
					$registration_name_fields = apply_filters( 'wcfm_membership_registration_fields', array(  
																																									"first_name" => array( 'label' => __('First Name', 'wc-multivendor-membership') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $first_name ),
																																									"last_name" => array( 'label' => __('Last Name', 'wc-multivendor-membership') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $last_name ),
																																									"store_name" => array( 'label' => __('Store Name', 'wc-multivendor-membership') , 'type' => 'text', 'custom_attributes' => array( 'required' => 1 ), 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $store_name ),
																																	   ) );
					
					if( !$is_first_name ) unset( $registration_name_fields['first_name'] );
					if( !$is_last_name ) unset( $registration_name_fields['last_name'] );
										
					$WCFM->wcfm_fields->wcfm_generate_form_field( $registration_name_fields );
					
					// Registration Static Field Support - 1.0.6
					$terms = '';
					$terms_page = '';
					if( !empty( $wcfmvm_registration_static_fields ) ) {
						foreach( $wcfmvm_registration_static_fields as $wcfmvm_registration_static_field => $wcfmvm_registration_static_field_val ) {
							$field_value = array();
							$field_name = 'wcfmvm_static_infos[' . $wcfmvm_registration_static_field . ']';
							
							if( !empty( $wcfmvm_static_infos ) ) {
								$field_value = isset( $wcfmvm_static_infos[$wcfmvm_registration_static_field] ) ? $wcfmvm_static_infos[$wcfmvm_registration_static_field] : array();
							} elseif( $user_id ) {
								$billing_address_fields = array( 	
																						'billing_address_1'  => 'addr_1',
																						'billing_address_2'  => 'addr_2',
																						'billing_country'    => 'country',
																						'billing_city'       => 'city',
																						'billing_state'      => 'state',
																						'billing_postcode'   => 'zip',
																					);
			
								foreach( $billing_address_fields as $billing_address_field_key => $billing_address_field ) {
									$field_value[$billing_address_field] = get_user_meta( $user_id, $billing_address_field_key, true );
								}
							}
							
							switch( $wcfmvm_registration_static_field ) {
							  case 'address':
								  $WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_membership_registration_fields_address', array(
																																			"addr_1" => array('label' => __('Address 1', 'wc-frontend-manager') , 'type' => 'text', 'name' => $field_name . '[addr_1]', 'custom_attributes' => array( 'required' => 1 ), 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => isset($field_value['addr_1']) ? $field_value['addr_1'] : '' ),
																																			"addr_2" => array('label' => __('Address 2', 'wc-frontend-manager') , 'type' => 'text', 'name' => $field_name . '[addr_2]', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => isset($field_value['addr_2']) ? $field_value['addr_2'] : '' ),
																																			"country" => array('label' => __('Country', 'wc-frontend-manager') , 'type' => 'country', 'name' => $field_name . '[country]', 'custom_attributes' => array( 'required' => 1 ), 'class' => 'wcfm-select wcfm_ele wcfmvm_country_to_select', 'label_class' => 'wcfm_title wcfm_ele', 'attributes' => array( 'style' => 'width: 60%;' ), 'value' => isset($field_value['country']) ? $field_value['country'] : '' ),
																																			"zip" => array('label' => __('Postcode/Zip', 'wc-frontend-manager') , 'type' => 'text', 'name' => $field_name . '[zip]', 'custom_attributes' => array( 'required' => 1 ), 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => isset($field_value['zip']) ? $field_value['zip'] : '' ),
																																			"city" => array('label' => __('City/Town', 'wc-frontend-manager') , 'type' => 'text', 'name' => $field_name . '[city]', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => isset($field_value['city']) ? $field_value['city'] : '' ),
																																			"state" => array('label' => __('State/County', 'wc-frontend-manager') , 'type' => 'select', 'name' => $field_name . '[state]', 'class' => 'wcfm-select wcfm_ele wcfmvm_state_to_select', 'label_class' => 'wcfm_title wcfm_ele', 'options' => isset($field_value['state']) ? array($field_value['state'] => $field_value['state']) : array(), 'value' => isset($field_value['state']) ? $field_value['state'] : '' ),
																																			) ) );
								break;
								
								case 'phone':
									if( is_array( $field_value ) ) $field_value = '';
									if( !$field_value && $user_id ) {
										$field_value = get_user_meta( $user_id, 'billing_phone', true );
									}
								  $WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_membership_registration_fields_phone', array(
																																			"phone" => array('label' => __('Store Phone', 'wc-frontend-manager') , 'type' => 'text', 'name' => $field_name, 'custom_attributes' => array( 'required' => 1 ), 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $field_value ),
																																			) ) );
								break;
								
								case 'terms':
									$terms = 'active';
								break;
								
								case 'terms_page':
									$terms_page = $wcfmvm_registration_static_field_val;
								break;
								
								default:
									do_action( 'wcfmvm_registration_static_field_show', $wcfmvm_registration_static_field, $field_name, $field_value );
								break;
							}
						}
					}
					
					
					// Registration Custom Field Support - 1.0.5
					if( !empty( $wcfmvm_registration_custom_fields ) ) {
						foreach( $wcfmvm_registration_custom_fields as $wcfmvm_registration_custom_field ) {
							if( !isset( $wcfmvm_registration_custom_field['enable'] ) ) continue;
							if( !$wcfmvm_registration_custom_field['label'] ) continue;
							$field_value = '';
							$wcfmvm_registration_custom_field['name'] = sanitize_title( $wcfmvm_registration_custom_field['label'] );
							$field_name = 'wcfmvm_custom_infos[' . $wcfmvm_registration_custom_field['name'] . ']';
						
							if( !empty( $wcfmvm_custom_infos ) ) {
								if( $wcfmvm_registration_custom_field['type'] == 'checkbox' ) {
									$field_value = isset( $wcfmvm_custom_infos[$wcfmvm_registration_custom_field['name']] ) ? $wcfmvm_custom_infos[$wcfmvm_registration_custom_field['name']] : 'no';
								} else {
									$field_value = isset( $wcfmvm_custom_infos[$wcfmvm_registration_custom_field['name']] ) ? $wcfmvm_custom_infos[$wcfmvm_registration_custom_field['name']] : '';
								}
							}
							
							// Is Required
							$custom_attributes = array();
							if( isset( $wcfmvm_registration_custom_field['required'] ) && $wcfmvm_registration_custom_field['required'] ) $custom_attributes = array( 'required' => 1 );
								
							switch( $wcfmvm_registration_custom_field['type'] ) {
								case 'text':
								case 'upload':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfmvm_registration_custom_field['name'] => array( 'label' => __($wcfmvm_registration_custom_field['label'], 'wc-multivendor-membership') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'text', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __($wcfmvm_registration_custom_field['help_text'], 'wc-multivendor-membership') ) ) );
								break;
								
								case 'number':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfmvm_registration_custom_field['name'] => array( 'label' => __($wcfmvm_registration_custom_field['label'], 'wc-multivendor-membership') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'number', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __($wcfmvm_registration_custom_field['help_text'], 'wc-multivendor-membership') ) ) );
								break;
								
								case 'textarea':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfmvm_registration_custom_field['name'] => array( 'label' => __($wcfmvm_registration_custom_field['label'], 'wc-multivendor-membership') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'textarea', 'class' => 'wcfm-textarea', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __($wcfmvm_registration_custom_field['help_text'], 'wc-multivendor-membership') ) ) );
								break;
								
								case 'datepicker':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfmvm_registration_custom_field['name'] => array( 'label' => __($wcfmvm_registration_custom_field['label'], 'wc-multivendor-membership') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __($wcfmvm_registration_custom_field['help_text'], 'wc-multivendor-membership') ) ) );
								break;
								
								case 'timepicker':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfmvm_registration_custom_field['name'] => array( 'label' => __($wcfmvm_registration_custom_field['label'], 'wc-multivendor-membership') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'time', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __($wcfmvm_registration_custom_field['help_text'], 'wc-multivendor-membership') ) ) );
								break;
								
								case 'checkbox':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfmvm_registration_custom_field['name'] => array( 'label' => __($wcfmvm_registration_custom_field['label'], 'wc-multivendor-membership') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox-title', 'value' => 'yes', 'dfvalue' => $field_value, 'hints' => __($wcfmvm_registration_custom_field['help_text'], 'wc-multivendor-membership') ) ) );
								break;
								
								case 'upload':
									//$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfmvm_registration_custom_field['name'] => array( 'label' => __($wcfmvm_registration_custom_field['label'], 'wc-multivendor-membership') , 'name' => $field_name, 'type' => 'upload', 'class' => 'wcfm_ele', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => $wcfmvm_registration_custom_field['help_text'] ) ) );
								break;
								
								case 'select':
									$select_opt_vals = array();
									$select_options = explode( '|', $wcfmvm_registration_custom_field['options'] );
									if( !empty ( $select_options ) ) {
										foreach( $select_options as $select_option ) {
											if( $select_option ) {
												$select_opt_vals[$select_option] = __(ucfirst( str_replace( "-", " " , $select_option ) ), 'wc-multivendor-membership');
											}
										}
									}
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfmvm_registration_custom_field['name'] => array( 'label' => __($wcfmvm_registration_custom_field['label'], 'wc-multivendor-membership') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'select', 'class' => 'wcfm-select', 'label_class' => 'wcfm_title', 'options' => $select_opt_vals, 'value' => $field_value, 'hints' => __($wcfmvm_registration_custom_field['help_text'], 'wc-multivendor-membership') ) ) );
								break;
							}
						}
					}
					
					if( !$user_id ) {
						$WCFM->wcfm_fields->wcfm_generate_form_field(  array( 
																																	"passoword" => array( 'label' => __('Password', 'wc-multivendor-membership') , 'type' => 'password', 'custom_attributes' => array( 'required' => 1, 'mismatch_message' => __( 'Password and Confirm-password are not same.', 'wc-multivendor-membership' ) ), 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => '' ),
																																	"confirm_pwd" => array( 'label' => __('Confirm Password', 'wc-multivendor-membership') , 'type' => 'password', 'custom_attributes' => array( 'required' => 1 ), 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => '' ),
																																) ) ;
					}
					
					do_action( 'end_wcfm_membership_registration_form' );
					
					// Terms & Conditions support add - 1.1.9
					if( $terms == 'active' ) {
						?>
						<input type="checkbox" id="terms" name="wcfmvm_static_infos[terms]" class="wcfm-checkbox" value="<?php _e( 'Agree', 'wc-multivendor-membership' ); ?>" data-required="1" data-required_message="<?php _e( 'Terms & Conditions', 'wc-multivendor-membership' ); ?>: <?php _e( 'This field is required.', 'wc-frontend-manager' ); ?>">
						<p class="terms_title wcfm_title">
							<strong>
								<span class="required">*</span>
								<?php 
								_e( 'Agree', 'wc-multivendor-membership' );
								echo '&nbsp;&nbsp;';
								if( $terms_page ) {
									?><a target="_blank" href="<?php echo get_permalink( $terms_page ); ?>"><?php _e( 'Terms & Conditions', 'wc-multivendor-membership' ); ?></a><?php
								} else {
									_e( 'Terms & Conditions', 'wc-multivendor-membership' );
								}
								?>
							</strong>
						</p>
						<?php
					}
				?>
			</div>
			<div class="wcfm-clearfix"></div>
		</div>
		
		<?php if ( function_exists( 'gglcptch_init' ) ) { ?>
			<div class="wcfm_clearfix"></div>
			<div class="wcfm_gglcptch_wrapper" style="float:right;">
				<?php echo apply_filters( 'gglcptch_display_recaptcha', '', 'wcfm_registration_form' ); ?>
			</div>
	  <?php } elseif ( class_exists( 'anr_captcha_class' ) && function_exists( 'anr_captcha_form_field' ) ) { ?>
			<div class="wcfm_clearfix"></div>
			<div class="wcfm_gglcptch_wrapper" style="float:right;">
				<?php do_action( 'anr_captcha_form_field' ); ?>
			</div>
		<?php } ?>
		<div class="wcfm-clearfix"></div>
		<div class="wcfm-message" tabindex="-1"></div>
			
		<div id="wcfm_membership_registration_submit" class="wcfm_form_simple_submit_wrapper">
		  <?php if( wcfm_is_allowed_membership() ) { ?>
			  <input type="submit" name="save-data" value="<?php if( $user_id ) { _e( 'Confirm', 'wc-multivendor-membership' ); } else { _e( 'Register', 'wc-multivendor-membership' ); } ?>" id="wcfm_membership_register_button" class="wcfm_submit_button" />
			  <?php if( is_wcfm_membership_page() ) { ?>
			  <a href="<?php echo apply_filters( 'wcfm_change_membership_url', get_wcfm_membership_url() ); ?>" class="wcfm_submit_button"><<&nbsp;<?php _e( 'Plans', 'wc-multivendor-membership' ); ?><a/>
			  <?php } ?>
			<?php } else { ?>
				<?php _e( 'Your user role not allowed to subscription!', 'wc-multivendor-membership' ); ?>
			<?php } ?>
		</div>
		<div class="wcfm-clearfix"></div>
	</form>
</div>