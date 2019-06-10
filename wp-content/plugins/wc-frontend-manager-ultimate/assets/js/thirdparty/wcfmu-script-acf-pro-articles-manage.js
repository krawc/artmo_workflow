jQuery(document).ready(function($) {
	$('.wcfm-acf-pro-multi-select').each(function() { $(this).select2(); } );
	
	function processAcfProBasedFieldGroupShow() {
		$('.wcfm_cat_based_acf_pro_article_manager_fields').addClass('wcfm_acf_hide wcfm_head_hide wcfm_block_hide wcfm_custom_hide');
		$('#article_cats_checklist').find('input[type="checkbox"]').each(function() {
			if( $(this).is(':checked') ) {
				$cat_val = $(this).val();
				$.each( wcfm_cat_based_acf_pro_fields, function( cat_id, allowed_groups ) {
				  if( $cat_val == cat_id ) {
				  	$.each( allowed_groups, function( i, allowed_group ) {
				  	  $('.wcfm_acf_articles_manage_'+allowed_group+'_collapsible').removeClass('wcfm_acf_hide wcfm_head_hide wcfm_block_hide wcfm_custom_hide');
				  	  $('.wcfm_acf_articles_manage_'+allowed_group+'_container').removeClass('wcfm_acf_hide wcfm_head_hide wcfm_block_hide wcfm_custom_hide');
				  	});
				  }
				});
			}
		});
		resetCollapsHeight($('.collapse-open').next('.wcfm-container').find('.wcfm_ele:not(.wcfm_title):first'));
	}
	
	if( $('#article_cats').hasClass('wcfm-select') ) {
		$('.wcfm_cat_based_acf_pro_article_manager_fields').addClass('wcfm_acf_hide wcfm_head_hide wcfm_block_hide wcfm_custom_hide');
		$('#article_cats').change(function() {
		  $article_cats = $(this).val();
		  $.each($article_cats, function(i, $article_cat) {
				$.each( wcfm_cat_based_acf_pro_fields, function( cat_id, allowed_groups ) {
					if( $article_cat == cat_id ) {
						$.each( allowed_groups, function( i, allowed_group ) {
							$('.wcfm_acf_articles_manage_'+allowed_group+'_collapsible').removeClass('wcfm_acf_hide wcfm_head_hide wcfm_block_hide wcfm_custom_hide');
							$('.wcfm_acf_articles_manage_'+allowed_group+'_container').removeClass('wcfm_acf_hide wcfm_head_hide wcfm_block_hide wcfm_custom_hide');
						});
					}
				});
			});
			resetCollapsHeight($('.collapse-open').next('.wcfm-container').find('.wcfm_ele:not(.wcfm_title):first'));
		}).change();
	} else {
		$('#article_cats_checklist').find('input[type="checkbox"]').each(function() {
			$(this).click(function() {
				processAcfProBasedFieldGroupShow();
			});
		});
		processAcfProBasedFieldGroupShow();
	}
});