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
	$free_thankyou_content = "Your account is ready.
														<br /><br />
														Click the button on the left
														<br /><br />
														to set up your dashboard and publish your collection.
														<br /><br />
														Thank You!";
}

$subscription_thankyou_content = '';
$subscription_thankyou_content = get_option( 'wcfm_membership_subscription_thankyou_content', '' );
if( !$subscription_thankyou_content ) {
	$subscription_thankyou_content = "Your account is ready.
																		<br /><br />
																		Click the button on the left
																		<br /><br />
																		to set up your dashboard and publish your collection.
																		<br /><br />
																		Thank You!";
}
?>

<div id="wcfm_membership_container">
  <div class="wcfm_membership_thankyou_content_wrapper">
		<?php if( wcfm_is_vendor() ) { ?>
			<i class="ion ion-checkmark-circled"></i>
			<h2 class="wcfm-membership-done"><?php _e('All done!', 'artmo'); ?></h2>
			<div class="wcfm_membership_thankyou_content">
				<?php echo $free_thankyou_content; ?>
			</div>
		<?php } else { ?>
			<i class="ion ion-checkmark-circled"></i>
			<h2 class="wcfm-membership-done"><?php _e('All done!', 'artmo'); ?></h2>
			<div class="wcfm_membership_thankyou_content">
				<?php echo $subscription_thankyou_content; ?>
			</div>
		<?php } ?>
	</div>
	<div class="wcfm_membership_thankyou_next">
		<a class="wcfm_submit_button" href="<?php echo get_wcfm_url(); ?>"><?php _e( 'Setup your dashboard', 'wc-multivendor-membership' ); ?></a>
	</div>
</div>
