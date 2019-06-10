<?php
global $WCFM, $wp_query;
$wcfm_is_allow_manage_products = apply_filters( 'wcfm_is_allow_manage_products', true );
if( !current_user_can( 'edit_products' ) || !$wcfm_is_allow_manage_products ) {
	wcfm_restriction_message_show( "Products" );
	return;
}
$wcfmu_products_menus = apply_filters( 'wcfmu_products_menus', array( 'any' => __( 'All', 'wc-frontend-manager'),
																																			'publish' => __( 'Published', 'wc-frontend-manager'),
																																			'draft' => __( 'Draft', 'wc-frontend-manager'),
																																			'pending' => __( 'Pending', 'wc-frontend-manager')
																																		) );
$product_status = ! empty( $_GET['product_status'] ) ? sanitize_text_field( $_GET['product_status'] ) : 'any';
$current_user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
if( current_user_can( 'administrator' ) ) $current_user_id = 0;
$count_products = array();
$count_products['publish'] = wcfm_get_user_posts_count( $current_user_id, 'product', 'publish' );
$count_products['pending'] = wcfm_get_user_posts_count( $current_user_id, 'product', 'pending' );
$count_products['draft']   = wcfm_get_user_posts_count( $current_user_id, 'product', 'draft' );
$count_products['any']     = $count_products['publish'] + $count_products['pending'] + $count_products['draft'];
?>


<div class="wcfm_products_filter_wrap wcfm_filters_wrap">
	<?php
	// Buk Edit Button action
	if( $wcfm_is_products_vendor_filter = apply_filters( 'wcfm_is_products_vendor_filter', true ) ) {
		$is_marketplace = wcfm_is_marketplace();
		if( $is_marketplace ) {
			if( !wcfm_is_vendor() ) {
				$vendor_arr = $WCFM->wcfm_vendor_support->wcfm_get_vendor_list();
				$WCFM->wcfm_fields->wcfm_generate_form_field( array(
																									"dropdown_vendor" => array( 'type' => 'select', 'options' => $vendor_arr, 'attributes' => array( 'style' => 'width: 150px;' ) )
																									 ) );
			}
		}
	}
	?>
</div>

<div class="collapse wcfm-collapse" id="wcfm_products_listing">

	<div class="wcfm-page-headig">
		<span class="wcfm-page-heading-text"><?php _e( 'Artworks', 'wc-frontend-manager' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
		<div class="add-new-btn">
			<a href="<?php echo get_wcfm_page(); ?>wcfm-products-manage">
				<i class="ion ion-ios-plus-outline"></i><?php _e('ADD NEW'); ?>
			</a>
		</div>
	</div>
	<div class="wcfm-collapse-content">
		<div id="wcfm_page_load"></div>
		<?php do_action( 'before_wcfm_products' ); ?>

		<div class="wcfm-container">
			<div id="wcfm_products_listing_expander" class="wcfm-content">
				<table id="wcfm-products" class="display" cellspacing="0" width="100%">
				</table>
				<div class="wcfm-clearfix"></div>
			</div>
		</div>
		<?php
		do_action( 'after_wcfm_products' );
		?>
	</div>
</div>
