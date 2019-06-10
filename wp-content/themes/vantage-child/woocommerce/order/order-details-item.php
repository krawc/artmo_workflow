<?php

/**
 * Order details item
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.3.0
 */

 ?>
					<li class="product cart_item">
						<div class="product-thumbnail">
						<?php
						$thumbnail = apply_filters( 'woocommerce_order_item_thumbnail', $product->get_image(), $item, $wishlist_item_key );
            $product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );

						if ( ! $product_permalink ) {
							echo $thumbnail;
						} else {
							printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
						}
						?>
						</div>
						<div class="product-data">
							<p class="product-title"><?php echo apply_filters( 'woocommerce_order_item_name', $product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;'; ?></p>
							<p class="product-price"><?php echo $order->get_formatted_line_subtotal( $item );  ?></p>
						</div>
					</li>
