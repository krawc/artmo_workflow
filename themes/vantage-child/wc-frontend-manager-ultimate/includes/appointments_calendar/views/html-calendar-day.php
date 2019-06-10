<div class="wrap woocommerce">
	<form method="get" id="mainform" enctype="multipart/form-data" class="wc_appointments_calendar_form day_view">
		<input type="hidden" name="view" value="<?php echo esc_attr( $view ); ?>" />
		<input type="hidden" name="tab" value="calendar" />
		<div class="tablenav">
			<div class="filters">
				<select id="calendar-appointments-filter" name="filter_appointments" class="wcfm-select" style="width:200px">
					<option value=""><?php _e( 'Filter Appointments', 'woocommerce-appointments' ); ?></option>
					<?php if ( $product_filters = $this->product_filters() ) : ?>
						<optgroup label="<?php _e( 'By appointable product', 'woocommerce-appointments' ); ?>">
							<?php foreach ( $product_filters as $filter_id => $filter_name ) : ?>
								<option value="product_<?php echo $filter_id; ?>" <?php selected( $product_filter, $filter_id ); ?>><?php echo $filter_name; ?></option>
							<?php endforeach; ?>
						</optgroup>
					<?php endif; ?>
					<?php if ( $staff_filters = $this->staff_filters() ) : ?>
						<optgroup label="<?php _e( 'By staff', 'woocommerce-appointments' ); ?>">
							<?php foreach ( $staff_filters as $filter_id => $filter_name ) : ?>
								<option value="staff_<?php echo $filter_id; ?>" <?php selected( $product_filter, $filter_id ); ?>><?php echo $filter_name; ?></option>
							<?php endforeach; ?>
						</optgroup>
					<?php endif; ?>
				</select>
			</div>
			<div class="date_selector">
				<a class="fa fa-arrow-circle-o-left" href="<?php echo esc_url( add_query_arg( 'calendar_day', $prev_day ) ); ?>"></a>
				<div>
					<input type="text" name="calendar_day" class="calendar_day date-picker wcfm-text" style="text-align:center;" value="<?php echo esc_attr( $day_formatted ); ?>" placeholder="<?php echo wc_date_format(); ?>" autocomplete="off" />
				</div>
				<a class="fa fa-arrow-circle-o-right" href="<?php echo esc_url( add_query_arg( 'calendar_day', $next_day ) ); ?>"></a>
			</div>
			<div class="views">
			  <a class="week text_tip" href="<?php echo esc_url( add_query_arg( 'view', 'week' ) ); ?>" data-tip="<?php _e( 'Week View', 'woocommerce-appointments' ); ?>"><?php _e( 'Week View', 'woocommerce-appointments' ); ?></a>
				<a class="month text_tip" href="<?php echo esc_url( add_query_arg( 'view', 'month' ) ); ?>" data-tip="<?php _e( 'Month View', 'woocommerce-appointments' ); ?>"><?php _e( 'Month View', 'woocommerce-appointments' ); ?></a>
			</div>
			<div class="wcfm-clearfix"></div>
		</div>
		<div class="wcfm-clearfix"></div><br />
		<div class="calendar_wrapper">
			<?php
			$calendar_scale = apply_filters( 'woocommerce_appointments_calendar_view_day_scale', 60 );
			$columns_by_staff = apply_filters( 'woocommerce_appointments_calendar_view_by_staff', false );
			$c_width = ''; //'min-width:' . ( ( ( $this->staff_columns( $variation = 1 ) + 1 ) * 170 ) + 100 ) . 'px;';
			$c_height = 'height: ' . ($calendar_scale * 24) . 'px;';
			$c_class = ( $columns_by_staff ) ? 'class="calendar_days calendar_view_by_staff" style="' . $c_width . '"' : 'class="calendar_days" style="' . $c_width . '"';
			?>
			<div <?php echo $c_class; ?>>
				<?php if ( $columns_by_staff ) : ?>
				<ul class="staff">
					<?php $this->staff_columns(); ?>
				</ul>
				<?php endif; ?>
				<label class="allday_label"><?php _e( 'All Day', 'woocommerce-appointments' ); ?></label>
				<ul class="allday appointments">
					<?php $this->list_appointments_for_day( 'all_day' ); ?>
				</ul>
				<div class="clear"></div>
				<div class="grid"  style="<?php echo $c_height ?>"></div>
				<ul class="hours" style="<?php echo $c_height ?>">
					<?php for ( $i = 0; $i < 24; $i ++ ) : ?>
						<li><label><?php if ( 0 != $i && 24 != $i ) echo date_i18n( wc_time_format(), strtotime( "midnight +{$i} hour" ) ); ?></label></li>
					<?php endfor; ?>
				</ul>
				<ul class="bytime appointments" style="<?php echo $c_height  ?>">
					<?php $this->list_appointments_for_day( 'by_time' ); ?>
				</ul>
			</div>
		</div>
	</form>
</div>
