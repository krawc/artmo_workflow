<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $product;
?>
<p class="price"><?php echo $product->get_price_html(); ?><i class="ion ion-ios-help"
	title="Price exclusive shipping and taxes.
If shipped within a country, the national VAT has to be added.
When shipped to a different country (export), then import tax maybe due depending on your country's custom tariffs.
For a quotation of the final price you may MESSAGE the artist directly."></i></p>
