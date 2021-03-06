<?php
/**
 * The Template for displaying products in a product category. Simply includes the archive template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/taxonomy-product_cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$term = get_queried_object();
$blog_id = get_current_blog_id();

if( 
    $blog_id == 1 && 
    (
    term_is_ancestor_of(67, $term->term_id, 'product_cat') || 
    is_product_category(67) || 
    term_is_ancestor_of(68, $term->term_id, 'product_cat') || 
    is_product_category(68) ||
    term_is_ancestor_of(1209, $term->term_id, 'product_cat') || 
    is_product_category(1209)
    ) 
) {
	// Spares & Fuel / Acc
	wc_get_template( 'archive-product_spares.php' );
} else {
	wc_get_template( 'archive-product.php' );
}