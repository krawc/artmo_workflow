<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

remove_action( 'woocommerce_before_shop_loop_item','woocommerce_template_loop_product_link_open', 10 );

//Open product in new tab

add_action ( 'woocommerce_before_shop_loop_item', 'ami_function_open_new_tab', 10 );
function ami_function_open_new_tab() {
  echo '<a target="_blank" href="' . get_the_permalink() . '" class="woocommerce-LoopProduct-link">';
}

//currency switcher

add_action( 'woocommerce_single_product_summary', 'my_extra_button_on_product_page', 19 );

function my_extra_button_on_product_page() {
  global $product;
  $price = $product->get_regular_price();
  echo do_shortcode ('[woocs]');
}

//Remove additional info tab from products

add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {
    global $product;
	  unset( $tabs['additional_information'] );
    return $tabs;
}

//Change order on product description

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 20 );

//Add function to display custom fields on single products:

add_action( 'woocommerce_single_product_summary', 'add_custom_fields_on_single_product', 18 );
add_action( 'woocommerce_after_shop_loop_item_title', 'add_custom_fields_on_single_product', 5);

function add_custom_fields_on_single_product(){

    global $woocommerce, $post, $product;

    $product_id = $post->ID;
    $author_id = $post->post_author;

    $size = $framed = '';

    $info_artist = array();
    $info_details = array();
    $info_size = array();

    $youTheArtist = get_post_meta($product_id, 'youTheArtist', true);
    $artistFirstName = get_post_meta($product_id, 'artistFirstName', true);
    $artistLastName = get_post_meta($product_id, 'artistLastName', true);
    $series = get_post_meta($product_id, 'series', true);
    $series_name = get_post_meta($product_id, 'series_name', true);
    $edition = get_post_meta($product_id, 'edition', true);
    $edition_no = get_post_meta($product_id, 'edition_no', true);
    $edition_t = get_post_meta($product_id, 'edition_t', true);
    $dimensions = get_post_meta($product_id, 'dimensions', true);
    $units = get_post_meta($product_id, 'units', true);
    $unique = get_post_meta($product_id, 'isUnique', true);
    $edition_number = 'Edition '.get_post_meta($product_id, 'editionNumber', true);
    $edition_total = get_post_meta($product_id, 'editionTotal', true);
    $in_a_frame = get_post_meta($product_id, 'inFrame', true);
    $framed_length = get_post_meta($product_id, 'framed_length', true);
    $framed_height = get_post_meta($product_id, 'framed_height', true);
    $length = get_post_meta($product_id, 'art_length', true);
    $width = get_post_meta($product_id, 'art_width', true);
    $height = get_post_meta($product_id, 'art_height', true);
    $artDescription = get_post_meta($product_id, 'artDescription', true);
    $artistCountry = get_user_meta($author_id, 'countryField', true);
    $artistCity = get_user_meta($author_id, 'cityField', true);
    $year_produced = get_post_meta($product_id, 'year', true);
    $material_and_media = get_post_meta($product_id, 'materialMedia', true);
    $country_of_production = get_post_meta($product_id, 'countryProd', true);

    if ($units == 1) {
      if (!empty($framed_length)) $framed_length = artmo_inches2feet($framed_length);
      if (!empty($framed_height)) $framed_height = artmo_inches2feet($framed_height);
      $length = artmo_inches2feet($length);
      $width = artmo_inches2feet($width);
      $height = artmo_inches2feet($height);
    }

    //specific rules


    $display_name = get_user_meta($author_id, 'user_display_name', true);

    if (empty($display_name)) {
      //check if the artwork's publisher is the artist themself
      if ((!empty($artistFirstName)) && ($youTheArtist == 0)) {
        $first_name = $artistFirstName;
      } else {
        $first_name = get_post_meta($product_id, 'firstName', true);
      }

      //check if the artwork's publisher is the artist themself
      if ((!empty($artistLastName)) && ($youTheArtist == 0)) {
        $last_name = $artistLastName;
      } else {
        $last_name = get_post_meta($product_id, 'lastName', true);
      }
      $full_name = $first_name.' '.$last_name;

    } else {
      $full_name = $display_name;
    }


    if ((empty($country_of_production)) || ($country_of_production === $artistCountry)) {
      $country_of_production = '';
    }

    if ($edition == 1) {
      $edition_word = 'Edition '.$edition_no.' / '.$edition_t;
    }

    if (($length > 0) && ($height > 0)) {
      $size = $length.' x '.$height;
      if (!$units) $size .=__(' cm', 'vantage-child');//check if metric
      $framed = __(' • Framed: ', 'vantage-child').$framed_length.' x '.$framed_height;
      if (!$units) $framed .=__(' cm', 'vantage-child');
      //if the artwork is 3D
      if( $dimensions == 1) {
        $size = $length.' x '.$width.' x '.$height;
        if (!$units) $size .=__(' cm', 'vantage-child');
        $framed = '';
      }
    }

    //array for product_artist

    foreach (array($full_name, $artistCity, $artistCountry) as $value) {
      if(!empty($value))
        $info_artist[$value] = '<span class="product-details__field">' . $value . '</span>';
    }

    foreach (array($country_of_production, $year_produced, $material_and_media) as $value) {
      if(!empty($value))
        $info_details[$value] = '<span class="product-size__field">' . $value . '</span>';
    }

    $output = '';
    $output .= '<p class="woocommerce-product_artist">'.implode('<span class="__dot"> • </span>', $info_artist).'</p>';
    $output .= '<p class="woocommerce-product_details artwork_details">'.implode('<span class="__dot"> • </span>', $info_details).'</p>';
    $output .= '<p class="woocommerce-product_details artwork_details">'.$edition_word.'</p>';
    $output .= '<p class="woocommerce-product_details artwork_size">'.$size;
    if ( ! $product->is_in_stock() )
      echo '<div class="stock out-of-stock">SOLD</div>';
    if (!empty($framed_length)) {
      $output .= $framed;
    }
    $output .= '</p>';
    $output .= '<p class="woocommerce-product_details artwork_description">'.$artDescription.'</p>';
    echo $output;

}

//Disable default Related Products section

add_filter('woocommerce_related_products_args','wc_remove_related_products', 10);

function wc_remove_related_products( $args ) {
	return array();
}

//Add HTML markup around breadcrumbs (to enable CSS edits)

add_filter( 'woocommerce_breadcrumb_defaults', 'my_woocommerce_breadcrumbs' );

function my_woocommerce_breadcrumbs() {
    return array(
        'delimiter'   => ' &#32; ',
        'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb">',
        'wrap_after'  => '</nav>',
        'before'      => '<span>',
        'after'       => '</span>',
        'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
    );
}

//Display: product order at the bottom of the shop archive

add_action ('init','relocate_product_order');

function relocate_product_order() {
  remove_action( 'woocommerce_before_shop_loop', 'wc_print_notices', 10 );
  remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
}

//Add the new Product Gallery Compatibility

add_action( 'after_setup_theme', 'yourtheme_setup' );

function yourtheme_setup() {
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

//Remove the default Woocommerce category/tag list

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

//custom product meta
add_action( 'woocommerce_single_product_summary', 'custom_product_meta_woocommerce', 40 );

function custom_product_meta_woocommerce(){
  if ( ! defined( 'ABSPATH' ) ) {
  	exit;
  }
  global $product;
  echo '<div class="product_meta">';
  do_action( 'woocommerce_product_meta_start' );

  $categories = get_the_terms( get_the_ID(), 'artists_cat' );
  $media_categories = get_the_terms( get_the_ID(), 'medium_cat' );
  $genre_tags = get_the_terms( get_the_ID(), 'genre_tag' );
  $theme_tags = get_the_terms( get_the_ID(), 'theme_tag' );
  $country_cat = get_the_terms( get_the_ID(), 'country_cat' );
  $color_tags = get_the_terms( get_the_ID(), 'color_tag' );

  $output = "";

  if ( $categories ) {
    $children = false;
    $childrenContent = '';

    foreach ($categories as $category) {
        if (!empty($category->parent)) {
          $children = true;
          $childrenContent .= '<span class="tagged_as"><a href="' . get_permalink( woocommerce_get_page_id( 'shop' ) ) . '?swoof=1&artists_cat=' . $category->slug . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '" > ' . $category->name.' </a></span>';
        }
    }

    if ($children === true) {
      $output .= '<div class="product-tax-section"><h3 class="product-tax-name">' . __('SERIES ', 'vantage-child') . '</h3>';
      $output .= '<div class="product-tax-list">';
      $output .= $childrenContent;
      $output .= '</div></div>';
    }
  }

  if ( $categories || $genre_tags || $country_cat ) {

    $output .= '<div class="product-tax-section"><h3 class="product-tax-name">' . __('CATEGORIES ', 'vantage-child') . '</h3>';
    $output .= '<div class="product-tax-list">';
    if( $categories ){
        foreach ($categories as $category) {
            if (empty($category->parent)) {
              $output .= '<span class="tagged_as"><a href="' . get_permalink( woocommerce_get_page_id( 'shop' ) ) . '?swoof=1&artists_cat=' . $category->slug . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '" > ' . $category->name.' </a></span>';
            }
        }
    }

    if( $media_categories ){
        foreach ($media_categories as $category) {
            $output .= '<span class="tagged_as"><a href="' . get_permalink( woocommerce_get_page_id( 'shop' ) ) . '?swoof=1&medium_cat=' . $category->slug . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '" > ' . $category->name.' </a></span>';
        }
    }

    if( $country_cat ){
        foreach ($country_cat as $category) {
            $output .= '<span class="tagged_as"><a href="' . get_permalink( woocommerce_get_page_id( 'shop' ) ) . '?swoof=1&country_cat=' . $category->name . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '" > ' . $category->name.' </a></span>';
        }
    }
    $output .= '</div></div>';
  }

  if( $genre_tags ){
    $output .= '<div class="product-tax-section"><h3 class="product-tax-name">' . __('GENRE ', 'vantage-child') . '</h3>';
    $output .= '<div class="product-tax-list">';
      foreach ($genre_tags as $category) {
          $output .= '<span class="tagged_as"><a href="' . get_permalink( woocommerce_get_page_id( 'shop' ) ) . '?swoof=1&genre_tag=' . $category->slug . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '" > ' . $category->name.' </a></span>';
      }
    $output .= '</div></div>';
  }

  if( $theme_tags ){
    $output .= '<div class="product-tax-section"><h3 class="product-tax-name">' . __('SUBJECT ', 'vantage-child') . '</h3>';
    $output .= '<div class="product-tax-list">';
      foreach ($theme_tags as $category) {
          $output .= '<span class="tagged_as"><a href="' . get_permalink( woocommerce_get_page_id( 'shop' ) ) . '?swoof=1&theme_tag=' . $category->slug . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '" > ' . $category->name.' </a></span>';
      }
    $output .= '</div></div>';
  }

  if ( $color_tags ) {
    $output .= '<div class="product-tax-section"><h3 class="product-tax-name">' . __('COLORS ', 'vantage-child') . '</h3>';
    $output .= '<div class="product-tax-list">';
    foreach ($color_tags as $color) {
      $output .= '<div class="color-link" style="background-color: ' . strtolower($color->name) . ';"><a href="' . get_permalink( woocommerce_get_page_id( 'shop' ) ) . '?swoof=1&color_tag=' . $color->name . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $color->name ) ) . '" >'.$color->name.'</a></div>';
    }
    $output .= '</div></div>';
  }

  echo $output;

  do_action( 'woocommerce_product_meta_end' );
  echo '</div>';
}


//Add product description in cart

add_filter( 'woocommerce_get_item_data', 'wc_checkout_description_so_15127954', 10, 2 );

function wc_checkout_description_so_15127954( $other_data, $cart_item ){
    $_product = $cart_item['data'];
    $other_data[] = array( 'name' =>  '', 'value' => $_product->get_short_description() );
    return $other_data;
    var_dump($other_data);
}

// Remove variation stock data from product page display

add_filter( 'woocommerce_available_variation', 'sww_wc_remove_variation_stock_display', 99 );

function sww_wc_remove_variation_stock_display( $data ) {
    unset( $data['availability_html'] );
    return $data;
}

//Remove "sort by popularity"

add_filter( "woocommerce_catalog_orderby", "my_woocommerce_catalog_orderby", 20 );

function my_woocommerce_catalog_orderby( $orderby ) {
    unset($orderby["popularity"]);
    return $orderby;
}

//Increase the number of products displayed per archive page

add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {
  $cols = 20;
  return $cols;
}

//Open products in a new tab
add_filter( 'wc_product_table_open_products_in_new_tab', '__return_true' );

add_filter('loop_shop_columns', 'loop_columns');

if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 1; // 3 products per row
	}
}

//save meta on product save (to be deprecated)

add_action( 'added_post_meta', 'mp_sync_on_product_save_update_meta', 10, 4 );

function mp_sync_on_product_save_update_meta( $meta_id, $post_id, $meta_key, $meta_value ) {
    if ( get_post_type( $post_id ) == 'product' ) { // we've been editing a product
        $product = wc_get_product( $post_id );

        $author_id = $product->post_author;

        $length = get_post_field( 'art_length', $post_id);
        $width = get_post_field( 'art_width', $post_id);
        $height = get_post_field( 'art_height', $post_id);

        $in_a_frame = get_post_field('inFrame', $post_id);
        $countryProd = get_post_field('countryProd', $post_id);

        $country = get_user_meta($author_id, 'countryField', true);

        $framed_length = get_post_field( 'framed_length', $post_id);
        $framed_width = get_post_field( 'framed_width', $post_id);
        $framed_height = get_post_field( 'framed_height', $post_id);

        if ( $in_a_frame == '1' ) {
          update_post_meta($post_id, '_length', $framed_length);
          update_post_meta($post_id, '_width', $framed_width);
          update_post_meta($post_id, '_height', $framed_height);
        } else {
          update_post_meta($post_id, '_length', $length);
          update_post_meta($post_id, '_width', $width);
          update_post_meta($post_id, '_height', $height);
        }
        $author = get_post_field( 'post_author', $post_id);
        $authordata = get_userdata($author);
        $first_n = $authordata->first_name;
        $last_n = $authordata->last_name;

        update_post_meta($post_id, 'firstName', $first_n);
        update_post_meta($post_id, 'lastName', $last_n);
        $artist_category = get_term_by('slug', 'artist_'.$author, 'artists_cat' );
        $artist_term = array( $artist_category->term_id );

        if(empty($countryProd)) {
          $countryProd = $country;
        }

        um_fetch_user( $author_id );
        $role = artmo_get_user_roles_by_id( $author_id );
        $display_name = um_user('display_name');
        $user_login = um_user('user_login');

        if (empty(um_user('user_login'))) {
          $display_name = $user_id;
          $user_login = $user_id;
        }
    }
}


add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab' );

function woo_new_product_tab( $tabs ) {

	// Adds the new tab
  $artist_id = get_the_author_meta( 'ID' );
  $display_name = get_user_meta($artist_id, 'user_display_name', true);

  $args = array(
      'post_type' => 'product',
      'posts_per_page' => 8,
      'author' => $artist_id
  );
  $query = new WP_Query( $args );
  $count = $query->post_count;

  if( ($query->have_posts()) && ($count >= 2)) :
  	$tabs['more_artworks'] = array(
  		'title' 	=> sprintf(__( 'MORE FROM %s', 'woocommerce' ), $display_name),
  		'priority' 	=> 50,
  		'callback' 	=> 'woo_more_products_tab'
  	);
  endif;

  $tabs['about_artist'] = array(
    'title' 	=> sprintf(__( 'ABOUT %s', 'woocommerce' ), $display_name),
    'priority' 	=> 50,
    'callback' 	=> 'woo_author_meta_tab'
  );

  $tabs['shipping_policy'] = array(
    'title' 	=> sprintf(__( 'Shipping Policy', 'woocommerce' ), $display_name),
    'priority' 	=> 50,
    'callback' 	=> 'woo_author_shipping_policy'
  );

	return $tabs;
}


function woo_author_shipping_policy() {
  $prod_id = get_the_ID();
  $artist_id = get_the_author_meta( 'ID' );
  $artist_shipping_policy = get_user_meta($artist_id, 'shipping_policy', true);
  $content = '';
  if (!empty($artist_shipping_policy)) {
    $content .= '<div class="note-from-seller">';
    $content .= get_user_meta($artist_id, 'shipping_policy', true);
    $content .= '</div>';
  }
  $content .= get_theme_mod('global_shipping_policy');
  echo $content;
}

function woo_more_products_tab() {

  $prod_id = get_the_ID();
  $artist_id = get_the_author_meta( 'ID' );
  $cats = get_the_category();
  $artist_category_link = get_term_link('artist_'.$artist_id, 'artists_cat' );
  $args = array(
      'post_type' => 'product',
      'posts_per_page' => 8,
      'author' => $artist_id,
      'orderby' => 'rand'
  );
  $query = new WP_Query( $args );
  ?>

  <div class="products_slick">
    <?php if( $query->have_posts() ) : while( $query->have_posts() ) : $query->the_post();
    $product = wc_get_product( get_the_ID() );?>
      <div class="slick-singleProduct">
        <a href="<?php the_permalink();?>">
          <div class="slick-singleProduct_thumb">
            <img class="thumbImage" src="<?php echo get_the_post_thumbnail_url(get_the_ID(),'full'); ?>">
          </div>
          <div class="slick-singleProduct-info">
            <p><?php the_title(); ?></p>
            <p> <?php echo $product->get_price_html(); ?></p>
          </div>
        </a>
      </div>
      <?php endwhile; endif; wp_reset_postdata();?>

  </div>
<?php
}

function woo_author_meta_tab() {

  $ultimatemember = new UM();
  $my_home_url = apply_filters( 'wpml_home_url', get_option( 'home' ) );
  $prod_id = get_the_ID();
  $artist_id = get_the_author_meta( 'ID' );
  um_fetch_user($artist_id);
  $artist_login = sanitize_title( um_user('user_login') );
  $artist_first_name = um_user('first_name');
  $artist_last_name = um_user('last_name');
	$artist_name = $artist_first_name.' '.$artist_last_name;
  $artist_avatar = um_get_avatar_uri( um_profile('profile_photo'), 250 );?>
    <div class="artist_info">
      <!-- <div class="artist-profilePic"> -->
      <!-- </div> -->
      <div class="artist_meta">
        <p><img class="artist-profilePic-img" align="top" src="<?php echo $artist_avatar; ?>"/><?php echo um_user('text_field');?></p>
      </div>
      <div class="profile-link">
        <a target="_blank" href="<?php echo $my_home_url.'/user/'.$artist_login; ?>"><?php _e('GO TO PROFILE', 'vantage-child'); ?></a>
      </div>
    </div>
  <?php
}

add_filter( 'woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98 );
	function wcs_woo_remove_reviews_tab($tabs) {
	unset($tabs['reviews']);
  unset($tabs['description']);
	return $tabs;
}

//add AUTHOR field to products

if ( post_type_exists( 'product' ) ) {
  add_post_type_support( 'product', 'author' );
}

add_action( 'woocommerce_product_query', 'custom_pre_get_posts_query' );
function custom_pre_get_posts_query( $query ) {

    $query->set( 'meta_query', array( array(
       'key' => '_thumbnail_id',
       'value' => '0',
       'compare' => '>'
    )));

}



add_action( 'restrict_manage_posts', 'wpse45436_admin_posts_filter_restrict_manage_posts' );

function wpse45436_admin_posts_filter_restrict_manage_posts(){
    $type = 'post';
    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];
    }

    if ('product' == $type){

        $values = array(
            'label' => 'value',
            'label1' => 'value1',
            'label2' => 'value2',
        );
        ?>
        <select name="ADMIN_FILTER_FIELD_VALUE">
        <option value=""><?php _e('Filter By ', 'wose45436'); ?></option>
        <?php
            $current_v = isset($_GET['ADMIN_FILTER_FIELD_VALUE'])? $_GET['ADMIN_FILTER_FIELD_VALUE']:'';
            foreach ($values as $label => $value) {
                printf
                    (
                        '<option value="%s"%s>%s</option>',
                        $value,
                        $value == $current_v? ' selected="selected"':'',
                        $label
                    );
                }
        ?>
        </select>
        <?php
    }
}


add_filter( 'parse_query', 'wpse45436_posts_filter' );

function wpse45436_posts_filter( $query ){
    global $pagenow;
    $type = 'post';
    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];
    }
    if ( 'POST_TYPE' == $type && is_admin() && $pagenow=='edit.php' && isset($_GET['ADMIN_FILTER_FIELD_VALUE']) && $_GET['ADMIN_FILTER_FIELD_VALUE'] != '') {
        $query->query_vars['meta_key'] = 'META_KEY';
        $query->query_vars['meta_value'] = $_GET['ADMIN_FILTER_FIELD_VALUE'];
    }
}

function rudr_posts_taxonomy_filter() {
	global $typenow; // this variable stores the current custom post type
	if( $typenow == 'product' ){ // choose one or more post types to apply taxonomy filter for them if( in_array( $typenow  array('post','games') )
		$taxonomy_names = array('artists_cat');
		foreach ($taxonomy_names as $single_taxonomy) {
			$current_taxonomy = isset( $_GET[$single_taxonomy] ) ? $_GET[$single_taxonomy] : '';
			$taxonomy_object = get_taxonomy( $single_taxonomy );
			$taxonomy_name = strtolower( $taxonomy_object->labels->name );
			$taxonomy_terms = get_terms( array( 'taxonomy' => $single_taxonomy, 'parent' => 0 ));
			if(count($taxonomy_terms) > 0) {
				echo "<select name='$single_taxonomy' id='$single_taxonomy' class='postform'>";
				echo "<option value=''>All $taxonomy_name</option>";
				foreach ($taxonomy_terms as $single_term) {
					echo '<option value='. $single_term->slug, $current_taxonomy == $single_term->slug ? ' selected="selected"' : '','>' . $single_term->name .' (' . $single_term->count .')</option>';
				}
				echo "</select>";
			}
		}
	}
}

add_action( 'restrict_manage_posts', 'rudr_posts_taxonomy_filter' );


add_action( 'wp_ajax_nopriv_ajax_pagination', 'my_ajax_pagination' );
add_action( 'wp_ajax_ajax_pagination', 'my_ajax_pagination' );

function my_ajax_pagination() {

    $output = '';

    $query_vars = json_decode( stripslashes( $_POST['query_vars'] ), true );

    $query_vars['paged'] = $_POST['page'];


    $args=array('post_type'=>'product','order'=>'desc','posts_per_page'=>-1);

    $posts = new WP_Query( $args );
    $GLOBALS['wp_query'] = $posts;

    add_filter( 'editor_max_image_size', 'my_image_size_override' );

    if( ! $posts->have_posts() ) {
        $output .= get_template_part( 'content', 'none' );
    }
    else {
        while ( $posts->have_posts() ) {
            $posts->the_post();
            wc_get_template_part( 'content', 'product' );
        }
    }
    remove_filter( 'editor_max_image_size', 'my_image_size_override' );

    the_posts_pagination( array(
        'prev_text'          => __( 'Previous page', 'twentyfifteen' ),
        'next_text'          => __( 'Next page', 'twentyfifteen' ),
        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
    ) );

    return $output;

    die();
}


function my_image_size_override() {
    return array( 825, 510 );
}



add_action('init', 'woocommerce_price_filter_init');
add_filter('loop_shop_post_in', 'woocommerce_price_filter');

function woocommerce_price_filter_init() {

	unset($_SESSION['min_price']);
	unset($_SESSION['max_price']);

	if (isset($_GET['min_price'])) :
		$_SESSION['min_price'] = $_GET['min_price'];
	endif;
	if (isset($_GET['max_price'])) :
		$_SESSION['max_price'] = $_GET['max_price'];
	endif;

}

/**
 * Price Filter post filter
 */
function woocommerce_price_filter( $filtered_posts ) {

	if (isset($_GET['max_price']) && isset($_GET['min_price'])) :

		$matched_products = array();

		$matched_products_query = get_posts(array(
			'post_type' => array(
				'product_variation',
				'product'
			),
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => '_price',
					'value' => array( $_GET['min_price'], $_GET['max_price'] ),
					'type' => 'NUMERIC',
					'compare' => 'BETWEEN'
				)
			)
		));

		if ($matched_products_query) :
			foreach ($matched_products_query as $product) :
				if ($product->post_type == 'product') $matched_products[] = $product->ID;
				if ($product->post_parent>0 && !in_array($product->post_parent, $matched_products))
					$matched_products[] = $product->post_parent;
			endforeach;
		endif;

		// Filter the id's
		if (sizeof($filtered_posts)==0) :
			$filtered_posts = $matched_products;
			$filtered_posts[] = 0;
		else :
			$filtered_posts = array_intersect($filtered_posts, $matched_products);
			$filtered_posts[] = 0;
		endif;

	endif;

	return (array) $filtered_posts;
}

add_filter('woocommerce_empty_price_html', 'custom_call_for_price');

function custom_call_for_price() {
     return 'Price on request';
}

add_filter( 'woocommerce_default_catalog_orderby', 'custom_woocommerce_catalog_orderby' );

function custom_woocommerce_catalog_orderby( $sortby ) {
    return 'random_order';
}

add_action( 'transition_post_status', 'product_approved_notification', 10, 3);

function product_approved_notification(  $new_status, $old_status, $post  ) {
  if ( ! WC_Product_Vendors_Utils::auth_vendor_user() || 'product' !== get_post_type( $post->ID ) ) {
    return;
  }
  if ( 'publish' === $new_status && $old_status !== $new_status ) {
    $emails = WC()->mailer()->get_emails();
    if ( ! empty( $emails ) ) {
      $emails[ 'WC_Product_Vendors_Product_Approved_Notice' ]->trigger( $post );
    }
  }
  return true;
}


add_action( 'added_post_meta', 'mp_sync_on_product_save', 10, 4 );
add_action( 'updated_post_meta', 'mp_sync_on_product_save', 10, 4 );

function mp_sync_on_product_save( $meta_id, $post_id, $meta_key, $meta_value ) {
    if ( $meta_key == '_thumbnail_id' ) { // we've been editing the post thumbnail
        if ( get_post_type( $post_id ) == 'product' ) { // we've been editing a product
            $product = wc_get_product( $post_id );
            // do something with this product
            $image_id = $product->get_image_id();
            $image_url = wp_get_attachment_url( $image_id );
  					$clarifai_url = 'https://api.clarifai.com/v2/models/eeed0b6733a644cea07cf4c60f87ebb7/outputs';
  					$clarifai_api_key = 'ffc3390c9aed4952b0f0900b9d223aea';

  					$response = wp_remote_post( $clarifai_url, array(
  						'method' => 'POST',
  						'timeout' => 45,
  						'blocking' => true,
  						'headers' => array( 'Authorization' => 'Key '.$clarifai_api_key, 'Content-Type' => 'application/json' ),
  						'body' => json_encode(
  							array (
  							  'inputs' =>
  							  array (
  							    0 =>
  							    array (
  							      'data' =>
  							      array (
  							        'image' =>
  							        array (
  							          'url' => $image_url,
  							        )
  							      )
  							    )
  							  )
  							)
  						)
  					)
  			  );

					if ( is_wp_error( $response ) ) {
					    $error_message = $response->get_error_message();
							update_post_meta($new_product_id, 'colors', $error_message);
					} else {
							$response_data = wp_remote_retrieve_body($response);
              $response_data_arr = json_decode($response_data, true);
					   	$colors = $response_data_arr['outputs'][0]['data']['colors'];
						 	if(!empty($colors)){
								$first = true;
								foreach( $colors as $color ) {
									$color_name = $color['w3c']['name'];
									if ( $first ) {
										wp_set_object_terms( $post_id, $color_name, 'color_tag' );
										update_post_meta($post_id, 'colors', $color_name);
										$first = false;
									} else {
										wp_set_object_terms( $post_id, $color_name, 'color_tag', true );
										update_post_meta($post_id, 'colors', $color_name);
									}
								}

                update_post_meta($post_id, 'colors', $colors);
							} else {
                update_post_meta($post_id, 'colors', $response_data);
              }
						}
        }
    }
}

add_action( 'woocommerce_after_add_to_cart_button', 'add_messaging_button_um');

function add_messaging_button_um() {
  global $woocommerce, $post, $product;
  $product_id = $post->ID;
  $authordata = get_userdata($post->post_author);
  $author = $authordata->ID;
  echo do_shortcode('[ultimatemember_message_button user_id="' . $author . '"]');
}


// Registering custom post status
function wpb_custom_post_status(){
    register_post_status('rejected', array(
        'label'                     => _x( 'Rejected', 'product' ),
        'public'                    => false,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Rejected <span class="count">(%s)</span>', 'Rejected <span class="count">(%s)</span>' ),
    ) );
}
add_action( 'init', 'wpb_custom_post_status' );

// Using jQuery to add it to post status dropdown
add_action('admin_footer-post.php', 'wpb_append_post_status_list');

function wpb_append_post_status_list(){
  global $post;
  $complete = '';
  $label = '';
  if($post->post_type == 'product'){
    if($post->post_status == 'rejected'){
      $complete = ' selected="selected"';
      $label = '<span id="post-status-display"> Rejected</span>';
    }
    echo '
    <script>
    jQuery(document).ready(function($){
    $("select#post_status").append("<option value=\"rejected\" '.$complete.'>Rejected</option>");
    $(".misc-pub-section label").append("'.$label.'");
    });
    </script>
    ';
  }
}


add_filter('woocs_raw_woocommerce_price', 'artmo_round_price');
add_filter('woocs_exchange_value', 'artmo_round_price');

function artmo_round_price($price) {

  $floored = floor($price);
  $diff = $price - $floored;

  if ($diff <= 0.15) {
    return $floored;
  } else if ($diff > 0.15 && $diff <= 0.65) {
    return round($price * 2, 0) / 2;
  } else {
    return round($price);
  }
}

add_filter( 'wc_price', 'my_price', 3, 60 );

function my_price( $return, $price, $args ) {

    $price = (string)$price;
    $currency = get_woocommerce_currency();
    $decimalCommaCurr = array( 'USD', 'GBP', 'CNY', 'JPY' );

    if (in_array($currency, $decimalCommaCurr)) {
      $decimal_separator = '.';
      $thousand_separator = ',';
    } else {
      $decimal_separator = wc_get_price_decimal_separator();
      $thousand_separator = wc_get_price_thousand_separator();
    }

     $decimals = ( is_woocommerce() ? 0 : wc_get_price_decimals() );

     if ($decimals > 0) {
       $priceArr = explode(wc_get_price_decimal_separator(), $price);
       $fullPriceStr = number_format(intval(str_replace(wc_get_price_thousand_separator(), '', $priceArr[0])), 0, $decimal_separator, $thousand_separator);
       $priceTagged = '<span class="price-full-numbers">' .  $fullPriceStr . '</span><span class="price-decimals">' . $decimal_separator . $priceArr[1] . '</span>';
     } else {
       $fullPriceStr = number_format(intval(str_replace(wc_get_price_thousand_separator(), '', $price)), 0, $decimal_separator, $thousand_separator);
       $priceTagged = $fullPriceStr;
     }


     $formatted_price = '<span class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol( ) . ' </span>' . '<span class="woocommerce-Price-number">' . $priceTagged . '</span>' ;
     $returnVal = '<span class="woocommerce-Price-amount amount">' . $formatted_price . '</span>';

  	return $returnVal;

}

function artmo_subscription_price( $price ) {

  $price = number_format($price, 2);

  $price = (string)$price;

  $currency = get_woocommerce_currency();

  $decimalCommaCurr = array( 'USD', 'GBP', 'CNY', 'JPY' );

  if (in_array($currency, $decimalCommaCurr)) {
    $decimal_separator = '.';
    $thousand_separator = ',';
  } else {
    $decimal_separator = wc_get_price_decimal_separator();
    $thousand_separator = wc_get_price_thousand_separator();
  }

   $decimals = wc_get_price_decimals();

   if ($decimals > 0) {
     $priceArr = explode('.', $price);
     $fullPriceStr = number_format(intval(str_replace(',', '', $priceArr[0])), 0, $decimal_separator, $thousand_separator);
     $priceTagged = '<span class="price-full-numbers">' .  $fullPriceStr . '</span><span class="price-decimals">' . $decimal_separator . $priceArr[1] . '</span>';
   } else {
     $fullPriceStr = number_format(intval(str_replace(',', '', $price)), 0, $decimal_separator, $thousand_separator);
     $priceTagged = $fullPriceStr;
   }

   $formatted_price = '<span class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol( ) . ' </span>' . '<span class="woocommerce-Price-number">' . $priceTagged . '</span>' ;
   $returnVal = '<span class="woocommerce-Price-amount amount">' . $formatted_price . '</span>';

	return $returnVal;
}



function artmo_collections_page_title() {

  $taxonomies = artmo_get_query_cats();
  $tax_val = array();

  foreach ($taxonomies as $tax) {
    $cats = get_query_var( $tax );
    if (isset($cats) && !empty($cats)) {
      $cats = explode(',', $cats);
      $values = array();
      foreach ($cats as $cat ) {
        $term = get_term_by( 'slug', $cat, $tax, ARRAY_A );//get term name by slug
        $values[] = $term['name'];
      }
      if ($values) {
        $tax_val[$tax] = $values;
      }
    }
  }

  //$first_up = reset($tax_val)[0];
  if (isset($tax_val) && !empty($tax_val)) {
    $tax_val = artmo_flatten_arr($tax_val);
    $tax_val = array_slice($tax_val, 0, 5);
    $tax_val = implode(' • ', $tax_val);
    return $tax_val;

  }
  return __('Collections', 'artmo');
}

function artmo_get_woof_tax_query() {
  $taxonomies = artmo_get_query_cats();
  $args = array();

  foreach ($taxonomies as $tax) {
    $cats = get_query_var( $tax );

    if (isset($cats) && !empty($cats)) {
      $cats = explode(',', $cats);

      $single_term = array();
      $single_term['taxonomy'] = $tax;
      $single_term['field'] = 'slug';
      $single_term['terms'] = $cats;

      $args[] = $single_term;
    }
  }

  if (count($args) > 1) {
    $args['relation'] = 'AND';
  }

  if (count($args) > 0) {
    return $args;
  }

  return false;

}


/*CLASSES*/

class Sorting_Filter extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'classname' => 'sorting_filter',
			'description' => 'Sorting Filter',
		);
		parent::__construct( 'sorting_filter', 'Sorting Filter for Products', $widget_ops );
	}

	public function widget( $args, $instance ) {
		// outputs the content of the widget
  //  WC()->woocommerce_catalog_ordering();
  if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
          return;
      }
      $orderby                 = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) ); // WPCS: sanitization ok, input var ok, CSRF ok.
      $show_default_orderby    = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
      $catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby', array(
          'menu_order' => __( 'Default sorting', 'woocommerce' ),
          'popularity' => __( 'Sort by popularity', 'woocommerce' ),
          'rating'     => __( 'Sort by average rating', 'woocommerce' ),
          'date'       => __( 'Sort by newness', 'woocommerce' ),
          'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
          'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
      ) );

      if ( wc_get_loop_prop( 'is_search' ) ) {
          $catalog_orderby_options = array_merge( array( 'relevance' => __( 'Relevance', 'woocommerce' ) ), $catalog_orderby_options );
          unset( $catalog_orderby_options['menu_order'] );
          if ( 'menu_order' === $orderby ) {
              $orderby = 'relevance';
          }
      }

      if ( ! $show_default_orderby ) {
          unset( $catalog_orderby_options['menu_order'] );
      }

      if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
          unset( $catalog_orderby_options['rating'] );
      }

      wc_get_template( 'loop/orderby.php', array(
          'catalog_orderby_options' => $catalog_orderby_options,
          'orderby'                 => $orderby,
          'show_default_orderby'    => $show_default_orderby,
      ) );
	}

	public function form( $instance ) {
		// outputs the options form on admin
	}

	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
	}
}

// register Foo_Widget widget
function register_sorting_filter_widget() {
    register_widget( 'Sorting_Filter' );
}
add_action( 'widgets_init', 'register_sorting_filter_widget' );
