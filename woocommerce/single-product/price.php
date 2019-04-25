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

$shipping_class = $product->get_shipping_class();

$shipping_classes = get_terms( array(
	'taxonomy' => 'product_shipping_class', 
	'hide_empty' => false,
	'slug' => $shipping_class ) );

foreach($shipping_classes as $shipping_class){
	print_r($shipping_class);
}

?>
<p class="price"><?php echo $product->get_price_html(); ?></p>
