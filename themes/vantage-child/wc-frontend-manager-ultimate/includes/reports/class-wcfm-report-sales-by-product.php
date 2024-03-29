<?php
/**
 * Report class responsible for handling sales by product reports.
 *
 * @since      3.1.2
 *
 * @package    WooCommerce Frontend Manager Ultimate
 * @subpackage wcfmu/includes/reports
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once( WC()->plugin_path() . '/includes/admin/reports/class-wc-admin-report.php' );

class WCFM_Report_Sales_By_Product extends WC_Admin_Report {

	/**
	 * Chart colors.
	 *
	 * @var array
	 */
	public $chart_colours      = array();

	/**
	 * Product ids.
	 *
	 * @var array
	 */
	public $product_ids        = array();

	/**
	 * Product ids with titles.
	 *
	 * @var array
	 */
	public $product_ids_titles = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ( isset( $_GET['product_ids'] ) && is_array( $_GET['product_ids'] ) ) {
			$this->product_ids = array_filter( array_map( 'absint', $_GET['product_ids'] ) );
		} elseif ( isset( $_GET['product_ids'] ) ) {
			$this->product_ids = array_filter( array( absint( $_GET['product_ids'] ) ) );
		}
	}

	/**
	 * Get the legend for the main chart sidebar.
	 * @return array
	 */
	public function get_chart_legend() {

		if ( empty( $this->product_ids ) ) {
			return array();
		}

		$legend   = array();

		$total_sales = $this->get_order_report_data( array(
			'data' => array(
				'_line_total' => array(
					'type'            => 'order_item_meta',
					'order_item_type' => 'line_item',
					'function' => 'SUM',
					'name'     => 'order_item_amount',
				),
			),
			'where_meta' => array(
				'relation' => 'OR',
				array(
					'type'       => 'order_item_meta',
					'meta_key'   => array( '_product_id', '_variation_id' ),
					'meta_value' => $this->product_ids,
					'operator'   => 'IN',
				)
			),
			'query_type'   => 'get_var',
			'filter_range' => true,
		) );

		$total_items = absint( $this->get_order_report_data( array(
			'data' => array(
				'_qty' => array(
					'type'            => 'order_item_meta',
					'order_item_type' => 'line_item',
					'function'        => 'SUM',
					'name'            => 'order_item_count',
				),
			),
			'where_meta' => array(
				'relation' => 'OR',
				array(
					'type'       => 'order_item_meta',
					'meta_key'   => array( '_product_id', '_variation_id' ),
					'meta_value' => $this->product_ids,
					'operator'   => 'IN',
				)
			),
			'query_type'   => 'get_var',
			'filter_range' => true,
		) ) );

		$legend[] = array(
			/* translators: %s: total items sold */
			'title' => sprintf( __( '%s sales for the selected items', 'woocommerce' ), '<strong>' . wc_price( $total_sales ) . '</strong>' ),
			'color' => $this->chart_colours['sales_amount'],
			'highlight_series' => 1,
		);

		$legend[] = array(
			/* translators: %s: total items purchased */
			'title' => sprintf( __( '%s purchases for the selected items', 'woocommerce' ), '<strong>' . ( $total_items ) . '</strong>' ),
			'color' => $this->chart_colours['item_count'],
			'highlight_series' => 0,
		);

		return $legend;
	}

	/**
	 * Output the report.
	 */
	public function output_report() {

		$ranges = array(
			'year'         => __( 'Year', 'woocommerce' ),
			'last_month'   => __( 'Last month', 'woocommerce' ),
			'month'        => __( 'This month', 'woocommerce' ),
			'7day'         => __( 'Last 7 days', 'woocommerce' ),
		);

		$this->chart_colours = array(
			'sales_amount' => '#3498db',
			'item_count'   => '#d4d9dc',
		);

		$current_range = ! empty( $_GET['range'] ) ? sanitize_text_field( $_GET['range'] ) : '7day';

		if ( ! in_array( $current_range, array( 'custom', 'year', 'last_month', 'month', '7day' ) ) ) {
			$current_range = '7day';
		}

		$this->check_current_range_nonce( $current_range );
		$this->calculate_current_range( $current_range );

		include( WC()->plugin_path() . '/includes/admin/views/html-report-by-date.php' );
	}

	/**
	 * Get chart widgets.
	 *
	 * @return array
	 */
	public function get_chart_widgets() {

		$widgets = array();

		if ( ! empty( $this->product_ids ) ) {
			$widgets[] = array(
				'title'    => __( 'Showing reports for:', 'woocommerce' ),
				'callback' => array( $this, 'current_filters' ),
			);
		}

		$widgets[] = array(
			'title'    => '',
			'callback' => array( $this, 'products_widget' ),
		);

		return $widgets;
	}

	/**
	 * Output current filters.
	 */
	public function current_filters() {

		$this->product_ids_titles = array();

		foreach ( $this->product_ids as $product_id ) {

			$product = wc_get_product( $product_id );

			if ( $product ) {
				$this->product_ids_titles[] = $product->get_formatted_name();
			} else {
				$this->product_ids_titles[] = '#' . $product_id;
			}
		}

		echo '<p>' . ' <strong>' . esc_html( implode( ', ', $this->product_ids_titles ) ) . '</strong></p>';
		echo '<p><a class="button" href="' . esc_url( remove_query_arg( 'product_ids' ) ) . '">' . __( 'Reset', 'woocommerce' ) . '</a></p>';
	}

	/**
	 * Output products widget.
	 */
	public function products_widget() {
		?>
		<h4 class="section_title"><span><?php _e( 'Product search', 'woocommerce' ); ?></span></h4>
		<div class="section">
			<form method="GET">
				<div>
					<select class="wc-product-search" style="width:203px;" multiple="multiple" id="product_ids" name="product_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_products_and_variations"></select>
					<input type="submit" class="submit button" value="<?php esc_attr_e( 'Show', 'woocommerce' ); ?>" />
					<input type="hidden" name="range" value="<?php echo ( ! empty( $_GET['range'] ) ) ? esc_attr( $_GET['range'] ) : ''; ?>" />
					<input type="hidden" name="start_date" value="<?php echo ( ! empty( $_GET['start_date'] ) ) ? esc_attr( $_GET['start_date'] ) : ''; ?>" />
					<input type="hidden" name="end_date" value="<?php echo ( ! empty( $_GET['end_date'] ) ) ? esc_attr( $_GET['end_date'] ) : ''; ?>" />
					<input type="hidden" name="page" value="<?php echo ( ! empty( $_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : ''; ?>" />
					<input type="hidden" name="tab" value="<?php echo ( ! empty( $_GET['tab'] ) ) ? esc_attr( $_GET['tab'] ) : ''; ?>" />
					<input type="hidden" name="report" value="<?php echo ( ! empty( $_GET['report'] ) ) ? esc_attr( $_GET['report'] ) : ''; ?>" />
					<?php wp_nonce_field( 'custom_range', 'wc_reports_nonce', false ); ?>
				</div>
			</form>
		</div>
		<h4 class="section_title"><span><?php _e( 'Top sellers', 'woocommerce' ); ?></span></h4>
		<div class="section">
			<table cellspacing="0">
				<?php
				$top_sellers = $this->get_order_report_data( array(
					'data' => array(
						'_product_id' => array(
							'type'            => 'order_item_meta',
							'order_item_type' => 'line_item',
							'function'        => '',
							'name'            => 'product_id',
						),
						'_qty' => array(
							'type'            => 'order_item_meta',
							'order_item_type' => 'line_item',
							'function'        => 'SUM',
							'name'            => 'order_item_qty',
						),
					),
					'order_by'     => 'order_item_qty DESC',
					'group_by'     => 'product_id',
					'limit'        => 12,
					'query_type'   => 'get_results',
					'filter_range' => true,
				) );

				if ( $top_sellers ) {
					foreach ( $top_sellers as $product ) {
						echo '<tr class="' . ( in_array( $product->product_id, $this->product_ids ) ? 'active' : '' ) . '">
							<td class="count">' . $product->order_item_qty . '</td>
							<td class="name"><a href="' . esc_url( add_query_arg( 'product_ids', $product->product_id ) ) . '">' . esc_html( get_the_title( $product->product_id ) ) . '</a></td>
							<td class="sparkline">' . $this->sales_sparkline( $product->product_id, 7, 'count' ) . '</td>
						</tr>';
					}
				} else {
					echo '<tr><td colspan="3">' . __( 'No products found in range', 'woocommerce' ) . '</td></tr>';
				}
				?>
			</table>
		</div>
		<h4 class="section_title"><span><?php _e( 'Top freebies', 'woocommerce' ); ?></span></h4>
		<div class="section">
			<table cellspacing="0">
				<?php
				$top_freebies = $this->get_order_report_data( array(
					'data' => array(
						'_product_id' => array(
							'type'            => 'order_item_meta',
							'order_item_type' => 'line_item',
							'function'        => '',
							'name'            => 'product_id',
						),
						'_qty' => array(
							'type'            => 'order_item_meta',
							'order_item_type' => 'line_item',
							'function'        => 'SUM',
							'name'            => 'order_item_qty',
						),
					),
					'where_meta'   => array(
						array(
							'type'       => 'order_item_meta',
							'meta_key'   => '_line_subtotal',
							'meta_value' => '0',
							'operator'   => '=',
						),
					),
					'order_by'     => 'order_item_qty DESC',
					'group_by'     => 'product_id',
					'limit'        => 12,
					'query_type'   => 'get_results',
					'filter_range' => true,
				) );

				if ( $top_freebies ) {
					foreach ( $top_freebies as $product ) {
						echo '<tr class="' . ( in_array( $product->product_id, $this->product_ids ) ? 'active' : '' ) . '">
							<td class="count">' . $product->order_item_qty . '</td>
							<td class="name"><a href="' . esc_url( add_query_arg( 'product_ids', $product->product_id ) ) . '">' . esc_html( get_the_title( $product->product_id ) ) . '</a></td>
							<td class="sparkline">' . $this->sales_sparkline( $product->product_id, 7, 'count' ) . '</td>
						</tr>';
					}
				} else {
					echo '<tr><td colspan="3">' . __( 'No products found in range', 'woocommerce' ) . '</td></tr>';
				}
				?>
			</table>
		</div>
		<h4 class="section_title"><span><?php _e( 'Top earners', 'woocommerce' ); ?></span></h4>
		<div class="section">
			<table cellspacing="0">
				<?php
				$top_earners = $this->get_order_report_data( array(
					'data' => array(
						'_product_id' => array(
							'type'            => 'order_item_meta',
							'order_item_type' => 'line_item',
							'function'        => '',
							'name'            => 'product_id',
						),
						'_line_total' => array(
							'type'            => 'order_item_meta',
							'order_item_type' => 'line_item',
							'function'        => 'SUM',
							'name'            => 'order_item_total',
						),
					),
					'order_by'     => 'order_item_total DESC',
					'group_by'     => 'product_id',
					'limit'        => 12,
					'query_type'   => 'get_results',
					'filter_range' => true,
				) );

				if ( $top_earners ) {
					foreach ( $top_earners as $product ) {
						echo '<tr class="' . ( in_array( $product->product_id, $this->product_ids ) ? 'active' : '' ) . '">
							<td class="count">' . wc_price( $product->order_item_total ) . '</td>
							<td class="name"><a href="' . esc_url( add_query_arg( 'product_ids', $product->product_id ) ) . '">' . esc_html( get_the_title( $product->product_id ) ) . '</a></td>
							<td class="sparkline">' . $this->sales_sparkline( $product->product_id, 7, 'sales' ) . '</td>
						</tr>';
					}
				} else {
					echo '<tr><td colspan="3">' . __( 'No products found in range', 'woocommerce' ) . '</td></tr>';
				}
				?>
			</table>
		</div>
		<script type="text/javascript">
			jQuery('.section_title').click(function(){
				var next_section = jQuery(this).next('.section');

				if ( jQuery(next_section).is(':visible') )
					return false;

				jQuery('.section:visible').slideUp();
				jQuery('.section_title').removeClass('open');
				jQuery(this).addClass('open').next('.section').slideDown();

				return false;
			});
			jQuery('.section').slideUp( 100, function() {
				<?php if ( empty( $this->product_ids ) ) : ?>
					jQuery('.section_title:eq(1)').click();
				<?php endif; ?>
			});
		</script>
		<?php
	}

	/**
	 * Output an export link.
	 */
	public function get_export_button() {
		return;
		$current_range = ! empty( $_GET['range'] ) ? sanitize_text_field( $_GET['range'] ) : '7day';
		?>
		<a
			href="#"
			download="report-<?php echo esc_attr( $current_range ); ?>-<?php echo date_i18n( 'Y-m-d', current_time( 'timestamp' ) ); ?>.csv"
			class="export_csv"
			data-export="chart"
			data-xaxes="<?php esc_attr_e( 'Date', 'woocommerce' ); ?>"
			data-groupby="<?php echo $this->chart_groupby; ?>"
		>
			<?php _e( 'Export CSV', 'woocommerce' ); ?>
		</a>
		<?php
	}

	/**
	 * Get the main chart.
	 *
	 * @return string
	 */
	public function get_main_chart() {
		global $wp_locale, $WCFM;

		if ( empty( $this->product_ids ) ) {
			?>
			<div class="chart-container">
				<p class="chart-prompt"><?php _e( 'Choose a product to view stats', 'woocommerce' ); ?></p>
			</div>
			<?php
		} else {
			// Get orders and dates in range - we want the SUM of order totals, COUNT of order items, COUNT of orders, and the date
			$order_item_counts = $this->get_order_report_data( array(
				'data' => array(
					'_qty' => array(
						'type'            => 'order_item_meta',
						'order_item_type' => 'line_item',
						'function'        => 'SUM',
						'name'            => 'order_item_count',
					),
					'post_date' => array(
						'type'     => 'post_data',
						'function' => '',
						'name'     => 'post_date',
					),
					'_product_id' => array(
						'type'            => 'order_item_meta',
						'order_item_type' => 'line_item',
						'function'        => '',
						'name'            => 'product_id',
					),
				),
				'where_meta' => array(
					'relation' => 'OR',
					array(
						'type'       => 'order_item_meta',
						'meta_key'   => array( '_product_id', '_variation_id' ),
						'meta_value' => $this->product_ids,
						'operator'   => 'IN',
					),
				),
				'group_by'     => 'product_id,' . $this->group_by_query,
				'order_by'     => 'post_date ASC',
				'query_type'   => 'get_results',
				'filter_range' => true,
			) );

			$order_item_amounts = $this->get_order_report_data( array(
				'data' => array(
					'_line_total' => array(
						'type'            => 'order_item_meta',
						'order_item_type' => 'line_item',
						'function' => 'SUM',
						'name'     => 'order_item_amount',
					),
					'post_date' => array(
						'type'     => 'post_data',
						'function' => '',
						'name'     => 'post_date',
					),
					'_product_id' => array(
						'type'            => 'order_item_meta',
						'order_item_type' => 'line_item',
						'function'        => '',
						'name'            => 'product_id',
					),
				),
				'where_meta' => array(
					'relation' => 'OR',
					array(
						'type'       => 'order_item_meta',
						'meta_key'   => array( '_product_id', '_variation_id' ),
						'meta_value' => $this->product_ids,
						'operator'   => 'IN',
					),
				),
				'group_by'     => 'product_id, ' . $this->group_by_query,
				'order_by'     => 'post_date ASC',
				'query_type'   => 'get_results',
				'filter_range' => true,
			) );

			// Prepare data for report
			$order_item_counts  = $this->prepare_chart_data( $order_item_counts, 'post_date', 'order_item_count', $this->chart_interval, $this->start_date, $this->chart_groupby );
			$order_item_amounts = $this->prepare_chart_data( $order_item_amounts, 'post_date', 'order_item_amount', $this->chart_interval, $this->start_date, $this->chart_groupby );

			// Encode in json format
			$chart_data = '{'
			. '  "order_item_counts"         : ' . $WCFM->wcfm_prepare_chart_data( $order_item_counts )
			. ', "order_item_amounts"        : ' . $WCFM->wcfm_prepare_chart_data( $order_item_amounts )
		  . '}';
			?>
			<div class="chart-container">
				<div class="chart-placeholder main"><canvas id="chart-placeholder-canvas"></canvas></div>
			</div>
			<script type="text/javascript">
				jQuery(function(){
					var sales_data = <?php echo $chart_data; ?>;
					var show_legend    = true;
					
					jQuery('.chart-placeholder').css( 'width', jQuery('.chart-placeholder').outerWidth() + 'px' );
					
					var ctx = document.getElementById("chart-placeholder-canvas").getContext("2d");
					var mySalesReportChart = new Chart(ctx, {
							type: 'bar',
							data: {
									labels: sales_data.order_item_amounts.labels,
									datasets: [
												{
													type: 'line',
													label: "Sales Amounts",
													backgroundColor: color(window.chartColors.blue).alpha(0.2).rgbString(),
													borderColor: window.chartColors.blue,
													fill: true,
													data: sales_data.order_item_amounts.datas,
												},
												{
													type: 'bar',
													label: "Sales Counts",
													backgroundColor: color(window.chartColors.orange).alpha(0.5).rgbString(),
													borderColor: window.chartColors.orange,
													borderWidth: 2,
													data: sales_data.order_item_counts.datas,
												}
												]
							},
							options: {
									responsive: true,
									title:{
											text: "Sales Report by Product"
									},
									legend: {
										position: "bottom",
										display: show_legend,
									},
									scales: {
										xAxes: [{
											type: "time",
											time: {
												format: timeFormat,
												round: 'day',
												tooltipFormat: 'll'
											},
											scaleLabel: {
												display: false,
												labelString: 'Date'
											}
										}],
										yAxes: [{
											scaleLabel: {
												display: false,
												labelString: 'Amount'
											}
										}]
									},
								}
							});
					
					var resizeId;
					jQuery(window).resize(function() {
						clearTimeout(resizeId);
						resizeId = setTimeout(afterResizing, 100);
					});
					function afterResizing() {
						var canvasheight = document.getElementById("chart-placeholder-canvas").height;
						if(canvasheight <= 350) {
							mySalesReportChart.options.legend.display=false;
						} else {
							mySalesReportChart.options.legend.display=true;
						}
						mySalesReportChart.update();
					}
					afterResizing();
				});
			</script>
			<?php
		}
	}
}
