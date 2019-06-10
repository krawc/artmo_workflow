<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'so_vantage_prevent_legacy' );

function so_vantage_prevent_legacy(){
	remove_action( 'save_post', 'vantage_panels_save_post', 5 );
}

add_action( 'init', 'vantage_remove_legacy_row_theme' );

function vantage_remove_legacy_row_theme() {
  remove_filter( 'siteorigin_panels_row_style_fields', 'vantage_panels_row_style_fields', 11 );
  remove_action( 'save_post', 'vantage_panels_save_post', 5, 2 );
}

add_action('vantage_before_footer', 'loader_overlay');

function loader_overlay() {?>
  <div class="overlay" id="processing">
    <div class="overlay-modal">
      <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
      <span class="sr-only">Loading...</span>
      <p class="processing">Processing...</p>
    </div>
  </div>
<?php }
