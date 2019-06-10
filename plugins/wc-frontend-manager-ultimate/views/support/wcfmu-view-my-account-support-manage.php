<?php
/**
 * WCFM plugin view
 *
 * wcfm Support Manage View
 *
 * @author 		WC Lovers
 * @package 	wcfmu/views/support
 * @version   4.0.3
 */
 
global $wp, $WCFM, $WCFMu, $wpdb;

if( !apply_filters( 'wcfm_is_pref_support', true ) || !apply_filters( 'wcfm_is_allow_support', true ) || !apply_filters( 'wcfm_is_allow_manage_support', true ) ) {
	wcfm_restriction_message_show( "Manage Support" );
	return;
}

$support_id = 0;
$support_ticket_title = '';
$support_ticket_content = '';
$allow_reply = 'no';
$close_new_reply = 'no';

$wcfm_myac_modified_endpoints = get_option( 'wcfm_myac_endpoints', array() );
$wcfm_myaccount_support_ticket_endpoint = ! empty( $wcfm_myac_modified_endpoints['support-tickets'] ) ? $wcfm_myac_modified_endpoints['support-tickets'] : 'support-tickets';
$wcfm_myaccount_view_support_ticket_endpoint = ! empty( $wcfm_myac_modified_endpoints['view-support-ticket'] ) ? $wcfm_myac_modified_endpoints['view-support-ticket'] : 'view-support-ticket';

if( isset( $wp->query_vars[$wcfm_myaccount_view_support_ticket_endpoint] ) && !empty( $wp->query_vars[$wcfm_myaccount_view_support_ticket_endpoint] ) ) {
	$support_id = absint( $wp->query_vars[$wcfm_myaccount_view_support_ticket_endpoint] );
	$support_post = $wpdb->get_row( "SELECT * from {$wpdb->prefix}wcfm_support WHERE `ID` = " . $support_id );
	// Fetching Support Data
	if($support_post && !empty($support_post)) {
		$support_ticket_content = $support_post->query;
		$support_order_id = $support_post->order_id;
		$support_item_id = $support_post->item_id;
		$support_product_id = $support_post->product_id;
		$support_vendor_id = $support_post->vendor_id;
		$support_customer_id = $support_post->customer_id;
		$customer_id = get_current_user_id();
		if( $support_customer_id != $customer_id ) {
			wcfm_restriction_message_show( "Ticket Not Found" );
			return;
		}
	} else {
		wcfm_restriction_message_show( "Ticket Not Found" );
		return;
	}
}
$support_categories     = $WCFMu->wcfmu_support->wcfm_support_categories();
$support_priority_types = $WCFMu->wcfmu_support->wcfm_support_priority_types();
$support_status_types   = $WCFMu->wcfmu_support->wcfm_support_status_types();

$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
if ( $myaccount_page_id ) {
	$myaccount_page_url = get_permalink( $myaccount_page_id );
}

do_action( 'before_my_account_wcfm_support_manage' );

?>

<div id="wcfm-main-contentainer">
	<div class="collapse wcfm-collapse">
		<div class="wcfm-collapse-content">
			<div class="wcfm-container wcfm-top-element-container">
				<h2><?php echo __( 'Ticket', 'wc-frontend-manager-ultimate' ) . ' #' . sprintf( '%06u', $support_id ); ?></h2>
				<span class="support-priority support-priority-<?php echo $support_post->priority;?>"><?php echo $support_priority_types[$support_post->priority]; ?></span>
				
				<?php
				echo '<a id="add_new_support_dashboard" class="add_new_wcfm_ele_dashboard text_tip" href="' . $myaccount_page_url . $wcfm_myaccount_support_ticket_endpoint . '/" data-tip="' . __('Support Tickets', 'wc-frontend-manager-ultimate') . '"><span class="fa fa-life-ring"></span><span class="text">' . __( 'Tickets', 'wc-frontend-manager-ultimate') . '</span></a>';
				?>
				<div class="wcfm-clearfix"></div>
			</div>
			<div class="wcfm-clearfix"></div><br />
			
			<?php do_action( 'begin_my_account_wcfm_support_manage_form' ); ?>
			
			<!-- collapsible -->
			<div class="wcfm-container">
				<div id="support_manage_general_expander" class="wcfm-content">
					<div class="support_ticket_content">
						<div class="support_ticket_content_order">
							<span class="support_ticket_content_order_title"><?php _e( 'Order', 'wc-frontend-manager-ultimate' ); ?></span>:&nbsp;#
							<?php
								echo $support_order_id;
							?>
						</div>
						<div class="support_ticket_content_category"><span class="support_ticket_content_category_title"><?php _e( 'Category', 'wc-frontend-manager-ultimate' ); ?></span>:&nbsp;<?php echo $support_post->category; ?></div>
						<?php echo $support_ticket_content; ?>
					</div>
					<div class="support_ticket_info">
						<div class="support_ticket_status">
							<?php
							if( $support_post->status == 'open' ) {
								echo '<span class="support-status tips wcicon-status-processing text_tip" data-tip="' . __( 'Open', 'wc-frontend-manager-ultimate' ) . '"></span>&nbsp;' . __( 'Open', 'wc-frontend-manager-ultimate' );
							} else {
								echo '<span class="support-status tips wcicon-status-completed text_tip" data-tip="' . __( 'Closed', 'wc-frontend-manager-ultimate' ) . '"></span>&nbsp;' . __( 'Closed', 'wc-frontend-manager-ultimate' );
							}
							?>
						</div>
						<div class="support_ticket_date"><span class="fa fa-clock-o"></span>&nbsp;<?php echo date_i18n( wc_date_format(), strtotime( $support_post->posted ) ); ?></div>
					</div>
					<div class="wcfm_clearfix"></div>
				</div>
			</div>
			<div class="wcfm_clearfix"></div><br />
			<!-- end collapsible -->
			
			<?php 
			if( $wcfm_is_allow_view_support_reply_view = apply_filters( 'wcfmcap_is_allow_support_reply_view', true ) ) {
				$wcfm_support_replies = $wpdb->get_results( "SELECT * from {$wpdb->prefix}wcfm_support_response WHERE `support_id` = " . $support_id );
				
				echo '<h2>' . __( 'Replies', 'wc-frontend-manager-ultimate' ) . ' (' . count( $wcfm_support_replies ) . ')</h2><div class="wcfm_clearfix"></div>';
				
				if( !empty( $wcfm_support_replies ) ) {
					foreach( $wcfm_support_replies as $wcfm_support_reply ) {
					?>
					<!-- collapsible -->
					<div class="wcfm-container">
						<div id="support_ticket_reply_<?php echo $wcfm_support_reply->ID; ?>" class="support_ticket_reply wcfm-content">
							<div class="support_ticket_reply_author">
								<?php
								$author_id = $wcfm_support_reply->reply_by;
								$wp_user_avatar_id = get_user_meta( $author_id, 'wp_user_avatar', true );
								$wp_user_avatar = wp_get_attachment_url( $wp_user_avatar_id );
								if ( !$wp_user_avatar ) {
									$wp_user_avatar = $WCFM->plugin_url . 'assets/images/user.png';
								}
								?>
								<img src="<?php echo $wp_user_avatar; ?>" /><br />
								<?php
								$userdata = get_userdata( $author_id );
								$first_name = $userdata->first_name;
								$last_name  = $userdata->last_name;
								$display_name  = $userdata->display_name;
								if( $first_name ) {
									echo $first_name . ' ' . $last_name;
								} else {
									echo $display_name;
								}
								?>
								<br /><?php echo date_i18n( wc_date_format(), strtotime( $wcfm_support_reply->posted ) ); ?>
							</div>
							<div class="support_ticket_reply_content">
								<?php echo $wcfm_support_reply->reply; ?>
							</div>
						</div>
					</div>
					<div class="wcfm_clearfix"></div><br />
					<!-- end collapsible -->
					<?php
					}
				}
			} 
			?>
			
			<?php if( $wcfm_is_allow_view_support_reply = apply_filters( 'wcfmcap_is_allow_support_reply', true ) ) { ?>
				<?php do_action( 'before_wcfm_support_reply_form' ); ?>
				<form id="wcfm_support_ticket_reply_form" class="wcfm">
					<h2><?php _e('New Reply', 'wc-frontend-manager-ultimate' ); ?></h2>
					<div class="wcfm-clearfix"></div>
					<div class="wcfm-container">
						<div id="wcfm_new_reply_listing_expander" class="wcfm-content">
							<?php
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_support_ticket_reply_fields', array(
																																																			"support_ticket_reply" => array( 'type' => 'wpeditor', 'class' => 'wcfm-textarea wcfm_ele wcfm_wpeditor', 'label_class' => 'wcfm_title', 'media_buttons' => false ),
																																																			"support_reply_beak"   => array( 'type' => 'html', 'value' => '<div class="wcfm-clearfix" style="margin-bottom: 15px;"></div>' ),
																																																			"support_priority"     => array( 'label' => __( 'Priority', 'wc-frontend-manager-ultimate' ), 'type' => 'select', 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title', 'options' => $support_priority_types, 'value' => $support_post->priority ),
																																																			"support_status"       => array( 'label' => __( 'Status', 'wc-frontend-manager-ultimate' ), 'type' => 'select', 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title', 'options' => $support_status_types, 'value' => $support_post->status ),
																																																			"support_ticket_id"    => array( 'type' => 'hidden', 'value' => $support_id ),
																																																			"support_order_id"     => array( 'type' => 'hidden', 'value' => $support_order_id ),
																																																			"support_item_id"      => array( 'type' => 'hidden', 'value' => $support_item_id ),
																																																			"support_product_id"   => array( 'type' => 'hidden', 'value' => $support_product_id ),
																																																			"support_vendor_id"    => array( 'type' => 'hidden', 'value' => $support_vendor_id ),
																																																			"support_customer_id"  => array( 'type' => 'hidden', 'value' => $support_customer_id )
																																																			) ) );
							?>
							<div class="wcfm-clearfix"></div>
							<div class="wcfm-message" tabindex="-1"></div>
							<div class="wcfm-clearfix"></div>
							<div id="wcfm_support_reply_submit">
								<input type="submit" name="save-data" value="<?php _e( 'Send', 'wc-frontend-manager-ultimate' ); ?>" id="wcfm_reply_send_button" class="wcfm_submit_button" />
							</div>
							<div class="wcfm-clearfix"></div>
						</div>
					</div>
				</form>
				<?php do_action( 'after_wcfm_support_reply_form' ); ?>
				<div class="wcfm-clearfix"></div><br />
			<?php } ?>
			
			<?php do_action( 'end_my_account_wcfm_support_manage_form' ); ?>
			
			<?php
			do_action( 'after_my_account_wcfm_support_manage' );
			?>
		</div>
	</div>
</div>