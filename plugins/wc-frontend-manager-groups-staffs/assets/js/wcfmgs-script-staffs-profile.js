jQuery(document).ready(function($) {
	$('.wcfm_datepicker').each(function() {
	  $(this).datepicker({
      dateFormat : $(this).data('date_format'),
      changeMonth: true,
      changeYear: true
    });
  });
  
  if( $("#group_allowed_categories").length > 0 ) {
		$("#group_allowed_categories").select2();
	}
	
	if( $('.group_allowed_custom_taxonomies').length > 0 ) {
		$('.group_allowed_custom_taxonomies').select2();
	}
	
	$('#has_custom_capability').change(function() {
	  if( $(this).is(':checked') ) {
	  	$('.user_custom_capability').show();
	  } else {
	  	$('.user_custom_capability').hide();
	  }
	}).change();
  
	$('.multi_input_holder').each(function() {
	  var multi_input_holder = $(this);
	  addMultiInputProperty(multi_input_holder);
	});
	
	function addMultiInputProperty(multi_input_holder) {
		var multi_input_limit = multi_input_holder.data('limit');
		if( typeof multi_input_limit == 'undefined' ) multi_input_limit = -1;
	  if(multi_input_holder.children('.multi_input_block').length == 1) multi_input_holder.children('.multi_input_block').children('.remove_multi_input_block').css('display', 'none');
	  if( multi_input_holder.children('.multi_input_block').length == multi_input_limit )  multi_input_holder.find('.add_multi_input_block').hide();
	  else multi_input_holder.find('.add_multi_input_block').show();
    multi_input_holder.children('.multi_input_block').each(function() {
      if($(this)[0] != multi_input_holder.children('.multi_input_block:last')[0]) {
        $(this).children('.add_multi_input_block').remove();
      }
    });
    
    multi_input_holder.children('.multi_input_block').children('.add_multi_input_block').off('click').on('click', function() {
      var holder_id = multi_input_holder.attr('id');
      var holder_name = multi_input_holder.data('name');
      var multi_input_blockCount = multi_input_holder.data('length');
      multi_input_blockCount++;
      var multi_input_blockEle = multi_input_holder.children('.multi_input_block:first').clone(false);
      
      multi_input_blockEle.find('textarea,input:not(input[type=button],input[type=submit],input[type=checkbox],input[type=radio])').val('');
       multi_input_blockEle.find('input[type=checkbox]').attr('checked', false);
      multi_input_blockEle.children('.wcfm-wp-fields-uploader,.multi_input_block_element:not(.multi_input_holder)').each(function() {
        var ele = $(this);
        var ele_name = ele.data('name');
				ele.attr('name', holder_name+'['+multi_input_blockCount+']['+ele_name+']');
				ele.attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount);
        
        if(ele.hasClass('wcfm_datepicker')) {
          ele.removeClass('hasDatepicker').datepicker({
            dateFormat : ele.data('date_format'),
            changeMonth: true,
            changeYear: true
          });
        } else if(ele.hasClass('time_picker')) {
          $('.time_picker').timepicker('remove').timepicker({ 'step': 15 });
          ele.timepicker('remove').timepicker({ 'step': 15 });
        }
      });
      
      // Nested multi-input block property
      multi_input_blockEle.children('.multi_input_holder').each(function() {
        setNestedMultiInputIndex($(this), holder_id, holder_name, multi_input_blockCount);
      });
       
      
      multi_input_blockEle.children('.remove_multi_input_block').off('click').on('click', function() {
      	var remove_ele_parent = $(this).parent().parent();
				var addEle = remove_ele_parent.children('.multi_input_block').children('.add_multi_input_block').clone(true);
				$(this).parent().remove();
				remove_ele_parent.children('.multi_input_block').children('.add_multi_input_block').remove();
				remove_ele_parent.children('.multi_input_block:last').append(addEle);
				if( remove_ele_parent.children('.multi_input_block').length == multi_input_limit ) remove_ele_parent.find('.add_multi_input_block').hide();
				else remove_ele_parent.find('.add_multi_input_block').show();
				if(remove_ele_parent.children('.multi_input_block').length == 1) remove_ele_parent.children('.multi_input_block').children('.remove_multi_input_block').css('display', 'none');
				resetCollapsHeight(multi_input_holder);
			});
      
      multi_input_blockEle.children('.add_multi_input_block').remove();
      multi_input_holder.append(multi_input_blockEle);
      multi_input_holder.children('.multi_input_block:last').append($(this));
      if(multi_input_holder.children('.multi_input_block').length > 1) multi_input_holder.children('.multi_input_block').children('.remove_multi_input_block').css('display', 'block');
      if( multi_input_holder.children('.multi_input_block').length == multi_input_limit ) multi_input_holder.find('.add_multi_input_block').hide();
      else multi_input_holder.find('.add_multi_input_block').show();
      multi_input_holder.data('length', multi_input_blockCount);
      resetCollapsHeight(multi_input_holder);
    });
    
    if(!multi_input_holder.hasClass('multi_input_block_element')) {
			//multi_input_holder.children('.multi_input_block').css('padding-bottom', '40px');
		}
		if(multi_input_holder.children('.multi_input_block').children('.multi_input_holder').length > 0) {
			//multi_input_holder.children('.multi_input_block').css('padding-bottom', '40px');
		}
    
    multi_input_holder.children('.multi_input_block').children('.remove_multi_input_block').off('click').on('click', function() {
    	var remove_ele_parent = $(this).parent().parent();
      var addEle = remove_ele_parent.children('.multi_input_block').children('.add_multi_input_block').clone(true);
      $(this).parent().remove();
      remove_ele_parent.children('.multi_input_block').children('.add_multi_input_block').remove();
      remove_ele_parent.children('.multi_input_block:last').append(addEle);
      if(remove_ele_parent.children('.multi_input_block').length == 1) remove_ele_parent.children('.multi_input_block').children('.remove_multi_input_block').css('display', 'none');
      if( remove_ele_parent.children('.multi_input_block').length == multi_input_limit ) remove_ele_parent.find('.add_multi_input_block').hide();
      else remove_ele_parent.find('.add_multi_input_block').show();
      resetCollapsHeight(multi_input_holder);
    });
  }
  
  function resetMultiInputIndex(multi_input_holder) {
  	var holder_id = multi_input_holder.attr('id');
		var holder_name = multi_input_holder.data('name');
		var multi_input_blockCount = 0;
		
		multi_input_holder.find('.multi_input_block').each(function() {
			$(this).children('.wcfm-wp-fields-uploader,.multi_input_block_element:not(.multi_input_holder)').each(function() {
				var ele = $(this);
				var ele_name = ele.data('name');
				var multiple = ele.attr('multiple');
				if (typeof multiple !== typeof undefined && multiple !== false) {
					ele.attr('name', holder_name+'['+multi_input_blockCount+']['+ele_name+'][]');
				} else {
					ele.attr('name', holder_name+'['+multi_input_blockCount+']['+ele_name+']');
				}
				ele.attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount);
			});
			multi_input_blockCount++;
		});
  }
  
  function setNestedMultiInputIndex(nested_multi_input, holder_id, holder_name, multi_input_blockCount) {
		nested_multi_input.children('.multi_input_block:not(:last)').remove();
		var multi_input_id = nested_multi_input.attr('id');
		multi_input_id = multi_input_id.replace(holder_id + '_', '');
		var multi_input_id_splited = multi_input_id.split('_');
		var multi_input_name = '';
		for(var i = 0; i < (multi_input_id_splited.length -1); i++) {
		 if(multi_input_name != '') multi_input_name += '_';
		 multi_input_name += multi_input_id_splited[i];
		}
		nested_multi_input.attr('data-name', holder_name+'['+multi_input_blockCount+']['+multi_input_name+']');
		nested_multi_input.attr('id', holder_id+'_'+multi_input_name+'_'+multi_input_blockCount);
		nested_multi_input.children('.multi_input_block').children('.wcfm-wp-fields-uploader,.multi_input_block_element:not(.multi_input_holder)').each(function() {
		  var ele = $(this);
		  var ele_name = ele.data('name');
			var multiple = ele.attr('multiple');
			if (typeof multiple !== typeof undefined && multiple !== false) {
				ele.attr('name', holder_name+'['+multi_input_blockCount+']['+multi_input_name+'][0]['+ele_name+'][]');
			} else {
				ele.attr('name', holder_name+'['+multi_input_blockCount+']['+multi_input_name+'][0]['+ele_name+']');
			}
			ele.attr('id', holder_id+'_'+multi_input_name+'_'+multi_input_blockCount + '_' + ele_name + '_0');
		  
		  if(ele.hasClass('wcfm_datepicker')) {
				ele.removeClass('hasDatepicker').datepicker({
					dateFormat : ele.data('date_format'),
					changeMonth: true,
					changeYear: true
				});
			} else if(ele.hasClass('time_picker')) {
				$('.time_picker').timepicker('remove').timepicker({ 'step': 15 });
				ele.timepicker('remove').timepicker({ 'step': 15 });
			}
		});
		
		addMultiInputProperty(nested_multi_input);
		
		if(nested_multi_input.children('.multi_input_block').children('.multi_input_holder').length > 0) nested_multi_input.children('.multi_input_block').css('padding-bottom', '40px');
		
		nested_multi_input.children('.multi_input_block').children('.multi_input_holder').each(function() {
			setNestedMultiInputIndex($(this), holder_id+'_'+multi_input_name+'_0', holder_name+'['+multi_input_blockCount+']['+multi_input_name+']', 0);
		});
	}
	
	
	// Availability rules type
	function availabilityRules() {
		$('#_wc_appointment_availability').find('.multi_input_block').each(function() {
			if ( $(this).find('.avail_range_type').parent().is( "span" ) ) { $(this).find('.avail_range_type').unwrap( "span" ); }
			$(this).find('.avail_range_type').change(function() {
				$avail_range_type = $(this).val();
				$(this).parent().find('.avail_rule_field').addClass('wcfm_ele_hide');
				if( $avail_range_type == 'custom' || $avail_range_type == 'months' || $avail_range_type == 'weeks' || $avail_range_type == 'days' ) {
					$(this).parent().find('.avail_rule_' + $avail_range_type).removeClass('wcfm_ele_hide');
				} else if( $avail_range_type == 'time:range' ) {
					$(this).parent().find('.avail_rule_custom').removeClass('wcfm_ele_hide');
					$(this).parent().find('.avail_rule_time').removeClass('wcfm_ele_hide');
				} else {
					$(this).parent().find('.avail_rule_time').removeClass('wcfm_ele_hide');
				}
				resetCollapsHeight($('#_wc_appointment_availability'));
			}).change();
		});
	}
	if( $('#_wc_appointment_availability').length > 0 ) {
		availabilityRules();
		$('#_wc_appointment_availability').find('.add_multi_input_block').click(function() {
			availabilityRules();
		});
	}
});