<?php

// Remove 
add_action( 'woocommerce_after_shop_loop_item', 'remove_add_to_cart_buttons', 1 );

function remove_add_to_cart_buttons() {
	if( is_product_category( array('fireplaces', 'stoves') ) ) {
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
	}
}

add_action('wp','disable_cars_initially');  

function disable_cars_initially() {
	global $product;

	// Set HERE your category ID, slug or name (or an array)
	$category = array('fireplaces', 'stoves');

	//Remove Add to Cart button from product description of product with any cat    
	if ( has_term( $category, 'product_cat', $product->id ) ) {
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	}
}

?>