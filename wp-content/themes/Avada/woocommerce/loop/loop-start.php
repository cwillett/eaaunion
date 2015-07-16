<?php
/**
 * Displayed when no products are found matching the current query.
 *
 * Override this template by copying it to yourtheme/woocommerce/loop/no-products-found.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce_loop;

// Store column count for displaying the grid
if( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 1 );
}

// Reset to 4 on following conditions ..
if( is_shop() || is_product_category() || is_product_tag() ) {
	$woocommerce_loop['columns'] = 4;
}
?>
<ul class="products clearfix products-<?php echo $woocommerce_loop['columns']; ?>">