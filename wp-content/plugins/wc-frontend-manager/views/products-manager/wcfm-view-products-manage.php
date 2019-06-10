<?php
global $wp, $WCFM, $wc_product_attributes, $country_arr;

$wcfm_is_allow_manage_products = apply_filters( 'wcfm_is_allow_manage_products', true );
if( !current_user_can( 'edit_products' ) || !$wcfm_is_allow_manage_products ) {
	wcfm_restriction_message_show( "Products" );
	return;
}

if( isset( $wp->query_vars['wcfm-products-manage'] ) && empty( $wp->query_vars['wcfm-products-manage'] ) ) {
	if( !apply_filters( 'wcfm_is_allow_add_products', true ) ) {
		wcfm_restriction_message_show( "Add Product" );
		return;
	}
	if( !apply_filters( 'wcfm_is_allow_product_limit', true ) ) {
		wcfm_restriction_message_show( "Product Limit Reached" );?>
		<div class="wcfm_upgrade overlay">
			<div class="wcfm_upgrade-popup">
				<svg width="72" height="72" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M6 70H5C5 70.5523 5.44772 71 6 71V70ZM17 70V71C17.5523 71 18 70.5523 18 70H17ZM17 44.5V43.5C16.4477 43.5 16 43.9477 16 44.5H17ZM21.5 44.5V45.5C21.8918 45.5 22.2475 45.2712 22.41 44.9146C22.5725 44.5581 22.5117 44.1395 22.2546 43.8438L21.5 44.5ZM11.5 33L12.2546 32.3438C12.0647 32.1254 11.7894 32 11.5 32C11.2106 32 10.9353 32.1254 10.7454 32.3438L11.5 33ZM1.5 44.5L0.745394 43.8438C0.488292 44.1395 0.42755 44.5581 0.590006 44.9146C0.752462 45.2712 1.10818 45.5 1.5 45.5L1.5 44.5ZM6 44.5H7C7 43.9477 6.55228 43.5 6 43.5V44.5ZM6 71H17V69H6V71ZM18 70V44.5H16V70H18ZM17 45.5H21.5V43.5H17V45.5ZM22.2546 43.8438L12.2546 32.3438L10.7454 33.6562L20.7454 45.1562L22.2546 43.8438ZM10.7454 32.3438L0.745394 43.8438L2.25461 45.1562L12.2546 33.6562L10.7454 32.3438ZM1.5 45.5H6V43.5H1.5V45.5ZM5 44.5V70H7V44.5H5Z" fill="black"/>
				<path d="M30.5 70.5H29.5C29.5 71.0523 29.9477 71.5 30.5 71.5V70.5ZM41.5 70.5V71.5C42.0523 71.5 42.5 71.0523 42.5 70.5H41.5ZM41.5 30.5V29.5C40.9477 29.5 40.5 29.9477 40.5 30.5H41.5ZM46 30.5V31.5C46.3918 31.5 46.7475 31.2712 46.91 30.9146C47.0725 30.5581 47.0117 30.1395 46.7546 29.8438L46 30.5ZM36 19L36.7546 18.3438C36.5647 18.1254 36.2894 18 36 18C35.7106 18 35.4353 18.1254 35.2454 18.3438L36 19ZM26 30.5L25.2454 29.8438C24.9883 30.1395 24.9275 30.5581 25.09 30.9146C25.2525 31.2712 25.6082 31.5 26 31.5V30.5ZM30.5 30.5H31.5C31.5 29.9477 31.0523 29.5 30.5 29.5V30.5ZM30.5 71.5H41.5V69.5H30.5V71.5ZM42.5 70.5V30.5H40.5V70.5H42.5ZM41.5 31.5H46V29.5H41.5V31.5ZM46.7546 29.8438L36.7546 18.3438L35.2454 19.6562L45.2454 31.1562L46.7546 29.8438ZM35.2454 18.3438L25.2454 29.8438L26.7546 31.1562L36.7546 19.6562L35.2454 18.3438ZM26 31.5H30.5V29.5H26V31.5ZM29.5 30.5V70.5H31.5V30.5H29.5Z" fill="black"/>
				<path d="M54.5 70.5H53.5C53.5 71.0523 53.9477 71.5 54.5 71.5V70.5ZM65.5 70.5V71.5C66.0523 71.5 66.5 71.0523 66.5 70.5H65.5ZM65.5 17.5V16.5C64.9477 16.5 64.5 16.9477 64.5 17.5H65.5ZM70 17.5V18.5C70.3918 18.5 70.7475 18.2712 70.91 17.9146C71.0725 17.5581 71.0117 17.1395 70.7546 16.8438L70 17.5ZM60 6L60.7546 5.34382C60.5647 5.12541 60.2894 5 60 5C59.7106 5 59.4353 5.12541 59.2454 5.34382L60 6ZM50 17.5L49.2454 16.8438C48.9883 17.1395 48.9275 17.5581 49.09 17.9146C49.2525 18.2712 49.6082 18.5 50 18.5V17.5ZM54.5 17.5H55.5C55.5 16.9477 55.0523 16.5 54.5 16.5V17.5ZM54.5 71.5H65.5V69.5H54.5V71.5ZM66.5 70.5V17.5H64.5V70.5H66.5ZM65.5 18.5H70V16.5H65.5V18.5ZM70.7546 16.8438L60.7546 5.34382L59.2454 6.65618L69.2454 18.1562L70.7546 16.8438ZM59.2454 5.34382L49.2454 16.8438L50.7546 18.1562L60.7546 6.65618L59.2454 5.34382ZM50 18.5H54.5V16.5H50V18.5ZM53.5 17.5V70.5H55.5V17.5H53.5Z" fill="black"/>
				<rect x="25" y="36" width="22" height="36" fill="white"/>
				<path d="M30.5 70H41.5V59L43 57V48.5L41.5 46.5H38.5C38.5 46.5 40.1848 44.4142 40 42.5C39.7612 40.0262 38.4853 38 36 38C33.5147 38 32.2388 40.0262 32 42.5C31.8152 44.4142 33.5 46.5 33.5 46.5H30.5L29 48.5V57L30.5 59V70Z" stroke="black" stroke-width="2" stroke-linejoin="round"/>
				<path d="M34.5 49.5L36 51M36 51L37.5 49.5M36 51V55.5" stroke="black" stroke-width="2"/>
				<path d="M21.5 22V16.5M21.5 11V16.5M21.5 16.5H27M21.5 16.5H16" stroke="black" stroke-width="2"/>
				<path d="M5.5 11V5.5M5.5 0V5.5M5.5 5.5H11M5.5 5.5H0" stroke="black" stroke-width="2"/>
				<rect x="40" y="2" width="2" height="2" fill="black"/>
				<rect x="70" y="69" width="2" height="2" fill="black"/>
				</svg>
				<h2><?php _e("It's your time to upgrade.", "vantage-child");?></h2>
				<p><?php _e("You reached the maximum of artworks available for upload in your plan. See what you can do to get your art business to the next level.", "vantage-child");?></p>
				<a class="wcfm_upgrade-button" target="_blank" href="<?php echo get_wcfm_membership_url();?>"><?php _e('SEE AVAILABLE PLANS', 'vantage-child'); ?></a>
			</div>
		</div>
		<?php
		return;
	}


		if( !get_current_user_completeness() ) {
			wcfm_restriction_message_show( "Product Limit Reached" );?>
			<div class="wcfm_upgrade overlay">
				<div class="wcfm_upgrade-popup">
					<svg width="72" height="72" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M6 70H5C5 70.5523 5.44772 71 6 71V70ZM17 70V71C17.5523 71 18 70.5523 18 70H17ZM17 44.5V43.5C16.4477 43.5 16 43.9477 16 44.5H17ZM21.5 44.5V45.5C21.8918 45.5 22.2475 45.2712 22.41 44.9146C22.5725 44.5581 22.5117 44.1395 22.2546 43.8438L21.5 44.5ZM11.5 33L12.2546 32.3438C12.0647 32.1254 11.7894 32 11.5 32C11.2106 32 10.9353 32.1254 10.7454 32.3438L11.5 33ZM1.5 44.5L0.745394 43.8438C0.488292 44.1395 0.42755 44.5581 0.590006 44.9146C0.752462 45.2712 1.10818 45.5 1.5 45.5L1.5 44.5ZM6 44.5H7C7 43.9477 6.55228 43.5 6 43.5V44.5ZM6 71H17V69H6V71ZM18 70V44.5H16V70H18ZM17 45.5H21.5V43.5H17V45.5ZM22.2546 43.8438L12.2546 32.3438L10.7454 33.6562L20.7454 45.1562L22.2546 43.8438ZM10.7454 32.3438L0.745394 43.8438L2.25461 45.1562L12.2546 33.6562L10.7454 32.3438ZM1.5 45.5H6V43.5H1.5V45.5ZM5 44.5V70H7V44.5H5Z" fill="black"/>
					<path d="M30.5 70.5H29.5C29.5 71.0523 29.9477 71.5 30.5 71.5V70.5ZM41.5 70.5V71.5C42.0523 71.5 42.5 71.0523 42.5 70.5H41.5ZM41.5 30.5V29.5C40.9477 29.5 40.5 29.9477 40.5 30.5H41.5ZM46 30.5V31.5C46.3918 31.5 46.7475 31.2712 46.91 30.9146C47.0725 30.5581 47.0117 30.1395 46.7546 29.8438L46 30.5ZM36 19L36.7546 18.3438C36.5647 18.1254 36.2894 18 36 18C35.7106 18 35.4353 18.1254 35.2454 18.3438L36 19ZM26 30.5L25.2454 29.8438C24.9883 30.1395 24.9275 30.5581 25.09 30.9146C25.2525 31.2712 25.6082 31.5 26 31.5V30.5ZM30.5 30.5H31.5C31.5 29.9477 31.0523 29.5 30.5 29.5V30.5ZM30.5 71.5H41.5V69.5H30.5V71.5ZM42.5 70.5V30.5H40.5V70.5H42.5ZM41.5 31.5H46V29.5H41.5V31.5ZM46.7546 29.8438L36.7546 18.3438L35.2454 19.6562L45.2454 31.1562L46.7546 29.8438ZM35.2454 18.3438L25.2454 29.8438L26.7546 31.1562L36.7546 19.6562L35.2454 18.3438ZM26 31.5H30.5V29.5H26V31.5ZM29.5 30.5V70.5H31.5V30.5H29.5Z" fill="black"/>
					<path d="M54.5 70.5H53.5C53.5 71.0523 53.9477 71.5 54.5 71.5V70.5ZM65.5 70.5V71.5C66.0523 71.5 66.5 71.0523 66.5 70.5H65.5ZM65.5 17.5V16.5C64.9477 16.5 64.5 16.9477 64.5 17.5H65.5ZM70 17.5V18.5C70.3918 18.5 70.7475 18.2712 70.91 17.9146C71.0725 17.5581 71.0117 17.1395 70.7546 16.8438L70 17.5ZM60 6L60.7546 5.34382C60.5647 5.12541 60.2894 5 60 5C59.7106 5 59.4353 5.12541 59.2454 5.34382L60 6ZM50 17.5L49.2454 16.8438C48.9883 17.1395 48.9275 17.5581 49.09 17.9146C49.2525 18.2712 49.6082 18.5 50 18.5V17.5ZM54.5 17.5H55.5C55.5 16.9477 55.0523 16.5 54.5 16.5V17.5ZM54.5 71.5H65.5V69.5H54.5V71.5ZM66.5 70.5V17.5H64.5V70.5H66.5ZM65.5 18.5H70V16.5H65.5V18.5ZM70.7546 16.8438L60.7546 5.34382L59.2454 6.65618L69.2454 18.1562L70.7546 16.8438ZM59.2454 5.34382L49.2454 16.8438L50.7546 18.1562L60.7546 6.65618L59.2454 5.34382ZM50 18.5H54.5V16.5H50V18.5ZM53.5 17.5V70.5H55.5V17.5H53.5Z" fill="black"/>
					<rect x="25" y="36" width="22" height="36" fill="white"/>
					<path d="M30.5 70H41.5V59L43 57V48.5L41.5 46.5H38.5C38.5 46.5 40.1848 44.4142 40 42.5C39.7612 40.0262 38.4853 38 36 38C33.5147 38 32.2388 40.0262 32 42.5C31.8152 44.4142 33.5 46.5 33.5 46.5H30.5L29 48.5V57L30.5 59V70Z" stroke="black" stroke-width="2" stroke-linejoin="round"/>
					<path d="M34.5 49.5L36 51M36 51L37.5 49.5M36 51V55.5" stroke="black" stroke-width="2"/>
					<path d="M21.5 22V16.5M21.5 11V16.5M21.5 16.5H27M21.5 16.5H16" stroke="black" stroke-width="2"/>
					<path d="M5.5 11V5.5M5.5 0V5.5M5.5 5.5H11M5.5 5.5H0" stroke="black" stroke-width="2"/>
					<rect x="40" y="2" width="2" height="2" fill="black"/>
					<rect x="70" y="69" width="2" height="2" fill="black"/>
					</svg>
					<h2><?php _e("Let's get to know each other.", "vantage-child");?></h2>
					<p><?php _e("Complete your profile information to become an established ARTMO vendor and start selling your art. You need to provide your country, city, birth date, mobile phone number and a short bio.", "vantage-child");?></p>
					<a class="wcfm_upgrade-button" target="_blank" href="<?php echo get_home_url();?>/user/?profiletab=main&um_action=edit"><?php _e('COMPLETE PROFILE', 'vantage-child'); ?></a>
				</div>
			</div>
			<?php
			return;
		}

} elseif( isset( $wp->query_vars['wcfm-products-manage'] ) && !empty( $wp->query_vars['wcfm-products-manage'] ) ) {
	$wcfm_products_single = get_post( $wp->query_vars['wcfm-products-manage'] );
	if( $wcfm_products_single->post_status == 'publish' ) {
		if( !current_user_can( 'edit_published_products' ) || !apply_filters( 'wcfm_is_allow_edit_products', true ) ) {
			wcfm_restriction_message_show( "Edit Product" );
			return;
		}
	}
	if( wcfm_is_vendor() ) {
		$is_product_from_vendor = $WCFM->wcfm_vendor_support->wcfm_is_product_from_vendor( $wp->query_vars['wcfm-products-manage'] );
		if( !$is_product_from_vendor ) {
			wcfm_restriction_message_show( "Restricted Product" );
			return;
		}
	}
}

$product_id = 0;
$product = array();
$product_type = apply_filters( 'wcfm_default_product_type', '' );
$is_virtual = '';
$title = '';
$sku = '';
$visibility = 'visible';
$excerpt = '';
$description = '';
$regular_price = '';
$sale_price = '';
$sale_date_from = '';
$sale_date_upto = '';
$product_url = '';
$button_text = '';
$is_downloadable = '';
$children = array();


$featured_img = '';
$gallery_img_ids = array();
$gallery_img_urls = array();
$categories = array();
$product_tags = '';
$manage_stock = '';
$stock_qty = 0;
$backorders = '';
$stock_status = '';
$sold_individually = '';
$weight = '';
$length = '';
$width = '';
$height = '';
$shipping_class = '';
$tax_status = '';
$tax_class = '';
$attributes = array();
$default_attributes = '';
$attributes_select_type = array();
$variations = array();

$upsell_ids = array();
$crosssell_ids = array();

$locked = ( ($wcfm_products_single->post_status == 'publish') ? true : false );
$title_disabled = (($locked) ? 'disabled' : 'enabled');

if( isset( $wp->query_vars['wcfm-products-manage'] ) && !empty( $wp->query_vars['wcfm-products-manage'] ) ) {

	$product = wc_get_product( $wp->query_vars['wcfm-products-manage'] );
	// Fetching Product Data
	if($product && !empty($product)) {
		$product_id = $wp->query_vars['wcfm-products-manage'];
		$wcfm_products_single = get_post($product_id);
		$product_type = $product->get_type();
		$title = $product->get_title();
		$sku = $product->get_sku();
		//$visibility = get_post_meta( $product_id, '_visibility', true);


		$youTheArtist = get_post_meta( $product_id, 'youTheArtist', true);

    $artistFirstName = get_post_meta( $product_id, 'artistFirstName', true);
    $artistLastName = get_post_meta( $product_id, 'artistLastName', true);
		$priceOnRequest = ( get_post_meta( $product_id, 'priceOnRequest', true) == '1' ) ? '1' : '0';

		$series = ( get_post_meta( $product_id, 'series', true) == '1' ) ? '1' : '0';
		$series_name = get_post_meta( $product_id, 'series_name', true);

		$edition = ( get_post_meta( $product_id, 'edition', true) == '1' ) ? '1' : '0';
		//$edition = ( get_post_meta( $product_id, 'edition', true) == 'yes' ) ? 'enable' : '';

    $edition_no = get_post_meta( $product_id, 'edition_no', true);
    $edition_t = get_post_meta( $product_id, 'edition_t', true);
		$edition_name = get_post_meta( $product_id, 'edition_name', true);
    $year_produced = get_post_meta( $product_id, 'year', true);
    $country_of_production = get_post_meta( $product_id, 'countryProd');
    $material_and_media = get_post_meta( $product_id, 'materialMedia', true);

		$in_a_frame = ( get_post_meta( $product_id, 'inFrame', true) == '1' ) ? '1' : '0';
		//$inFrame = ( get_post_meta( $product_id, 'inFrame', true) == 'yes' ) ? 'enable' : '';

		$dimensions = ( get_post_meta( $product_id, 'dimensions', true) == '1' ) ? '1' : '0';

    $framed_length = get_post_meta( $product_id, 'framed_length', true);
    //$framed_width = get_post_meta( $product_id, 'framed_width', true);
    $framed_height = get_post_meta( $product_id, 'framed_height', true);
    $length = get_post_meta( $product_id, 'art_length', true);
    $width = get_post_meta( $product_id, 'art_width', true);
    $height = get_post_meta( $product_id, 'art_height', true);

		$artDescription = get_post_meta( $product_id, 'artDescription', true);
		$noteToVendor = get_post_meta( $product_id, 'note_to_vendor', true);


		$description = $product->get_description();
		$regular_price = $product->get_regular_price();
		$sale_price = $product->get_sale_price();

		// External product option
		$product_url = get_post_meta( $product_id, '_product_url', true);
		$button_text = get_post_meta( $product_id, '_button_text', true);

		// Virtual
		$is_virtual = ( get_post_meta( $product_id, '_virtual', true) == 'yes' ) ? 'enable' : '';

		// Download ptions
		$is_downloadable = ( get_post_meta( $product_id, '_downloadable', true) == 'yes' ) ? 'enable' : '';
		if( $product_type != 'simple' ) $is_downloadable = '';

		// Product Images
		$featured_img = ($product->get_image_id()) ? $product->get_image_id() : '';
		if($featured_img) $featured_img = wp_get_attachment_url($featured_img);
		if(!$featured_img) $featured_img = '';
		$gallery_img_ids = $product->get_gallery_image_ids();
		if(!empty($gallery_img_ids)) {
			foreach($gallery_img_ids as $gallery_img_id) {
				$gallery_img_urls[]['image'] = wp_get_attachment_url($gallery_img_id);
			}
		}

		// Product Categories
		$pcategories = get_the_terms( $product_id, 'product_cat' );
		if( !empty($pcategories) ) {
			foreach($pcategories as $pkey => $pcategory) {
				$categories[] = $pcategory->term_id;
			}
		} else {
			$categories = array();
		}

		// Product Tags
		$product_tag_list = wp_get_post_terms($product_id, 'product_tag', array("fields" => "names"));
		$product_tags = implode(',', $product_tag_list);

		$genre_tag_list = wp_get_post_terms($product_id, 'genre_tag', array("fields" => "names"));
		$genre_tags = implode(',', $genre_tag_list);

		$theme_tag_list = wp_get_post_terms($product_id, 'theme_tag', array("fields" => "names"));
		$theme_tags = implode(',', $theme_tag_list);

		// Product Stock options
		$manage_stock = $product->managing_stock() ? 'enable' : '';
		$stock_qty = $product->get_stock_quantity();
		$backorders = $product->get_backorders();
		$stock_status = $product->get_stock_status();
		$sold_individually = $product->is_sold_individually() ? 'enable' : '';

		// Product Shipping Data
		$weight = $product->get_weight();
		$shipping_class = $product->get_shipping_class_id();

		// Product Tax Data
		$tax_status = $product->get_tax_status();
		$tax_class = $product->get_tax_class();

		// Product Attributes
		$wcfm_attributes = get_post_meta( $product_id, '_product_attributes', true );
		if(!empty($wcfm_attributes)) {
			$acnt = 0;
			foreach($wcfm_attributes as $wcfm_attribute) {

				if ( $wcfm_attribute['is_taxonomy'] ) {
					$att_taxonomy = $wcfm_attribute['name'];

					if ( ! taxonomy_exists( $att_taxonomy ) ) {
						continue;
					}

					$attribute_taxonomy = $wc_product_attributes[ $att_taxonomy ];

					$attributes[$acnt]['term_name'] = $att_taxonomy;
					$attributes[$acnt]['name'] = wc_attribute_label( $att_taxonomy );
					$attributes[$acnt]['attribute_taxonomy'] = $attribute_taxonomy;
					$attributes[$acnt]['tax_name'] = $att_taxonomy;
					$attributes[$acnt]['is_taxonomy'] = 1;

					if ( 'text' !== $attribute_taxonomy->attribute_type ) {
						$attributes[$acnt]['attribute_type'] = 'select';
					} else {
						$attributes[$acnt]['attribute_type'] = 'text';
						$attributes[$acnt]['value'] = esc_attr( implode( ' ' . WC_DELIMITER . ' ', wp_get_post_terms( $product_id, $att_taxonomy, array( 'fields' => 'names' ) ) ) );
					}
				} else {
					$attributes[$acnt]['term_name'] = apply_filters( 'woocommerce_attribute_label', $wcfm_attribute['name'], $wcfm_attribute['name'], $product );
					$attributes[$acnt]['name'] = apply_filters( 'woocommerce_attribute_label', $wcfm_attribute['name'], $wcfm_attribute['name'], $product );
					$attributes[$acnt]['value'] = $wcfm_attribute['value'];
					$attributes[$acnt]['tax_name'] = '';
					$attributes[$acnt]['is_taxonomy'] = 0;
					$attributes[$acnt]['attribute_type'] = 'text';
				}

				$attributes[$acnt]['is_active'] = 'enable';
				$attributes[$acnt]['is_visible'] = $wcfm_attribute['is_visible'] ? 'enable' : '';
				$attributes[$acnt]['is_variation'] = $wcfm_attribute['is_variation'] ? 'enable' : '';

				if( 'text' !== $attributes[$acnt]['attribute_type'] ) {
					$attributes_select_type[$acnt] = $attributes[$acnt];
					unset($attributes[$acnt]);
				}
				$acnt++;
			}
		}

		// Product Default Attributes
		$default_attributes = json_encode( (array) get_post_meta( $product_id, '_default_attributes', true ) );

		// Variable Product Variations
		$variation_ids = $product->get_children();
		if(!empty($variation_ids)) {
			foreach($variation_ids as $variation_id_key => $variation_id) {
				$variation_data = new WC_Product_Variation($variation_id);

				$variations[$variation_id_key]['id'] = $variation_id;
				$variations[$variation_id_key]['enable'] = $variation_data->is_purchasable() ? 'enable' : '';
				$variations[$variation_id_key]['sku'] = $variation_data->get_sku();

				// Variation Image
				$variation_img = $variation_data->get_image_id();
				if($variation_img) $variation_img = wp_get_attachment_url($variation_img);
				else $variation_img = '';
				$variations[$variation_id_key]['image'] = $variation_img;

				// Variation Price
				$variations[$variation_id_key]['regular_price'] = $variation_data->get_regular_price();
				$variations[$variation_id_key]['sale_price'] = $variation_data->get_sale_price();

				// Variation Sales Schedule
				$variations[$variation_id_key]['sale_price_dates_from'] = ( $date = get_post_meta( $variation_id, '_sale_price_dates_from', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';
				$variations[$variation_id_key]['sale_price_dates_to'] = ( $date = get_post_meta( $variation_id, '_sale_price_dates_to', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';

				// Variation Stock Data
				$variations[$variation_id_key]['manage_stock'] = $variation_data->managing_stock() ? 'enable' : '';
				$variations[$variation_id_key]['stock_status'] = $variation_data->get_stock_status();
				$variations[$variation_id_key]['stock_qty'] = $variation_data->get_stock_quantity();
				$variations[$variation_id_key]['backorders'] = $variation_data->get_backorders();

				// Variation Virtual Data
				$variations[$variation_id_key]['is_virtual'] = ( 'yes' == get_post_meta($variation_id, '_virtual', true) ) ? 'enable' : '';

				// Variation Downloadable Data
				$variations[$variation_id_key]['is_downloadable'] = ( 'yes' == get_post_meta($variation_id, '_downloadable', true) ) ? 'enable' : '';
				$variations[$variation_id_key]['downloadable_files'] = get_post_meta($variation_id, '_downloadable_files', true);
				$variations[$variation_id_key]['download_limit'] = ( -1 == get_post_meta($variation_id, '_download_limit', true) ) ? '' : get_post_meta($variation_id, '_download_limit', true);
				$variations[$variation_id_key]['download_expiry'] = ( -1 == get_post_meta($variation_id, '_download_expiry', true) ) ? '' : get_post_meta($variation_id, '_download_expiry', true);
				if(!empty($variations[$variation_id_key]['downloadable_files'])) {
					foreach($variations[$variation_id_key]['downloadable_files'] as $variations_downloadable_files) {
						$variations[$variation_id_key]['downloadable_file'] = $variations_downloadable_files['file'];
						$variations[$variation_id_key]['downloadable_file_name'] = $variations_downloadable_files['name'];
					}
				}

				// Variation Shipping Data
				$variations[$variation_id_key]['weight'] = $variation_data->get_weight();
				$variations[$variation_id_key]['length'] = $variation_data->get_length();
				$variations[$variation_id_key]['width'] = $variation_data->get_width();
				$variations[$variation_id_key]['height'] = $variation_data->get_height();
				$variations[$variation_id_key]['shipping_class'] = $variation_data->get_shipping_class_id();

				// Variation Tax
				$variations[$variation_id_key]['tax_class'] = $variation_data->get_tax_class();

				// Variation Attributes
				$variations[$variation_id_key]['attributes'] = json_encode( $variation_data->get_variation_attributes() );

				// Description
				$variations[$variation_id_key]['description'] = get_post_meta($variation_id, '_variation_description', true);

				$variations = apply_filters( 'wcfm_variation_edit_data', $variations, $variation_id, $variation_id_key );
			}
		}

		$upsell_ids = get_post_meta( $product_id, '_upsell_ids', true ) ? get_post_meta( $product_id, '_upsell_ids', true ) : array();
		$crosssell_ids = get_post_meta( $product_id, '_crosssell_ids', true ) ? get_post_meta( $product_id, '_crosssell_ids', true ) : array();
		$children = get_post_meta( $product_id, '_children', true ) ? get_post_meta( $product_id, '_children', true ) : array();
	}
}

$current_user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );

// Shipping Class List
$product_shipping_class = get_terms( 'product_shipping_class', array('hide_empty' => 0));
$product_shipping_class = apply_filters( 'wcfm_product_shipping_class', $product_shipping_class );
$variation_shipping_option_array = array('-1' => __('Same as parent', 'wc-frontend-manager'));
$shipping_option_array = array('_no_shipping_class' => __('No shipping class', 'wc-frontend-manager'));
if( $product_shipping_class && !empty( $product_shipping_class ) ) {
	foreach($product_shipping_class as $product_shipping) {
		$variation_shipping_option_array[$product_shipping->term_id] = $product_shipping->name;
		$shipping_option_array[$product_shipping->term_id] = $product_shipping->name;
	}
}

// Tax Class List
$tax_classes         = WC_Tax::get_tax_classes();
$classes_options     = array();
$variation_tax_classes_options['parent'] = __( 'Same as parent', 'wc-frontend-manager' );
$variation_tax_classes_options[''] = __( 'Standard', 'wc-frontend-manager' );
$tax_classes_options[''] = __( 'Standard', 'wc-frontend-manager' );

if ( ! empty( $tax_classes ) ) {

	foreach ( $tax_classes as $class ) {
		$tax_classes_options[ sanitize_title( $class ) ] = esc_html( $class );
		$variation_tax_classes_options[ sanitize_title( $class ) ] = esc_html( $class );
	}
}

$args = array(
	'posts_per_page'   => -1,
	'offset'           => 0,
	'category'         => '',
	'category_name'    => '',
	'orderby'          => 'date',
	'order'            => 'DESC',
	'include'          => '',
	'exclude'          => '',
	'meta_key'         => '',
	'meta_value'       => '',
	'post_type'        => 'product',
	'post_mime_type'   => '',
	'post_parent'      => '',
	//'author'	   => get_current_user_id(),
	'post_status'      => array('publish'),
	'suppress_filters' => 0
);
$args = apply_filters( 'wcfm_products_args', $args );

$products_objs = get_posts( $args );
$products_array = array();
if( !empty($products_objs) ) {
	foreach( $products_objs as $products_obj ) {
		$product_data      = wc_get_product( $products_obj->ID );
		$products_array[esc_attr( $products_obj->ID )] = (!empty($product_data)) ? wp_kses_post( $product_data->get_formatted_name() ) : $products_obj->ID;
	}
}
$product_types = apply_filters( 'wcfm_product_types', array('simple' => __('Simple Product', 'wc-frontend-manager'), 'variable' => __('Variable Product', 'wc-frontend-manager'), 'grouped' => __('Grouped Product', 'wc-frontend-manager'), 'external' => __('External/Affiliate Product', 'wc-frontend-manager') ) );
$product_categories   = get_terms( 'product_cat', 'orderby=name&hide_empty=0&parent=0' );

$product_type_class = '';
if( count( $product_types ) == 0 ) {
	$product_types = array('simple' => __('Simple Product', 'wc-frontend-manager') );
	$product_type_class = 'wcfm_custom_hide';
} elseif( count( $product_types ) == 1 ) {
	$product_type_class = 'wcfm_custom_hide';
}
?>

<div class="collapse wcfm-collapse" id="">
  <div class="wcfm-page-headig">
		<span class="wcfm-page-heading-text"><?php _e( 'Edit Artwork', 'wc-frontend-manager' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>


		<form id="wcfm_products_manage_form" class="wcfm">

			<?php do_action( 'begin_wcfm_products_manage_form' ); ?>

			<!-- collapsible -->
			<div class="wcfm-container simple variable external grouped booking">
				<div id="wcfm_products_manage_form_general_expander" class="wcfm-content">


					<div class="wcfm_product_manager_gallery_fields">
					  <?php
					  if( $wcfm_is_allow_featured = apply_filters( 'wcfm_is_allow_featured', true ) ) {
					  	$gallerylimit = apply_filters( 'wcfm_gallerylimit', -1 );
					  	if( !WCFM_Dependencies::wcfmu_plugin_active_check() ) {
					  		$gallerylimit = apply_filters( 'wcfm_free_gallerylimit', 4 );
					  	}
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_product_manage_fields_images', array(  "featured_img" => array( 'type' => 'upload', 'class' => 'wcfm-product-feature-upload wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title', 'prwidth' => 250, 'value' => $featured_img),
																																																												"gallery_img"  => array( 'type' => 'multiinput', 'class' => 'wcfm-text wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title', 'custom_attributes' => array( 'limit' => $gallerylimit ), 'value' => $gallery_img_urls, 'options' => array(
																																																																									"image" => array( 'type' => 'upload', 'class' => 'wcfm_gallery_upload', 'prwidth' => 75 ),
																																																																								) )
																																													), $gallery_img_urls ) );
						}
 					do_action( 'wcfm_product_manager_gallery_fields_end', $product_id ); ?>
					</div>

				  <div class="wcfm_product_manager_general_fields">

						<?php if (isset($noteToVendor) && !empty($noteToVendor)): ?>
							<div class="wcfm_note_to_vendor">
								<p><i class="ion ion-alert-circled"></i> Please make the following adjustments and submit again:<br/> <?php echo $noteToVendor; ?></p>
							</div>
						<?php
							endif;
							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_general', array("title" => array(
									'placeholder' => __('Title', 'wc-frontend-manager') , 'type' => 'text', 'hints' => 'The title cannot be changed after submitting for review. If you\'re not sure about the title yet you can draft the artwork and edit later.', 'class' => 'wcfm-text wcfm_ele wcfm_product_title wcfm_full_ele simple variable external grouped booking required-field required-unfilled overview-field', 'value' => $title, )
							), $product_id, $product_type ) );

							// echo '<i class="ion ion-ios-help"
							// 	title="The title cannot be changed after submitting for review. If you\'re not sure about the title yet you can draft the artwork and edit later."></i>';

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_details', array("countryProd" => array(
									'placeholder' => __('Country', 'wc-frontend-manager') , 'type' => 'country', 'options' => $country_arr, 'class' => 'wcfm-country wcfm_ele wcfm_full_ele simple variable external grouped booking overview-field', 'value' => $country_of_production)
							), $product_id, $product_type ) );

							echo '<div class="name-fields">';

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_general', array("artistFirstName" => array(
									'placeholder' => __('First Name', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_full_ele simple variable external grouped booking overview-field required-field required-unfilled', 'value' => $artistFirstName)
							), $product_id, $product_type ) );

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_general', array("artistLastName" => array(
									'placeholder' => __('Last Name', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_full_ele simple variable external grouped booking overview-field required-field required-unfilled', 'value' => $artistLastName)
							), $product_id, $product_type ) );

							echo '</div>';

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_general', array("year" => array(
									'placeholder' => __('Year', 'wc-frontend-manager') , 'type' => 'number', 'class' => 'wcfm-text wcfm_ele wcfm_full_ele simple variable external grouped booking required-field required-unfilled overview-field', 'value' => $year_produced)
							), $product_id, $product_type ) );

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_details', array("materialMedia" => array(
									'placeholder' => __('Art Material', 'wc-frontend-manager') , 'type' => 'textarea', 'class' => 'wcfm-text wcfm_ele wcfm_full_ele simple variable external grouped booking required-field required-unfilled overview-field', 'value' => $material_and_media)
							), $product_id, $product_type ) );

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_general', array("youTheArtist" => array(
									 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele simple variable external grouped booking overview-field required-field required-unfilled', 'value' => '1', 'dfvalue' => $youTheArtist)
							), $product_id, $product_type ) );

							echo '<div class="wcfm-field-postLabel">I am the creator/author and owner of the artwork.</div>';

							echo '<div class="series-fields">';

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_general', array("series" => array(
								 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele simple variable external grouped booking overview-field optional', 'value' => '1', 'dfvalue' => $series)
							), $product_id, $product_type ) );

							echo '<div class="wcfm-field-postLabel">This artwork is part of a series</div>';

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_details', array("series_name" => array(
									'placeholder' => __('Series Name', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_full_ele simple variable external grouped booking overview-field optional', 'value' => $series_name)
							), $product_id, $product_type ) );

							echo '</div>';

							echo '<div class="edition-fields">';

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_general', array("edition" => array(
								 'type' => 'radio', 'class' => 'wcfm-radio wcfm_ele simple variable external grouped booking overview-field required-field required-unfilled', 'dfvalue' => $edition, 'options' => array('This artwork is unique', 'This artwork is part of an edition'))
							), $product_id, $product_type ) );

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_general', array("edition_no" => array(
									'placeholder' => __('Nº', 'wc-frontend-manager') , 'type' => 'number', 'class' => 'wcfm-text wcfm_ele wcfm_full_ele simple variable external grouped booking overview-field optional', 'value' => $edition_no)
							), $product_id, $product_type ) );

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_general', array("edition_t" => array(
									'placeholder' => __('Total', 'wc-frontend-manager') , 'type' => 'number', 'class' => 'wcfm-text wcfm_ele wcfm_full_ele simple variable external grouped booking overview-field optional', 'value' => $edition_t)
							), $product_id, $product_type ) );

							echo '</div>';

							echo '<section class="price-section"><div class="price-tag">';

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_pricing', array(	"regular_price" => array(
									'placeholder' => __('Insert Price', 'wc-frontend-manager'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele wcfm_half_ele simple external non-subscription non-variable-subscription non-auction non-redq_rental non-accommodation-booking non-lottery required-field required-unfilled overview-field', 'label_class' => 'wcfm_ele wcfm_half_ele_title wcfm_title simple external non-subscription non-variable-subscription non-auction non-redq_rental non-accommodation-booking non-lottery', 'value' => $regular_price )), $product_id, $product_type ) );

							//echo get_post_meta( $product_id, 'colors', true);

							echo '</div>';

							echo sprintf(
								'<p class="price-curr"> EUR <span class="img_tip fa fa-question-circle-o" data-tip="%s"></span></p>',
								'Insert the price in Euro, even if Euro is not your national currency. Euro is the base currency on ARTMO. The Euro price will be automatically converted into other currencies based on the daily exchange rate of the ECB (European Central Bank).'
							);

							echo '<div class="priceOnRequest-container">';

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_general', array("priceOnRequest" => array(
								 'label' => 'Price on Request', 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele simple variable external grouped booking overview-field', 'value' => '1', 'dfvalue' => $priceOnRequest)
							), $product_id, $product_type ) );

							echo '</div>';

							echo '</section>';

							echo '<h4 class="wcfm-product-manage section-header">'.__('SIZE', 'woocommerce').'</h4>';

							echo '<div class="size-containers twodimensional">';

							echo '<div class="size-switch">';

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_general', array("dimensions" => array(
									 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele simple variable external grouped booking size-field', 'value' => '1', 'dfvalue' => $dimensions)
							), $product_id, $product_type ) );

							echo '<div class="wcfm-field-postLabel">3D</div>';

							echo '</div>';

							?>



							<svg id="threed" width="234" height="180" viewBox="0 0 234 232" fill="none" xmlns="http://www.w3.org/2000/svg">
<rect x="0.5" y="0.5" width="140" height="140" transform="translate(0 59)" stroke="black"/>
<path d="M58.5 140.5V0.5H198.5V140.5H58.5Z" transform="translate(1)" stroke="black"/>
<path d="M0 59L59 0" transform="translate(0.5 0.5)" stroke="black"/>
<path d="M0 59L59 0" transform="translate(140.5 0.5)" stroke="black"/>
<path d="M59 0L0 59" transform="translate(140.5 140.5)" stroke="black"/>
<path d="M59 0L0 59" transform="translate(0.5 140.5)" stroke="black"/>
<path d="M0 0H141" transform="translate(0 210)" stroke="#222222" stroke-dasharray="4 3"/>
<path d="M0 0H86.267" transform="translate(149 206.049) rotate(-45.0224)" stroke="#222222" stroke-dasharray="4 3"/>
<path d="M0 0H141" transform="translate(213 141) rotate(-90)" stroke="#222222" stroke-dasharray="4 3"/>
<path d="M48.568 3.612H49.506V14H48.568V3.612ZM55.1821 6.636C56.3208 6.636 57.1888 7 57.7861 7.728C58.3834 8.44667 58.6588 9.41733 58.6121 10.64H52.4941C52.5594 11.424 52.8488 12.0587 53.3621 12.544C53.8754 13.02 54.5101 13.258 55.2661 13.258C55.7328 13.258 56.1668 13.174 56.5681 13.006C56.9694 12.838 57.3148 12.6 57.6041 12.292L58.1361 12.824C57.7814 13.2067 57.3521 13.5053 56.8481 13.72C56.3534 13.9253 55.8074 14.028 55.2101 14.028C54.5008 14.028 53.8708 13.874 53.3201 13.566C52.7694 13.2487 52.3354 12.81 52.0181 12.25C51.7101 11.69 51.5561 11.0507 51.5561 10.332C51.5561 9.62267 51.7101 8.988 52.0181 8.428C52.3354 7.85867 52.7694 7.42 53.3201 7.112C53.8708 6.79467 54.4914 6.636 55.1821 6.636ZM57.7721 9.912C57.7254 9.14667 57.4688 8.54 57.0021 8.092C56.5448 7.63467 55.9428 7.406 55.1961 7.406C54.4588 7.406 53.8428 7.63467 53.3481 8.092C52.8534 8.54933 52.5688 9.156 52.4941 9.912H57.7721ZM64.4062 6.636C65.2836 6.636 65.9696 6.89267 66.4642 7.406C66.9682 7.91 67.2202 8.60533 67.2202 9.492V14H66.2682V9.716C66.2682 9.02533 66.0769 8.484 65.6942 8.092C65.3116 7.7 64.7796 7.504 64.0982 7.504C63.3422 7.51333 62.7356 7.74667 62.2782 8.204C61.8302 8.66133 61.5736 9.27733 61.5082 10.052V14H60.5702V6.664H61.5082V8.372C61.7602 7.80267 62.1289 7.37333 62.6142 7.084C63.1089 6.79467 63.7062 6.64533 64.4062 6.636ZM76.0724 6.664V13.23C76.0724 13.9393 75.9231 14.56 75.6244 15.092C75.3257 15.624 74.8964 16.03 74.3364 16.31C73.7857 16.5993 73.1464 16.744 72.4184 16.744C71.3357 16.744 70.3324 16.3707 69.4084 15.624L69.8564 14.952C70.2297 15.2787 70.6217 15.526 71.0324 15.694C71.4431 15.862 71.8911 15.946 72.3764 15.946C73.2164 15.946 73.8837 15.6987 74.3784 15.204C74.8824 14.7187 75.1344 14.0653 75.1344 13.244V12.054C74.8731 12.5673 74.4997 12.9687 74.0144 13.258C73.5291 13.5473 72.9691 13.692 72.3344 13.692C71.6811 13.692 71.0977 13.5427 70.5844 13.244C70.0804 12.936 69.6837 12.516 69.3944 11.984C69.1144 11.4427 68.9744 10.8313 68.9744 10.15C68.9744 9.46867 69.1144 8.862 69.3944 8.33C69.6837 7.798 70.0851 7.38267 70.5984 7.084C71.1117 6.78533 71.6951 6.636 72.3484 6.636C72.9831 6.636 73.5384 6.78067 74.0144 7.07C74.4904 7.35 74.8637 7.75133 75.1344 8.274V6.664H76.0724ZM72.5024 12.936C73.0064 12.936 73.4591 12.8193 73.8604 12.586C74.2617 12.3433 74.5744 12.012 74.7984 11.592C75.0224 11.172 75.1344 10.7007 75.1344 10.178C75.1344 9.646 75.0224 9.17 74.7984 8.75C74.5744 8.33 74.2617 8.00333 73.8604 7.77C73.4591 7.52733 73.0064 7.406 72.5024 7.406C71.9984 7.406 71.5457 7.52733 71.1444 7.77C70.7431 8.00333 70.4257 8.33 70.1924 8.75C69.9684 9.17 69.8564 9.646 69.8564 10.178C69.8564 10.71 69.9684 11.186 70.1924 11.606C70.4257 12.0167 70.7384 12.3433 71.1304 12.586C71.5317 12.8193 71.9891 12.936 72.5024 12.936ZM82.3131 13.496C81.8091 13.8413 81.2678 14.0233 80.6891 14.042C80.1291 14.042 79.6858 13.8693 79.3591 13.524C79.0325 13.1787 78.8691 12.6607 78.8691 11.97V7.602H77.8191L77.8051 6.874H78.8691V4.984H79.8071V6.874L82.1871 6.86V7.602H79.8071V11.816C79.8071 12.7213 80.1431 13.174 80.8151 13.174C81.1885 13.174 81.5945 13.0433 82.0331 12.782L82.3131 13.496ZM87.9238 6.636C88.8012 6.636 89.4872 6.89267 89.9818 7.406C90.4858 7.91 90.7378 8.60533 90.7378 9.492V14H89.7858V9.716C89.7858 9.02533 89.5945 8.484 89.2118 8.092C88.8292 7.7 88.2972 7.504 87.6158 7.504C86.8038 7.51333 86.1645 7.784 85.6978 8.316C85.2405 8.83867 85.0118 9.534 85.0118 10.402V14H84.0738V3.612H85.0118V8.4C85.5065 7.24267 86.4772 6.65467 87.9238 6.636Z" transform="translate(1 215)" fill="black"/>
<path d="M14.1843 6.664H15.1923L17.4463 13.062L19.6723 6.664H20.6663L22.9063 13.062L25.1463 6.664H26.1263L23.3823 14H22.4023L20.1623 7.798L17.9223 14H16.9423L14.1843 6.664ZM27.4765 6.664H28.4145V14H27.4765V6.664ZM27.9525 3.892C28.1392 3.892 28.2932 3.95733 28.4145 4.088C28.5358 4.20933 28.5965 4.36333 28.5965 4.55C28.5965 4.73667 28.5312 4.89533 28.4005 5.026C28.2792 5.15667 28.1298 5.222 27.9525 5.222C27.7658 5.222 27.6072 5.15667 27.4765 5.026C27.3552 4.89533 27.2945 4.73667 27.2945 4.55C27.2945 4.36333 27.3552 4.20933 27.4765 4.088C27.6072 3.95733 27.7658 3.892 27.9525 3.892ZM37.8186 3.612V14H36.8806V12.32C36.6006 12.8707 36.2086 13.3 35.7046 13.608C35.2006 13.9067 34.6126 14.056 33.9406 14.056C33.2499 14.056 32.6339 13.8973 32.0926 13.58C31.5512 13.2627 31.1312 12.8193 30.8326 12.25C30.5339 11.6807 30.3846 11.0367 30.3846 10.318C30.3846 9.59933 30.5339 8.96 30.8326 8.4C31.1406 7.84 31.5606 7.40133 32.0926 7.084C32.6339 6.76667 33.2452 6.608 33.9266 6.608C34.5986 6.608 35.1866 6.762 35.6906 7.07C36.2039 7.378 36.6006 7.80733 36.8806 8.358V3.612H37.8186ZM34.1086 13.244C34.6406 13.244 35.1166 13.1227 35.5366 12.88C35.9566 12.628 36.2832 12.2827 36.5166 11.844C36.7592 11.4053 36.8806 10.906 36.8806 10.346C36.8806 9.786 36.7592 9.28667 36.5166 8.848C36.2832 8.4 35.9566 8.05 35.5366 7.798C35.1166 7.546 34.6406 7.42 34.1086 7.42C33.5766 7.42 33.1006 7.546 32.6806 7.798C32.2606 8.05 31.9292 8.4 31.6866 8.848C31.4439 9.28667 31.3226 9.786 31.3226 10.346C31.3226 10.906 31.4392 11.4053 31.6726 11.844C31.9152 12.2827 32.2466 12.628 32.6666 12.88C33.0959 13.1227 33.5766 13.244 34.1086 13.244ZM44.3248 13.496C43.8208 13.8413 43.2795 14.0233 42.7008 14.042C42.1408 14.042 41.6975 13.8693 41.3708 13.524C41.0442 13.1787 40.8808 12.6607 40.8808 11.97V7.602H39.8308L39.8168 6.874H40.8808V4.984H41.8188V6.874L44.1988 6.86V7.602H41.8188V11.816C41.8188 12.7213 42.1548 13.174 42.8268 13.174C43.2002 13.174 43.6062 13.0433 44.0448 12.782L44.3248 13.496ZM49.9356 6.636C50.8129 6.636 51.4989 6.89267 51.9936 7.406C52.4976 7.91 52.7496 8.60533 52.7496 9.492V14H51.7976V9.716C51.7976 9.02533 51.6062 8.484 51.2236 8.092C50.8409 7.7 50.3089 7.504 49.6276 7.504C48.8156 7.51333 48.1762 7.784 47.7096 8.316C47.2522 8.83867 47.0236 9.534 47.0236 10.402V14H46.0856V3.612H47.0236V8.4C47.5182 7.24267 48.4889 6.65467 49.9356 6.636Z" transform="translate(179 170)" fill="black"/>
<path d="M52.459 6.636C53.3363 6.636 54.0223 6.89267 54.517 7.406C55.021 7.91 55.273 8.60533 55.273 9.492V14H54.321V9.716C54.321 9.02533 54.1297 8.484 53.747 8.092C53.3643 7.7 52.8323 7.504 52.151 7.504C51.339 7.51333 50.6997 7.784 50.233 8.316C49.7757 8.83867 49.547 9.534 49.547 10.402V14H48.609V3.612H49.547V8.4C50.0417 7.24267 51.0123 6.65467 52.459 6.636ZM60.6372 6.636C61.7758 6.636 62.6438 7 63.2412 7.728C63.8385 8.44667 64.1138 9.41733 64.0672 10.64H57.9492C58.0145 11.424 58.3038 12.0587 58.8172 12.544C59.3305 13.02 59.9652 13.258 60.7212 13.258C61.1878 13.258 61.6218 13.174 62.0232 13.006C62.4245 12.838 62.7698 12.6 63.0592 12.292L63.5912 12.824C63.2365 13.2067 62.8072 13.5053 62.3032 13.72C61.8085 13.9253 61.2625 14.028 60.6652 14.028C59.9558 14.028 59.3258 13.874 58.7752 13.566C58.2245 13.2487 57.7905 12.81 57.4732 12.25C57.1652 11.69 57.0112 11.0507 57.0112 10.332C57.0112 9.62267 57.1652 8.988 57.4732 8.428C57.7905 7.85867 58.2245 7.42 58.7752 7.112C59.3258 6.79467 59.9465 6.636 60.6372 6.636ZM63.2272 9.912C63.1805 9.14667 62.9238 8.54 62.4572 8.092C61.9998 7.63467 61.3978 7.406 60.6512 7.406C59.9138 7.406 59.2978 7.63467 58.8032 8.092C58.3085 8.54933 58.0238 9.156 57.9492 9.912H63.2272ZM66.0253 6.664H66.9633V14H66.0253V6.664ZM66.5013 3.892C66.688 3.892 66.842 3.95733 66.9633 4.088C67.0847 4.20933 67.1453 4.36333 67.1453 4.55C67.1453 4.73667 67.08 4.89533 66.9493 5.026C66.828 5.15667 66.6787 5.222 66.5013 5.222C66.3147 5.222 66.156 5.15667 66.0253 5.026C65.904 4.89533 65.8433 4.73667 65.8433 4.55C65.8433 4.36333 65.904 4.20933 66.0253 4.088C66.156 3.95733 66.3147 3.892 66.5013 3.892ZM76.0314 6.664V13.23C76.0314 13.9393 75.882 14.56 75.5834 15.092C75.2847 15.624 74.8554 16.03 74.2954 16.31C73.7447 16.5993 73.1054 16.744 72.3774 16.744C71.2947 16.744 70.2914 16.3707 69.3674 15.624L69.8154 14.952C70.1887 15.2787 70.5807 15.526 70.9914 15.694C71.402 15.862 71.85 15.946 72.3354 15.946C73.1754 15.946 73.8427 15.6987 74.3374 15.204C74.8414 14.7187 75.0934 14.0653 75.0934 13.244V12.054C74.832 12.5673 74.4587 12.9687 73.9734 13.258C73.488 13.5473 72.928 13.692 72.2934 13.692C71.64 13.692 71.0567 13.5427 70.5434 13.244C70.0394 12.936 69.6427 12.516 69.3534 11.984C69.0734 11.4427 68.9334 10.8313 68.9334 10.15C68.9334 9.46867 69.0734 8.862 69.3534 8.33C69.6427 7.798 70.044 7.38267 70.5574 7.084C71.0707 6.78533 71.654 6.636 72.3074 6.636C72.942 6.636 73.4974 6.78067 73.9734 7.07C74.4494 7.35 74.8227 7.75133 75.0934 8.274V6.664H76.0314ZM72.4614 12.936C72.9654 12.936 73.418 12.8193 73.8194 12.586C74.2207 12.3433 74.5334 12.012 74.7574 11.592C74.9814 11.172 75.0934 10.7007 75.0934 10.178C75.0934 9.646 74.9814 9.17 74.7574 8.75C74.5334 8.33 74.2207 8.00333 73.8194 7.77C73.418 7.52733 72.9654 7.406 72.4614 7.406C71.9574 7.406 71.5047 7.52733 71.1034 7.77C70.702 8.00333 70.3847 8.33 70.1514 8.75C69.9274 9.17 69.8154 9.646 69.8154 10.178C69.8154 10.71 69.9274 11.186 70.1514 11.606C70.3847 12.0167 70.6974 12.3433 71.0894 12.586C71.4907 12.8193 71.948 12.936 72.4614 12.936ZM82.4961 6.636C83.3734 6.636 84.0594 6.89267 84.5541 7.406C85.0581 7.91 85.3101 8.60533 85.3101 9.492V14H84.3581V9.716C84.3581 9.02533 84.1668 8.484 83.7841 8.092C83.4014 7.7 82.8694 7.504 82.1881 7.504C81.3761 7.51333 80.7368 7.784 80.2701 8.316C79.8128 8.83867 79.5841 9.534 79.5841 10.402V14H78.6461V3.612H79.5841V8.4C80.0788 7.24267 81.0494 6.65467 82.4961 6.636ZM91.5143 13.496C91.0103 13.8413 90.469 14.0233 89.8903 14.042C89.3303 14.042 88.887 13.8693 88.5603 13.524C88.2336 13.1787 88.0703 12.6607 88.0703 11.97V7.602H87.0203L87.0063 6.874H88.0703V4.984H89.0083V6.874L91.3883 6.86V7.602H89.0083V11.816C89.0083 12.7213 89.3443 13.174 90.0163 13.174C90.3896 13.174 90.7956 13.0433 91.2343 12.782L91.5143 13.496Z" transform="translate(237 1) rotate(90)" fill="black"/>
</svg>

<svg id="twod" width="163" height="200" viewBox="0 0 163 200" fill="none" xmlns="http://www.w3.org/2000/svg">
<rect x="0.5" y="0.5" width="145" height="182" stroke="#222222"/>
<path d="M24.2348 4.34C23.8428 4.34 23.5114 4.47533 23.2408 4.746C22.9794 5.01667 22.8488 5.376 22.8488 5.824V6.86H25.0048V7.602H22.8488V14H21.9108V7.602H20.9728V6.86H21.9108V5.936C21.9108 5.45067 22.0134 5.026 22.2188 4.662C22.4241 4.28867 22.6994 4.004 23.0448 3.808C23.3901 3.612 23.7634 3.514 24.1648 3.514C24.6221 3.514 25.0514 3.626 25.4528 3.85L25.2148 4.606C24.8788 4.42867 24.5521 4.34 24.2348 4.34ZM27.2055 8.372C27.4482 7.812 27.7935 7.38733 28.2415 7.098C28.6988 6.79933 29.2448 6.64533 29.8795 6.636V7.518C29.1048 7.50867 28.4795 7.73733 28.0035 8.204C27.5368 8.66133 27.2708 9.28667 27.2055 10.08V14H26.2675V6.664H27.2055V8.372ZM35.6313 14L35.6173 12.768C35.328 13.1973 34.95 13.5193 34.4833 13.734C34.0167 13.9393 33.48 14.042 32.8733 14.042C32.3787 14.042 31.94 13.9487 31.5573 13.762C31.184 13.566 30.8947 13.3 30.6893 12.964C30.484 12.628 30.3813 12.25 30.3813 11.83C30.3813 11.1953 30.624 10.696 31.1093 10.332C31.604 9.968 32.2807 9.786 33.1393 9.786H35.6033V9.226C35.6033 8.64733 35.4307 8.19933 35.0853 7.882C34.74 7.56467 34.236 7.406 33.5733 7.406C32.7893 7.406 32.0007 7.7 31.2073 8.288L30.7873 7.644C31.282 7.29867 31.7533 7.04667 32.2013 6.888C32.6493 6.72 33.1627 6.636 33.7413 6.636C34.628 6.636 35.314 6.86 35.7993 7.308C36.2847 7.74667 36.532 8.358 36.5413 9.142L36.5553 14H35.6313ZM33.0413 13.258C33.6947 13.258 34.2453 13.1087 34.6933 12.81C35.1507 12.502 35.4587 12.0727 35.6173 11.522V10.514H33.2513C32.6073 10.514 32.1173 10.6213 31.7813 10.836C31.4453 11.0507 31.2773 11.3727 31.2773 11.802C31.2773 12.2407 31.436 12.5953 31.7533 12.866C32.08 13.1273 32.5093 13.258 33.0413 13.258ZM48.4304 6.636C49.2984 6.636 49.975 6.888 50.4604 7.392C50.9457 7.896 51.1884 8.596 51.1884 9.492V14H50.2364V9.716C50.2364 9.016 50.045 8.47467 49.6624 8.092C49.289 7.7 48.771 7.504 48.1084 7.504C47.3244 7.52267 46.7084 7.79333 46.2604 8.316C45.8217 8.83867 45.6024 9.52933 45.6024 10.388V14H44.6504V9.716C44.6504 9.016 44.459 8.47467 44.0764 8.092C43.703 7.7 43.185 7.504 42.5224 7.504C41.7384 7.52267 41.1224 7.79333 40.6744 8.316C40.2264 8.83867 40.0024 9.52933 40.0024 10.388V14H39.0644V6.664H40.0024V8.386C40.245 7.80733 40.6044 7.37333 41.0804 7.084C41.5657 6.79467 42.1537 6.64533 42.8444 6.636C43.5444 6.636 44.123 6.80867 44.5804 7.154C45.047 7.49933 45.355 7.98467 45.5044 8.61C45.7284 7.95667 46.0877 7.46667 46.5824 7.14C47.0864 6.81333 47.7024 6.64533 48.4304 6.636ZM56.5493 6.636C57.688 6.636 58.556 7 59.1533 7.728C59.7506 8.44667 60.026 9.41733 59.9793 10.64H53.8613C53.9266 11.424 54.216 12.0587 54.7293 12.544C55.2426 13.02 55.8773 13.258 56.6333 13.258C57.1 13.258 57.534 13.174 57.9353 13.006C58.3366 12.838 58.682 12.6 58.9713 12.292L59.5033 12.824C59.1486 13.2067 58.7193 13.5053 58.2153 13.72C57.7206 13.9253 57.1746 14.028 56.5773 14.028C55.868 14.028 55.238 13.874 54.6873 13.566C54.1366 13.2487 53.7026 12.81 53.3853 12.25C53.0773 11.69 52.9233 11.0507 52.9233 10.332C52.9233 9.62267 53.0773 8.988 53.3853 8.428C53.7026 7.85867 54.1366 7.42 54.6873 7.112C55.238 6.79467 55.8586 6.636 56.5493 6.636ZM59.1393 9.912C59.0926 9.14667 58.836 8.54 58.3693 8.092C57.912 7.63467 57.31 7.406 56.5633 7.406C55.826 7.406 55.21 7.63467 54.7153 8.092C54.2206 8.54933 53.936 9.156 53.8613 9.912H59.1393ZM68.6154 3.612V14H67.6774V12.32C67.3974 12.8707 67.0054 13.3 66.5014 13.608C65.9974 13.9067 65.4094 14.056 64.7374 14.056C64.0468 14.056 63.4308 13.8973 62.8894 13.58C62.3481 13.2627 61.9281 12.8193 61.6294 12.25C61.3308 11.6807 61.1814 11.0367 61.1814 10.318C61.1814 9.59933 61.3308 8.96 61.6294 8.4C61.9374 7.84 62.3574 7.40133 62.8894 7.084C63.4308 6.76667 64.0421 6.608 64.7234 6.608C65.3954 6.608 65.9834 6.762 66.4874 7.07C67.0008 7.378 67.3974 7.80733 67.6774 8.358V3.612H68.6154ZM64.9054 13.244C65.4374 13.244 65.9134 13.1227 66.3334 12.88C66.7534 12.628 67.0801 12.2827 67.3134 11.844C67.5561 11.4053 67.6774 10.906 67.6774 10.346C67.6774 9.786 67.5561 9.28667 67.3134 8.848C67.0801 8.4 66.7534 8.05 66.3334 7.798C65.9134 7.546 65.4374 7.42 64.9054 7.42C64.3734 7.42 63.8974 7.546 63.4774 7.798C63.0574 8.05 62.7261 8.4 62.4834 8.848C62.2408 9.28667 62.1194 9.786 62.1194 10.346C62.1194 10.906 62.2361 11.4053 62.4694 11.844C62.7121 12.2827 63.0434 12.628 63.4634 12.88C63.8928 13.1227 64.3734 13.244 64.9054 13.244ZM75.3512 3.612H76.2892V14H75.3512V3.612ZM81.9653 6.636C83.104 6.636 83.972 7 84.5693 7.728C85.1666 8.44667 85.442 9.41733 85.3953 10.64H79.2773C79.3426 11.424 79.632 12.0587 80.1453 12.544C80.6586 13.02 81.2933 13.258 82.0493 13.258C82.516 13.258 82.95 13.174 83.3513 13.006C83.7526 12.838 84.098 12.6 84.3873 12.292L84.9193 12.824C84.5646 13.2067 84.1353 13.5053 83.6313 13.72C83.1366 13.9253 82.5906 14.028 81.9933 14.028C81.284 14.028 80.654 13.874 80.1033 13.566C79.5526 13.2487 79.1186 12.81 78.8013 12.25C78.4933 11.69 78.3393 11.0507 78.3393 10.332C78.3393 9.62267 78.4933 8.988 78.8013 8.428C79.1186 7.85867 79.5526 7.42 80.1033 7.112C80.654 6.79467 81.2746 6.636 81.9653 6.636ZM84.5553 9.912C84.5086 9.14667 84.252 8.54 83.7853 8.092C83.328 7.63467 82.726 7.406 81.9793 7.406C81.242 7.406 80.626 7.63467 80.1313 8.092C79.6366 8.54933 79.352 9.156 79.2773 9.912H84.5553ZM91.1894 6.636C92.0668 6.636 92.7528 6.89267 93.2474 7.406C93.7514 7.91 94.0034 8.60533 94.0034 9.492V14H93.0514V9.716C93.0514 9.02533 92.8601 8.484 92.4774 8.092C92.0948 7.7 91.5628 7.504 90.8814 7.504C90.1254 7.51333 89.5188 7.74667 89.0614 8.204C88.6134 8.66133 88.3568 9.27733 88.2914 10.052V14H87.3534V6.664H88.2914V8.372C88.5434 7.80267 88.9121 7.37333 89.3974 7.084C89.8921 6.79467 90.4894 6.64533 91.1894 6.636ZM102.856 6.664V13.23C102.856 13.9393 102.706 14.56 102.408 15.092C102.109 15.624 101.68 16.03 101.12 16.31C100.569 16.5993 99.9296 16.744 99.2016 16.744C98.1189 16.744 97.1156 16.3707 96.1916 15.624L96.6396 14.952C97.0129 15.2787 97.4049 15.526 97.8156 15.694C98.2263 15.862 98.6743 15.946 99.1596 15.946C99.9996 15.946 100.667 15.6987 101.162 15.204C101.666 14.7187 101.918 14.0653 101.918 13.244V12.054C101.656 12.5673 101.283 12.9687 100.798 13.258C100.312 13.5473 99.7523 13.692 99.1176 13.692C98.4643 13.692 97.8809 13.5427 97.3676 13.244C96.8636 12.936 96.4669 12.516 96.1776 11.984C95.8976 11.4427 95.7576 10.8313 95.7576 10.15C95.7576 9.46867 95.8976 8.862 96.1776 8.33C96.4669 7.798 96.8683 7.38267 97.3816 7.084C97.8949 6.78533 98.4783 6.636 99.1316 6.636C99.7663 6.636 100.322 6.78067 100.798 7.07C101.274 7.35 101.647 7.75133 101.918 8.274V6.664H102.856ZM99.2856 12.936C99.7896 12.936 100.242 12.8193 100.644 12.586C101.045 12.3433 101.358 12.012 101.582 11.592C101.806 11.172 101.918 10.7007 101.918 10.178C101.918 9.646 101.806 9.17 101.582 8.75C101.358 8.33 101.045 8.00333 100.644 7.77C100.242 7.52733 99.7896 7.406 99.2856 7.406C98.7816 7.406 98.3289 7.52733 97.9276 7.77C97.5263 8.00333 97.2089 8.33 96.9756 8.75C96.7516 9.17 96.6396 9.646 96.6396 10.178C96.6396 10.71 96.7516 11.186 96.9756 11.606C97.2089 12.0167 97.5216 12.3433 97.9136 12.586C98.3149 12.8193 98.7723 12.936 99.2856 12.936ZM109.096 13.496C108.592 13.8413 108.051 14.0233 107.472 14.042C106.912 14.042 106.469 13.8693 106.142 13.524C105.816 13.1787 105.652 12.6607 105.652 11.97V7.602H104.602L104.588 6.874H105.652V4.984H106.59V6.874L108.97 6.86V7.602H106.59V11.816C106.59 12.7213 106.926 13.174 107.598 13.174C107.972 13.174 108.378 13.0433 108.816 12.782L109.096 13.496ZM114.707 6.636C115.584 6.636 116.27 6.89267 116.765 7.406C117.269 7.91 117.521 8.60533 117.521 9.492V14H116.569V9.716C116.569 9.02533 116.378 8.484 115.995 8.092C115.612 7.7 115.08 7.504 114.399 7.504C113.587 7.51333 112.948 7.784 112.481 8.316C112.024 8.83867 111.795 9.534 111.795 10.402V14H110.857V3.612H111.795V8.4C112.29 7.24267 113.26 6.65467 114.707 6.636Z" transform="translate(3 183)" fill="#222222"/>
<rect width="112" height="151" transform="translate(17 16)" fill="white"/>
<rect x="0.5" y="0.5" width="111" height="150" transform="translate(17 16)" stroke="#222222"/>
<path d="M48.568 3.612H49.506V14H48.568V3.612ZM55.1821 6.636C56.3208 6.636 57.1888 7 57.7861 7.728C58.3834 8.44667 58.6588 9.41733 58.6121 10.64H52.4941C52.5594 11.424 52.8488 12.0587 53.3621 12.544C53.8754 13.02 54.5101 13.258 55.2661 13.258C55.7328 13.258 56.1668 13.174 56.5681 13.006C56.9694 12.838 57.3148 12.6 57.6041 12.292L58.1361 12.824C57.7814 13.2067 57.3521 13.5053 56.8481 13.72C56.3534 13.9253 55.8074 14.028 55.2101 14.028C54.5008 14.028 53.8708 13.874 53.3201 13.566C52.7694 13.2487 52.3354 12.81 52.0181 12.25C51.7101 11.69 51.5561 11.0507 51.5561 10.332C51.5561 9.62267 51.7101 8.988 52.0181 8.428C52.3354 7.85867 52.7694 7.42 53.3201 7.112C53.8708 6.79467 54.4914 6.636 55.1821 6.636ZM57.7721 9.912C57.7254 9.14667 57.4688 8.54 57.0021 8.092C56.5448 7.63467 55.9428 7.406 55.1961 7.406C54.4588 7.406 53.8428 7.63467 53.3481 8.092C52.8534 8.54933 52.5688 9.156 52.4941 9.912H57.7721ZM64.4062 6.636C65.2836 6.636 65.9696 6.89267 66.4642 7.406C66.9682 7.91 67.2202 8.60533 67.2202 9.492V14H66.2682V9.716C66.2682 9.02533 66.0769 8.484 65.6942 8.092C65.3116 7.7 64.7796 7.504 64.0982 7.504C63.3422 7.51333 62.7356 7.74667 62.2782 8.204C61.8302 8.66133 61.5736 9.27733 61.5082 10.052V14H60.5702V6.664H61.5082V8.372C61.7602 7.80267 62.1289 7.37333 62.6142 7.084C63.1089 6.79467 63.7062 6.64533 64.4062 6.636ZM76.0724 6.664V13.23C76.0724 13.9393 75.9231 14.56 75.6244 15.092C75.3257 15.624 74.8964 16.03 74.3364 16.31C73.7857 16.5993 73.1464 16.744 72.4184 16.744C71.3357 16.744 70.3324 16.3707 69.4084 15.624L69.8564 14.952C70.2297 15.2787 70.6217 15.526 71.0324 15.694C71.4431 15.862 71.8911 15.946 72.3764 15.946C73.2164 15.946 73.8837 15.6987 74.3784 15.204C74.8824 14.7187 75.1344 14.0653 75.1344 13.244V12.054C74.8731 12.5673 74.4997 12.9687 74.0144 13.258C73.5291 13.5473 72.9691 13.692 72.3344 13.692C71.6811 13.692 71.0977 13.5427 70.5844 13.244C70.0804 12.936 69.6837 12.516 69.3944 11.984C69.1144 11.4427 68.9744 10.8313 68.9744 10.15C68.9744 9.46867 69.1144 8.862 69.3944 8.33C69.6837 7.798 70.0851 7.38267 70.5984 7.084C71.1117 6.78533 71.6951 6.636 72.3484 6.636C72.9831 6.636 73.5384 6.78067 74.0144 7.07C74.4904 7.35 74.8637 7.75133 75.1344 8.274V6.664H76.0724ZM72.5024 12.936C73.0064 12.936 73.4591 12.8193 73.8604 12.586C74.2617 12.3433 74.5744 12.012 74.7984 11.592C75.0224 11.172 75.1344 10.7007 75.1344 10.178C75.1344 9.646 75.0224 9.17 74.7984 8.75C74.5744 8.33 74.2617 8.00333 73.8604 7.77C73.4591 7.52733 73.0064 7.406 72.5024 7.406C71.9984 7.406 71.5457 7.52733 71.1444 7.77C70.7431 8.00333 70.4257 8.33 70.1924 8.75C69.9684 9.17 69.8564 9.646 69.8564 10.178C69.8564 10.71 69.9684 11.186 70.1924 11.606C70.4257 12.0167 70.7384 12.3433 71.1304 12.586C71.5317 12.8193 71.9891 12.936 72.5024 12.936ZM82.3131 13.496C81.8091 13.8413 81.2678 14.0233 80.6891 14.042C80.1291 14.042 79.6858 13.8693 79.3591 13.524C79.0325 13.1787 78.8691 12.6607 78.8691 11.97V7.602H77.8191L77.8051 6.874H78.8691V4.984H79.8071V6.874L82.1871 6.86V7.602H79.8071V11.816C79.8071 12.7213 80.1431 13.174 80.8151 13.174C81.1885 13.174 81.5945 13.0433 82.0331 12.782L82.3131 13.496ZM87.9238 6.636C88.8012 6.636 89.4872 6.89267 89.9818 7.406C90.4858 7.91 90.7378 8.60533 90.7378 9.492V14H89.7858V9.716C89.7858 9.02533 89.5945 8.484 89.2118 8.092C88.8292 7.7 88.2972 7.504 87.6158 7.504C86.8038 7.51333 86.1645 7.784 85.6978 8.316C85.2405 8.83867 85.0118 9.534 85.0118 10.402V14H84.0738V3.612H85.0118V8.4C85.5065 7.24267 86.4772 6.65467 87.9238 6.636Z" transform="translate(3 147)" fill="black"/>
<path d="M52.459 6.636C53.3363 6.636 54.0223 6.89267 54.517 7.406C55.021 7.91 55.273 8.60533 55.273 9.492V14H54.321V9.716C54.321 9.02533 54.1297 8.484 53.747 8.092C53.3643 7.7 52.8323 7.504 52.151 7.504C51.339 7.51333 50.6997 7.784 50.233 8.316C49.7757 8.83867 49.547 9.534 49.547 10.402V14H48.609V3.612H49.547V8.4C50.0417 7.24267 51.0123 6.65467 52.459 6.636ZM60.6372 6.636C61.7758 6.636 62.6438 7 63.2412 7.728C63.8385 8.44667 64.1138 9.41733 64.0672 10.64H57.9492C58.0145 11.424 58.3038 12.0587 58.8172 12.544C59.3305 13.02 59.9652 13.258 60.7212 13.258C61.1878 13.258 61.6218 13.174 62.0232 13.006C62.4245 12.838 62.7698 12.6 63.0592 12.292L63.5912 12.824C63.2365 13.2067 62.8072 13.5053 62.3032 13.72C61.8085 13.9253 61.2625 14.028 60.6652 14.028C59.9558 14.028 59.3258 13.874 58.7752 13.566C58.2245 13.2487 57.7905 12.81 57.4732 12.25C57.1652 11.69 57.0112 11.0507 57.0112 10.332C57.0112 9.62267 57.1652 8.988 57.4732 8.428C57.7905 7.85867 58.2245 7.42 58.7752 7.112C59.3258 6.79467 59.9465 6.636 60.6372 6.636ZM63.2272 9.912C63.1805 9.14667 62.9238 8.54 62.4572 8.092C61.9998 7.63467 61.3978 7.406 60.6512 7.406C59.9138 7.406 59.2978 7.63467 58.8032 8.092C58.3085 8.54933 58.0238 9.156 57.9492 9.912H63.2272ZM66.0253 6.664H66.9633V14H66.0253V6.664ZM66.5013 3.892C66.688 3.892 66.842 3.95733 66.9633 4.088C67.0847 4.20933 67.1453 4.36333 67.1453 4.55C67.1453 4.73667 67.08 4.89533 66.9493 5.026C66.828 5.15667 66.6787 5.222 66.5013 5.222C66.3147 5.222 66.156 5.15667 66.0253 5.026C65.904 4.89533 65.8433 4.73667 65.8433 4.55C65.8433 4.36333 65.904 4.20933 66.0253 4.088C66.156 3.95733 66.3147 3.892 66.5013 3.892ZM76.0314 6.664V13.23C76.0314 13.9393 75.882 14.56 75.5834 15.092C75.2847 15.624 74.8554 16.03 74.2954 16.31C73.7447 16.5993 73.1054 16.744 72.3774 16.744C71.2947 16.744 70.2914 16.3707 69.3674 15.624L69.8154 14.952C70.1887 15.2787 70.5807 15.526 70.9914 15.694C71.402 15.862 71.85 15.946 72.3354 15.946C73.1754 15.946 73.8427 15.6987 74.3374 15.204C74.8414 14.7187 75.0934 14.0653 75.0934 13.244V12.054C74.832 12.5673 74.4587 12.9687 73.9734 13.258C73.488 13.5473 72.928 13.692 72.2934 13.692C71.64 13.692 71.0567 13.5427 70.5434 13.244C70.0394 12.936 69.6427 12.516 69.3534 11.984C69.0734 11.4427 68.9334 10.8313 68.9334 10.15C68.9334 9.46867 69.0734 8.862 69.3534 8.33C69.6427 7.798 70.044 7.38267 70.5574 7.084C71.0707 6.78533 71.654 6.636 72.3074 6.636C72.942 6.636 73.4974 6.78067 73.9734 7.07C74.4494 7.35 74.8227 7.75133 75.0934 8.274V6.664H76.0314ZM72.4614 12.936C72.9654 12.936 73.418 12.8193 73.8194 12.586C74.2207 12.3433 74.5334 12.012 74.7574 11.592C74.9814 11.172 75.0934 10.7007 75.0934 10.178C75.0934 9.646 74.9814 9.17 74.7574 8.75C74.5334 8.33 74.2207 8.00333 73.8194 7.77C73.418 7.52733 72.9654 7.406 72.4614 7.406C71.9574 7.406 71.5047 7.52733 71.1034 7.77C70.702 8.00333 70.3847 8.33 70.1514 8.75C69.9274 9.17 69.8154 9.646 69.8154 10.178C69.8154 10.71 69.9274 11.186 70.1514 11.606C70.3847 12.0167 70.6974 12.3433 71.0894 12.586C71.4907 12.8193 71.948 12.936 72.4614 12.936ZM82.4961 6.636C83.3734 6.636 84.0594 6.89267 84.5541 7.406C85.0581 7.91 85.3101 8.60533 85.3101 9.492V14H84.3581V9.716C84.3581 9.02533 84.1668 8.484 83.7841 8.092C83.4014 7.7 82.8694 7.504 82.1881 7.504C81.3761 7.51333 80.7368 7.784 80.2701 8.316C79.8128 8.83867 79.5841 9.534 79.5841 10.402V14H78.6461V3.612H79.5841V8.4C80.0788 7.24267 81.0494 6.65467 82.4961 6.636ZM91.5143 13.496C91.0103 13.8413 90.469 14.0233 89.8903 14.042C89.3303 14.042 88.887 13.8693 88.5603 13.524C88.2336 13.1787 88.0703 12.6607 88.0703 11.97V7.602H87.0203L87.0063 6.874H88.0703V4.984H89.0083V6.874L91.3883 6.86V7.602H89.0083V11.816C89.0083 12.7213 89.3443 13.174 90.0163 13.174C90.3896 13.174 90.7956 13.0433 91.2343 12.782L91.5143 13.496Z" transform="translate(109 161) rotate(-90)" fill="black"/>
<path d="M26.2035 4.34C25.8115 4.34 25.4802 4.47533 25.2095 4.746C24.9482 5.01667 24.8175 5.376 24.8175 5.824V6.86H26.9735V7.602H24.8175V14H23.8795V7.602H22.9415V6.86H23.8795V5.936C23.8795 5.45067 23.9822 5.026 24.1875 4.662C24.3929 4.28867 24.6682 4.004 25.0135 3.808C25.3589 3.612 25.7322 3.514 26.1335 3.514C26.5909 3.514 27.0202 3.626 27.4215 3.85L27.1835 4.606C26.8475 4.42867 26.5209 4.34 26.2035 4.34ZM29.1743 8.372C29.4169 7.812 29.7623 7.38733 30.2103 7.098C30.6676 6.79933 31.2136 6.64533 31.8483 6.636V7.518C31.0736 7.50867 30.4483 7.73733 29.9723 8.204C29.5056 8.66133 29.2396 9.28667 29.1743 10.08V14H28.2363V6.664H29.1743V8.372ZM37.6001 14L37.5861 12.768C37.2967 13.1973 36.9187 13.5193 36.4521 13.734C35.9854 13.9393 35.4487 14.042 34.8421 14.042C34.3474 14.042 33.9087 13.9487 33.5261 13.762C33.1527 13.566 32.8634 13.3 32.6581 12.964C32.4527 12.628 32.3501 12.25 32.3501 11.83C32.3501 11.1953 32.5927 10.696 33.0781 10.332C33.5727 9.968 34.2494 9.786 35.1081 9.786H37.5721V9.226C37.5721 8.64733 37.3994 8.19933 37.0541 7.882C36.7087 7.56467 36.2047 7.406 35.5421 7.406C34.7581 7.406 33.9694 7.7 33.1761 8.288L32.7561 7.644C33.2507 7.29867 33.7221 7.04667 34.1701 6.888C34.6181 6.72 35.1314 6.636 35.7101 6.636C36.5967 6.636 37.2827 6.86 37.7681 7.308C38.2534 7.74667 38.5007 8.358 38.5101 9.142L38.5241 14H37.6001ZM35.0101 13.258C35.6634 13.258 36.2141 13.1087 36.6621 12.81C37.1194 12.502 37.4274 12.0727 37.5861 11.522V10.514H35.2201C34.5761 10.514 34.0861 10.6213 33.7501 10.836C33.4141 11.0507 33.2461 11.3727 33.2461 11.802C33.2461 12.2407 33.4047 12.5953 33.7221 12.866C34.0487 13.1273 34.4781 13.258 35.0101 13.258ZM50.3991 6.636C51.2671 6.636 51.9438 6.888 52.4291 7.392C52.9145 7.896 53.1571 8.596 53.1571 9.492V14H52.2051V9.716C52.2051 9.016 52.0138 8.47467 51.6311 8.092C51.2578 7.7 50.7398 7.504 50.0771 7.504C49.2931 7.52267 48.6771 7.79333 48.2291 8.316C47.7905 8.83867 47.5711 9.52933 47.5711 10.388V14H46.6191V9.716C46.6191 9.016 46.4278 8.47467 46.0451 8.092C45.6718 7.7 45.1538 7.504 44.4911 7.504C43.7071 7.52267 43.0911 7.79333 42.6431 8.316C42.1951 8.83867 41.9711 9.52933 41.9711 10.388V14H41.0331V6.664H41.9711V8.386C42.2138 7.80733 42.5731 7.37333 43.0491 7.084C43.5345 6.79467 44.1225 6.64533 44.8131 6.636C45.5131 6.636 46.0918 6.80867 46.5491 7.154C47.0158 7.49933 47.3238 7.98467 47.4731 8.61C47.6971 7.95667 48.0565 7.46667 48.5511 7.14C49.0551 6.81333 49.6711 6.64533 50.3991 6.636ZM58.518 6.636C59.6567 6.636 60.5247 7 61.122 7.728C61.7194 8.44667 61.9947 9.41733 61.948 10.64H55.83C55.8954 11.424 56.1847 12.0587 56.698 12.544C57.2114 13.02 57.846 13.258 58.602 13.258C59.0687 13.258 59.5027 13.174 59.904 13.006C60.3054 12.838 60.6507 12.6 60.94 12.292L61.472 12.824C61.1174 13.2067 60.688 13.5053 60.184 13.72C59.6894 13.9253 59.1434 14.028 58.546 14.028C57.8367 14.028 57.2067 13.874 56.656 13.566C56.1054 13.2487 55.6714 12.81 55.354 12.25C55.046 11.69 54.892 11.0507 54.892 10.332C54.892 9.62267 55.046 8.988 55.354 8.428C55.6714 7.85867 56.1054 7.42 56.656 7.112C57.2067 6.79467 57.8274 6.636 58.518 6.636ZM61.108 9.912C61.0614 9.14667 60.8047 8.54 60.338 8.092C59.8807 7.63467 59.2787 7.406 58.532 7.406C57.7947 7.406 57.1787 7.63467 56.684 8.092C56.1894 8.54933 55.9047 9.156 55.83 9.912H61.108ZM70.5842 3.612V14H69.6462V12.32C69.3662 12.8707 68.9742 13.3 68.4702 13.608C67.9662 13.9067 67.3782 14.056 66.7062 14.056C66.0155 14.056 65.3995 13.8973 64.8582 13.58C64.3168 13.2627 63.8968 12.8193 63.5982 12.25C63.2995 11.6807 63.1502 11.0367 63.1502 10.318C63.1502 9.59933 63.2995 8.96 63.5982 8.4C63.9062 7.84 64.3262 7.40133 64.8582 7.084C65.3995 6.76667 66.0108 6.608 66.6922 6.608C67.3642 6.608 67.9522 6.762 68.4562 7.07C68.9695 7.378 69.3662 7.80733 69.6462 8.358V3.612H70.5842ZM66.8742 13.244C67.4062 13.244 67.8822 13.1227 68.3022 12.88C68.7222 12.628 69.0488 12.2827 69.2822 11.844C69.5248 11.4053 69.6462 10.906 69.6462 10.346C69.6462 9.786 69.5248 9.28667 69.2822 8.848C69.0488 8.4 68.7222 8.05 68.3022 7.798C67.8822 7.546 67.4062 7.42 66.8742 7.42C66.3422 7.42 65.8662 7.546 65.4462 7.798C65.0262 8.05 64.6948 8.4 64.4522 8.848C64.2095 9.28667 64.0882 9.786 64.0882 10.346C64.0882 10.906 64.2048 11.4053 64.4382 11.844C64.6808 12.2827 65.0122 12.628 65.4322 12.88C65.8615 13.1227 66.3422 13.244 66.8742 13.244ZM81.1699 6.636C82.0473 6.636 82.7333 6.89267 83.2279 7.406C83.7319 7.91 83.9839 8.60533 83.9839 9.492V14H83.0319V9.716C83.0319 9.02533 82.8406 8.484 82.4579 8.092C82.0753 7.7 81.5433 7.504 80.8619 7.504C80.0499 7.51333 79.4106 7.784 78.9439 8.316C78.4866 8.83867 78.2579 9.534 78.2579 10.402V14H77.3199V3.612H78.2579V8.4C78.7526 7.24267 79.7233 6.65467 81.1699 6.636ZM89.3481 6.636C90.4868 6.636 91.3548 7 91.9521 7.728C92.5495 8.44667 92.8248 9.41733 92.7781 10.64H86.6601C86.7255 11.424 87.0148 12.0587 87.5281 12.544C88.0415 13.02 88.6761 13.258 89.4321 13.258C89.8988 13.258 90.3328 13.174 90.7341 13.006C91.1355 12.838 91.4808 12.6 91.7701 12.292L92.3021 12.824C91.9475 13.2067 91.5181 13.5053 91.0141 13.72C90.5195 13.9253 89.9735 14.028 89.3761 14.028C88.6668 14.028 88.0368 13.874 87.4861 13.566C86.9355 13.2487 86.5015 12.81 86.1841 12.25C85.8761 11.69 85.7221 11.0507 85.7221 10.332C85.7221 9.62267 85.8761 8.988 86.1841 8.428C86.5015 7.85867 86.9355 7.42 87.4861 7.112C88.0368 6.79467 88.6575 6.636 89.3481 6.636ZM91.9381 9.912C91.8915 9.14667 91.6348 8.54 91.1681 8.092C90.7108 7.63467 90.1088 7.406 89.3621 7.406C88.6248 7.406 88.0088 7.63467 87.5141 8.092C87.0195 8.54933 86.7348 9.156 86.6601 9.912H91.9381ZM94.7363 6.664H95.6743V14H94.7363V6.664ZM95.2123 3.892C95.3989 3.892 95.5529 3.95733 95.6743 4.088C95.7956 4.20933 95.8563 4.36333 95.8563 4.55C95.8563 4.73667 95.7909 4.89533 95.6603 5.026C95.5389 5.15667 95.3896 5.222 95.2123 5.222C95.0256 5.222 94.8669 5.15667 94.7363 5.026C94.6149 4.89533 94.5543 4.73667 94.5543 4.55C94.5543 4.36333 94.6149 4.20933 94.7363 4.088C94.8669 3.95733 95.0256 3.892 95.2123 3.892ZM104.742 6.664V13.23C104.742 13.9393 104.593 14.56 104.294 15.092C103.996 15.624 103.566 16.03 103.006 16.31C102.456 16.5993 101.816 16.744 101.088 16.744C100.006 16.744 99.0023 16.3707 98.0783 15.624L98.5263 14.952C98.8997 15.2787 99.2917 15.526 99.7023 15.694C100.113 15.862 100.561 15.946 101.046 15.946C101.886 15.946 102.554 15.6987 103.048 15.204C103.552 14.7187 103.804 14.0653 103.804 13.244V12.054C103.543 12.5673 103.17 12.9687 102.684 13.258C102.199 13.5473 101.639 13.692 101.004 13.692C100.351 13.692 99.7677 13.5427 99.2543 13.244C98.7503 12.936 98.3537 12.516 98.0643 11.984C97.7843 11.4427 97.6443 10.8313 97.6443 10.15C97.6443 9.46867 97.7843 8.862 98.0643 8.33C98.3537 7.798 98.755 7.38267 99.2683 7.084C99.7817 6.78533 100.365 6.636 101.018 6.636C101.653 6.636 102.208 6.78067 102.684 7.07C103.16 7.35 103.534 7.75133 103.804 8.274V6.664H104.742ZM101.172 12.936C101.676 12.936 102.129 12.8193 102.53 12.586C102.932 12.3433 103.244 12.012 103.468 11.592C103.692 11.172 103.804 10.7007 103.804 10.178C103.804 9.646 103.692 9.17 103.468 8.75C103.244 8.33 102.932 8.00333 102.53 7.77C102.129 7.52733 101.676 7.406 101.172 7.406C100.668 7.406 100.216 7.52733 99.8143 7.77C99.413 8.00333 99.0957 8.33 98.8623 8.75C98.6383 9.17 98.5263 9.646 98.5263 10.178C98.5263 10.71 98.6383 11.186 98.8623 11.606C99.0957 12.0167 99.4083 12.3433 99.8003 12.586C100.202 12.8193 100.659 12.936 101.172 12.936ZM111.207 6.636C112.084 6.636 112.77 6.89267 113.265 7.406C113.769 7.91 114.021 8.60533 114.021 9.492V14H113.069V9.716C113.069 9.02533 112.878 8.484 112.495 8.092C112.112 7.7 111.58 7.504 110.899 7.504C110.087 7.51333 109.448 7.784 108.981 8.316C108.524 8.83867 108.295 9.534 108.295 10.402V14H107.357V3.612H108.295V8.4C108.79 7.24267 109.76 6.65467 111.207 6.636ZM120.225 13.496C119.721 13.8413 119.18 14.0233 118.601 14.042C118.041 14.042 117.598 13.8693 117.271 13.524C116.945 13.1787 116.781 12.6607 116.781 11.97V7.602H115.731L115.717 6.874H116.781V4.984H117.719V6.874L120.099 6.86V7.602H117.719V11.816C117.719 12.7213 118.055 13.174 118.727 13.174C119.101 13.174 119.507 13.0433 119.945 12.782L120.225 13.496Z" transform="translate(146 161) rotate(-90)" fill="black"/>
<path d="M0 0L111 150" transform="translate(17.5 16.5)" stroke="#222222"/>
<path d="M111 0L0 150" transform="translate(17.5 16.5)" stroke="#222222"/>
</svg>


							<?php

							echo '<div class="size-inputs">';

							echo '<div class="threed-size-inputs">';

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_general', array("art_length" => array(
									'placeholder' => __('length (cm)', 'wc-frontend-manager') , 'type' => 'number', 'class' => 'wcfm-text wcfm_ele wcfm_full_ele simple variable external grouped booking required-field required-unfilled size-field', 'value' => $length)
							), $product_id, $product_type ) );

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_general', array("art_width" => array(
									'placeholder' => __('width (cm)', 'wc-frontend-manager') , 'type' => 'number', 'class' => 'wcfm-text wcfm_ele wcfm_full_ele simple variable external grouped booking required-field required-unfilled threed size-field', 'value' => $width)
							), $product_id, $product_type ) );

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_general', array("art_height" => array(
									'placeholder' => __('height (cm)', 'wc-frontend-manager') , 'type' => 'number', 'class' => 'wcfm-text wcfm_ele wcfm_full_ele simple variable external grouped booking required-field required-unfilled size-field', 'value' => $height)
							), $product_id, $product_type ) );

							echo '</div>';//size input 3d

							echo '<div class="twod-size-inputs">';

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_general', array("inFrame" => array(
									 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele simple variable external grouped booking twod size-field', 'value' => '1', 'dfvalue' => $in_a_frame)
							), $product_id, $product_type ) );

							echo '<div class="wcfm-field-postLabel twod">Framed?</div>';

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_general', array("framed_length" => array(
									'placeholder' => __('length (cm)', 'wc-frontend-manager') , 'type' => 'number', 'class' => 'wcfm-text wcfm_ele wcfm_full_ele simple variable external grouped booking twod required-field required-unfilled size-field', 'value' => $framed_length)
							), $product_id, $product_type ) );

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters( 'wcfm_product_manage_fields_general', array("framed_height" => array(
									'placeholder' => __('height (cm)', 'wc-frontend-manager') , 'type' => 'number', 'class' => 'wcfm-text wcfm_ele wcfm_full_ele simple variable external grouped booking twod required-field required-unfilled size-field', 'value' => $framed_height)
							), $product_id, $product_type ) );

							echo '</div>';//size input 2d

							echo '</div></div>';//container

							echo '<h4 class="wcfm-product-manage section-header">'.__('MEDIUM', 'woocommerce').'</h4>';

						?>
						<div class="wcfm_clearfix"></div>

						<?php if( !$wcfm_is_category_checklist = apply_filters( 'wcfm_is_category_checklist', true ) ) { ?>
						  <?php if( apply_filters( 'wcfm_is_allow_category', true ) ) { ?>
						  	<?php if( apply_filters( 'wcfm_is_allow_product_category', true ) ) { $catlimit = apply_filters( 'wcfm_catlimit', -1 ); ?>
									<p class="wcfm_title"><strong><?php _e( 'Categories', 'wc-frontend-manager' ); ?></strong></p><label class="screen-reader-text" for="product_cats"><?php _e( 'Categories', 'wc-frontend-manager' ); ?></label>
									<select id="product_cats" name="product_cats[]" class="wcfm-select wcfm_ele simple variable external grouped booking" multiple="multiple" data-catlimit="<?php echo $catlimit; ?>" style="width: 100%; margin-bottom: 10px;">
										<?php
											if ( $product_categories ) {
												$this->generateTaxonomyHTML( 'product_cat', $product_categories, $categories );
											}
										?>
									</select>
								<?php } ?>

								<?php

								if( $wcfm_is_allow_custom_taxonomy = apply_filters( 'wcfm_is_allow_custom_taxonomy', true ) ) {
									$product_taxonomies = get_object_taxonomies( 'product', 'objects' );
									if( !empty( $product_taxonomies ) ) {
										foreach( $product_taxonomies as $product_taxonomy ) {
											if( !in_array( $product_taxonomy->name, array( 'product_cat', 'product_tag', 'wcpv_product_vendors' ) ) ) {
												if( $product_taxonomy->public && $product_taxonomy->show_ui && $product_taxonomy->meta_box_cb && $product_taxonomy->hierarchical ) {
													// Fetching Saved Values
													$taxonomy_values_arr = array();
													if($product && !empty($product)) {
														$taxonomy_values = get_the_terms( $product_id, $product_taxonomy->name );
														if( !empty($taxonomy_values) ) {
															foreach($taxonomy_values as $pkey => $ptaxonomy) {
																$taxonomy_values_arr[] = $ptaxonomy->term_id;
															}
														}
													}
													?>
													<p class="wcfm_title"><strong><?php _e( $product_taxonomy->label, 'wc-frontend-manager' ); ?></strong></p><label class="screen-reader-text" for="<?php echo $product_taxonomy->name; ?>"><?php _e( $product_taxonomy->label, 'wc-frontend-manager' ); ?></label>
													<select id="<?php echo $product_taxonomy->name; ?>" name="product_custom_taxonomies[<?php echo $product_taxonomy->name; ?>][]" class="wcfm-select product_taxonomies wcfm_ele simple variable external grouped booking" multiple="multiple" style="width: 100%; margin-bottom: 10px;">
														<?php
															$product_taxonomy_terms   = get_terms( $product_taxonomy->name, 'orderby=name&hide_empty=0&parent=0' );
															if ( $product_taxonomy_terms ) {
																$this->generateTaxonomyHTML( $product_taxonomy->name, $product_taxonomy_terms, $taxonomy_values_arr );
															}
														?>
													</select>
													<?php
												}
											}
										}
									}
								}
							}

							echo '<h4 class="wcfm-product-manage section-header">'.__('THEME', 'woocommerce').'</h4>';

							if( $wcfm_is_allow_tags = apply_filters( 'wcfm_is_allow_tags', true ) ) {
									if( $wcfm_is_allow_custom_taxonomy = apply_filters( 'wcfm_is_allow_custom_taxonomy', true ) ) {
										$product_taxonomies = get_object_taxonomies( 'product', 'objects' );
										if( !empty( $product_taxonomies ) ) {
											foreach( $product_taxonomies as $product_taxonomy ) {
												if( in_array( $product_taxonomy->name, array( 'genre_tag' ) ) ) {
													if( $product_taxonomy->public && $product_taxonomy->show_ui && $product_taxonomy->meta_box_cb && !$product_taxonomy->hierarchical ) {
														// Fetching Saved Values
														$taxonomy_values_arr = wp_get_post_terms($product_id, $product_taxonomy->name, array("fields" => "names"));
														$taxonomy_values = implode(',', $taxonomy_values_arr);
														$WCFM->wcfm_fields->wcfm_generate_form_field( array(  $product_taxonomy->name => array( 'label' => $product_taxonomy->label, 'name' => 'product_custom_taxonomies_flat[' . $product_taxonomy->name . '][]', 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele wcfm_full_ele simple variable external grouped booking', 'label_class' => 'wcfm_title wcfm_full_ele', 'value' => $taxonomy_values, 'placeholder' => __('Separate Product ' . $product_taxonomy->label . ' with commas', 'wc-frontend-manager') )	) );
													}
												}
											}
										}
									}
								}
							?>
						<?php }

						if( apply_filters( 'wcfm_is_category_checklist', true ) ) {

								if( $wcfm_is_allow_custom_taxonomy = apply_filters( 'wcfm_is_allow_custom_taxonomy', true ) ) {
									$product_taxonomies = get_object_taxonomies( 'product', 'objects' );

									if( !empty( $product_taxonomies ) ) {
										foreach( $product_taxonomies as $product_taxonomy ) {
											if( in_array( $product_taxonomy->name, array( 'medium_cat' ) ) ) {
												if( $product_taxonomy->public && $product_taxonomy->show_ui && $product_taxonomy->meta_box_cb && $product_taxonomy->hierarchical ) {
													// Fetching Saved Values
													$taxonomy_values_arr = array();
													if($product && !empty($product)) {
														$taxonomy_values = get_the_terms( $product_id, $product_taxonomy->name );
														if( !empty($taxonomy_values) ) {
															foreach($taxonomy_values as $pkey => $ptaxonomy) {
																$taxonomy_values_arr[] = $ptaxonomy->term_id;
															}
														}
													}
							// 						?>
							 						<div class="wcfm_clearfix"></div>
							 						<div class="wcfm_product_manager_cats_checklist_fields wcfm_product_taxonomy_<?php echo $product_taxonomy->name; ?>">
							 							<p class="wcfm_title wcfm_full_ele"><strong><?php _e( $product_taxonomy->label, 'wc-frontend-manager' ); ?></strong></p><label class="screen-reader-text" for="<?php echo $product_taxonomy->name; ?>"><?php _e( $product_taxonomy->label, 'wc-frontend-manager' ); ?></label>
							 							<ul id="<?php echo $product_taxonomy->name; ?>" class="product_taxonomy_checklist wcfm_ele simple variable external grouped booking">
							 								<?php
																$product_taxonomy_terms   = get_terms( $product_taxonomy->name, 'orderby=name&hide_empty=0&parent=0' );
																if ( $product_taxonomy_terms ) {
																	//echo print_r($product_taxonomy_terms);
																	$WCFM->library->generateTaxonomyHTML( 'medium_cat', $product_taxonomy_terms, $taxonomy_values_arr, '', true, true );
																}
							// 								?>
							 							</ul>
							 						</div>
							 						<?php
												}
											}
										}
									}
								}

							echo '<p class="description">You may choose a maximum of 2 main-medium. If the medium has sub-categories, then you can choose one. If you don’t see the genre your artwork should be categorised with, then please send us a message to <a href="https://artmo.com/user/artmo">ARTMO</a>, so we can review and extend the list.</p>';


							echo '<h4 class="wcfm-product-manage section-header">'.__('GENRE', 'woocommerce').'<i class="ion ion-ios-help"
								title="'. __('This is a section where you can specify the genre of your work.
For example, if your paintings are representative of expressionist style, you can insert: Expressionism. ') .'"></i>'.'</h4>';

							//$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'product_simple_fields_tag', array(  "genre_tag" => array('type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele simple variable external grouped booking', 'value' => $genre_tags, 'placeholder' => __('Separate Product Tags with commas', 'wc-frontend-manager'), 'desc' => __( "If you don't see your tag contact us at hello@artmo.com.", 'wc-frontend-manager' ) ) ) ) );

							if( $wcfm_is_allow_custom_taxonomy = apply_filters( 'wcfm_is_allow_custom_taxonomy', true ) ) {
								$product_taxonomies = get_object_taxonomies( 'product', 'objects' );

								if( !empty( $product_taxonomies ) ) {
									foreach( $product_taxonomies as $product_taxonomy ) {
										if( in_array( $product_taxonomy->name, array( 'genre_tag' ) ) ) {
											if( $product_taxonomy->public && $product_taxonomy->show_ui && $product_taxonomy->meta_box_cb && $product_taxonomy->hierarchical ) {
												// Fetching Saved Values
												$taxonomy_values_arr = array();
												if($product && !empty($product)) {
													$taxonomy_values = get_the_terms( $product_id, $product_taxonomy->name );
													if( !empty($taxonomy_values) ) {
														foreach($taxonomy_values as $pkey => $ptaxonomy) {
															$taxonomy_values_arr[] = $ptaxonomy->term_id;
														}
													}
												}
						// 						?>
												<div class="wcfm_clearfix"></div>
												<div class="wcfm_product_manager_cats_checklist_fields wcfm_product_taxonomy_<?php echo $product_taxonomy->name; ?>">
													<ul id="<?php echo $product_taxonomy->name; ?>" class="product_taxonomy_checklist wcfm_ele simple variable external grouped booking">
														<?php
															$product_taxonomy_terms   = get_terms( $product_taxonomy->name, 'orderby=name&hide_empty=0&parent=0' );
															if ( $product_taxonomy_terms ) {
																//echo print_r($product_taxonomy_terms);
																$WCFM->library->generateTaxonomyHTML( 'genre_tag', $product_taxonomy_terms, $taxonomy_values_arr, '', true, true );
															}
						// 								?>
													</ul>
												</div>
												<?php
											}
										}
									}
								}
							}

							echo '<p class="description">Your artwork is marked contemporary by default, because it was produced in the present time. You may choose a maximum of 2 more genres.</p>';


							echo '<h4 class="wcfm-product-manage section-header">'.__('SUBJECT', 'woocommerce').'<i class="ion ion-ios-help"
								title="'. __('This is a section where you can specify your work\'s overall subject.
For example, if you paint urban landscapes, you can use a subject: Urban.') .'"></i>'.'</h4>';

							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'product_simple_fields_tag', array(  "theme_tag" => array('type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele simple variable external grouped booking', 'value' => $theme_tags, 'placeholder' => __('Separate Product Tags with commas', 'wc-frontend-manager'), 'desc' => __( "You can insert a maximum of 4 tags. You may not use the hashtag (#) symbol, just words describing the overall subject of the artwork.", 'wc-frontend-manager' ) ) ) ) );

							echo '<h4 class="wcfm-product-manage section-header">'.__('DESCRIPTION', 'woocommerce').'</h4>';

							?>
						<?php } ?>

						<?php if( $wcfm_is_category_checklist = apply_filters( 'wcfm_is_category_checklist', true ) ) { ?>
							<div class="wcfm_clearfix"></div><br />
							<div class="wcfm_product_manager_content_fields">
								<?php

								$WCFM->wcfm_fields->wcfm_generate_form_field(
									apply_filters( 'wcfm_product_manage_fields_general', array("artDescription" => array(
										'placeholder' => __('Short Description', 'wc-frontend-manager') , 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele wcfm_full_ele simple variable external grouped booking', 'value' => $artDescription)
								), $product_id, $product_type ) );

								$WCFM->wcfm_fields->wcfm_generate_form_field(
									apply_filters( 'wcfm_product_manage_fields_general', array("description" => array(
										'placeholder' => __('Note to Self (not visible on your Artwork)', 'wc-frontend-manager') , 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele wcfm_full_ele simple variable external grouped booking', 'value' => $description)
								), $product_id, $product_type ) );

								$WCFM->wcfm_fields->wcfm_generate_form_field(
									apply_filters( 'wcfm_product_manage_fields_content', array("pro_id" =>
								array('type' => 'hidden', 'value' => $product_id)), $product_id, $product_type ));

								?>
							</div>
						<?php } ?>

						<div id="wcfm_products_simple_submit" class="wcfm_form_simple_submit_wrapper">
							<div class="wcfm-message" tabindex="-1"></div>
							<?php if( $product_id && ( $wcfm_products_single->post_status == 'publish' ) ) { ?>
							  <input type="submit" name="submit-data" value="<?php if( apply_filters( 'wcfm_is_allow_publish_live_products', true ) ) { _e( 'Submit', 'wc-frontend-manager' ); } else { _e( 'Submit for Review', 'wc-frontend-manager' ); } ?>" id="wcfm_products_simple_submit_button" class="wcfm_submit_button" />
							<?php } else { ?>
								<input type="submit" name="submit-data" value="<?php if( current_user_can( 'publish_products' ) && apply_filters( 'wcfm_is_allow_publish_products', true ) ) { _e( 'Submit', 'wc-frontend-manager' ); } else { _e( 'Submit for Review', 'wc-frontend-manager' ); } ?>" id="wcfm_products_simple_submit_button" class="wcfm_submit_button" />
							<?php } ?>
							<?php if( apply_filters( 'wcfm_is_allow_draft_published_products', true ) && apply_filters( 'wcfm_is_allow_add_products', true ) ) { ?>
							  <input type="submit" name="draft-data" value="<?php _e( 'Draft', 'wc-frontend-manager' ); ?>" id="wcfm_products_simple_draft_button" class="wcfm_submit_button" />
							<?php } ?>

							<?php
							if( $product_id && ( $wcfm_products_single->post_status != 'publish' ) ) {
								echo '<a target="_blank" href="' . apply_filters( 'wcfm_product_preview_url', get_permalink( $wcfm_products_single->ID ) ) . '">';
								?>
								<input type="button" class="wcfm_submit_button" value="<?php _e( 'Preview', 'wc-frontend-manager' ); ?>" />
								<?php
								echo '</a>';
							} elseif( $product_id && ( $wcfm_products_single->post_status == 'publish' ) ) {
								echo '<a target="_blank" href="' . apply_filters( 'wcfm_product_preview_url', get_permalink( $wcfm_products_single->ID ) ) . '">';
								?>
								<input type="button" class="wcfm_submit_button" value="<?php _e( 'View', 'wc-frontend-manager' ); ?>" />
								<?php
								echo '</a>';
							}
							?>
						</div>
						<div id="wcfm-cancel-button" class="wcfm_cancel_button"><a href="<?php echo get_wcfm_page(); ?>wcfm-products"><?php _e( 'CANCEL', 'vantage-child' ); ?></a></div>

					</div>

					<div class="progress-bar">
						<div class="progress-bar-container">
							<p class="progress-bar-item progress-bar_photo"><?php echo __('Photo', 'vantage-child'); ?>
								<span class="tip"><?php echo __('Upload a high resolution photo', 'vantage-child'); ?></span>
							</p>
							<p class="progress-bar-item progress-bar_overview"><?php echo __('Overview', 'vantage-child'); ?>
								<span class="tip"><?php echo __('Insert title, year, medium and price', 'vantage-child'); ?></span>
							</p>
							<p class="progress-bar-item progress-bar_size"><?php echo __('Size', 'vantage-child'); ?>
								<span class="tip"><?php echo __('Insert length and width', 'vantage-child'); ?></span>
							</p>
							<p class="progress-bar-item progress-bar_tags"><?php echo __('Medium | genre', 'vantage-child'); ?>
								<span class="tip"><?php echo __('Pick a genre', 'vantage-child'); ?></span>
							</p>
							<p class="progress-bar-item progress-bar_ready"><?php echo __('Ready!', 'vantage-child'); ?></p>
						</div>
					</div>



				</div>
			</div>
			<!-- end collapsible -->
			<div class="wcfm_clearfix"></div><br />



		</form>
		<?php
		//do_action( 'after_wcfm_products_manage' );
		?>
	</div>
</div>
