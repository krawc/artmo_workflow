<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'artist_register_tax' ) ) {

  // Register Custom Taxonomy
  function artist_register_tax() {

  	$labels = array(
  		'name'                       => _x( 'Artists', 'Taxonomy General Name', 'vantage-child' ),
  		'singular_name'              => _x( 'Artist', 'Taxonomy Singular Name', 'vantage-child' ),
  		'menu_name'                  => __( 'Artist Categories', 'vantage-child' ),
  		'all_items'                  => __( 'All Artist Categories', 'vantage-child' ),
  		'parent_item'                => __( 'Parent Artist Category', 'vantage-child' ),
  		'parent_item_colon'          => __( 'Parent Artist Category:', 'vantage-child' ),
  		'new_item_name'              => __( 'New Artist Category', 'vantage-child' ),
  		'add_new_item'               => __( 'Add New Artist Category', 'vantage-child' ),
  		'edit_item'                  => __( 'Edit Artist Category', 'vantage-child' ),
  		'update_item'                => __( 'Update Artist Category', 'vantage-child' ),
  		'view_item'                  => __( 'View Artist Category', 'vantage-child' ),
  		'separate_items_with_commas' => __( 'Separate items with commas', 'vantage-child' ),
  		'add_or_remove_items'        => __( 'Add or remove items', 'vantage-child' ),
  		'choose_from_most_used'      => __( 'Choose from the most used', 'vantage-child' ),
  		'popular_items'              => __( 'Popular Items', 'vantage-child' ),
  		'search_items'               => __( 'Search Artist Categories', 'vantage-child' ),
  		'not_found'                  => __( 'Not Found', 'vantage-child' ),
  		'no_terms'                   => __( 'No categories', 'vantage-child' ),
  		'items_list'                 => __( 'Categories list', 'vantage-child' ),
  		'items_list_navigation'      => __( 'Artist categories list navigation', 'vantage-child' ),
  	);
  	$args = array(
  		'labels'                     => $labels,
  		'hierarchical'               => true,
  		'public'                     => true,
  		'show_ui'                    => true,
  		'show_admin_column'          => true,
  		'show_in_nav_menus'          => true,
  		'show_tagcloud'              => true,
  	);
  	register_taxonomy( 'artists_cat', array( 'product' ), $args );

  }
  add_action( 'init', 'artist_register_tax', 0 );

}

if ( ! function_exists( 'country_cat_register_tax' ) ) {
  // Register Custom Taxonomy
  function country_cat_register_tax() {

  	$labels = array(
  		'name'                       => _x( 'Country Category', 'Taxonomy General Name', 'vantage-child' ),
  		'singular_name'              => _x( 'Country Category', 'Taxonomy Singular Name', 'vantage-child' ),
  		'menu_name'                  => __( 'Country Categories', 'vantage-child' ),
  		'all_items'                  => __( 'All Country Categories', 'vantage-child' ),
  		'parent_item'                => __( 'Parent Country Category', 'vantage-child' ),
  		'parent_item_colon'          => __( 'Parent Country Category:', 'vantage-child' ),
  		'new_item_name'              => __( 'New Country Category', 'vantage-child' ),
  		'add_new_item'               => __( 'Add New Country Category', 'vantage-child' ),
  		'edit_item'                  => __( 'Edit Country Category', 'vantage-child' ),
  		'update_item'                => __( 'Update Country Category', 'vantage-child' ),
  		'view_item'                  => __( 'View Country Category', 'vantage-child' ),
  		'separate_items_with_commas' => __( 'Separate items with commas', 'vantage-child' ),
  		'add_or_remove_items'        => __( 'Add or remove items', 'vantage-child' ),
  		'choose_from_most_used'      => __( 'Choose from the most used', 'vantage-child' ),
  		'popular_items'              => __( 'Popular Items', 'vantage-child' ),
  		'search_items'               => __( 'Search Country Categories', 'vantage-child' ),
  		'not_found'                  => __( 'Not Found', 'vantage-child' ),
  		'no_terms'                   => __( 'No categories', 'vantage-child' ),
  		'items_list'                 => __( 'Categories list', 'vantage-child' ),
  		'items_list_navigation'      => __( 'Country categories list navigation', 'vantage-child' ),
  	);
  	$args = array(
  		'labels'                     => $labels,
  		'hierarchical'               => false,
  		'public'                     => true,
  		'show_ui'                    => true,
  		'show_admin_column'          => true,
  		'show_in_nav_menus'          => true,
  		'show_tagcloud'              => true,
  		'show_in_rest'               => true,
  	);
  	register_taxonomy( 'country_cat', array( 'product' ), $args );
  }
  add_action( 'init', 'country_cat_register_tax', 0 );
}

if ( ! function_exists( 'color_tag_register_tax' ) ) {

  // Register Custom Taxonomy
  function color_tag_register_tax() {

  	$labels = array(
  		'name'                       => _x( 'Colors', 'Taxonomy General Name', 'vantage-child' ),
  		'singular_name'              => _x( 'Colors', 'Taxonomy Singular Name', 'vantage-child' ),
  		'menu_name'                  => __( 'Colors', 'vantage-child' ),
  		'all_items'                  => __( 'All Colors', 'vantage-child' ),
  		'parent_item'                => __( 'Colors', 'vantage-child' ),
  		'parent_item_colon'          => __( 'Colors:', 'vantage-child' ),
  		'new_item_name'              => __( 'New Color', 'vantage-child' ),
  		'add_new_item'               => __( 'Add New Color', 'vantage-child' ),
  		'edit_item'                  => __( 'Edit Color', 'vantage-child' ),
  		'update_item'                => __( 'Update Color', 'vantage-child' ),
  		'view_item'                  => __( 'View Color', 'vantage-child' ),
  		'separate_items_with_commas' => __( 'Separate Color', 'vantage-child' ),
  		'add_or_remove_items'        => __( 'Add or remove items', 'vantage-child' ),
  		'choose_from_most_used'      => __( 'Choose from the most used', 'vantage-child' ),
  		'popular_items'              => __( 'Popular Colors', 'vantage-child' ),
  		'search_items'               => __( 'Search Colors', 'vantage-child' ),
  		'not_found'                  => __( 'Not Found', 'vantage-child' ),
  		'no_terms'                   => __( 'No categories', 'vantage-child' ),
  		'items_list'                 => __( 'Colors list', 'vantage-child' ),
  		'items_list_navigation'      => __( 'Colors list navigation', 'vantage-child' ),
  	);
  	$args = array(
  		'labels'                     => $labels,
  		'hierarchical'               => false,
  		'public'                     => true,
  		'show_ui'                    => true,
  		'show_admin_column'          => true,
  		'show_in_nav_menus'          => true,
  		'show_tagcloud'              => true,
  		'show_in_rest'               => true,
  	);
  	register_taxonomy( 'color_tag', array( 'product' ), $args );
  }
  add_action( 'init', 'color_tag_register_tax', 0 );
}


if ( ! function_exists( 'medium_cat_register_tax' ) ) {

// Register Custom Taxonomy
function medium_cat_register_tax() {

	$labels = array(
		'name'                       => _x( 'Art Media Category *', 'Taxonomy General Name', 'vantage-child' ),
		'singular_name'              => _x( 'Art Media Categories', 'Taxonomy Singular Name', 'vantage-child' ),
		'menu_name'                  => __( 'Art Media Categories', 'vantage-child' ),
		'all_items'                  => __( 'All Art Media Categories', 'vantage-child' ),
		'parent_item'                => __( 'Parent Art Media Category', 'vantage-child' ),
		'parent_item_colon'          => __( 'Parent Art Media Category:', 'vantage-child' ),
		'new_item_name'              => __( 'New Art Media Category', 'vantage-child' ),
		'add_new_item'               => __( 'Add New Art Media Category', 'vantage-child' ),
		'edit_item'                  => __( 'Edit Art Media Category', 'vantage-child' ),
		'update_item'                => __( 'Update Art Media Category', 'vantage-child' ),
		'view_item'                  => __( 'View Art Media Category', 'vantage-child' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'vantage-child' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'vantage-child' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'vantage-child' ),
		'popular_items'              => __( 'Popular Items', 'vantage-child' ),
		'search_items'               => __( 'Search Art Media Categories', 'vantage-child' ),
		'not_found'                  => __( 'Not Found', 'vantage-child' ),
		'no_terms'                   => __( 'No categories', 'vantage-child' ),
		'items_list'                 => __( 'Categories list', 'vantage-child' ),
		'items_list_navigation'      => __( 'Art Media categories list navigation', 'vantage-child' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'medium_cat', array( 'product' ), $args );

  }
  add_action( 'init', 'medium_cat_register_tax', 0 );
}

if ( ! function_exists( 'genre_tag_register_tax' ) ) {
  // Register Custom Taxonomy
  function genre_tag_register_tax() {

  	$labels = array(
  		'name'                       => _x( 'Genre Tag', 'Taxonomy General Name', 'vantage-child' ),
  		'singular_name'              => _x( 'Genre Tag', 'Taxonomy Singular Name', 'vantage-child' ),
  		'menu_name'                  => __( 'Genre Tags', 'vantage-child' ),
  		'all_items'                  => __( 'All Genre Tags', 'vantage-child' ),
  		'parent_item'                => __( 'Parent Genre Tag', 'vantage-child' ),
  		'parent_item_colon'          => __( 'Parent Genre Tag:', 'vantage-child' ),
  		'new_item_name'              => __( 'New Genre Tag', 'vantage-child' ),
  		'add_new_item'               => __( 'Add New Genre Tag', 'vantage-child' ),
  		'edit_item'                  => __( 'Edit Genre Tag', 'vantage-child' ),
  		'update_item'                => __( 'Update Genre Tag', 'vantage-child' ),
  		'view_item'                  => __( 'View Genre Tag', 'vantage-child' ),
  		'separate_items_with_commas' => __( 'Separate items with commas', 'vantage-child' ),
  		'add_or_remove_items'        => __( 'Add or remove items', 'vantage-child' ),
  		'choose_from_most_used'      => __( 'Choose from the most used', 'vantage-child' ),
  		'popular_items'              => __( 'Popular Items', 'vantage-child' ),
  		'search_items'               => __( 'Search Genre Tags', 'vantage-child' ),
  		'not_found'                  => __( 'Not Found', 'vantage-child' ),
  		'no_terms'                   => __( 'No Genre Tags', 'vantage-child' ),
  		'items_list'                 => __( 'Genre Tags list', 'vantage-child' ),
  		'items_list_navigation'      => __( 'Genre Tags list navigation', 'vantage-child' ),
  	);
  	$args = array(
  		'labels'                     => $labels,
  		'hierarchical'               => true,
  		'public'                     => true,
  		'show_ui'                    => true,
  		'show_admin_column'          => true,
  		'show_in_nav_menus'          => true,
  		'show_tagcloud'              => true,
  		'show_in_rest'               => true,
  	);
  	register_taxonomy( 'genre_tag', array( 'product' ), $args );
  }
  add_action( 'init', 'genre_tag_register_tax', 0 );
}

if ( ! function_exists( 'theme_tag_register_tax' ) ) {
  // Register Custom Taxonomy
  function theme_tag_register_tax() {

  	$labels = array(
  		'name'                       => _x( 'Theme Tag', 'Taxonomy General Name', 'vantage-child' ),
  		'singular_name'              => _x( 'Theme Tag', 'Taxonomy Singular Name', 'vantage-child' ),
  		'menu_name'                  => __( 'Theme Tags', 'vantage-child' ),
  		'all_items'                  => __( 'All Theme Tags', 'vantage-child' ),
  		'parent_item'                => __( 'Parent Theme Tag', 'vantage-child' ),
  		'parent_item_colon'          => __( 'Parent Theme Tag:', 'vantage-child' ),
  		'new_item_name'              => __( 'New Theme Tag', 'vantage-child' ),
  		'add_new_item'               => __( 'Add New Theme Tag', 'vantage-child' ),
  		'edit_item'                  => __( 'Edit Theme Tag', 'vantage-child' ),
  		'update_item'                => __( 'Update Theme Tag', 'vantage-child' ),
  		'view_item'                  => __( 'View Theme Tag', 'vantage-child' ),
  		'separate_items_with_commas' => __( 'Separate items with commas', 'vantage-child' ),
  		'add_or_remove_items'        => __( 'Add or remove items', 'vantage-child' ),
  		'choose_from_most_used'      => __( 'Choose from the most used', 'vantage-child' ),
  		'popular_items'              => __( 'Popular Themes', 'vantage-child' ),
  		'search_items'               => __( 'Search Theme Tags', 'vantage-child' ),
  		'not_found'                  => __( 'Not Found', 'vantage-child' ),
  		'no_terms'                   => __( 'No Theme Tags', 'vantage-child' ),
  		'items_list'                 => __( 'Theme Tags list', 'vantage-child' ),
  		'items_list_navigation'      => __( 'Theme Tags list navigation', 'vantage-child' ),
  	);
  	$args = array(
  		'labels'                     => $labels,
  		'hierarchical'               => false,
  		'public'                     => true,
  		'show_ui'                    => true,
  		'show_admin_column'          => true,
  		'show_in_nav_menus'          => true,
  		'show_tagcloud'              => true,
  		'show_in_rest'               => true,
  	);
  	register_taxonomy( 'theme_tag', array( 'product' ), $args );
  }
  add_action( 'init', 'theme_tag_register_tax', 0 );
}
