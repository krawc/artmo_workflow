<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


add_shortcode('artmo_home_page_static', 'artmo_home_page_static');

function artmo_home_page_static() {

  $a = shortcode_atts( array(
    'product_slider_id' => '67106',
  ), $atts );

  $response = '';

  $main_buttons = array(
    "home" => array(
      "endpoint" => '/wall',
      "image" => 'https://artmo.com/wp-content/uploads/2019/03/1.png',
    ),
    "artists" => array(
      "endpoint" => '/artists',
      "image" => 'https://artmo.com/wp-content/uploads/2019/03/2.jpg',
    ),
    "galleries" => array(
      "endpoint" => '/galleries',
      "image" => 'https://artmo.com/wp-content/uploads/2019/03/3.jpg',
    ),
    "exhibitions" => array(
      "endpoint" => '/exhibitions',
      "image" => 'https://artmo.com/wp-content/uploads/2019/03/4.jpg',
    ),
    "members" => array(
      "endpoint" => '/members',
      "image" => 'https://artmo.com/wp-content/uploads/2019/03/5.jpg',
    ),
    "collections" => array(
      "endpoint" => '/collections',
      "image" => 'https://artmo.com/wp-content/uploads/2019/03/6.jpg',
    ),
    "genres"  => array(
      "endpoint" => '/genres',
      "image" => 'https://artmo.com/wp-content/uploads/2019/03/7.jpg',
    ),
    "universities" => array(
      "endpoint" => '/universities',
      "image" => 'https://artmo.com/wp-content/uploads/2019/03/8.jpg',
    )
  );

  $categories = array('videos', 'buzz', 'exhibitions', 'potd');

  $response .= '<div class="homepage">';

  $response .= artmo_profiles_carousel();

  if (isMobile()) {
    $response .= dynamic_sidebar( 'artmo_main_page_widget' );
  }

  $response .= '<section class="main-buttons">';

  foreach ($main_buttons as $key => $values) {
    $response .= '<div class="main-buttons-single">';
    $response .= '<a href="' . get_home_url() . $values['endpoint'] . '">';
    $response .= '<div class="main-buttons-single-image"><img src="' . $values['image'] . '"/></div>';
    $response .= '<p class="main-buttons-single-text">';
    $response .= $key;
    $response .= '</p>';
    $response .= '</a>';
    $response .= '</div>';
  }

  $response .= '</section>';

  $response .= '<section class="products-slider">';
  $response .= do_shortcode('[wpcsp id="' . $a['product_slider_id'] . '"]');
  $response .= '</section>';

  foreach ($categories as $category) {

    $posts_per_page = 3;
    if (($category === 'potd') || ($category === 'exhibitions')) {
      $posts_per_page = 4;
    }

    $category_obj = get_category_by_slug($category);

    $args = array(
      'post_type'              => array( 'post' ),
      'post_status'            => array( 'publish' ),
      'posts_per_page'         => $posts_per_page,
      'orderby'                => 'rand',
      'tax_query' => array(
        array(
          'taxonomy' => 'category',
          'field'    => 'slug',
          'terms'    => $category
        ),
      ),
    );

    if ($category === 'exhibitions') {
      $args['tag'] = 'exhibitions';
    }

    if ($category === 'potd') {
      $args['orderby'] = 'rand';
    }

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {

      $response .= '<section class="posts-gallery posts-gallery-' . $category . '">';

      $response .= '<h2 class="posts-gallery-header">' . $category_obj->name . '</h2>';

      $response .= '<div class="posts-gallery-container">';

      while ( $query->have_posts() ) {
        $query->the_post();
        $post = get_post();
        $response .= '<div class="posts-gallery-single">';
        $response .= '<a href="' . get_permalink() . '">';
        $response .= '<div class="posts-gallery-single-image"><img width="272" height="182" src="' . get_the_post_thumbnail_url( $post->ID, array(272,182) ) . '"/></div>';
        $response .= '<p>' . apply_filters( 'the_title', $post->post_title, $post->ID ) . '</p>';
        $response .= '</a>';
        $response .= '</div>';
      }

      $response .= '</div>';
      //THIS MEANS THE PAGE NEEDS TO HAVE AN EXACT SAME SLUG AS ITS CATEGORY
      $response .= '<div class="posts-gallery-seemore"><a href="' . get_home_url() . "/" . $category . '">SEE MORE</a></div>';
      $response .= '</section>';

    } else {
      // no posts found
    }
    wp_reset_postdata();

  }

  $response .= '</div>';//END OF HOMEPAGE

  return $response;
}

add_action( 'widgets_init', 'artmo_main_page_widget_area_init' );

function artmo_main_page_widget_area_init() {

	register_sidebar( array(
		'name'          => 'Main Page Widget',
		'id'            => 'artmo_main_page_widget',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="rounded">',
		'after_title'   => '</h2>',
	) );

}
