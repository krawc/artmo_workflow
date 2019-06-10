<?php
/**
 * WCFMu plugin view
 *
 * WCFM Followers view
 *
 * @author 		WC Lovers
 * @package 	wcfm/views/followers
 * @version   4.0.6
 */
 
global $WCFM;


if( !apply_filters( 'wcfm_is_pref_vendor_followers', true ) || !apply_filters( 'wcfm_is_allow_followers', true ) || !wcfm_is_vendor() ) {
	wcfm_restriction_message_show( "Followers" );
	return;
}

?>

<div class="collapse wcfm-collapse" id="wcfm_followers_listing">
	
	<div class="wcfm-page-headig">
		<span class="fa fa-child"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Followers', 'wc-frontend-manager-ultimate' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
		<div id="wcfm_page_load"></div>
		
		<div class="wcfm-container wcfm-top-element-container">
			<h2><?php _e( 'Followers List', 'wc-frontend-manager-ultimate' ); ?></h2>
			<div class="wcfm-clearfix"></div>
		</div>
	  <div class="wcfm-clearfix"></div><br />
	  
	  <?php do_action( 'before_wcfm_followers' ); ?>
	  
		<div class="wcfm-container">
			<div id="wcfm_followers_listing_expander" class="wcfm-content">
				<table id="wcfm-followers" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
						  <th><?php _e( 'Name', 'wc-frontend-manager-ultimate' ); ?></th>
						  <th><?php _e( 'Email', 'wc-frontend-manager-ultimate' ); ?></th>
							<th><?php _e( 'Actions', 'wc-frontend-manager-ultimate' ); ?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?php _e( 'Name', 'wc-frontend-manager-ultimate' ); ?></th>
						  <th><?php _e( 'Email', 'wc-frontend-manager-ultimate' ); ?></th>
							<th><?php _e( 'Actions', 'wc-frontend-manager-ultimate' ); ?></th>
						</tr>
					</tfoot>
				</table>
				<div class="wcfm-clearfix"></div>
			</div>
		</div>
			
		<?php do_action( 'after_wcfm_followers' ); ?>
	</div>
</div>