<?php

// Remove cart button
//add_action( 'woocommerce_after_shop_loop_item', 'remove_add_to_cart_buttons', 1 );

function remove_add_to_cart_buttons() {
	if( is_product_category( array('fireplaces', 'stoves') ) ) {
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
	}
}

add_action( 'wp', 'remove_addcart' );

function remove_addcart() {
   $product = wc_get_product();
   if ( has_term ( array('fireplaces', 'stoves'), 'product_cat') ) {
       remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
       remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
       remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
       remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
       remove_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
       remove_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
   }
}

?>