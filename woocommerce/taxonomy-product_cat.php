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

if( term_is_ancestor_of(16, $term->term_id, 'product_cat') || term_is_ancestor_of(56, $term->term_id, 'product_cat') || is_product_category(16) || is_product_category(56) ) {
	// Frireplaces & Stoves
	wc_get_template( 'archive-product_fireplaces.php' );
} elseif( term_is_ancestor_of(67, $term->term_id, 'product_cat') || is_product_category(67) ) {
	// Spares
	wc_get_template( 'archive-product_spares.php' );
} elseif( term_is_ancestor_of(68, $term->term_id, 'product_cat') || is_product_category(68) ) {
	// Spares
	wc_get_template( 'archive-product_spares.php' );
} elseif( term_is_ancestor_of(63, $term->term_id, 'product_cat') || is_product_category(63) ) {
	// Outdoor
	wc_get_template( 'archive-product_outdoor.php' );
} else {
	wc_get_template( 'archive-product.php' );
}