<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Memberships Registration Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmvm/controllers
 * @version   1.0.0
 */

class WCFMvm_Memberships_Registration_Controller {

	public function __construct() {
		global $WCFM, $WCFMu;

		$this->processing();
	}

	public function processing() {
		global $WCFM, $WCFMvm, $wpdb, $wcfm_membership_registration_form_data;

		$wcfm_membership_registration_form_data = array();
	  parse_str($_POST['wcfm_membership_registration_form'], $wcfm_membership_registration_form_data);

	  $wcfm_membership_registration_messages = get_wcfmvm_membership_registration_messages();
	  $has_error = false;
	  $subscription_pay_mode = 'by_wcfm';

	  // Google reCaptcha support
	  if ( function_exists( 'gglcptch_init' ) ) {
			if(isset($wcfm_membership_registration_form_data['g-recaptcha-response']) && !empty($wcfm_membership_registration_form_data['g-recaptcha-response'])) {
				$_POST['g-recaptcha-response'] = $wcfm_membership_registration_form_data['g-recaptcha-response'];
			}
			$check_result = apply_filters( 'gglcptch_verify_recaptcha', true, 'string', 'wcfm_registration_form' );
			if ( true === $check_result ) {
					/* do necessary action */
			} else {
				echo '{"status": false, "message": "' . $check_result . '"}';
				die;
			}
		} elseif ( function_exists( 'anr_captcha_form_field' ) ) {
			$check_result = anr_verify_captcha( $wcfm_membership_registration_form_data['g-recaptcha-response'] );
			if ( true === $check_result ) {
					/* do necessary action */
			} else {
				echo '{"status": false, "message": "' . __( 'Captcha failed, please try again.', 'wc-frontend-manager' ) . '"}';
				die;
			}
		}

		if ( empty( $wcfm_membership_registration_form_data['user_email'] ) || ! is_email( $wcfm_membership_registration_form_data['user_email'] ) ) {
			echo '{"status": false, "message": "' . __( 'Please provide a valid email address.', 'woocommerce' ) . '"}';
			die;
		}

		$wcfmvm_registration_static_fields = get_option( 'wcfmvm_registration_static_fields', array() );
		$is_user_name = isset( $wcfmvm_registration_static_fields['user_name'] ) ? 'yes' : '';
		if( !$is_user_name ) {
			$user_email = sanitize_email( $wcfm_membership_registration_form_data['user_email'] );

			$username   = sanitize_user( current( explode( '@', $user_email ) ), true );

			$append     = 1;
			$o_username = $username;

			while ( username_exists( $username ) ) {
				$username = $o_username . $append;
				$append++;
			}
			$wcfm_membership_registration_form_data['user_name'] = $username;
		}

		if ( empty( $wcfm_membership_registration_form_data['user_name'] ) || ! validate_username( $wcfm_membership_registration_form_data['user_name'] ) ) {
			echo '{"status": false, "message": "' . __( 'Please enter a valid account username.', 'woocommerce' ) . '"}';
			die;
		}

	  if(isset($wcfm_membership_registration_form_data['user_name']) && !empty($wcfm_membership_registration_form_data['user_name'])) {
	  	if(isset($wcfm_membership_registration_form_data['user_email']) && !empty($wcfm_membership_registration_form_data['user_email'])) {
				$member_id = 0;
				$password = wp_generate_password( $length = 12, $include_standard_special_chars=false );
				$is_update = false;
				if( isset($wcfm_membership_registration_form_data['member_id']) && $wcfm_membership_registration_form_data['member_id'] != 0 ) {
					$member_id = absint( $wcfm_membership_registration_form_data['member_id'] );
					$is_update = true;
				} else {
					if( username_exists( $wcfm_membership_registration_form_data['user_name'] ) ) {
						$has_error = true;
						echo '{"status": false, "message": "' . $wcfm_membership_registration_messages['username_exists'] . '"}';
					} else {
						if( email_exists( $wcfm_membership_registration_form_data['user_email'] ) == false ) {

						} else {
							$has_error = true;
							echo '{"status": false, "message": "' . $wcfm_membership_registration_messages['email_exists'] . '"}';
						}
					}
					$password = $wcfm_membership_registration_form_data['passoword'];
				}

				if( apply_filters( 'wcfm_is_allow_email_verification', true ) ) {
					$email_verified = false;
					if( !$has_error ) {
						$wcfm_membership_options = get_option( 'wcfm_membership_options', array() );
						$membership_type_settings = array();
						if( isset( $wcfm_membership_options['membership_type_settings'] ) ) $membership_type_settings = $wcfm_membership_options['membership_type_settings'];
						$email_verification = isset( $membership_type_settings['email_verification'] ) ? 'yes' : '';

						if( $email_verification ) {
							if( $is_update ) {
								$email_verified = $wcfm_membership_registration_form_data['email_verified'];
							}

							if( !$is_update || !$email_verified ) {
								$verification_code = '';
								if( isset( $_SESSION['wcfm_membership'] ) && isset( $_SESSION['wcfm_membership']['email_verification_code'] ) ) {
									$verification_code = $_SESSION['wcfm_membership']['email_verification_code'];
								}
								$wcfm_email_verified_input = $wcfm_membership_registration_form_data['wcfm_email_verified_input'];

								if( $verification_code != $wcfm_email_verified_input ) {
									$has_error = true;
									echo '{"status": false, "message": "' . $wcfm_membership_registration_messages['email_invalid_code'] . '"}';
								}

								if( !$has_error ) {
									$verification_email = '';
									if( isset( $_SESSION['wcfm_membership'] ) && isset( $_SESSION['wcfm_membership']['email_verification_for'] ) ) {
										$verification_email = $_SESSION['wcfm_membership']['email_verification_for'];
									}
									$wcfm_email_verified_for = $wcfm_membership_registration_form_data['user_email'];

									if( $verification_email != $wcfm_email_verified_for ) {
										$has_error = true;
										echo '{"status": false, "message": "' . $wcfm_membership_registration_messages['email_invalid_code'] . '"}';
									}
								}
							}
							if( !$has_error ) $email_verified = true;
						}
					}
				}

				if( !$has_error ) {
					$user_data = array( 'user_login'     => $wcfm_membership_registration_form_data['user_name'],
															'user_email'     => sanitize_email( $wcfm_membership_registration_form_data['user_email'] ),
															'display_name'   => sanitize_user( $wcfm_membership_registration_form_data['user_name'] ),
															'nickname'       => $wcfm_membership_registration_form_data['user_name'],
															'first_name'     => isset($wcfm_membership_registration_form_data['first_name']) ? $wcfm_membership_registration_form_data['first_name'] : '',
															'last_name'      => isset($wcfm_membership_registration_form_data['last_name']) ? $wcfm_membership_registration_form_data['last_name'] : '',
															'user_pass'      => $password,
															'role'           => apply_filters('wcfmvm_register_as_role', 'um_artist'),
															'ID'             => $member_id
															);
					if( $is_update ) {
						unset( $user_data['user_login'] );
						unset( $user_data['display_name'] );
						unset( $user_data['nickname'] );
						unset( $user_data['user_pass'] );
						unset( $user_data['role'] );
						unset( $user_data['first_name'] );
						unset( $user_data['last_name'] );
						$member_id = wp_update_user( $user_data ) ;
					} else {
						$member_id = wp_insert_user( $user_data ) ;
					}

					if( !$member_id ) {
						$has_error = true;
					} else {
						/*if( !$is_update ) {
						  if( !defined( 'DOING_WCFM_EMAIL' ) )
							  define( 'DOING_WCFM_EMAIL', true );

							// Sending Mail to new user
							$mail_to = $wcfm_membership_registration_form_data['user_email'];
							$new_account_mail_subject = "{site_name}: New Account Created";
							$new_account_mail_body = __( 'Dear', 'wc-frontend-membership_registration-vendor-membership' ) . ' {first_name}' .
																			 ',<br/><br/>' .
																			 __( 'Your account has been created as {user_role}. Follow the bellow details to log into the system', 'wc-frontend-membership_registration-vendor-membership' ) .
																			 '<br/><br/>' .
																			 __( 'Site', 'wc-frontend-membership_registration-vendor-membership' ) . ': {site_url}' .
																			 '<br/>' .
																			 __( 'Login', 'wc-frontend-membership_registration-vendor-membership' ) . ': {username}' .
																			 '<br/>' .
																			 __( 'Password', 'wc-frontend-membership_registration-vendor-membership' ) . ': {password}' .
																			 '<br /><br/>Thank You';

							$wcfmgs_new_account_mail_subject = get_option( 'wcfmgs_new_account_mail_subject' );
							if( $wcfmgs_new_account_mail_subject ) $new_account_mail_subject =  $wcfmgs_new_account_mail_subject;
							$wcfmgs_new_account_mail_body = get_option( 'wcfmgs_new_account_mail_body' );
							if( $wcfmgs_new_account_mail_body ) $new_account_mail_body =  $wcfmgs_new_account_mail_body;

							$subject = str_replace( '{site_name}', get_bloginfo( 'name' ), $new_account_mail_subject );
							$message = str_replace( '{site_url}', get_bloginfo( 'url' ), $new_account_mail_body );
							$message = str_replace( '{first_name}', $wcfm_membership_registration_form_data['first_name'], $message );
							$message = str_replace( '{username}', $wcfm_membership_registration_form_data['user_name'], $message );
							$message = str_replace( '{password}', $password, $message );
							$message = str_replace( '{user_role}', 'Shop Membership_registration', $message );
							$message = apply_filters( 'wcfm_email_content_wrapper', $message, __( 'New Account', 'wc-frontend-manager' ) );

							wp_mail( $mail_to, $subject, $message );
						}*/

						// Update First Name as Billing & Shipping First Name
						if( isset( $wcfm_membership_registration_form_data['first_name'] ) ) {
							update_user_meta( $member_id, 'billing_first_name', $wcfm_membership_registration_form_data['first_name'] );
							update_user_meta( $member_id, 'shipping_first_name', $wcfm_membership_registration_form_data['first_name'] );
						}

						// Update Last Name as Billing & Shipping Last Name
						if( isset( $wcfm_membership_registration_form_data['last_name'] ) ) {
							update_user_meta( $member_id, 'billing_last_name', $wcfm_membership_registration_form_data['last_name'] );
							update_user_meta( $member_id, 'shipping_last_name', $wcfm_membership_registration_form_data['last_name'] );
						}

						// Update Store Address as Billing & Shipping Address
						$wcfmvm_registration_static_fields = get_option( 'wcfmvm_registration_static_fields', array() );
						if( !empty( $wcfmvm_registration_static_fields ) && isset( $wcfm_membership_registration_form_data['wcfmvm_static_infos'] ) && !empty( $wcfm_membership_registration_form_data['wcfmvm_static_infos'] ) ) {
							foreach( $wcfmvm_registration_static_fields as $wcfmvm_registration_static_field => $wcfmvm_registration_static_field_val ) {
								if( !empty( $wcfm_membership_registration_form_data['wcfmvm_static_infos'] ) ) {
									$field_value = isset( $wcfm_membership_registration_form_data['wcfmvm_static_infos'][$wcfmvm_registration_static_field] ) ? $wcfm_membership_registration_form_data['wcfmvm_static_infos'][$wcfmvm_registration_static_field] : array();
								}

								switch( $wcfmvm_registration_static_field ) {
									case 'address':
										if( isset($field_value['addr_1']) ) {
											$billing_address_fields = array(
																						'billing_address_1'  => 'addr_1',
																						'billing_address_2'  => 'addr_2',
																						'billing_country'    => 'country',
																						'billing_city'       => 'city',
																						'billing_state'      => 'state',
																						'billing_postcode'   => 'zip',
																					);

											foreach( $billing_address_fields as $billing_address_field_key => $billing_address_field ) {
												if( isset( $field_value[$billing_address_field] ) ) {
													update_user_meta( $member_id, $billing_address_field_key, $field_value[$billing_address_field] );
												}
											}

											$shipping_address_fields = array(
																						'shipping_address_1'  => 'addr_1',
																						'shipping_address_2'  => 'addr_2',
																						'shipping_country'    => 'country',
																						'shipping_city'       => 'city',
																						'shipping_state'      => 'state',
																						'shipping_postcode'   => 'zip',
																					);

											foreach( $shipping_address_fields as $shipping_address_field_key => $shipping_address_field ) {
												if( isset( $field_value[$shipping_address_field] ) ) {
													update_user_meta( $member_id, $shipping_address_field_key, $field_value[$shipping_address_field] );
												}
											}
										}
									break;

									case 'phone':
										update_user_meta( $member_id, 'billing_phone', $field_value );
									break;
								}
							}
						}

						// Update Store name
						if( isset( $wcfm_membership_registration_form_data['store_name'] ) && !empty( $wcfm_membership_registration_form_data['store_name'] ) ) {
							update_user_meta( $member_id, 'store_name', $wcfm_membership_registration_form_data['store_name'] );
						}

						// Update User Membership
						if( isset( $_SESSION['wcfm_membership'] ) && isset( $_SESSION['wcfm_membership']['membership'] ) && $_SESSION['wcfm_membership']['membership'] ) {
							$wcfm_membership = $_SESSION['wcfm_membership']['membership'];
							update_user_meta( $member_id, 'temp_wcfm_membership', $wcfm_membership );
						} elseif( $wcfm_membership = get_wcfm_free_membership() ) {
							update_user_meta( $member_id, 'temp_wcfm_membership', $wcfm_membership );
						}

						// Update Static Infos - 1.0.6
						if( isset( $wcfm_membership_registration_form_data['wcfmvm_static_infos'] ) ) {
							update_user_meta( $member_id, 'wcfmvm_static_infos', $wcfm_membership_registration_form_data['wcfmvm_static_infos'] );
						}

						// Update Additional Infos - 1.0.5
						if( isset( $wcfm_membership_registration_form_data['wcfmvm_custom_infos'] ) ) {
							update_user_meta( $member_id, 'wcfmvm_custom_infos', $wcfm_membership_registration_form_data['wcfmvm_custom_infos'] );

							// Toolset User Fields Compatibility added
							$wcfmvm_registration_custom_fields = get_option( 'wcfmvm_registration_custom_fields', array() );
							$wcfmvm_custom_infos = (array) $wcfm_membership_registration_form_data['wcfmvm_custom_infos'];

							if( !empty( $wcfmvm_registration_custom_fields ) ) {
								foreach( $wcfmvm_registration_custom_fields as $wcfmvm_registration_custom_field ) {
									if( !isset( $wcfmvm_registration_custom_field['enable'] ) ) continue;
									if( !$wcfmvm_registration_custom_field['label'] ) continue;
									$field_value = '';
									$wcfmvm_registration_custom_field['name'] = sanitize_title( $wcfmvm_registration_custom_field['label'] );

									if( !empty( $wcfmvm_custom_infos ) ) {
										if( $wcfmvm_registration_custom_field['type'] == 'checkbox' ) {
											$field_value = isset( $wcfmvm_custom_infos[$wcfmvm_registration_custom_field['name']] ) ? $wcfmvm_custom_infos[$wcfmvm_registration_custom_field['name']] : 'no';
										} else {
											$field_value = isset( $wcfmvm_custom_infos[$wcfmvm_registration_custom_field['name']] ) ? $wcfmvm_custom_infos[$wcfmvm_registration_custom_field['name']] : '';
										}
									}
									if( !$field_value ) $field_value = '';
									update_user_meta( $member_id, $wcfmvm_registration_custom_field['name'], $field_value );
								}
							}
						}

						// Email Verification Update - 1.3.2
						if( apply_filters( 'wcfm_is_allow_email_verification', true ) ) {
							if( $email_verified ) {
								update_user_meta( $member_id, '_wcfm_email_verified', true );
								update_user_meta( $member_id, '_wcfm_email_verified_for', $wcfm_membership_registration_form_data['user_email'] );
								unset( $_SESSION['wcfm_membership']['email_verification_code'] );
							}
						}

						// Free Membership Registration - 1.2.0
						if( isset( $_SESSION['wcfm_membership'] ) && isset( $_SESSION['wcfm_membership']['free_registration'] ) && $_SESSION['wcfm_membership']['free_registration'] ) {
							$member_user = new WP_User(absint($member_id));
							$shop_name = $wcfm_membership_registration_form_data['store_name'];
							if( ( $wcfm_membership == -1 ) || ( $wcfm_membership == '-1' ) ) {
								$membership_reject_rules = array();
								if( isset( $wcfm_membership_options['membership_reject_rules'] ) ) $membership_reject_rules = $wcfm_membership_options['membership_reject_rules'];
								$required_approval = isset( $membership_reject_rules['required_approval'] ) ? $membership_reject_rules['required_approval'] : 'no';
							} else {
								$required_approval = get_post_meta( $wcfm_membership, 'required_approval', true ) ? get_post_meta( $wcfm_membership, 'required_approval', true ) : 'no';
							}

							if( $required_approval == 'no') {
								$has_error = $WCFMvm->register_vendor( $member_id );
								$WCFMvm->store_subscription_data( $member_id, 'free', '', 'free_subscription', 'Completed', '' );
							} else {
								$WCFMvm->send_approval_reminder_admin( $member_id );
							}
						} elseif( isset( $_SESSION['wcfm_membership'] ) && isset( $_SESSION['wcfm_membership']['membership'] ) && $_SESSION['wcfm_membership']['membership'] ) {
							$wcfm_membership = absint($_SESSION['wcfm_membership']['membership']);
							// Set WC Cart for WC Checout Pay Mode - 1.3.0
							$subscription = (array) get_post_meta( $wcfm_membership, 'subscription', true );
							$subscription_pay_mode = isset( $subscription['subscription_pay_mode'] ) ? $subscription['subscription_pay_mode'] : 'by_wcfm';
							$subscription_product = isset( $subscription['subscription_product'] ) ? $subscription['subscription_product'] : '';
							if( ( $subscription_pay_mode == 'by_wc' ) && $subscription_product ) {
								WC()->cart->empty_cart();
								WC()->cart->add_to_cart( $subscription_product );
							}
						}

						update_user_meta( $member_id, 'show_admin_bar_front', false );

						if( $member_id && !$is_update ) {
							global $current_user;
							$current_user = get_user_by( 'id', $member_id );
							wp_set_auth_cookie( $member_id, true );
						}

						do_action( 'wcfm_membership_registration', $member_id, $wcfm_membership_registration_form_data );
					}

					if(!$has_error) {
						if( isset( $_SESSION['wcfm_membership'] ) && isset( $_SESSION['wcfm_membership']['free_registration'] ) && $_SESSION['wcfm_membership']['free_registration'] ) {
							echo '{"status": true, "message": "' . $wcfm_membership_registration_messages['registration_success'] . '", "redirect": "' . add_query_arg( 'vmstep', 'thankyou', get_wcfm_membership_url() ) . '"}';
						} elseif( $subscription_pay_mode == 'by_wc' ) {
							echo '{"status": true, "message": "' . $wcfm_membership_registration_messages['registration_success'] . '", "redirect": "' . wc_get_checkout_url() . '"}';
						} else {
							echo '{"status": true, "message": "' . $wcfm_membership_registration_messages['registration_success'] . '", "redirect": "' . add_query_arg( 'vmstep', 'payment', get_wcfm_membership_url() ) . '"}';
						}
					} else { echo '{"status": false, "message": "' . $wcfm_membership_registration_messages['registration_failed'] . '"}'; }
				}
			} else {
				echo '{"status": false, "message": "' . $wcfm_membership_registration_messages['no_email'] . '"}';
			}

	  } else {
			echo '{"status": false, "message": "' . $wcfm_membership_registration_messages['no_username'] . '"}';
		}

		die;
	}
}
