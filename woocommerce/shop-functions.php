<?php

add_action('woocommerce_after_shop_loop_item', 'woo_custom_hook_function');

function woo_cusom_hook_function() {

    global $post;

    if (function_exists( 'get_product' )) {
        $product = get_product( $post->ID );

        if ($product->is_type( 'grouped' )) {
        // anything you hook into above will be run here for grouped products only.
        add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

        }
    }
}

?>