var removed_variations = [];
var removed_person_types = [];
var product_form_is_valid = true;
jQuery( document ).ready( function( $ ) {

  $(window).keydown(function(event){
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
    } else {
      console.log('Series Autocomplete Error');
    }
  });

  function initiateTagField(response) {
    $('#wcfm-main-contentainer textarea.wcfm-textarea#' + tag_slug).tagThis({
      noDuplicates: true,
      defaultText : tag_description,
      callbacks: {
        onChange: changeTagsOnDisplay
      },
      autocompleteSource : response
    });
    initiateTags();
  }

  function initiateTags() {

    var tagInput = $('#wcfm-main-contentainer textarea.wcfm-textarea#' + tag_slug);
    var text = tagInput.val();
    var tags = [];

    if(tag_slug === 'genre_tag') {
      var alteredText = (text !== '') ? text : 'Contemporary';
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

  create_tag_area('genre_tag', 'Insert genre');
  //create_tag_area('theme_tag');
  setTimeout(create_tag_area('theme_tag', 'Insert theme'), 500);




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

    // var photoInit: wcfm_product_photo_validate();
    // var overviewInit: wcfm_product_overview_validate();
    // var sizeInit: wcfm_product_size_validate();
    // var tagsInit: wcfm_product_tags_validate();

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


  function optional_fields_onChange() {
    var youTheArtist = $('#wcfm_products_manage_form input#youTheArtist');
    var edition = $('#wcfm_products_manage_form input#edition');

    $(youTheArtist).change(function() {
      $('#wcfm_products_manage_form #artistFirstName').val('');
      $('#wcfm_products_manage_form #artistLastName').val('');
    }).change();

    $(edition).change(function() {
      $('#wcfm_products_manage_form #edition_no').val('');
      $('#wcfm_products_manage_form #edition_t').val('');
    }).change();
  }



















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
						$('#wcfm_products_manage_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown( "slow", function() {
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
						$('#wcfm_products_manage_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown( "slow", function() {
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
} );
