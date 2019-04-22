jQuery(document).ready(function( $ ){

  //READ MORE module

  !function(t){"function"==typeof define&&define.amd?define(["jquery"],t):"object"==typeof exports?module.exports=t(require("jquery")):t(jQuery)}(function(t){"use strict";function e(t,e,i){var o;return function(){var n=this,a=arguments,s=function(){o=null,i||t.apply(n,a)},r=i&&!o;clearTimeout(o),o=setTimeout(s,e),r&&t.apply(n,a)}}function i(t){var e=++h;return String(null==t?"rmjs-":t)+e}function o(t){var e=t.clone().css({height:"auto",width:t.width(),maxHeight:"none",overflow:"hidden"}).insertAfter(t),i=e.outerHeight(),o=parseInt(e.css({maxHeight:""}).css("max-height").replace(/[^-\d\.]/g,""),10),n=t.data("defaultHeight");e.remove();var a=o||t.data("collapsedHeight")||n;t.data({expandedHeight:i,maxHeight:o,collapsedHeight:a}).css({maxHeight:"none"})}function n(t){if(!d[t.selector]){var e=" ";t.embedCSS&&""!==t.blockCSS&&(e+=t.selector+" + [data-readmore-toggle], "+t.selector+"[data-readmore]{"+t.blockCSS+"}"),e+=t.selector+"[data-readmore]{transition: height "+t.speed+"ms;overflow: hidden;}",function(t,e){var i=t.createElement("style");i.type="text/css",i.styleSheet?i.styleSheet.cssText=e:i.appendChild(t.createTextNode(e)),t.getElementsByTagName("head")[0].appendChild(i)}(document,e),d[t.selector]=!0}}function a(e,i){this.element=e,this.options=t.extend({},r,i),n(this.options),this._defaults=r,this._name=s,this.init(),window.addEventListener?(window.addEventListener("load",c),window.addEventListener("resize",c)):(window.attachEvent("load",c),window.attachEvent("resize",c))}var s="readmore",r={speed:100,collapsedHeight:200,heightMargin:16,moreLink:'<a href="#">Read More</a>',lessLink:'<a href="#">Close</a>',embedCSS:!0,blockCSS:"display: block; width: 100%;",startOpen:!1,blockProcessed:function(){},beforeToggle:function(){},afterToggle:function(){}},d={},h=0,c=e(function(){t("[data-readmore]").each(function(){var e=t(this),i="true"===e.attr("aria-expanded");o(e),e.css({height:e.data(i?"expandedHeight":"collapsedHeight")})})},100);a.prototype={init:function(){var e=t(this.element);e.data({defaultHeight:this.options.collapsedHeight,heightMargin:this.options.heightMargin}),o(e);var n=e.data("collapsedHeight"),a=e.data("heightMargin");if(e.outerHeight(!0)<=n+a)return this.options.blockProcessed&&"function"==typeof this.options.blockProcessed&&this.options.blockProcessed(e,!1),!0;var s=e.attr("id")||i(),r=this.options.startOpen?this.options.lessLink:this.options.moreLink;e.attr({"data-readmore":"","aria-expanded":this.options.startOpen,id:s}),e.after(t(r).on("click",function(t){return function(i){t.toggle(this,e[0],i)}}(this)).attr({"data-readmore-toggle":s,"aria-controls":s})),this.options.startOpen||e.css({height:n}),this.options.blockProcessed&&"function"==typeof this.options.blockProcessed&&this.options.blockProcessed(e,!0)},toggle:function(e,i,o){o&&o.preventDefault(),e||(e=t('[aria-controls="'+this.element.id+'"]')[0]),i||(i=this.element);var n=t(i),a="",s="",r=!1,d=n.data("collapsedHeight");n.height()<=d?(a=n.data("expandedHeight")+"px",s="lessLink",r=!0):(a=d,s="moreLink"),this.options.beforeToggle&&"function"==typeof this.options.beforeToggle&&this.options.beforeToggle(e,n,!r),n.css({height:a}),n.on("transitionend",function(i){return function(){i.options.afterToggle&&"function"==typeof i.options.afterToggle&&i.options.afterToggle(e,n,r),t(this).attr({"aria-expanded":r}).off("transitionend")}}(this)),t(e).replaceWith(t(this.options[s]).on("click",function(t){return function(e){t.toggle(this,i,e)}}(this)).attr({"data-readmore-toggle":n.attr("id"),"aria-controls":n.attr("id")}))},destroy:function(){t(this.element).each(function(){var e=t(this);e.attr({"data-readmore":null,"aria-expanded":null}).css({maxHeight:"",height:""}).next("[data-readmore-toggle]").remove(),e.removeData()})}},t.fn.readmore=function(e){var i=arguments,o=this.selector;return e=e||{},"object"==typeof e?this.each(function(){if(t.data(this,"plugin_"+s)){var i=t.data(this,"plugin_"+s);i.destroy.apply(i)}e.selector=o,t.data(this,"plugin_"+s,new a(this,e))}):"string"==typeof e&&"_"!==e[0]&&"init"!==e?this.each(function(){var o=t.data(this,"plugin_"+s);o instanceof a&&"function"==typeof o[e]&&o[e].apply(o,Array.prototype.slice.call(i,1))}):void 0}});

  //all functions go here

  readMore();
  already_on_wishlist();
  change_wishlist_link_text_in_account();
  change_button_text_after_wishlist();
  unique_select();
  products_masonry();
  product_manager_sizes();
  membership_table_hover();
  sliderOptions();
  subscriptions_adjust_pricing();
  price_range_settings();
  my_collection_targetblank();
  selective_check_medium();
  selective_check_genre();
  mobmenu_scroll();
  video_infinite_scroll();
  video_click_interactions();
  embed_codes();

  function readMore() {
    $('.um-field-textarea').readmore({
      speed: 100,
      moreLink: '<div class="readMore-btn"><a href="#"><i class="fa fa-angle-down" aria-hidden="true"></i></a></div>',
      lessLink: '<div class="readMore-btn"><a href="#"><i class="fa fa-angle-up" aria-hidden="true"></i></a></div>',
      collapsedHeight: 300,
      heightMargin: 50
    });
    if ($(window).width() < 768) {
      $('.um-row.aboutMe .um-field-value').readmore({
          speed: 100,
          moreLink: '<div class="readMore-btn"><a href="#"><i class="fa fa-angle-down" aria-hidden="true"></i></a></div>',
          lessLink: '<div class="readMore-btn"><a href="#"><i class="fa fa-angle-up" aria-hidden="true"></i></a></div>',
      });
    }
    $('.product_taxonomy_sub_checklist').readmore({
      speed: 200,
      moreLink: '<i class="subcat-toggler fa fa-angle-down" aria-hidden="true"></i>',
      lessLink: '<i class="subcat-toggler fa fa-angle-up" aria-hidden="true"></i>',
      collapsedHeight: 0,
      heightMargin: 50,
      afterToggle: function(trigger, element, expanded) {
         if(expanded) { // The "Open" link was clicked
           $(element).css( 'height', 'auto' );
         } else {
           $(element).css( 'height', collheight + 'px'  );
         }
      }
    });
    $('.woocommerce-Tabs-panel .artist_meta').readmore({
      speed: 200,
      moreLink: '<div class="readMore-btn"><a href="#"><i class="fa fa-angle-down" aria-hidden="true"></i></a></div>',
      lessLink: '<div class="readMore-btn"><a href="#"><i class="fa fa-angle-up" aria-hidden="true"></i></a></div>',
      collapsedHeight: 200,
      heightMargin: 50,
      afterToggle: function(trigger, element, expanded) {
         if(expanded) { // The "Open" link was clicked
           $(element).css( 'height', 'auto' );
         } else {
           $(element).css( 'height', collheight + 'px'  );
         }
      }
    });
    $('.single-product .woocommerce-product_details.artwork_description').readmore({
      speed: 100,
      moreLink: '<div class="readMore-btn"><a href="#"><i class="fa fa-angle-down" aria-hidden="true"></i></a></div>',
      lessLink: '<div class="readMore-btn"><a href="#"><i class="fa fa-angle-up" aria-hidden="true"></i></a></div>',
      collapsedHeight: 105,
      heightMargin: 50
    })
    $('.product_cats_checklist_item label').click(function() {
      $(this).parent().find('.product_taxonomy_sub_checklist').readmore('toggle');
    });

    var collheight = $('.woof_reset_search_form').length > 0 ? 100 : 50;

    if ($(window).width() < 768) {
      $('.woocommerce #secondary.widget-area').readmore({
          speed: 100,
          moreLink: '<div class="readMore-btn"><a href="#">FILTERS <i class="fa fa-angle-down" aria-hidden="true"></i></a></div>',
          lessLink: '<div class="readMore-btn"><a href="#">FILTERS <i class="fa fa-angle-up" aria-hidden="true"></i></a></div>',
          collapsedHeight: collheight,
          beforeToggle: function(trigger, element, expanded) {
             if(expanded) { // The "Open" link was clicked
               $('.woocommerce-products-header').removeClass('shrink');
             } else {
               $('.woocommerce-products-header').addClass('shrink');
             }
          },
          afterToggle: function(trigger, element, expanded) {
             if(expanded) { // The "Open" link was clicked
               $(element).css( 'height', 'auto' );
             } else {
               $(element).css( 'height', collheight + 'px'  );
             }
          }
        });
      }
  }

  function already_on_wishlist() {
    $('.wl-already-in ul li a').addClass('button alt');
    $('.wl-already-in ul li a').text(trans_object.seeOnWishlist);
  }

  function change_wishlist_link_text_in_account() {
    $('.woocommerce-MyAccount-navigation-link.woocommerce-MyAccount-navigation-link--account-wishlists a').html( trans_object.wishlist );
  }

  function change_button_text_after_wishlist() {
    $('.wl-add-to.wl-add-but.button.present').click(function(){
    $(this).html( trans_object.added );
    $('div.wl-list-pop.woocommerce').css('visibility', 'hidden');
    function trigger(){
      $('div.wl-list-pop.woocommerce dl dd a:first-child').trigger("click");
    }
    setTimeout(trigger, 500);
    });
  }

  function unique_select() {
    var $select = $('select#unique');
    $select.each(function() {
        $(this).addClass($(this).children(':selected').val());
    }).on('change', function(ev) {
        $(this).removeClass('0').removeClass('1').addClass($(this).children(':selected').val());
    });
  }

  function products_masonry() {

  	// Main content container
  	var $container = $('.woocommerce ul.products');

  	// Masonry + ImagesLoaded
  	$container.imagesLoaded(function(){
  		$container.masonry({
  			itemSelector: 'li.product',
        horizontalOrder: true
  		});
  	});

  	// Infinite Scroll
  	$container.infinitescroll({

  		// selector for the paged navigation (it will be hidden)
  		navSelector  : ".page-numbers",
  		// selector for the NEXT link (to page 2)
  		nextSelector : ".page-numbers.next",
  		// selector for all items you'll retrieve
  		itemSelector : "li.product",
      //prefill
      loadingText: 'Loading new items…',
      behavior : 'twitter',
  		// finished message
  		loading: { finishedMsg: trans_object.finishedMsg, img: "https://i.imgur.com/qkKy8.gif", msgText: trans_object.msgText }

      },

  		// Trigger Masonry as a callback
  		function( newElements ) {

        if ( newElements ){
    			// hide new items while they are loading
    			var $newElems = $( newElements ).css({ opacity: 0 });
          $('#loading').show();
    			// ensure that images load before adding to masonry layout
    			$newElems.imagesLoaded(function(){
    				// show elems now they're ready
    				$newElems.animate({ opacity: 1 });
    				$container.masonry( 'appended', $newElems, true );
            $('#loading').hide();
    			});
        } else {
          console.log('No more');
        }

  	});

  };

  function changeTo3D (input){
    $('.size-containers').removeClass('twodimensional').addClass('threedimensional');
  }

  function changeTo2D(input) {
    $('.size-containers').removeClass('threedimensional').addClass('twodimensional');
  }

  function product_manager_sizes() {
    var check = $('input#dimensions');
    var checked = check.prop('checked');
    if (checked) {
      changeTo3D(check);
    } else {
      changeTo2D(check);
    }

    $(check).change(function(){
      var checked = check.prop('checked');
      if (checked) {
        changeTo3D(check);
        //$('.size-containers input.wcfm-text').val('');
        $('.size-containers input#art_length').trigger("change");
        $('.size-containers input#art_width').trigger("change");
        $('.size-containers input#art_height').trigger("change");

      } else {
        changeTo2D(check);
        //$('.size-containers input.wcfm-text').val('');
        $('.size-containers input#art_length').trigger("change");
        $('.size-containers input#art_height').trigger("change");
      }
    });
  };


  $('.woof_show_text_search').keypress(function (e) {
   var key = e.which;
   if(key == 13)  // the enter key code
    {
      $('.woof_submit_search_form').click();
      return false;
    }
  });

  if($('fieldset#edition input[name="edition"]').val() == 1) {
    $(this).parent().parent().addClass('edition');
  } else {
    $(this).parent().parent().removeClass('edition');
  }

  $('fieldset#edition input[name="edition"]').change(function() {
    if($(this).val() == 1) {
      $(this).parent().parent().addClass('edition');
    } else {
      $(this).parent().parent().removeClass('edition');
    }
  });

    function sliderOptions() {

      var memberSliderCounter = 0;

      var sliders = $('.members-slider');

      var ie = $(document.body).hasClass('ie');

      if ((sliders.length > 0) && (!ie)) { //if there's at least one slider on the page
        var slideInterval = setInterval(slideAuto, 3000);
      }

      function slideAuto() {
        var showreel = $('.members-slider-container');
        var showreelWidth = parseFloat($(showreel).outerWidth( true )).toFixed(3);
        var showreelLength = $('.members-slider-container').first().find('.um-member-slider').length;
        var child = $('.members-slider-container').first().find('.um-member-slider');
        //var childStyle = $(window).getComputedStyle ? getComputedStyle($(child), null) : $(child).currentStyle;
        var childWidth = $(child).outerWidth( true ).toFixed(3);
        var showing = Math.round(showreelWidth / childWidth);
        var childWidth = showreelWidth / showing;

        if (memberSliderCounter < (showreelLength - showing)) {
          memberSliderCounter++;
          $(showreel).css('transform', 'translateX(-' + parseFloat(childWidth * memberSliderCounter).toFixed(3) + 'px)');
        } else {
          memberSliderCounter = 0;
          $(showreel).css('transform', 'translateX(-' + parseFloat(childWidth * memberSliderCounter).toFixed(3) + 'px)');
        }
      }

      function slideForward(e) {

        var showreel = $(e.target).closest('.members-slider').find('.members-slider-container');
        var showreelWidth = parseFloat($(showreel).outerWidth( true )).toFixed(3);
        var showreelLength = $(e.target).closest('.members-slider').find('.members-slider-container').first().find('.um-member-slider').length;
        var child = $(e.target).closest('.members-slider').find('.members-slider-container').first().find('.um-member-slider');
        //var childStyle = $(window).getComputedStyle ? getComputedStyle($(child), null) : $(child).currentStyle;
        var childWidth = $(child).outerWidth( true ).toFixed(3);
        var showing = Math.round(showreelWidth / childWidth);
        var childWidth = showreelWidth / showing;


        if (memberSliderCounter < (showreelLength - showing)) {
          memberSliderCounter++;
          if (ie) {
            $(showreel).css('transform', 'translateX(-' + parseFloat(16.66 * memberSliderCounter) + '%)');
            $(showreel).css('transform', 'translateX(-' + parseFloat(17 * memberSliderCounter) + '%)');
          } else {
            $(showreel).css('transform', 'translateX(-' + parseFloat(childWidth * memberSliderCounter).toFixed(3) + 'px)');
          }
        } else {
          memberSliderCounter = 0;
          if (ie) {
            $(showreel).css('transform', 'translateX(-' + parseFloat(16.66 * memberSliderCounter) + '%)');
            $(showreel).css('transform', 'translateX(-' + parseFloat(17 * memberSliderCounter) + '%)');
          } else {
            $(showreel).css('transform', 'translateX(-' + parseFloat(childWidth * memberSliderCounter).toFixed(3) + 'px)');
          }
        }
      }

      function slideBackwards(e) {
        clearInterval(slideInterval);
        var showreel = $(e.target).closest('.members-slider').find('.members-slider-container');
        var showreelWidth = parseFloat($(showreel).outerWidth( true )).toFixed(3);
        var showreelLength = $(e.target).closest('.members-slider').find('.members-slider-container').first().find('.um-member-slider').length;
        var child = $(e.target).closest('.members-slider').find('.members-slider-container').first().find('.um-member-slider');
        //var childStyle = $(window).getComputedStyle ? getComputedStyle($(child), null) : $(child).currentStyle;
        var childWidth = $(child).outerWidth( true ).toFixed(3);
        var showing = Math.round(showreelWidth / childWidth);
        var childWidth = showreelWidth / showing;

        if (memberSliderCounter > 0) {
          memberSliderCounter--;
          $(showreel).css('transform', 'translateX(-' + (childWidth * memberSliderCounter) + 'px)');
        } else {
          memberSliderCounter = showreelLength - showing;
          $(showreel).css('transform', 'translateX(-' + (childWidth * memberSliderCounter) + 'px)');
        }
      }

      $('.slider-nav-bck').click(function(e) {
        slideBackwards(e);
        clearInterval(slideInterval);
      });

      $('.slider-nav-fwd').click(function(e) {
        slideForward(e);
        clearInterval(slideInterval);
      });

      $( ".members-slider-container" ).on( "swipeleft", function() {
        $(".slider-nav-fwd").trigger('click');
        clearInterval(slideInterval);
      });

      $( ".members-slider-container" ).on( "swiperight", function() {
        $(".slider-nav-bck").trigger('click');
        clearInterval(slideInterval);
      });

    }


  function subscriptions_adjust_pricing() {

    var member_box = $('.wcfm_membership_boxes').find('.wcfm_membership_box');

    console.log(member_box);

    function switch_subscriptions (component, m_id) {
      for (var j = 0; j < component.length; j++) {
        var tag = component[j];
        var tagid = $(tag).attr("data-membership-id");
        if ( tagid === m_id) {
          $(tag).css('display', 'block');
        } else {
          $(tag).css('display', 'none');
        }
      }
    }

    for (var i = 0; i < member_box.length; i++) {

      var current_box = member_box[i];
      var pricing_select = $(current_box).find('.subscription_pricing');
      var membership_id = $(pricing_select).children("option:selected").val();
      var container = $(pricing_select).parent();
      var price_tag = $(container).find('.wcfm_membership_price');
      var subscribe_button = $(container).find('.wcfm_membership_subscribe_button_wrapper');
      var subscribe_button_footer = $(container).parent().find('.wcfm_membership_box_foot .wcfm_membership_subscribe_button_wrapper');

      switch_subscriptions(price_tag, membership_id);
      switch_subscriptions(subscribe_button, membership_id);
      switch_subscriptions(subscribe_button_footer, membership_id);

    }

    $('.subscription_pricing').change(function() {
      membership_id = $(this).children("option:selected").val();
      var container = $(this).parent();
      var price_tag = $(container).find('.wcfm_membership_price');
      var subscribe_button = $(container).find('.wcfm_membership_subscribe_button_wrapper');
      var subscribe_button_footer = $(container).parent().find('.wcfm_membership_box_foot .wcfm_membership_subscribe_button_wrapper');
      switch_subscriptions(price_tag, membership_id);
      switch_subscriptions(subscribe_button, membership_id);
      switch_subscriptions(subscribe_button_footer, membership_id);
    });

  }

  function membership_table_hover() {
      $('.wcfm_membership_box_body .wcfm_membership_element').hover(function () {
        var index = $(this).index();
        var oneBased = index + 1;
        $('.wcfm_membership_box_body .wcfm_membership_element:nth-of-type(' + oneBased + ')').addClass('wcfm-table-grey');
      }, function () {
        var index = $(this).index();
        var oneBased = index + 1;
        $('.wcfm_membership_box_body .wcfm_membership_element:nth-of-type(' + oneBased + ')').removeClass('wcfm-table-grey');
      });
  }

    function price_range_settings() {
      var ion = $('input.woof_range_slider');

      var woocs = $('#secondary .woocommerce-currency-switcher');

      $(woocs).appendTo($('#secondary .woof_price3_search_container'));

      //var ionData = $(ion).data("max", 100000);

      function get_min_val() {
        var max_price_log = Math.log($(ion).data('min-now'));
        // calculate logarithms
        var minLog = Math.log($(ion).data('min')),
            maxLog = Math.log($(ion).data('max'));
        var frac = (max_price_log - minLog) / (maxLog - minLog);
        var result = $(ion).data('max') * frac;
        return result;
      }

      function get_max_val() {
        var max_price_log = Math.log($(ion).data('max-now'));
        // calculate logarithms
        var minLog = Math.log($(ion).data('min')),
            maxLog = Math.log($(ion).data('max'));
        var frac = (max_price_log - minLog) / (maxLog - minLog);
        var result = $(ion).data('min') + (($(ion).data('max') - $(ion).data('min')) * frac);
        return Math.ceil(result);
      }

      if($(ion).length > 0) {

      $(ion).ionRangeSlider({
        min: $(ion).data('min'),
        max: $(ion).data('max'),
        from: get_min_val(),
        to: get_max_val(),
        type: 'double',
        prefix: $(ion).data('slider-prefix'),
        postfix: $(ion).data('slider-postfix'),
        prettify_enabled: true,
        prettify: function (n) {
            // current position
            var position = Math.floor(n / this.max * 100);
            // position will be between 0 and 100
            var minPos = 0,
                maxPos = 100;
            // calculate logarithms
            var minLog = Math.log(this.min),
                maxLog = Math.log(this.max);
            // calculate adjustment factor
            var scale = (maxLog-minLog) / (maxPos-minPos);
            // round numbers
            n = Math.ceil(Math.exp(minLog + scale * (position - minPos)))
            return n;
        },
        hideMinMax: true,
        hideFromTo: false,
        grid: true,
        onFinish: function (ui) {
                        var tax=$(ion).data('taxes');
                        console.log(tax);
            woof_current_values.min_price = (parseInt(this.prettify(ui.from), 10)/tax);
            woof_current_values.max_price = (parseInt(this.prettify(ui.to), 10)/tax);
          //   //woocs adaptation
            if (typeof woocs_current_currency !== 'undefined') {
          woof_current_values.min_price = Math.ceil(woof_current_values.min_price / parseFloat(woocs_current_currency.rate));
          woof_current_values.max_price = Math.ceil(woof_current_values.max_price / parseFloat(woocs_current_currency.rate));
            }
          //   //***
            woof_ajax_page_num = 1;
            //$(input).within('.woof').length -> if slider is as shortcode
            if (woof_autosubmit || $(ion).within('.woof').length == 0) {
          woof_submit_link(woof_get_submit_link());
            }
            return false;
        }
      });
    }
    }

    // helper to get query parameters
    function getParameter(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results==null) {
           return null;
        }
        return decodeURI(results[1]) || 0;
    }

    function my_collection_targetblank() {
      // var my_collection_link = $('.um-profile-nav-item.um-profile-nav-my_collection');
      if ($('.um-profile-nav-item.um-profile-nav-my_collection a').length > 0) {
        $('.um-profile-nav-item.um-profile-nav-my_collection a').attr('target', '_blank');
      }
    }

    function selective_check_medium() {
      var checks = $("#medium_cat > .product_cats_checklist_item > .selectit > .wcfm-checkbox");
      var checksSub = $("#medium_cat > .product_cats_checklist_item > .product_taxonomy_sub_checklist .wcfm-checkbox");
      var max = 2;
      var maxSub = 1;
      for (var i = 0; i < checks.length; i++)
        checks[i].onclick = selectiveCheck;
      for (var i = 0; i < checksSub.length; i++)
        checksSub[i].onclick = selectiveCheckSub;

      function selectiveCheck (event) {
        var checkedAll = $("#medium_cat .product_cats_checklist_item .selectit .wcfm-checkbox:checked");
        var checkedChecks = $("#medium_cat > .product_cats_checklist_item > .selectit > .wcfm-checkbox:checked");
        if ((checkedChecks.length >= max + 1))  {
          if ($(this).parent().find('.selectit__warning').length === 0) {
            $(this).parent().append('<span class="selectit__warning fadeOut">Limit exceeded!</span>');
          } else {
            $(this).parent().find('.selectit__warning').remove();
            $(this).parent().append('<span class="selectit__warning fadeOut">Limit exceeded!</span>');
          }
          return false;
        } else if(!this.checked){ // if checked - check all parent checkboxes
          $(this).parents('.product_cats_checklist_item').children('.product_taxonomy_sub_checklist').find('input[type=checkbox]').prop('checked',false);
        }
      }

      function selectiveCheckSub (event) {
        //var checkedAll = $("#medium_cat .product_cats_checklist_item .selectit .wcfm-checkbox:checked");
        var isParentChecked = $(this).parents('.product_cats_checklist_item').children('label.selectit').find('input[type=checkbox]').prop('checked');

        var checkedParen = $("#medium_cat > .product_cats_checklist_item > .selectit > .wcfm-checkbox:checked");
        var checkedChecks = $(this).parents('.product_taxonomy_sub_checklist').find('input[type=checkbox]:checked');
        if ( this.checked && ((checkedChecks.length >= maxSub + 1) || ((checkedParen.length >= max) && !isParentChecked))) {
          if ($(this).parent().find('.selectit__warning').length === 0) {
            $(this).parent().append('<span class="selectit__warning fadeOut">Limit exceeded!</span>');
          } else {
            $(this).parent().find('.selectit__warning').remove();
            $(this).parent().append('<span class="selectit__warning fadeOut">Limit exceeded!</span>');
          }
          return false;
        } else if(this.checked){ // if checked - check all parent checkboxes
          $(this).parents('.product_cats_checklist_item').children('label.selectit').find('input[type=checkbox]').prop('checked',true);
        } else {
          $(this).parents('.product_cats_checklist_item').children('label.selectit').find('input[type=checkbox]').prop('checked',false);
        }
      }
    }

    function selective_check_genre() {
      var checks = $("#genre_tag > .product_cats_checklist_item > .selectit > .wcfm-checkbox");
      var max = 3;
      for (var i = 0; i < checks.length; i++)
        checks[i].onclick = selectiveCheck;

      function selectiveCheck (event) {
        var checkedChecks = $("#genre_tag > .product_cats_checklist_item > .selectit > .wcfm-checkbox:checked");
        if (checkedChecks.length >= max + 1) {
          if ($(this).parent().find('.selectit__warning').length === 0) {
            $(this).parent().append('<span class="selectit__warning fadeOut">Limit exceeded!</span>');
          } else {
            $(this).parent().find('.selectit__warning').remove();
            $(this).parent().append('<span class="selectit__warning fadeOut">Limit exceeded!</span>');
          }
          return false;
        }
      }
    }


    function mobmenu_scroll() {
      var mobmenuContent = $('.mob-menu-content-container');
      var arrowTop = $(mobmenuContent).find('.mob-menu-arrow-top');
      var arrowBottom = $(mobmenuContent).find('.mob-menu-arrow-bottom');
      var windowHeight = window.screen.height;
      $(mobmenuContent).on('scroll', function() {

        var scrollValue = this.scrollTop;
        var containerHeight = this.offsetHeight;
        var contentHeight = $(this).find('.mobmenu_content').outerHeight();

        if (scrollValue === (contentHeight - containerHeight)) {
          $(arrowBottom).addClass('mobmenu-arrow-hide');
        } else if (scrollValue === 0){
          $(arrowTop).addClass('mobmenu-arrow-hide');
        } else {
          $(arrowTop).removeClass('mobmenu-arrow-hide');
          $(arrowBottom).removeClass('mobmenu-arrow-hide');
        }

      });
    }

    function video_infinite_scroll() {

      var count = 0;
      var container = $('.youtube-videos-container');
      if ( $(container).length > 0 ) {

        var timer;
        var windowHeight = $(window).height();
        var triggerHeight = 0.5 * windowHeight;

        load_videos(count);

        $(window).on('scroll', function() {

          if(timer) {
        		window.clearTimeout(timer);
        	}

        	timer = window.setTimeout(function() {
        		let scrollTop = $(this).scrollTop() + $(window).height();
            let containerPos = $(container).offset().top + $(container).outerHeight();
              if(scrollTop >= containerPos){
                count++;
                load_videos(count);
        	    }
        	}, 1000);
        });
      }
    }

    function load_videos(page) {

      let urlParams = new URLSearchParams(window.location.search);
      let category = urlParams.get('category');
      let country = urlParams.get('country');
      let role = urlParams.get('role');
      let sort = urlParams.get('sort');
      let user_display_name = urlParams.get('user_display_name');

      $.ajax({
        url : trans_object.ajax_url,
        type : 'post',
        data : {
          action : 'artmo_ajax_get_user_videos',
          videos_page : page,
          videos_category : category,
          videos_country : country,
          videos_role : role,
          videos_sort : sort,
          videos_name : user_display_name
        },
        success : function( response ) {
          if ( response !== '') {
            let content = $('.youtube-videos').append(response);
          } else {
            $('.youtube-videos-preloader').html('<span class="no-results">No results.</span>');
          }

        }
      });
    }

    function video_click_interactions() {

      $('.youtube-videos-container').on('click', '.video-single .video_thumb', function() {
        let platform = $(this).parent().attr('data-platform');
        let videoid = $(this).parent().attr('data-videoid');
        $('.youtube-videos-player-container').addClass('open');
        if (platform === 'youtube') {
          $('.youtube-videos-player').html('<iframe src="https://www.youtube.com/embed/' + videoid + '" width=560 height=315 frameborder=0 allowfullscreen></iframe>');
        } else if (platform === 'vimeo') {
          $('.youtube-videos-player').html('<iframe src="https://player.vimeo.com/video/' + videoid + '?color=ffffff&title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
        }
      });

      $('.youtube-videos-player-overlay').click(function() {
        $('.youtube-videos-player-container').removeClass('open');
        $('.youtube-videos-player').html('');
      });
    }

    function embed_codes() {

      let selectColorField = $('.embed-options .um-search-filter.embed-color input[type="radio"]:checked');
      let selectSizeField = $('.embed-options .um-search-filter.embed-size input[type="radio"]:checked');
      let selectColor = $(selectColorField).val();
      let selectSize = $(selectSizeField).val();

      $('.embed-generator').find('.embed-iframe.' + selectColor + '.' + selectSize).addClass('embed-visible');
      $('.embed-options').find('.embed-copy-code.' + selectColor + '.' + selectSize).addClass('embed-visible');

      $('.embed-generator').on('change', '.um-search-filter.embed-color input[type="radio"]', function() {
        selectColor = $(this).val();
        let bgColor = (selectColor === 'white' ? '#000' : '#f5f5f5');
        $(this).closest('.embed-generator').find('.embed-preview-stage').css('background', bgColor);
        $(this).closest('.embed-generator').find('.embed-iframe.' + selectColor + '.' + selectSize).addClass('embed-visible').siblings().removeClass('embed-visible');
        $(this).closest('.embed-options').find('.embed-copy-code.' + selectColor + '.' + selectSize).addClass('embed-visible').siblings().removeClass('embed-visible');
      });

      $('.embed-generator').on('change', '.um-search-filter.embed-size input[type="radio"]', function() {
        selectSize = $(this).val();
        $(this).closest('.embed-generator').find('.embed-iframe.' + selectColor + '.' + selectSize).addClass('embed-visible').siblings().removeClass('embed-visible');
        $(this).closest('.embed-options').find('.embed-copy-code.' + selectColor + '.' + selectSize).addClass('embed-visible').siblings().removeClass('embed-visible');
      });

      $('.embed-copy-btn').on('click', function() {
        let that = this;
        $(this).siblings('.embed-visible').first().find('input').select();
        document.execCommand("copy");
        $(this).text('Copied!');
        setTimeout(function() {
          $(that).text('Copy the code');
        }, 2000);
      });

    }

  jQuery('.ajax-upload-dragdrop').remove();
  jQuery('.um-groups-insert-photo').each(function () {

    apu = jQuery(this);
    var formData = {
      key: 'wall_img_upload',
      action: 'um_imageupload',
      set_id: 0,
      set_mode: 'wall',
      timestamp: apu.data('timestamp'),
      _wpnonce: apu.data('nonce'),
      group_id: jQuery('input[name="group_id"]').val()
    };

    apu.uploadFile({
      url: wp.ajax.settings.url,
      method: "POST",
      multiple: false,
      formData: formData,
      fileName: 'wall_img_upload',
      allowedTypes: apu.attr('data-allowed'),
      maxFileSize: 9999999,
      dragDropStr: '',
      sizeErrorStr: apu.attr('data-size-err'),
      extErrorStr: apu.attr('data-ext-err'),
      maxFileCountErrorStr: '',
      maxFileCount: 1,
      showDelete: false,
      showAbort: false,
      showDone: false,
      showFileCounter: false,
      showStatusAfterSuccess: true,
      returnType: 'json',
      onSubmit: function (files) {

        apu.parents('.um-groups-widget').find('.um-error-block').remove();
        apu.parents('.um-groups-widget').find('.um-groups-post').addClass('um-disabled');
        apu.parents('.um-groups-widget').find('.um-groups-preview').hide();
        apu.parents('.um-groups-widget').find('.um-groups-preview img').attr('src', '');
        apu.parents('.um-groups-widget').find('.um-groups-preview input[type=hidden]').val('');

      },

      onSuccess: function (files, response, xhr) {

        apu.selectedFiles = 0;

        if ( response.status && response.status == false ) {

          apu.parents('.um-groups-widget').find('.um-groups-post').addClass('um-disabled');

          apu.parents('.um-groups-widget').find('.um-groups-textarea-elem').attr('placeholder', jQuery('.um-groups-textarea-elem').attr('data-ph'));

          apu.parents('.um-groups-widget').find('.upload-statusbar').prev('div').append('<div class="um-error-block">' + response.error + '</div>');

          apu.parents('.um-groups-widget').find('.upload-statusbar').remove();

        } else {

          apu.parents('.um-groups-widget').find('.um-groups-post').removeClass('um-disabled');

          apu.parents('.um-groups-widget').find('.um-groups-textarea-elem').attr('placeholder', jQuery('.um-groups-textarea-elem').attr('data-photoph'));

          apu.parents('.um-groups-widget').find('.upload-statusbar').remove();

          jQuery.each( response.data, function ( key, data ) {

            apu.parents('.um-groups-widget').find('.um-groups-preview').show();
            apu.parents('.um-groups-widget').find('.um-groups-preview img').attr('src', data.url );
            apu.parents('.um-groups-widget').find('.um-groups-preview input[type=hidden][name="_post_img"]').val( data.file );
            apu.parents('.um-groups-widget').find('.um-groups-preview input[type=hidden][name="_post_img_url"]').val( data.url );

          });

        }

      },
      onError: function( e ){
        console.log( e );
      }
    });

  });

});
