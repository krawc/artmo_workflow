var removed_variations = [];
var removed_person_types = [];
var product_form_is_valid = true;
jQuery( document ).ready( function( $ ) {


  //CHECK CONTEMPORARY REGARDLESS
  $('.product_cats_checklist_item.checklist_item_2859').find('input[type="checkbox"]').prop('checked', true);

    $('#wcfm_products_manage_form .wcfm-text').keydown(function(event){
      if(event.keyCode == 13) {
        event.preventDefault();
        return false;
      }
    });


   var productid = $('#pro_id').val();

   var dataSeries = {
     action : 'wcfm_get_series',
     id : productid
   }

   $.post(wcfm_params.ajax_url, dataSeries, function(response) {
     if( response ) {
       var obj = JSON.parse(response);
       $( "#series_name" ).autocomplete({
         source: obj.categories,
       });
     } else {
       console.log('Series Autocomplete Error');
     }
   });

   function create_tag_area(tag_slug, tag_description) {

     var actionName = 'wcfm_get_' + tag_slug;
     var dataTags = {
       action : actionName
     }
     var tags_obj = [];

     $.post(wcfm_params.ajax_url, dataTags, function(response) {
       if( response ) {
         var obj = JSON.parse(response);
         initiateTagField(obj.categories);
         tags_obj = obj.categories;
         //console.log(tags_obj);
       } else {
         console.log('Series Autocomplete Error');
       }
     });

     function initiateTagField(response) {
       $('#wcfm-main-contentainer textarea.wcfm-textarea#' + tag_slug).tagThis({
         noDuplicates: true,
         defaultText : tag_description,
         maxTags: 4,
         callbacks: {
           onChange: changeTagsOnDisplay
         },
         autocompleteSource : response
       });
       // console.log(response);
       initiateTags();
     }

     function initiateTags() {

       var tagInput = $('#wcfm-main-contentainer textarea.wcfm-textarea#' + tag_slug);
       var text = tagInput.val();
       var tags = [];

       if(tag_slug === 'genre_tag') {
         var alteredText = (text && (text !== '')) ? text : 'Contemporary';
         tags = alteredText.split(',');
       } else {
         tags = text.split(',');
       }

       for(var i=0; i<tags.length; i++) {
         if (tags[i] !== '') {
           tagInput.addTag({
               id : (6969 + i),
               text : tags[i]
           });
         }
       }

       $('#wcfm-main-contentainer textarea.wcfm-textarea#' + tag_slug).val( function( index, val ) {
           return tags.join(', ');
       });

       $('input#title').focus();
       $('#theme_tag--tag').attr('maxlength', '25');
       //changeTagsOnDisplay();
     }

     function changeTagsOnDisplay() {
       var tags_arr = tags_obj;
       var tags = $('#wcfm-main-contentainer textarea.wcfm-textarea#' + tag_slug).data('tags');
       var outputTags = [];

       for(var i=0; i<tags.length; i++) {
         if ( (tags_arr.includes(tags[i].text)) && (tag_slug === 'genre_tag') ) {
           outputTags.push(tags[i].text);
         } else {
           outputTags.push(tags[i].text);
         }
       }

       $('#wcfm-main-contentainer textarea.wcfm-textarea#' + tag_slug).val( function( index, val ) {
           return outputTags.join(', ');
       });
     }
   }

     create_tag_area('theme_tag', 'Insert subject (max. 4)');




  setInterval(function(){
    $('.wcfm_img_uploader #featured_img').trigger('change');
  }, 1000);

  function wcfm_product_photo_validate() {

    var photoField = $('.wcfm_img_uploader #featured_img');
    var photo = $('.wcfm-product-feature-upload img');

    if (photoField.val() !== '') {
      return true;
    } else {
      return false;
    }
  }

  function wcfm_product_overview_validate() {

    var titleField = $('#wcfm_products_manage_form #title');
    var authorFieldChecked = $('#wcfm_products_manage_form #youTheArtist:checked').length > 0;
    var artistField = $('#wcfm_products_manage_form #artistFirstName');
    var yearField = $('#wcfm_products_manage_form #year');
    var materialField = $('#wcfm_products_manage_form #materialMedia');
    var priceField = $('#wcfm_products_manage_form #regular_price');
    var priceOnRequestFieldChecked = $('#wcfm_products_manage_form #priceOnRequest:checked').length > 0;

    var seriesChecked = $("#wcfm_products_manage_form input.wcfm-checkbox#series:checked").length > 0;
    var seriesName = $("#wcfm_products_manage_form input#series_name");
    var editionChecked = $("#wcfm_products_manage_form .edition_field input.wcfm-radio:checked").length > 0;
    var editionVal = $("#wcfm_products_manage_form .edition_field input.wcfm-radio:checked").val() == 1;
    var editionNo = $("#wcfm_products_manage_form input#edition_no");
    var editiont = $("#wcfm_products_manage_form input#edition_t");

    if ((titleField.val() === '') || (!authorFieldChecked) || (yearField.val() === '') || (materialField.val() === '') ) {
      //false if title, author, year or material fields are empty
      return false;
    } else if ((priceField.val() === '') && (!priceOnRequestFieldChecked)) {
      //false if neither price or price on request is chosen
      return false;
    } else if ((seriesChecked) && (seriesName.val() === '')) {
      //false if series is checked, but its name is empty
      return false;
    } else if ((editionVal) && ((editionNo.val() === '') || (editiont.val() === ''))) {
      return false;
    } else if ((priceField.val() !== '') && (priceOnRequestFieldChecked)) {
      priceField.val('');
      return true;
    }

    return true;

  }

  function wcfm_product_size_validate() {

    var dimensionsChecked = $('#wcfm_products_manage_form #dimensions:checked').length > 0;
    var lengthField = $('#wcfm_products_manage_form #art_length');
    var widthField = $('#wcfm_products_manage_form #art_width');
    var heightField = $('#wcfm_products_manage_form #art_height');
    var inFrame = $("#wcfm_products_manage_form input.wcfm-checkbox#inFrame:checked").length > 0;
    var framedLengthField = $('#wcfm_products_manage_form #framed_length');
    var framedHeightField = $('#wcfm_products_manage_form #framed_height');

    if ((lengthField.val() === '') || (heightField.val() === '') ) {
      //false if length or height hide_empty
      return false;
    } else if ((dimensionsChecked) && (widthField.val() === '')) {
      //false if 3D and width empty
      return false;
    } else if ((inFrame) && ((framedLengthField.val() === '') || (framedHeightField.val() === ''))) {
      //false if in frame and frame size empty
      return false;
    }

    return true;

  }


  function wcfm_product_tags_validate() {
    var checked = $("#wcfm_products_manage_form #medium_cat input.wcfm-checkbox:checked").length > 0;
    if(!checked) {
      return false;
    }
    return true;
  }

    var groupsArr = ['photo', 'overview', 'size', 'tags'];

    var validationStore = {
      photo: wcfm_product_photo_validate(),
      overview: wcfm_product_overview_validate(),
      size: wcfm_product_size_validate(),
      tags: wcfm_product_tags_validate(),
    }

    for (var i = 0; i < groupsArr.length; i++) {
      var group = groupsArr[i];
      if (validationStore[group] === true) {
        $('.progress-bar_' + group).addClass('ready');
      } else {
        $('.progress-bar_' + group).removeClass('ready');
      }
    }

    $('.wcfm_img_uploader #featured_img').change(function() {
      if (wcfm_product_photo_validate()) {
        $('.progress-bar_photo').addClass('ready');
        validationStore.photo = true;
        wcfm_product_final_validate();
      } else {
        $('.progress-bar_photo').removeClass('ready');
        validationStore.photo = false;
      }
    });

    $('#wcfm_products_manage_form').on('change', '.overview-field', function () {
      if (wcfm_product_overview_validate()) {
        $('.progress-bar_overview').addClass('ready');
        validationStore.overview = true;
        wcfm_product_final_validate();
      } else {
        $('.progress-bar_overview').removeClass('ready');
        validationStore.overview = false;
      }
    });

    $('#wcfm_products_manage_form').on('change', '.size-field', function () {
      if (wcfm_product_size_validate()) {
        $('.progress-bar_size').addClass('ready');
        validationStore.size = true;
        wcfm_product_final_validate();
      } else {
        $('.progress-bar_size').removeClass('ready');
        validationStore.size = false;
      }
    });

    $("#wcfm_products_manage_form #medium_cat input.wcfm-checkbox").change(function() {
      if (wcfm_product_tags_validate()) {
        $('.progress-bar_tags').addClass('ready');
        validationStore.tags = true;
        wcfm_product_final_validate();
      } else {
        $('.progress-bar_tags').removeClass('ready');
        validationStore.tags = false;
      }
    }).change();

    function wcfm_product_final_validate() {

      if (validationStore.photo && validationStore.overview && validationStore.size && validationStore.tags) {
        $('.progress-bar_ready').addClass('done');
        $('#wcfm_products_simple_submit_button').addClass('enabled');
        // console.log(validationStore);
      } else {
        $('.progress-bar_ready').removeClass('done');
        $('#wcfm_products_simple_submit_button').removeClass('enabled');
        // console.log(validationStore);
      }
    }

  optional_fields_onChange();

  function optional_fields_onChange() {
    var youTheArtist = $('#wcfm_products_manage_form input#youTheArtist');
    var edition = $('#wcfm_products_manage_form fieldset#edition input');
    var editionChecked = $("#wcfm_products_manage_form .edition_field input.wcfm-radio:checked").length > 0;
    var series = $('#wcfm_products_manage_form input#series');

    $(youTheArtist).change(function() {
      $('#wcfm_products_manage_form #artistFirstName').attr('value', '');
      $('#wcfm_products_manage_form #artistLastName').attr('value', '');
    });

    $(edition).change(function() {
      $('#wcfm_products_manage_form #edition_no').attr('value', '');
      $('#wcfm_products_manage_form #edition_t').attr('value', '');
    });

    $(series).change(function() {
      $("#wcfm_products_manage_form input#series_name").attr('value', '');
    });

    if (editionChecked) {
      $('#wcfm_products_manage_form .edition_field input.wcfm-radio').removeClass('required-unfilled');
    } else {
      $('#wcfm_products_manage_form .edition_field input.wcfm-radio').addClass('required-unfilled');
    }
  }

  // $('#wcfm_products_manage_form .required-field').each(function () {
  //   if ($(this).val !== '') {
  //     $(this).removeClass('required-unfilled');
  //   } else {
  //     $(this).addClass('required-unfilled');
  //   }
  // });


  $('#wcfm_products_manage_form').on('change', '.required-field', function () {
    if ($(this).val !== '') {
      $(this).removeClass('required-unfilled');
    } else {
      $(this).addClass('required-unfilled');
    }
  }).change();


















	function wcfm_products_manage_form_validate() {
		product_form_is_valid = true;
		$('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
		var title = $.trim($('#wcfm_products_manage_form').find('#title').val());
		$('#wcfm_products_manage_form').find('#title').removeClass('wcfm_validation_failed').addClass('wcfm_validation_success');
		if(title.length == 0) {
			$('#wcfm_products_manage_form').find('#title').removeClass('wcfm_validation_success').addClass('wcfm_validation_failed');
			product_form_is_valid = false;
			$('#wcfm_products_manage_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + wcfm_products_manage_messages.no_title).addClass('wcfm-error').slideDown();
			audio.play();
		}

		$( document.body ).trigger( 'wcfm_products_manage_form_validate' );

		$wcfm_is_valid_form = product_form_is_valid;
		$( document.body ).trigger( 'wcfm_form_validate' );
		product_form_is_valid = $wcfm_is_valid_form;

		return product_form_is_valid;
	}

	// Draft Product
	$('#wcfm_products_simple_draft_button').click(function(event) {
	  event.preventDefault();

	  // Validations
	  $is_valid = wcfm_products_manage_form_validate();

	  if($is_valid) {
			$('#wcfm-content').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			var excerpt = $('#artDescription').val();

			var description = $('#description').val();

			// WC Box Office Support
			var ticket_content = '';
			if( $('#_ticket_content').length > 0 ) {
				if( $('#_ticket_content').hasClass('rich_editor') ) {
					if( tinymce.get('_ticket_content') != null ) ticket_content = tinymce.get('_ticket_content').getContent({format: 'raw'});
				} else {
					ticket_content = $('#_ticket_content').val();
				}
			}

			var ticket_email_html = '';
			if( $('#_ticket_email_html').length > 0 ) {
				if( $('#_ticket_email_html').hasClass('rich_editor') ) {
					if( tinymce.get('_ticket_email_html') != null ) ticket_email_html = tinymce.get('_ticket_email_html').getContent({format: 'raw'});
				} else {
					ticket_email_html = $('#_ticket_email_html').val();
				}
			}

			var data = {
				action : 'wcfm_ajax_controller',
				controller : 'wcfm-products-manage',
				wcfm_products_manage_form : $('#wcfm_products_manage_form').serialize(),
				excerpt     : excerpt,
				description : description,
				status : 'draft',
				removed_variations : removed_variations,
				removed_person_types : removed_person_types,
				ticket_content : ticket_content,
				ticket_email_html : ticket_email_html
			}
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					$('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
					if($response_json.status) {
						audio.play();
						$('#wcfm_products_manage_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message + ' <a href="https://artmo.com/?post_type=product&p=' + $response_json.id + '">Click here to preview.</a>').addClass('wcfm-success').slideDown( "slow", function() {
							if( $response_json.redirect ) window.location = $response_json.redirect;
						} );
					} else {
						audio.play();
						$('#wcfm_products_manage_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
					}
					if($response_json.id) $('#pro_id').val($response_json.id);
					$('#wcfm-content').unblock();
				}
			});
		}
	});

	// Submit Product
	$('#wcfm_products_simple_submit_button').click(function(event) {
	  event.preventDefault();

	  // Validations
	  $is_valid = wcfm_products_manage_form_validate();

	  if($is_valid) {
			$('#wcfm-content').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			var excerpt = '';
			if( $('#excerpt').hasClass('rich_editor') ) {
				if( tinymce.get('excerpt') != null ) excerpt = tinymce.get('excerpt').getContent({format: 'raw'});
			} else {
				excerpt = $('#excerpt').val();
			}

			var description = '';
			if( $('#description').hasClass('rich_editor') ) {
				if( tinymce.get('description') != null ) description = tinymce.get('description').getContent({format: 'raw'});
			} else {
				description = $('#description').val();
			}

			// WC Box Office Support
			var ticket_content = '';
			if( $('#_ticket_content').length > 0 ) {
				if( $('#_ticket_content').hasClass('rich_editor') ) {
					if( tinymce.get('_ticket_content') != null ) ticket_content = tinymce.get('_ticket_content').getContent({format: 'raw'});
				} else {
					ticket_content = $('#_ticket_content').val();
				}
			}

			var ticket_email_html = '';
			if( $('#_ticket_email_html').length > 0 ) {
				if( $('#_ticket_email_html').hasClass('rich_editor') ) {
					if( tinymce.get('_ticket_email_html') != null ) ticket_email_html = tinymce.get('_ticket_email_html').getContent({format: 'raw'});
				} else {
					ticket_email_html = $('#_ticket_email_html').val();
				}
			}

			var data = {
				action : 'wcfm_ajax_controller',
				controller : 'wcfm-products-manage',
				wcfm_products_manage_form : $('#wcfm_products_manage_form').serialize(),
				excerpt     : excerpt,
				description : description,
				status : 'submit',
				removed_variations : removed_variations,
				removed_person_types : removed_person_types,
				ticket_content : ticket_content,
				ticket_email_html : ticket_email_html
			}
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					$('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
					if($response_json.status) {
						audio.play();
						$('#wcfm_products_manage_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message + ' <a href="https://artmo.com/?post_type=product&p=' + $response_json.id + '">Click here to preview.</a>').addClass('wcfm-success').slideDown( "slow", function() {
						  if( $response_json.redirect ) window.location = $response_json.redirect;
						} );
					} else {
						audio.play();
						$('#wcfm_products_manage_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
					}
					if($response_json.id) $('#pro_id').val($response_json.id);
					$('#wcfm-content').unblock();
				}
			});
		}
	});


	function playSound(filename) {
	  document.getElementById("sound").innerHTML='<audio autoplay="autoplay"><source src="' + filename + '.mp3" type="audio/mpeg" /><source src="' + filename + '.ogg" type="audio/ogg" /><embed hidden="true" autostart="true" loop="false" src="' + filename +'.mp3" /></audio>';
	}

	function jsUcfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }


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

      //$(this).children('.add_multi_input_block').addClass('img_tip');
			$(this).children('.add_multi_input_block').attr( 'title', wcfm_dashboard_messages.wcfm_multiblick_addnew_help );
			//$(this).children('.remove_multi_input_block').addClass('img_tip');
			$(this).children('.remove_multi_input_block').attr( 'title', wcfm_dashboard_messages.wcfm_multiblick_remove_help );
    });
    var multi_input_has_dummy = multi_input_holder.data('has-dummy');
    if( multi_input_has_dummy ) multi_input_holder.find('.add_multi_input_block').hide();

    multi_input_holder.children('.multi_input_block').children('.add_multi_input_block').off('click').on('click', function() {
      var holder_id = multi_input_holder.attr('id');
      var holder_name = multi_input_holder.data('name');
      var multi_input_blockCount = multi_input_holder.data('length');
      multi_input_blockCount++;
      var multi_input_blockEle = multi_input_holder.children('.multi_input_block:first').clone(false);

      multi_input_blockEle.find('textarea,input:not(input[type=button],input[type=submit],input[type=checkbox],input[type=radio])').val('');
      multi_input_blockEle.find('input[type=checkbox]').attr('checked', false);
      multi_input_blockEle.find('.select2-container').remove();
      multi_input_blockEle.find('select').select2();
      multi_input_blockEle.find('select').select2('destroy');
      multi_input_blockEle.removeClass('multi_input_block_dummy');
      multi_input_blockEle.children('.wcfm-wp-fields-uploader,.wp-picker-container,.multi_input_block_element:not(.multi_input_holder)').each(function() {
        var ele = $(this);
        var ele_name = ele.data('name');
        if(ele.hasClass('wcfm-wp-fields-uploader')) {
					var uploadEle = ele;
					ele_name = uploadEle.find('.multi_input_block_element').data('name');
					uploadEle.find('img').attr('src', uploadEle.find('img').data('placeholder')).attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount + '_display').addClass('placeHolder');
					uploadEle.find('.multi_input_block_element').attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount).attr('name', holder_name+'['+multi_input_blockCount+']['+ele_name+']');
					uploadEle.find('.upload_button').attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount + '_button').show();
					uploadEle.find('.remove_button').attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount + '_remove_button').hide();
					if(uploadEle.hasClass('wcfm_gallery_upload')) {
						addWCFMMultiUploaderProperty(uploadEle);
					} else {
						addWCFMUploaderProperty(uploadEle);
					}
				} else if(ele.hasClass('wp-picker-container')) {
					$new_ele = ele.find('.multi_input_block_element');
					ele.replaceWith( $new_ele );
					ele_name = $new_ele.data('name');
					$new_ele.attr('name', holder_name+'['+multi_input_blockCount+']['+ele_name+']');
					$new_ele.attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount);
          $new_ele.removeClass('wp-color-picker').wpColorPicker();
				} else {
					ele.attr('name', holder_name+'['+multi_input_blockCount+']['+ele_name+']');
					ele.attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount);
        }

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
				if( multi_input_has_dummy ) multi_input_holder.find('.add_multi_input_block').hide();
				if(remove_ele_parent.children('.multi_input_block').length == 1) remove_ele_parent.children('.multi_input_block').children('.remove_multi_input_block').css('display', 'none');
				if( !multi_input_holder.hasClass( 'wcfm_additional_variation_images' ) && !multi_input_holder.hasClass( 'wcfm_per_product_shipping_variation_fields' ) && !multi_input_holder.hasClass( 'wcfm_wcaddons_fields' ) )  resetCollapsHeight(multi_input_holder);
			});

      multi_input_blockEle.children('.add_multi_input_block').remove();
      multi_input_holder.append(multi_input_blockEle);
      initiateTip();
      multi_input_holder.children('.multi_input_block:last').find('.wcfm-select2').select2({ placeholder: wcfm_dashboard_messages.choose_select2 + ' ...' });
      multi_input_holder.children('.multi_input_block:last').append($(this));
      if(multi_input_holder.children('.multi_input_block').length > 1) multi_input_holder.children('.multi_input_block').children('.remove_multi_input_block').css('display', 'block');
      if( multi_input_holder.children('.multi_input_block').length == multi_input_limit ) multi_input_holder.find('.add_multi_input_block').hide();
      else multi_input_holder.find('.add_multi_input_block').show();
      if( multi_input_has_dummy ) multi_input_holder.find('.add_multi_input_block').hide();
      multi_input_holder.data('length', multi_input_blockCount);

      addVariationManageStockProperty();

      if( !multi_input_holder.hasClass( 'wcfm_additional_variation_images' ) && !multi_input_holder.hasClass( 'wcfm_per_product_shipping_variation_fields' ) && !multi_input_holder.hasClass( 'wcfm_wcaddons_fields' ) )  resetCollapsHeight(multi_input_holder);
      else if( multi_input_holder.hasClass( 'wcfm_per_product_shipping_variation_fields' ) || multi_input_holder.hasClass( 'wcfm_wcaddons_fields' ) ) resetCollapsHeight( multi_input_holder.parent() );
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
      // For Attributes
      if( $(this).parent().find( $('input[data-name="is_taxonomy"]').data('name') == 1 ) ) {
				$taxonomy = $(this).parent().find( $('input[data-name="tax_name"]') ).val();
				$( 'select.wcfm_attribute_taxonomy' ).find( 'option[value="' + $taxonomy + '"]' ).removeAttr( 'disabled' );
			}
      $(this).parent().remove();
      remove_ele_parent.children('.multi_input_block').children('.add_multi_input_block').remove();
      remove_ele_parent.children('.multi_input_block:last').append(addEle);
      if(remove_ele_parent.children('.multi_input_block').length == 1) remove_ele_parent.children('.multi_input_block').children('.remove_multi_input_block').css('display', 'none');
      if( remove_ele_parent.children('.multi_input_block').length == multi_input_limit ) remove_ele_parent.find('.add_multi_input_block').hide();
      else remove_ele_parent.find('.add_multi_input_block').show();
      if( multi_input_has_dummy ) multi_input_holder.find('.add_multi_input_block').hide();

      if( !multi_input_holder.hasClass( 'wcfm_additional_variation_images' ) && !multi_input_holder.hasClass( 'wcfm_per_product_shipping_variation_fields' ) && !multi_input_holder.hasClass( 'wcfm_wcaddons_fields' ) ) resetCollapsHeight(multi_input_holder);
    });
  }

  function resetMultiInputIndex(multi_input_holder) {
  	var holder_id = multi_input_holder.attr('id');
		var holder_name = multi_input_holder.data('name');
		var multi_input_blockCount = 0;

		multi_input_holder.children('.multi_input_block').each(function() {
			$(this).children('.wcfm-wp-fields-uploader,.multi_input_block_element:not(.multi_input_holder)').each(function() {
				var ele = $(this);
				var ele_name = ele.data('name');
				if(ele.hasClass('wcfm-wp-fields-uploader')) {
					var uploadEle = ele;
					ele_name = uploadEle.find('.multi_input_block_element').data('name');
					uploadEle.find('img').attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount + '_display');
					uploadEle.find('.multi_input_block_element').attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount).attr('name', holder_name+'['+multi_input_blockCount+']['+ele_name+']');
					uploadEle.find('.upload_button').attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount + '_button');
					uploadEle.find('.remove_button').attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount + '_remove_button');
				} else {
					var multiple = ele.attr('multiple');
					if (typeof multiple !== typeof undefined && multiple !== false) {
						ele.attr('name', holder_name+'['+multi_input_blockCount+']['+ele_name+'][]');
					} else {
						ele.attr('name', holder_name+'['+multi_input_blockCount+']['+ele_name+']');
					}
					ele.attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount);
				}
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
		var nested_multi_input_block_count = 0;
		nested_multi_input.children('.multi_input_block').children('.wcfm-wp-fields-uploader,.multi_input_block_element:not(.multi_input_holder)').each(function() {
		  var ele = $(this);
		  var ele_name = ele.data('name');
		  if(ele.hasClass('wcfm-wp-fields-uploader')) {
				var uploadEle = ele;
				ele_name = uploadEle.find('.multi_input_block_element').data('name');
				uploadEle.find('img').attr('id', holder_id+'_'+multi_input_name+'_'+multi_input_blockCount + '_' + ele_name + '_' + nested_multi_input_block_count + '_display');
				uploadEle.find('.multi_input_block_element').attr('id', holder_id+'_'+multi_input_name+'_'+multi_input_blockCount + '_' + ele_name + '_' + nested_multi_input_block_count).attr('name', holder_name+'['+multi_input_blockCount+']['+multi_input_name+']['+nested_multi_input_block_count+']['+ele_name+']');
				uploadEle.find('.upload_button').attr('id', holder_id+'_'+multi_input_name+'_'+multi_input_blockCount + '_' + ele_name + '_' + nested_multi_input_block_count + '_button').attr('name', holder_id+'_'+multi_input_name+'_'+multi_input_blockCount + '_' + ele_name + '_' + nested_multi_input_block_count + '_button');
				uploadEle.find('.remove_button').attr('id', holder_id+'_'+multi_input_name+'_'+multi_input_blockCount + '_' + ele_name + '_' + nested_multi_input_block_count + '_remove_button').attr('name', holder_id+'_'+multi_input_name+'_'+multi_input_blockCount + '_' + ele_name + '_' + nested_multi_input_block_count + '_remove_button');
				if(uploadEle.hasClass('wcfm_gallery_upload')) {
					addWCFMMultiUploaderProperty(uploadEle);
				} else {
					addWCFMUploaderProperty(uploadEle);
				}
			} else {
				var multiple = ele.attr('multiple');
				if (typeof multiple !== typeof undefined && multiple !== false) {
					ele.attr('name', holder_name+'['+multi_input_blockCount+']['+multi_input_name+']['+nested_multi_input_block_count+']['+ele_name+'][]');
				} else {
					ele.attr('name', holder_name+'['+multi_input_blockCount+']['+multi_input_name+']['+nested_multi_input_block_count+']['+ele_name+']');
				}
				ele.attr('id', holder_id+'_'+multi_input_name+'_'+multi_input_blockCount + '_' + ele_name + '_' + nested_multi_input_block_count);
		  }

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
			//nested_multi_input_block_count++;
		});

		addMultiInputProperty(nested_multi_input);

		if(nested_multi_input.children('.multi_input_block').children('.multi_input_holder').length > 0) nested_multi_input.children('.multi_input_block').css('padding-bottom', '40px');

		nested_multi_input.children('.multi_input_block').children('.multi_input_holder').each(function() {
			setNestedMultiInputIndex($(this), holder_id+'_'+multi_input_name+'_0', holder_name+'['+multi_input_blockCount+']['+multi_input_name+']', 0);
			$(this).find('.multi_input_block_manupulate').each(function() {
				$(this).off('click').on('click', function() {
					resetCollapsHeight(nested_multi_input);
				} );
			} );
		});
	}

	// Add Taxonomy Attribute Rows.
	$( 'button.wcfm_add_attribute' ).on( 'click', function() {
		var attribute    = $( 'select.wcfm_attribute_taxonomy' ).val();

		if ( attribute ) {
			$('#attributes').children('.multi_input_block').children('.add_multi_input_block').click();
			$('#attributes').find('.remove_multi_input_block').remove();
			$('#attributes').find('.multi_input_block').each(function() {
				$(this).find('input[data-name="is_variation"]').off('change').on('change', function() {
					resetVariationsAttributes();
				});
			});
			resetMultiInputIndex($('#attributes'));
			initAttributesCollapser(false);
			$('.attributes_collapser').click();
			$('#attributes').children('.multi_input_block:last').find('input[data-name="is_active"]').click();
			$('#attributes').children('.multi_input_block:last').find('input[type="checkbox"]').attr( 'checked', true );
			$('#attributes').children('.multi_input_block:last').find('.attributes_collapser').click();
			$('#attributes').children('.multi_input_block:last').find('.attribute_ele').focus();
		}

		return false;
	});

	if($('.wcfm_select_attributes').length > 0) {
		$('.wcfm_select_attributes').each(function() {
			$('#attributes').append($(this).html());
			$(this).remove();
		});
		addMultiInputProperty($('#attributes'));
		resetMultiInputIndex($('#attributes'));
		initiateTip();
		$('#attributes').find('.remove_multi_input_block').remove();
	}

	if($('#text_attributes').length > 0) {
		$('#attributes').append($('#text_attributes').html());
		$('#text_attributes').remove();
		addMultiInputProperty($('#attributes'));
		resetMultiInputIndex($('#attributes'));
		initiateTip();
		$('#attributes').find('.remove_multi_input_block').remove();
	}

	$('#attributes').find('.multi_input_block').each(function() {
		$multi_input_block = $(this);
		$multi_input_block.prepend('<span class="fields_collapser attributes_collapser fa fa-arrow-circle-o-down" title="'+wcfm_dashboard_messages.wcfm_multiblick_collapse_help+'"></span>');
	  if( $multi_input_block.find( $('input[data-name="is_taxonomy"]').data('name') == 1 ) ) {
	  	$taxonomy = $multi_input_block.find( 'input[data-name="tax_name"]' ).val();
	  	$( 'select.wcfm_attribute_taxonomy' ).find( 'option[value="' + $taxonomy + '"]' ).attr( 'disabled','disabled' );
	  }
	  $multi_input_block.find('input[data-name="is_variation"]').off('change').on('change', function() {
	    resetVariationsAttributes();
	  });
	  $multi_input_block.find('input[data-name="is_active"]').off('change').on('change', function() {
	  	if( $(this).is(':checked') ) {
	      $(this).parent().find('.wcfm_ele:not(.attribute_ele), .wcfm_title, .select2, .wcfm_add_attribute_term').removeClass('variation_ele_hide');
				$(this).parent().find('input[type="checkbox"]').attr( 'checked', true ).removeClass('collapsed_checkbox');
				//$(this).parent().find('.wcfm_select_all_attributes').click();
				$(this).parent().find('.attributes_collapser').addClass('fa-arrow-circle-o-up');
	  	} else {
	  		$(this).parent().find('.wcfm_ele:not(.attribute_ele), .wcfm_title, .select2, .wcfm_add_attribute_term').addClass('variation_ele_hide');
				$(this).parent().find('input[type="checkbox"]').attr( 'checked', false ).addClass('collapsed_checkbox');
				$(this).parent().find('.wcfm_select_no_attributes').click();
				$(this).parent().find('.attributes_collapser').removeClass('fa-arrow-circle-o-up');
			}
			resetCollapsHeight($('#attributes'));
	  });
	  if( $multi_input_block.find('select').length > 0 ) {
	  	$attrlimit = $multi_input_block.find('select').data('attrlimit');
	  	if( $attrlimit != 1 ) {
	  		$multi_input_block.find('select').after($('<div class="wcfm-clearfix"></div>'));
				$multi_input_block.find('select').after($('<button type="button" class="button wcfm_add_attribute_term wcfm_select_all_attributes">'+wcfm_dashboard_messages.select_all+'</button>'));
				$multi_input_block.find('select').after($('<button type="button" class="button wcfm_add_attribute_term wcfm_select_no_attributes">'+wcfm_dashboard_messages.select_none+'</button>'));
				if( $multi_input_block.find('select').hasClass('allow_add_term') ) {
					$multi_input_block.find('select').after($('<button type="button" class="button wcfm_add_attribute_term wcfm_add_attributes_new_term">'+wcfm_dashboard_messages.add_new+'</button>'));
				}
				$multi_input_block.find('select').after($('<div class="wcfm-clearfix"></div>'));
			}
			$multi_input_block.find('select').each(function() {
				$(this).select2({
					placeholder: wcfm_dashboard_messages.search_attribute_select2,
					maximumSelectionLength: $attrlimit
				});
			});
		}
	});


} );
