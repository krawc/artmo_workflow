jQuery( document ).ready( function($) {
	$date_format = $( '.range_datepicker' ).data('date_format');
  var dates = $( '.range_datepicker' ).datepicker({
		changeMonth: true,
		changeYear: true,
		defaultDate: '',
		dateFormat: $date_format,
		numberOfMonths: 1,
		maxDate: '+0D',
		showButtonPanel: true,
		showOn: 'focus',
		buttonImageOnly: true,
		onSelect: function( selectedDate ) {
			var option = $( this ).is( '.from' ) ? 'minDate' : 'maxDate',
				instance = $( this ).data( 'datepicker' ),
				date = $.datepicker.parseDate( instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings );

			dates.not( this ).datepicker( 'option', option, date );
		}
	});
} );