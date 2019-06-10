jQuery(document).ready(function($) {

    var startDateTextBox = $('#_auction_dates_from');
    var endDateTextBox = $('#_auction_dates_to');

    $.timepicker.datetimeRange(
        startDateTextBox,
        endDateTextBox,
        {
            minInterval: (1000*60), // 1min
            dateFormat: 'yy-mm-dd',
            timeFormat: 'HH:mm',
            start: {}, // start picker options
            end: {} // end picker options
        }
    );

});