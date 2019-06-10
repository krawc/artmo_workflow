<?php
global $WCFM, $wpdb;

$order_count = 0;
$on_hold_count    = 0;
$processing_count = 0;

foreach ( wc_get_order_types( 'order-count' ) as $type ) {
	$counts           = (array) wp_count_posts( $type );
	$on_hold_count    += isset( $counts['wc-on-hold'] ) ? $counts['wc-on-hold'] : 0;
	$processing_count += isset( $counts['wc-processing'] ) ? $counts['wc-processing'] : 0;

	$order_count    += isset( $counts['wc-on-hold'] ) ? $counts['wc-on-hold'] : 0;
	$order_count    += isset( $counts['wc-processing'] ) ? $counts['wc-processing'] : 0;
	$order_count    += isset( $counts['wc-completed'] ) ? $counts['wc-completed'] : 0;
	$order_count    += isset( $counts['wc-pending'] ) ? $counts['wc-pending'] : 0;
}


// Get products using a query - this is too advanced for get_posts :(
$stock          = absint( max( get_option( 'woocommerce_notify_low_stock_amount' ), 1 ) );
$nostock        = absint( max( get_option( 'woocommerce_notify_no_stock_amount' ), 0 ) );
$transient_name = 'wc_low_stock_count';

if ( false === ( $lowinstock_count = get_transient( $transient_name ) ) ) {
	$query_from = apply_filters( 'woocommerce_report_low_in_stock_query_from', "FROM {$wpdb->posts} as posts
		INNER JOIN {$wpdb->postmeta} AS postmeta ON posts.ID = postmeta.post_id
		INNER JOIN {$wpdb->postmeta} AS postmeta2 ON posts.ID = postmeta2.post_id
		WHERE 1=1
		AND posts.post_type IN ( 'product', 'product_variation' )
		AND posts.post_status = 'publish'
		AND postmeta2.meta_key = '_manage_stock' AND postmeta2.meta_value = 'yes'
		AND postmeta.meta_key = '_stock' AND CAST(postmeta.meta_value AS SIGNED) <= '{$stock}'
		AND postmeta.meta_key = '_stock' AND CAST(postmeta.meta_value AS SIGNED) > '{$nostock}'
	" );
	$lowinstock_count = absint( $wpdb->get_var( "SELECT COUNT( DISTINCT posts.ID ) {$query_from};" ) );
	set_transient( $transient_name, $lowinstock_count, DAY_IN_SECONDS * 30 );
}

$transient_name = 'wc_outofstock_count';

if ( false === ( $outofstock_count = get_transient( $transient_name ) ) ) {
	$query_from = apply_filters( 'woocommerce_report_out_of_stock_query_from', "FROM {$wpdb->posts} as posts
		INNER JOIN {$wpdb->postmeta} AS postmeta ON posts.ID = postmeta.post_id
		INNER JOIN {$wpdb->postmeta} AS postmeta2 ON posts.ID = postmeta2.post_id
		WHERE 1=1
		AND posts.post_type IN ( 'product', 'product_variation' )
		AND posts.post_status = 'publish'
		AND postmeta2.meta_key = '_manage_stock' AND postmeta2.meta_value = 'yes'
		AND postmeta.meta_key = '_stock' AND CAST(postmeta.meta_value AS SIGNED) <= '{$nostock}'
	" );
	$outofstock_count = absint( $wpdb->get_var( "SELECT COUNT( DISTINCT posts.ID ) {$query_from};" ) );
	set_transient( $transient_name, $outofstock_count, DAY_IN_SECONDS * 30 );
}

include_once( $WCFM->plugin_path . 'includes/reports/class-wcfm-report-sales-by-date.php' );

// For net sales block value
$wcfm_report_sales_by_date_block = new WCFM_Report_Sales_By_Date( '31day' );
$wcfm_report_sales_by_date_block->calculate_current_range( '31day' );
$report_data_block   = $wcfm_report_sales_by_date_block->get_report_data();

// For sales by date graph
$wcfm_report_sales_by_date = new WCFM_Report_Sales_By_Date( 'month' );
$wcfm_report_sales_by_date->calculate_current_range( 'month' );
$report_data   = $wcfm_report_sales_by_date->get_report_data();

// WCFM Analytics
include_once( $WCFM->plugin_path . 'includes/reports/class-wcfm-report-analytics.php' );
$wcfm_report_analytics = new WCFM_Report_Analytics();
$wcfm_report_analytics->chart_colors = apply_filters( 'wcfm_report_analytics_chart_colors', array(
			'view_count'       => '#C79810',
		) );
$wcfm_report_analytics->calculate_current_range( '7day' );

$user_id = get_current_user_id();
$wp_user_avatar_id = get_user_meta( $user_id, 'wp_user_avatar', true );
$wp_user_avatar = wp_get_attachment_url( $wp_user_avatar_id );
if ( !$wp_user_avatar ) {
	$wp_user_avatar = $WCFM->plugin_url . 'assets/images/user.png';
}

$is_marketplace = wcfm_is_marketplace();

do_action( 'before_wcfm_dashboard' );
?>

<div class="collapse wcfm-collapse" id="wcfm_dashboard">

  <div class="wcfm-page-headig">
		<span class="fa fa-dashboard"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Dashboard', 'wc-frontend-manager' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
		<div id="wcfm_page_load"></div>

		<?php do_action( 'begin_wcfm_dashboard' ); ?>

		<?php require_once( $WCFM->library->views_path . 'dashboard/wcfm-view-dashboard-welcome-box.php' ); ?>

		<?php if( apply_filters( 'wcfm_is_pref_stats_box', true ) ) { ?>
			<div class="wcfm_dashboard_stats">
				<?php if ( apply_filters( 'wcfm_is_allow_reports', true ) && current_user_can( 'view_woocommerce_reports' ) && ( $report_data_block ) ) { ?>
					<div class="wcfm_dashboard_stats_block">
						<a href="<?php echo get_wcfm_reports_url( 'month' ); ?>">
							<span class="fa fa-currency"><?php echo get_woocommerce_currency_symbol() ; ?></span>
							<div>
								<strong><?php echo wc_price( $report_data_block->total_sales ); ?></strong><br />
								<?php _e( 'gross sales in last 31 days', 'wc-frontend-manager' ); ?>
							</div>
						</a>
					</div>
				<?php } ?>

				<?php
				if( $is_marketplace ) {
					$commission = $WCFM->wcfm_vendor_support->wcfm_get_commission_by_vendor();
					//$total_sell = $WCFM->wcfm_vendor_support->wcfm_get_total_sell_by_vendor();

					$admin_fee_mode = false;
					if( $is_marketplace == 'wcmarketplace' ) {
						global $WCMp;
						if (isset($WCMp->vendor_caps->payment_cap['revenue_sharing_mode'])) {
							if ($WCMp->vendor_caps->payment_cap['revenue_sharing_mode'] == 'admin') {
								$admin_fee_mode = true;
								$grose_sell = $WCFM->wcfm_vendor_support->wcfm_get_gross_sales_by_vendor();
								$commission = $grose_sell - $commission;
							}
						}
					} elseif( $is_marketplace == 'dokan' ) {
						$grose_sell = $WCFM->wcfm_vendor_support->wcfm_get_gross_sales_by_vendor();
						$commission = $grose_sell - $commission;
					}
				?>
					<div class="wcfm_dashboard_stats_block">
						<a href="<?php echo get_wcfm_reports_url( ); ?>">
							<span class="fa fa-money"></span>
							<div>
								<strong><?php echo wc_price( $commission ); ?></strong><br />
								<?php if( $admin_fee_mode ) { _e( 'admin fees in last 31 days', 'wc-frontend-manager' ); } else { _e( 'commission in last 31 days', 'wc-frontend-manager' ); } ?>
							</div>
						</a>
					</div>
					<div class="wcfm_dashboard_stats_block">
						<a href="<?php echo apply_filters( 'sales_by_product_report_url', get_wcfm_reports_url( ), '' ); ?>">
							<span class="fa fa-cubes"></span>
							<div>
								<?php printf( _n( "<strong>%s item</strong><br />", "<strong>%s items</strong><br />", $report_data_block->total_items, 'wc-frontend-manager' ), $report_data_block->total_items ); ?>
								<?php _e( 'sold in last 31 days', 'wc-frontend-manager' ); ?>
							</div>
						</a>
					</div>
				<?php
				}
				?>
				<?php if ( apply_filters( 'wcfm_is_allow_orders', true ) && current_user_can( 'edit_shop_orders' ) ) { ?>
					<div class="wcfm_dashboard_stats_block">
						<a href="<?php echo get_wcfm_orders_url( ); ?>">
							<span class="fa fa-cart-plus"></span>
							<div>
								<?php printf( _n( "<strong>%s order</strong><br />", "<strong>%s orders</strong><br />", $report_data_block->total_orders, 'wc-frontend-manager' ), $report_data_block->total_orders ); ?>
								<?php _e( 'received in last 31 days', 'wc-frontend-manager' ); ?>
							</div>
						</a>
					</div>
				<?php } ?>
			</div>
			<div class="wcfm-clearfix"></div>
		<?php } ?>
		<?php do_action( 'wcfm_after_dashboard_stats_box' ); ?>

	</div>
</div>
