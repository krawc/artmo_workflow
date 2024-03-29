<?php
global $woocommerce, $WOOCS;

$product = wc_get_product();
$amount = WC_Deposits_Product_Meta::get_meta( $product->get_id(), '_wc_deposit_amount' );

if ( ! $amount ) {
	$amount = get_option( 'wc_deposits_default_amount' );
}
$priceN = $product->get_price();

if (isset($WOOCS)) {
	$converted = $WOOCS->woocs_exchange_value($priceN);
}

$f = new NumberFormatter(get_locale(), NumberFormatter::CURRENCY);

$price = $priceN;
$currencyPrice = $converted;
$symbol = get_woocommerce_currency_symbol();
$currencyCode = get_woocommerce_currency();

$priceForm = $f->formatCurrency($currencyPrice, $currencyCode);

$deposit = $amount;
$depositSum = round($currencyPrice * $deposit * 0.01 );
$depositForm = $f->formatCurrency($depositSum, $currencyCode);

$default_selected_type = WC_Deposits_Product_Manager::get_deposit_selected_type( $post->ID );
$style = 'deposit' === $default_selected_type ? 'style="display:block;"' : 'style="display:none;"';
?>
<div class="wc-deposits-wrapper <?php echo WC_Deposits_Product_Manager::deposits_forced( $post->ID ) ? 'wc-deposits-forced' : 'wc-deposits-optional'; ?>">
	<?php if ( ! WC_Deposits_Product_Manager::deposits_forced( $post->ID ) ) : ?>
		<ul class="wc-deposits-option">
			<li>
				<input type="radio" name="wc_deposit_option" value="yes" id="wc-option-pay-deposit" <?php checked( $default_selected_type, 'deposit' ); ?> />
				<label for="wc-option-pay-deposit">
					<?php esc_html_e( 'Pay Deposit', 'woocommerce-deposits' ); ?>
				</label>
				<label class="wc_show_deposit_amount">
					<?php echo $depositForm . ' (' . $deposit . '%)'; ?>
				</label>
			</li>
			<li>
				<input type="radio" name="wc_deposit_option" value="no" id="wc-option-pay-full" <?php checked( $default_selected_type, 'full' ); ?> />
				<label for="wc-option-pay-full">
					<?php esc_html_e( 'Pay in Full', 'woocommerce-deposits' ); ?>
				</label>
				<label class="price">
					<?php echo $priceForm; ?>
				</label>
			</li>
		</ul>
	<?php endif; ?>

	<?php if ( 'plan' === WC_Deposits_Product_Manager::get_deposit_type( $post->ID ) ) : ?>
		<ul class="wc-deposits-payment-plans" <?php echo $style; ?>>
			<?php foreach( WC_Deposits_Plans_Manager::get_plans_for_product( $post->ID ) as $key => $plan ) : ?>
				<li class="wc-deposits-payment-plan">
					<input type="radio" name="wc_deposit_payment_plan" <?php checked( $key, 0 ); ?> value="<?php echo esc_attr( $plan->get_id() ); ?>" id="wc-deposits-payment-plan-<?php echo esc_attr( $plan->get_id() ); ?>" />
					<label for="wc-deposits-payment-plan-<?php echo esc_attr( $plan->get_id() ); ?>">
						<strong class="wc-deposits-payment-plan-name"><?php echo esc_html( $plan->get_name() ); ?></strong>
						<small class="wc-deposits-payment-plan-description"><?php echo wp_kses_post( $plan->get_description() ); ?></small>
					</label>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php else : ?>
		<div class="wc-deposits-payment-description" <?php echo $style; ?>>
			<?php echo WC_Deposits_Product_Manager::get_formatted_deposit_amount( $post->ID ); ?>
		</div>
	<?php endif; ?>
</div>
