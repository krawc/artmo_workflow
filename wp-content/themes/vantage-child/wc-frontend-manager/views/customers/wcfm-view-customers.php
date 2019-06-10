<?php
/**
 * WCFM plugin view
 *
 * WCFM Shop Customers View
 *
 * @author 		WC Lovers
 * @package 	wcfm/views/customers
 * @version   3.5.0
 */

global $WCFM;

$wcfm_is_allow_manage_customer = apply_filters( 'wcfm_is_allow_manage_customer', true );
if( !$wcfm_is_allow_manage_customer ) {
	wcfm_restriction_message_show( "Customers" );
	return;
}

?>

<div class="collapse wcfm-collapse" id="wcfm_shop_listing">
  <div class="wcfm-page-headig">
		<span class="ion-ios-people-outline"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Customers', 'wc-frontend-manager' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>


	  <div class="wcfm-clearfix"></div><br />

		<?php do_action( 'before_wcfm_customers' ); ?>

		<div class="wcfm-container">
			<div id="wwcfm_customers_expander" class="wcfm-content">
				<table id="wcfm-shop-customers" class="display" cellspacing="0" width="100%">

				</table>
				<div class="wcfm-clearfix"></div>
			</div>
		</div>
		<?php
		do_action( 'after_wcfm_customers' );
		?>
	</div>
</div>
