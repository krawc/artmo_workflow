jQuery(document).ready(function($) {
	// Appointment Status Update
	$('#wcfm_modify_appointment_status').click(function(event) {
		event.preventDefault();
		modifyWCFMAppointmentStatus();
		return false;
	});
		
	function modifyWCFMAppointmentStatus() {
		$('#appointments_details_general_expander').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var data = {
			action       : 'wcfm_modify_appointment_status',
			appointment_status : $('#wcfm_appointment_status').val(),
			appointment_id     : $('#wcfm_modify_appointment_status').data('appointmentid')
		}	
		$.ajax({
			type:		'POST',
			url: wcfm_params.ajax_url,
			data: data,
			success:	function(response) {
				$('#appointments_details_general_expander').unblock();
			}
		});
	}
});