<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Appointment Settings Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmu/controllers
 * @version   2.4.2
 */

class WCFMu_WCAppointments_Settings_Controller {
	
	public function __construct() {
		global $WCFM, $WCFMu;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $WCFMu, $wpdb, $wcfm_wcappointments_settings_form_data;
		
		$wcfm_wcappointments_settings_form_data = array();
	  parse_str($_POST['wcfm_wcappointments_settings_form'], $wcfm_wcappointments_settings_form_data);
	  
	  $wcfm_staff_messages = get_wcfm_wcappointments_settings_messages();
	  $has_error = false;
	  
	  // Availability Rules
		$availability_rule_index = 0;
		$availability_rules = array();
		$availability_default_rules = array(  "type"        => 'custom',
																					"from"        => '',
																					"to"          => '',
																					"appointable" => '',
																					"qty"         => ''
																				);
	  
		if( isset($wcfm_wcappointments_settings_form_data['wc_global_appointment_availability']) && !empty($wcfm_wcappointments_settings_form_data['wc_global_appointment_availability']) ) {
			foreach( $wcfm_wcappointments_settings_form_data['wc_global_appointment_availability'] as $availability_rule ) {
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
		update_option( 'wc_global_appointment_availability', $availability_rules );
			
		do_action( 'wcfm_wcappointments_settings', $staff_id );
						
		if(!$has_error) { echo '{"status": true, "message": "' . $wcfm_staff_messages['settings_saved'] . '"}'; }
		else { echo '{"status": false, "message": "' . $wcfm_staff_messages['settings_failed'] . '"}'; }
		
		die;
	}
}