<?php
/**
 * WCFM Membership plugin core
 *
 * Plugin Ajax Controler
 *
 * @author 		WC Lovers
 * @package 	wcfmvm/core
 * @version   1.0.0
 */

class WCFMvm_Ajax {

	public $controllers_path;

	public function __construct() {
		global $WCFM, $WCFMvm;

		$this->controllers_path = $WCFMvm->plugin_path . 'controllers/';

		add_action( 'after_wcfm_ajax_controller', array( &$this, 'wcfmvm_ajax_controller' ) );
		add_action( 'wp_ajax_nopriv_wcfm_ajax_controller', array( &$this, 'wcfmvm_ajax_controller' ) );

		// Choose Membship Plan
		add_action( 'wp_ajax_wcfm_choose_membership', array( &$this, 'wcfm_choose_membership' ) );
		add_action( 'wp_ajax_nopriv_wcfm_choose_membership', array( &$this, 'wcfm_choose_membership' ) );

		// Generate Vendor Approval Response Html
    add_action( 'wp_ajax_wcfmvm_vendor_approval_html', array( &$this, 'wcfmvm_vendor_approval_html' ) );

    // Update Vendor Approval Response
    add_action( 'wp_ajax_wcfmvm_vendor_approval_response_update', array( &$this, 'wcfmvm_vendor_approval_response_update' ) );

    // Vendor membership cancel
    add_action( 'wp_ajax_wcfmvm_membership_cancel', array( &$this, 'wcfmvm_membership_cancel' ) );

    // Vendor membership change
    add_action( 'wp_ajax_wcfmvm_membership_change', array( &$this, 'wcfmvm_membership_change' ) );

    // Membership Delete
		add_action( 'wp_ajax_delete_wcfm_membership', array( &$this, 'delete_wcfm_membership' ) );

		// Membership Schedule Change Html
    add_action('wp_ajax_wcfmvm_change_next_renewal_html', array( &$this, 'wcfmvm_change_next_renewal_html' ) );

    // Membership Schedule Change Update
    add_action( 'wp_ajax_wcfmvm_change_next_renewal', array( &$this, 'wcfmvm_change_next_renewal_update' ) );

		// Email Verification Code
		add_action( 'wp_ajax_wcfmvm_email_verification_code', array( &$this, 'wcfmvm_email_verification_code' ) );
		add_action( 'wp_ajax_nopriv_wcfmvm_email_verification_code', array( &$this, 'wcfmvm_email_verification_code' ) );
	}

	/**
   * WCFM Membership Ajax Controllers
   */
  public function wcfmvm_ajax_controller() {
  	global $WCFM, $WCFMvm;

  	$controller = '';
  	if( isset( $_POST['controller'] ) ) {
  		$controller = $_POST['controller'];

  		switch( $controller ) {

				case 'wcfm-memberships':
					include_once( $this->controllers_path . 'wcfmvm-controller-memberships.php' );
					new WCFMvm_Memberships_Controller();
				break;

				case 'wcfm-memberships-manage':
					include_once( $this->controllers_path . 'wcfmvm-controller-memberships-manage.php' );
					new WCFMvm_memberships_Manage_Controller();
				break;

				case 'wcfm-memberships-registration':
					include_once( $this->controllers_path . 'wcfmvm-controller-memberships-registration.php' );
					new WCFMvm_Memberships_Registration_Controller();
				break;

				case 'wcfm-memberships-payment':
					include_once( $this->controllers_path . 'wcfmvm-controller-memberships-payment.php' );
					new WCFMvm_Memberships_Payment_Controller();
				break;

				case 'wcfm-memberships-settings':
					include_once( $this->controllers_path . 'wcfmvm-controller-memberships-settings.php' );
					new WCFMvm_Memberships_Settings_Controller();
				break;
			}
		}
	}

	/**
	 * WCFM Choose Membership Plan
	 */
	function wcfm_choose_membership() {
		global $WCFM, $WCFMvm, $_SESSION;

		if( isset( $_POST['membership'] ) && !empty( $_POST['membership'] ) ) {
			$membership = $_POST['membership'];
			// Session store
			$_SESSION['wcfm_membership']['membership'] = $membership;

      $member_id = get_current_user_id();

      update_user_meta( $member_id, 'temp_wcfm_membership', $membership );

      $step = (wcfm_get_membership() ? 'payment' : 'registration');

			echo '{"status": true, "redirect": "' . add_query_arg( 'vmstep', $step, get_wcfm_membership_url() ) . '"}';
		}

		die;
	}

	/**
	 * Generate Vendor Approval HTMl
	 */
	function wcfmvm_vendor_approval_html() {
		global $WCFM, $WCFMvm;

		if( isset( $_POST['messageid'] ) && isset($_POST['member_id']) ) {
			$message_id = absint( $_POST['messageid'] );
			$member_id = absint( $_POST['member_id'] );

			if( $member_id && $message_id ) {

				$member_data = get_userdata( $member_id );
				$store_name = get_user_meta( $member_id, 'store_name', true );
				$paymode = get_user_meta( $member_id, 'wcfm_membership_paymode', true );
				if( $paymode ) {
					$wcfm_membership_payment_methods = get_wcfm_membership_payment_methods();
					if( in_array( $paymode, array( 'paypal_subs', 'paypal_subs_subs' ) ) ) $paymode = 'paypal';
					if( in_array( $paymode, array( 'stripe', 'stripe_subs', 'stripe_subs_subs' ) ) ) $paymode = 'stripe';
					if( in_array( $paymode, array( 'bank_transfer', 'bank_transfer_subs' ) ) ) $paymode = 'bank_transfer';
					if( !$paymode ) $paymode = 'bank_transfer';
					if( isset( $wcfm_membership_payment_methods[$paymode] ) ) {
						$paymode = $wcfm_membership_payment_methods[$paymode];
					} else {
						if ( WC()->payment_gateways() ) {
							$payment_gateways = WC()->payment_gateways->payment_gateways();
							$paymode = isset( $payment_gateways[ $paymode ] ) ? esc_html( $payment_gateways[ $paymode ]->get_title() ) : __( 'FREE', 'wc-multivendor-membership' );
						}
					}
				}

				$wcfmvm_registration_static_fields = get_option( 'wcfmvm_registration_static_fields', array() );
				$wcfmvm_static_infos = (array) get_user_meta( $member_id, 'wcfmvm_static_infos', true );

				$wcfmvm_registration_custom_fields = get_option( 'wcfmvm_registration_custom_fields', array() );
				$wcfmvm_custom_infos = (array) get_user_meta( $member_id, 'wcfmvm_custom_infos', true );

				?>
				<form id="wcfm_vendor_approval_response_form" class="wcfm_popup_wrapper">
				  <div style="margin-bottom: 15px;"><h2 style="float: none;"><?php _e( 'Vendor Application', 'wc-frontend-manager-ultimate' ); ?></h2></div>
					<table>
						<tbody>
							<tr>
								<td class="wcfm_vendor_approval_response_form_label wcfm_popup_label"><?php _e( 'First Name', 'wc-multivendor-membership' ); ?></td>
								<td><?php echo $member_data->first_name; ?></td>
							</tr>
							<tr>
								<td class="wcfm_vendor_approval_response_form_label wcfm_popup_label"><?php _e( 'Last Name', 'wc-multivendor-membership' ); ?></td>
								<td><?php echo $member_data->last_name; ?></td>
							</tr>
							<tr>
								<td class="wcfm_vendor_approval_response_form_label wcfm_popup_label"><?php _e( 'Login', 'wc-multivendor-membership' ); ?></td>
								<td><?php echo $member_data->user_login; ?></td>
							</tr>
							<tr>
								<td class="wcfm_vendor_approval_response_form_label wcfm_popup_label"><?php _e( 'Email', 'wc-multivendor-membership' ); ?></td>
								<td><?php echo $member_data->user_email; ?></td>
							</tr>
							<tr>
								<td class="wcfm_vendor_approval_response_form_label wcfm_popup_label"><?php _e( 'Store Name', 'wc-multivendor-membership' ); ?></td>
								<td><?php echo $store_name; ?></td>
							</tr>
							<?php if( $paymode ) { ?>
								<tr>
									<td class="wcfm_vendor_approval_response_form_label wcfm_popup_label"><?php _e( 'Pay mode', 'wc-multivendor-membership' ); ?></td>
									<td><?php echo $paymode; ?></td>
								</tr>
							<?php } ?>
							<?php
							// Registration Static Field Support - 1.0.6
							if( !empty( $wcfmvm_registration_static_fields ) ) {
								foreach( $wcfmvm_registration_static_fields as $wcfmvm_registration_static_field => $wcfmvm_registration_static_field_val ) {
									$field_value = array();
									$field_name = 'wcfmvm_static_infos[' . $wcfmvm_registration_static_field . ']';

									if( !empty( $wcfmvm_static_infos ) ) {
										$field_value = isset( $wcfmvm_static_infos[$wcfmvm_registration_static_field] ) ? $wcfmvm_static_infos[$wcfmvm_registration_static_field] : array();
									}

									switch( $wcfmvm_registration_static_field ) {
										case 'address':
											if( isset($field_value['addr_1']) ) {
												$state_code = $field_value['state'];
												$country_code = $field_value['country'];
												$state   = isset( WC()->countries->states[ $country_code ][ $state_code ] ) ? WC()->countries->states[ $country_code ][ $state_code ] : $state_code;
												$country = isset( WC()->countries->countries[ $country_code ] ) ? WC()->countries->countries[ $country_code ] : $country_code;

												$address = $field_value['addr_1'] . ' ' . $field_value['addr_2']. '<br/>' . $field_value['city']. ', ' . $state. '<br />' . $field_value['zip']. '<br />' . $country;
											  ?>
												<tr>
													<td class="wcfm_vendor_approval_response_form_label wcfm_popup_label"><?php _e( 'Store Address', 'wc-frontend-manager' ); ?></td>
													<td><?php echo $address; ?></td>
												</tr>
												<?php
											}
										break;

										case 'phone':
											?>
											<tr>
												<td class="wcfm_vendor_approval_response_form_label wcfm_popup_label"><?php _e( 'Store Phone', 'wc-frontend-manager' ); ?></td>
												<td><?php echo $field_value; ?></td>
											</tr>
											<?php
										break;

										default:
											do_action( 'wcfmvm_registration_static_field_popup_show', $member_id, $wcfmvm_registration_static_field, $field_value );
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

									if( !empty( $wcfmvm_custom_infos ) ) {
										if( $wcfmvm_registration_custom_field['type'] == 'checkbox' ) {
											$field_value = isset( $wcfmvm_custom_infos[$wcfmvm_registration_custom_field['name']] ) ? $wcfmvm_custom_infos[$wcfmvm_registration_custom_field['name']] : 'no';
										} else {
											$field_value = isset( $wcfmvm_custom_infos[$wcfmvm_registration_custom_field['name']] ) ? $wcfmvm_custom_infos[$wcfmvm_registration_custom_field['name']] : '';
										}
									}
									?>
									<tr>
										<td class="wcfm_vendor_approval_response_form_label wcfm_popup_label"><?php _e( $wcfmvm_registration_custom_field['label'], 'wc-multivendor-membership'); ?></td>
										<td><?php echo $field_value; ?></td>
									</tr>
									<?php
								}
							}
							?>
							<tr>
								<td class="wcfm_vendor_approval_response_form_label wcfm_popup_label"><?php _e( 'Rejection Reason', 'wc-multivendor-membership' ); ?></td>
								<td>
								  <textarea id="wcfm_vendor_rejection_reason" class="wcfm_popup_input wcfm_popup_textarea" name="wcfm_vendor_rejection_reason" style="width: 95%;"></textarea>
								</td>
							</tr>
							<tr>
								<td class="wcfm_vendor_approval_response_form_label wcfm_popup_label"><?php _e( 'Status Update', 'wc-multivendor-membership' ); ?></td>
								<td>
								  <label for="wcfm_vendor_approval_response_status_approve"><input type="radio" id="wcfm_vendor_approval_response_status_approve" name="wcfm_vendor_approval_response_status" value="approve" checked /><?php _e( 'Approve', 'wc-multivendor-membership' ); ?></label>
								  <label for="wcfm_vendor_approval_response_status_reject"><input type="radio" id="wcfm_vendor_approval_response_status_reject" name="wcfm_vendor_approval_response_status" value="reject" /><?php _e( 'Reject', 'wc-multivendor-membership' ); ?></label>
								</td>
							</tr>
						</tbody>
					</table>
					<input type="hidden" name="wcfm_vendor_approval_member_id" value="<?php echo $member_id; ?>" />
					<input type="hidden" name="wcfm_vendor_approval_message_id" value="<?php echo $message_id; ?>" />
					<div class="wcfm-message" tabindex="-1"></div>
					<input type="button" class="wcfm_vendor_approval_response_button wcfm_submit_button wcfm_popup_button" id="wcfm_vendor_approval_response_button" value="<?php _e( 'Update', 'wc-multivendor-membership' ); ?>" />
				</form>
				<?php
			}
		}
		die;
	}

	function wcfmvm_vendor_approval_response_update() {
		global $WCFM, $WCFMvm, $_POST, $wpdb;

		$wcfm_vendor_approval_response_form_data = array();
	  parse_str($_POST['wcfm_vendor_approval_response_form'], $wcfm_vendor_approval_response_form_data);

		if( isset( $wcfm_vendor_approval_response_form_data['wcfm_vendor_approval_message_id'] ) && isset($wcfm_vendor_approval_response_form_data['wcfm_vendor_approval_member_id']) ) {
			$message_id = absint( $wcfm_vendor_approval_response_form_data['wcfm_vendor_approval_message_id'] );
			$member_id  = absint( $wcfm_vendor_approval_response_form_data['wcfm_vendor_approval_member_id'] );

			if( $member_id && $message_id ) {
				$member_user = new WP_User(absint($member_id));
				$approval_status = $wcfm_vendor_approval_response_form_data['wcfm_vendor_approval_response_status'];

				delete_user_meta( $member_id, 'wcfm_membership_application_status' );

				if( $approval_status == 'approve' ) {
					$paymode    = get_user_meta( $member_id, 'wcfm_membership_paymode', true );
					if( !$paymode ) $paymode = 'bank_transfer';

					$has_error = $WCFMvm->register_vendor( $member_id );

					$membership_id = get_user_meta( $member_id, 'wcfm_membership', true );
					if( $membership_id ) {
						$subscription = (array) get_post_meta( $membership_id, 'subscription', true );
						$subscription_type = isset( $subscription['subscription_type'] ) ? $subscription['subscription_type'] : 'one_time';
						$subscription_pay_mode = isset( $subscription['subscription_pay_mode'] ) ? $subscription['subscription_pay_mode'] : 'by_wcfm';

						if( ($paymode != 'paypal') && ($paymode != 'stripe') ) {
							$WCFMvm->store_subscription_data( $member_id, $paymode, '', $paymode.'_subscription', 'Completed', '' );
							if( ( $subscription_type == 'recurring' ) && ( $subscription_pay_mode == 'by_wcfm' ) ) {
								$WCFMvm->store_subscription_data( $member_id, $paymode.'_subs', '', $paymode.'_reccuring_subscription', 'Completed', '' );
							}
						}
					}
				} else {

					$wcfm_membership_options = get_option( 'wcfm_membership_options', array() );
					$membership_reject_rules = array();
					if( isset( $wcfm_membership_options['membership_reject_rules'] ) ) $membership_reject_rules = $wcfm_membership_options['membership_reject_rules'];
					$vendor_reject_rule = isset( $membership_reject_rules['vendor_reject_rule'] ) ? $membership_reject_rules['vendor_reject_rule'] : 'same';
					$send_notification = isset( $membership_reject_rules['send_notification'] ) ? $membership_reject_rules['send_notification'] : 'yes';

					if( $send_notification == 'yes' ) {
						if( !defined( 'DOING_WCFM_EMAIL' ) )
							  define( 'DOING_WCFM_EMAIL', true );

						$rejection_reason = $wcfm_vendor_approval_response_form_data['wcfm_vendor_rejection_reason'];

						$reject_notication_subject = get_option( 'wcfm_membership_reject_notication_subject', '{site_name}: Vendor Application Rejected' );
						$reject_notication_content = get_option( 'wcfm_membership_reject_notication_content', '' );
						if( !$reject_notication_content ) {
							$reject_notication_content = "Hi {first_name},
																						<br /><br />
																						Sorry to inform you that, your vendor application has been rejected. 
																						<br /><br />
																						<strong><i>{rejection_reason}</i></strong>
																						<br /><br />
																						Thank You";
						}

						$subject = str_replace( '{site_name}', get_bloginfo( 'name' ), $reject_notication_subject );
						$message = str_replace( '{first_name}', $member_user->first_name, $reject_notication_content );
						$message = str_replace( '{rejection_reason}', $rejection_reason, $message );
						$message = apply_filters( 'wcfm_email_content_wrapper', $message, __( 'Vendor Application Rejected', 'wc-multivendor-membership' ) );

						wp_mail( $member_user->user_email, $subject, $message );

					}

					if( apply_filters( 'wcfm_is_allow_delete_vendor_reject_user', true ) ) {
						$membership_id = get_user_meta( $member_id, 'temp_wcfm_membership', true );
						if( ( $membership_id != -1 ) && ( $membership_id != '-1' ) ) {
							$vendor_reject_rule = get_post_meta( $membership_id, 'vendor_reject_rule', true ) ? get_post_meta( $membership_id, 'vendor_reject_rule', true ) : $vendor_reject_rule;
						}
						delete_user_meta( $member_id, 'temp_wcfm_membership' );
						delete_user_meta( $member_id, 'wcfm_membership_application_status' );
						if( $vendor_reject_rule == 'delete' ) {
							wp_delete_user( $member_id );
						}
					}
				}

				// Vendor Approval message mark read
				$author_id = apply_filters( 'wcfm_message_author', get_current_user_id() );
				$todate = date('Y-m-d H:i:s');

				$wcfm_read_message     = "INSERT into {$wpdb->prefix}wcfm_messages_modifier
																		(`message`, `is_read`, `read_by`, `read_on`)
																		VALUES
																		({$message_id}, 1, {$author_id}, '{$todate}')";
				$wpdb->query($wcfm_read_message);

				echo '{"status": true, "message": "' . __( 'Vendor Approval ststus successfully updated.', 'wc-multivendor-membership' ) . '"}';
				die;
			}
		}
		echo '{"status": false, "message": "' . __( 'Vendor Approval ststus update failed.', 'wc-multivendor-membership' ) . '"}';
		die;
	}

	/**
	 * Vednor membership cancel
	 */
	function wcfmvm_membership_cancel() {
		global $WCFM, $WCFMvm, $_POST, $wpdb;

		if( isset( $_POST['memberid'] ) && isset($_POST['membershipid']) ) {
			$member_id = absint( $_POST['memberid'] );
			$wcfm_membership_id = absint( $_POST['membershipid'] );

			$WCFMvm->wcfmvm_vendor_membership_cancel( $member_id, $wcfm_membership_id );
			$WCFMvm->store_subscription_data( $member_id, $paymode, '', 'subscr_cancel', 'Cancelled', __(  'Manual Cancellation', 'wc-multivendor-membership' ) );

			echo '{"status": true, "message": "' . __( 'Your membership successfully cancelled.', 'wc-multivendor-membership' ) . '", "redirect": "' . get_wcfm_profile_url() . '"}';
			die;
		}
		echo '{"status": false, "message": "' . __( 'Your membership can not be cancelled right now, please contact your store admin.', 'wc-multivendor-membership' ) . '"}';
		die;
	}

	/**
	 * Vednor membership change byAdmin
	 */
	function wcfmvm_membership_change() {
		global $WCFM, $WCFMvm, $_POST, $wpdb;

		if( isset( $_POST['memberid'] ) && isset($_POST['membershipid']) ) {
			$member_id = absint( $_POST['memberid'] );
			$wcfm_membership_id = absint( $_POST['membershipid'] );
			$member_user = new WP_User( $member_id );
			$shop_name = get_user_meta( $member_id, 'store_name', true );

			update_user_meta( $member_id, 'temp_wcfm_membership', $wcfm_membership_id );
			$has_error = $WCFMvm->register_vendor( $member_id );
			$WCFMvm->store_subscription_data( $member_id, 'manual', '', 'manual_subscription', 'Completed', '' );


			echo '{"status": true, "message": "' . __( 'Vendor membership successfully changed.', 'wc-multivendor-membership' ) . '"}';
			die;
		}
		echo '{"status": false, "message": "' . __( 'Vendor membership can not be changed right now, please try after sometime.', 'wc-multivendor-membership' ) . '"}';
		die;
	}

	/**
   * Handle membership Delete
   */
  public function delete_wcfm_membership() {
  	global $WCFM, $WCFMvm;

  	$membershipid = $_POST['membershipid'];

		if( $membershipid ) {
			if( wp_delete_post( $membershipid ) ) {
				echo 'success';
				die;
			}
			die;
		}
  }

  /**
   * Membership Schedule Change HTML
   */
  function wcfmvm_change_next_renewal_html() {
  	global $WCFM, $WCFMvm;

  	$schedule = $_POST['schedule'];
  	$member   = absint($_POST['member']);

		?>
		<div id="wcfmvm_change_next_renewal_form" class="wcfm-collapse-content wcfm_popup_wrapper" style="padding: 10px;">
			<div style="margin-bottom: 15px;"><h2 style="float: none;"><?php _e( 'Membership Renewal Update', 'wc-multivendor-membership' ); ?></h2></div>

			<?php
			$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfmvm_change_next_renewal_fields', array(
																													"wcfmvm_next_renewal" => array( 'label' => __( 'Next Renewal', 'wc-multivendor-membership' ), 'type' => 'text', 'class' => 'wcfm-text wcfm_popup_input', 'label_class' => 'wcfm_popup_label', 'value' => $schedule ),
																													"wcfmvm_member" => array( 'type' => 'hidden', 'value' => $member ),
																												) ) );
			?>
			<div class="wcfm-message"></div>
			<input type="submit" id="wcfmvm_change_next_renewal_button" name="wcfmvm_change_next_renewal_button" class="wcfm_submit_button wcfm_popup_button" value="<?php _e( 'Submit', 'wc-frontend-manager' ); ?>" />
		</div>
		<?php
		die;
  }

  /**
   * Membership Next Schedule Update
   */
  function wcfmvm_change_next_renewal_update() {
  	global $WCFM, $WCFMvm;

  	$next_renewal = $_POST['next_renewal'];
  	$member_id    = absint($_POST['member']);

  	if( $next_renewal ) {
  		$next_renewal = strtotime( $next_renewal );
  		update_user_meta( $member_id, 'wcfm_membership_next_schedule', $next_renewal );
  		$next_renewal_display = date_i18n( wc_date_format(), $next_renewal );
  	} else  {
  		delete_user_meta( $member_id, 'wcfm_membership_next_schedule' );
  		$next_renewal_display = __( 'Never Expire', 'wc-multivendor-membership' );
  	}

  	echo '{ "status": true, "message": "' . __( 'Next renewal successfully updated.', 'wc-multivendor-membership' ) . '", "next_renewal_display": "' . $next_renewal_display . '" }';
  	die;
  }

  /**
   * WCfM Registration email verification code send
   */
  function wcfmvm_email_verification_code() {
  	global $WCFM, $WCFMvm, $_SESSION;

  	$user_email = $_POST['user_email'];

		if( $user_email ) {
			if( isset( $_SESSION['wcfm_membership'] ) && isset( $_SESSION['wcfm_membership']['email_verification_code'] ) ) {
				$verification_code = $_SESSION['wcfm_membership']['email_verification_code'];
			} else {
				$verification_code = rand( 100000, 999999 );
			}
			// Session store
			$_SESSION['wcfm_membership']['email_verification_code'] = $verification_code;
			$_SESSION['wcfm_membership']['email_verification_for'] = $user_email;

			// Sending verification code in email
			if( !defined( 'DOING_WCFM_EMAIL' ) )
			  define( 'DOING_WCFM_EMAIL', true );
			$verification_mail_subject = "{site_name}: " . __( "Email Verification Code", "wc-frontend-manager" ) . " - " . $verification_code;
			$verification_mail_body = __( 'Hi', 'wc-multivendor-membership' ) .
																	 ',<br/><br/>' .
																	 sprintf( __( 'Here is your email verification code - <b>%s</b>', 'wc-multivendor-membership' ), '{verification_code}' ) .
																	 '<br /><br/>' . __( 'Thank You', 'wc-multivendor-membership' );

			$subject = str_replace( '{site_name}', get_bloginfo( 'name' ), $verification_mail_subject );
			$subject = str_replace( '{verification_code}', $verification_code, $subject );
			$message = str_replace( '{verification_code}', $verification_code, $verification_mail_body );
			$message = apply_filters( 'wcfm_email_content_wrapper', $message, __( 'Email Verification', 'wc-multivendor-membership' ) );

			wp_mail( $user_email, $subject, $message );

			echo '{"status": true, "message": "' . __( 'Email verification code send to your email.', 'wc-multivendor-membership' ) . '"}';
		} else {
			echo '{"status": false, "message": "' . __( 'Email verification not working right now, please try after sometime.', 'wc-multivendor-membership' ) . '"}';
		}
		die;
  }
}
