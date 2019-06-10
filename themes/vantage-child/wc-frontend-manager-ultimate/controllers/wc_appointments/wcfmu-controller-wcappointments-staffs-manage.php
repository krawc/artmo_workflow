<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Appointment Staffs Manage Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmu/controllers
 * @version   2.4.0
 */

class WCFMu_WCAppointments_Staffs_Manage_Controller {
	
	public function __construct() {
		global $WCFM, $WCFMu;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $WCFMu, $wpdb, $wcfm_staff_manager_form_data;
		
		$wcfm_staff_manager_form_data = array();
	  parse_str($_POST['wcfm_staffs_manage_form'], $wcfm_staff_manager_form_data);
	  
	  $wcfm_staff_messages = get_wcfm_staffs_manage_messages();
	  $has_error = false;
	  
	  if(isset($wcfm_staff_manager_form_data['user_name']) && !empty($wcfm_staff_manager_form_data['user_name'])) {
	  	if(isset($wcfm_staff_manager_form_data['user_email']) && !empty($wcfm_staff_manager_form_data['user_email'])) {
				$staff_id = 0;
				if( isset($wcfm_staff_manager_form_data['staff_id']) && $wcfm_staff_manager_form_data['staff_id'] != 0 ) {
					$staff_id = absint( $wcfm_staff_manager_form_data['staff_id'] );
				} else {
					if( username_exists( $wcfm_staff_manager_form_data['user_name'] ) ) {
						$has_error = true;
						echo '{"status": false, "message": "' . $wcfm_staff_messages['username_exists'] . '"}';
					} else {
						if( email_exists($user_email) == false ) {
							
						} else {
							$has_error = true;
							echo '{"status": false, "message": "' . $wcfm_staff_messages['email_exists'] . '"}';
						}
					}
				}
				
				if( !$has_error ) {
					$user_data = array( 'user_login'     => $wcfm_staff_manager_form_data['user_name'],
															'user_email'     => $wcfm_staff_manager_form_data['user_email'],
															'display_name'   => $wcfm_staff_manager_form_data['user_name'],
															'nickname'       => $wcfm_staff_manager_form_data['user_name'],
															'first_name'     => $wcfm_staff_manager_form_data['first_name'],
															'last_name'      => $wcfm_staff_manager_form_data['last_name'],
															'user_pass'      => wp_generate_password( $length = 12, $include_standard_special_chars=false ),
															'role'           => 'shop_staff',
															'ID'             => $staff_id
															);
					$staff_id = wp_insert_user( $user_data ) ;
				}
						
				if( !$staff_id ) {
					$has_error = true;
				} else {
					// Availability Rules
					$availability_rule_index = 0;
					$availability_rules = array();
					$availability_default_rules = array(  "type"        => 'custom',
																								"from"        => '',
																								"to"          => '',
																								"appointable" => '',
																								"qty"         => ''
																							);
					if( isset($wcfm_staff_manager_form_data['_wc_appointment_availability']) && !empty($wcfm_staff_manager_form_data['_wc_appointment_availability']) ) {
						foreach( $wcfm_staff_manager_form_data['_wc_appointment_availability'] as $availability_rule ) {
							$availability_rules[$availability_rule_index] = $availability_default_rules;
							$availability_rules[$availability_rule_index]['type'] = $availability_rule['type'];
							if( $availability_rule['type'] == 'custom' ) {
								$availability_rules[$availability_rule_index]['from'] = $availability_rule['from_custom'];
								$availability_rules[$availability_rule_index]['to']   = $availability_rule['to_custom'];
							} elseif( $availability_rule['type'] == 'months' ) {
								$availability_rules[$availability_rule_index]['from'] = $availability_rule['from_months'];
								$availability_rules[$availability_rule_index]['to']   = $availability_rule['to_months'];
							} elseif($availability_rule['type'] == 'weeks' ) {
								$availability_rules[$availability_rule_index]['from'] = $availability_rule['from_weeks'];
								$availability_rules[$availability_rule_index]['to']   = $availability_rule['to_weeks'];
							} elseif($availability_rule['type'] == 'days' ) {
								$availability_rules[$availability_rule_index]['from'] = $availability_rule['from_days'];
								$availability_rules[$availability_rule_index]['to']   = $availability_rule['to_days'];
							} elseif($availability_rule['type'] == 'time:range' ) {
								$availability_rules[$availability_rule_index]['from_date'] = $availability_rule['from_custom'];
								$availability_rules[$availability_rule_index]['to_date']   = $availability_rule['to_custom'];
								$availability_rules[$availability_rule_index]['from'] = $availability_rule['from_time'];
								$availability_rules[$availability_rule_index]['to']   = $availability_rule['to_time'];
							} else {
								$availability_rules[$availability_rule_index]['from'] = $availability_rule['from_time'];
								$availability_rules[$availability_rule_index]['to']   = $availability_rule['to_time'];
							}
							$availability_rules[$availability_rule_index]['appointable'] = $availability_rule['appointable'];
							$availability_rules[$availability_rule_index]['qty'] = $availability_rule['qty'];
							$availability_rule_index++;
						}
					}
					update_user_meta( $staff_id, '_wc_appointment_availability', $availability_rules );
					update_user_meta( $staff_id, '_wc_appointment_staff_qty', $wcfm_staff_manager_form_data['_wc_appointment_staff_qty'] );
						
					do_action( 'wcfm_staffs_manage', $staff_id );
				}
						
				if(!$has_error) { echo '{"status": true, "message": "' . $wcfm_staff_messages['staff_saved'] . '", "redirect": "' . get_wcfm_appointments_staffs_url() . '"}'; }
				else { echo '{"status": false, "message": "' . $wcfm_staff_messages['staff_failed'] . '"}'; }
						
			} else {
				echo '{"status": false, "message": "' . $wcfm_staff_messages['no_email'] . '"}';
			}
	  	
	  } else {
			echo '{"status": false, "message": "' . $wcfm_staff_messages['no_username'] . '"}';
		}
		
		die;
	}
}