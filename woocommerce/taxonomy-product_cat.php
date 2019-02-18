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

function woocommerce_subcats_from_parentcat_by_name($parent_cat_name) {
	$ids_by_name = get_term_by('name', $parent_cat_name, 'product_cat');
	$product_cat_ID = $ids_by_name->term_id;
	$args = array(
		'hierarchical' => 1,
		'show_option_none' => '',
		'hide_empty' => 0,
		'parent' => $product_cat_ID,
		'taxonomy' => 'product_cat'
	);
	$subcats = get_categories($args);
	$subcat_names = array($parent_cat_name);

	foreach ($subcats as $sc) {
		array_push($subcat_names, $sc->slug);
	}
	
	return $subcat_names;
}

if( is_product_category( woocommerce_subcats_from_parentcat_by_name('fireplaces') ) ) {
	wc_get_template( 'archive-product.php' );
}