<?php
/**
 * WCFM plugin view
 *
 * WCFMvm Memberships Template
 *
 * @author 		WC Lovers
 * @package 	wcfmvm/templates
 * @version   1.0.0
 */

global $WCFM, $WCFMvm;

$member_id = get_current_user_id();

$free_thankyou_content = '';
$free_thankyou_content = get_option( 'wcfm_membership_free_thankyou_content', '' );
if( !$free_thankyou_content ) {
	$free_thankyou_content = "<strong>Welcome,</strong>
														<br /><br />
														You have successfully subscribed to our membership plan. 
														<br /><br />
														Your account already setup and ready to configure.
														<br /><br />
														Kindly follow the below the link to visit your dashboard.
														<br /><br />
														Thank You";
}

$subscription_thankyou_content = '';
$subscription_thankyou_content = get_option( 'wcfm_membership_subscription_thankyou_content', '' );
if( !$subscription_thankyou_content ) {
	$subscription_thankyou_content = "<strong>Welcome,</strong>
																		<br /><br />
																		You have successfully subscribed to our membership plan. 
																		<br /><br />
																		Your account still under processing.
																		<br /><br />
																		You will receive details in mail very soon!
																		<br /><br />
																		Thank You";
}
?>

<div id="wcfm_membership_container">
  <div class="wcfm_membership_thankyou_content_wrapper">
		<?php if( wcfm_is_vendor() ) { ?>
			<div class="wcfm_membership_thankyou_content">
				<?php echo $free_thankyou_content; ?>
			</div>
			<a class="wcfm_submit_button" href="<?php echo get_wcfm_url(); ?>"><?php _e( 'Goto Dashboard', 'wc-multivendor-membership' ); ?> >></a>
			<a class="wcfm_submit_button" href="<?php echo get_wcfm_settings_url(); ?>"><?php _e( 'Setup your store', 'wc-multivendor-membership' ); ?> >></a>
		<?php } else { ?>
			<div class="wcfm_membership_thankyou_content">
				<?php echo $subscription_thankyou_content; ?>
			</div>
		<?php } ?>
	</div>
</div>