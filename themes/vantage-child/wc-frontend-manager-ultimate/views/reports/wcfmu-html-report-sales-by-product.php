<?php
/**
 * Admin View: Report by Date (with date filters)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wp, $WCFM, $wpdb;

$current_range = ! empty( $_GET['range'] ) ? sanitize_text_field( $_GET['range'] ) : '7day';
?>

<div id="poststuff" class="woocommerce-reports-wide">
	<div class="postbox">

		<div class="stats_range">
		  <?php $wcfm_report_sales_by_product->get_export_button(); ?>
			<ul>
				<?php
					foreach ( $ranges as $range => $name ) {
						echo '<li class="' . ( $current_range == $range ? 'active' : '' ) . '"><a href="' . esc_url( remove_query_arg( array( 'start_date', 'end_date' ), add_query_arg( 'range', $range ) ) ) . '">' . $name . '</a></li>';
					}
				?>
				<li class="custom <?php echo $current_range == 'custom' ? 'active' : ''; ?>">
					<?php _e( 'Custom:', 'wc-frontend-manager-ultimate' ); ?>
					<form method="GET">
						<div>
							<?php
								// Maintain query string
								foreach ( $_GET as $key => $value ) {
									if ( is_array( $value ) ) {
										foreach ( $value as $v ) {
											echo '<input type="hidden" name="' . esc_attr( sanitize_text_field( $key ) ) . '[]" value="' . esc_attr( sanitize_text_field( $v ) ) . '" />';
										}
									} else {
										echo '<input type="hidden" name="' . esc_attr( sanitize_text_field( $key ) ) . '" value="' . esc_attr( sanitize_text_field( $value ) ) . '" />';
									}
								}
							?>
							<input type="hidden" name="range" value="custom" />
							<input type="text" size="9" placeholder="<?php echo apply_filters( 'wcfm_date_filter_format', 'yyyy-mm-dd' ); ?>" data-date_format="<?php echo apply_filters( 'wcfm_date_filter_format_js', 'yy-mm-dd' ); ?>" value="<?php if ( ! empty( $_GET['start_date'] ) ) echo esc_attr( $_GET['start_date'] ); ?>" name="start_date" class="range_datepicker from" />
							<input type="text" size="9" placeholder="<?php echo apply_filters( 'wcfm_date_filter_format', 'yyyy-mm-dd' ); ?>" data-date_format="<?php echo apply_filters( 'wcfm_date_filter_format_js', 'yy-mm-dd' ); ?>" value="<?php if ( ! empty( $_GET['end_date'] ) ) echo esc_attr( $_GET['end_date'] ); ?>" name="end_date" class="range_datepicker to" />
							<input type="submit" class="button wcfm_add_attribute" value="<?php esc_attr_e( 'Go', 'wc-frontend-manager-ultimate' ); ?>" />
						</div>
					</form>
				</li>
			</ul>
		</div>
		<?php if ( empty( $hide_sidebar ) ) : ?>
			<div class="inside chart-with-sidebar">
				<div class="chart-sidebar">
					<?php if ( $legends = $wcfm_report_sales_by_product->get_chart_legend() ) : ?>
						<ul class="chart-legend">
							<?php foreach ( $legends as $legend ) : ?>
								<li style="border-color: <?php echo $legend['color']; ?>" <?php if ( isset( $legend['highlight_series'] ) ) echo 'class="highlight_series ' . ( isset( $legend['placeholder'] ) ? 'tips' : '' ) . '" data-series="' . esc_attr( $legend['highlight_series'] ) . '"'; ?> data-tip="<?php echo isset( $legend['placeholder'] ) ? $legend['placeholder'] : ''; ?>">
									<?php echo $legend['title']; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<ul class="chart-widgets">
						<?php foreach ( $wcfm_report_sales_by_product->get_chart_widgets() as $widget ) : ?>
							<li class="chart-widget">
								<?php if ( $widget['title'] ) : ?><h4><?php echo $widget['title']; ?></h4><?php endif; ?>
								<?php call_user_func( $widget['callback'] ); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="main">
					<?php $wcfm_report_sales_by_product->get_main_chart(); ?>
				</div>
			</div>
		<?php else : ?>
			<div class="inside">
				<?php $wcfm_report_sales_by_product->get_main_chart(); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
