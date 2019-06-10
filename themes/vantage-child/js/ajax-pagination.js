//jQuery(document).ready(function( $ ){
  (function($) {

	function find_page_number( element ) {
		element.find('span').remove();
		return parseInt( element.html() );
	}

	$(document).on( 'click', '.woocommerce-products-header__title', function( event ) {
		event.preventDefault();

		page = find_page_number( $(this).clone() );

		$.ajax({
			url: ajaxpagination.ajaxurl,
			type: 'post',
			data: {
				action: 'ajax_pagination',
				query_vars: ajaxpagination.query_vars,
				page: page
			},
			success: function( html ) {
        console.log(html);
			}

		})
	})
})(jQuery);
//});
