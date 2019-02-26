<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
require_once(__DIR__.'/../classes/acf.php');
$acfClass = new CustomACF();
$term = get_queried_object();

// Number of rows + products
function spares_loop_columns() {
	return 4;
}
add_filter('loop_shop_columns', 'spares_loop_columns', 999);

// Button text
function custom_woocommerce_product_add_to_cart_text()  {
	global $product;
	if( $product->is_type( 'grouped' ) ){
		return __( 'View spares', 'woocommerce' );
	} elseif( $product->managing_stock() && $product->is_in_stock() ) {
		return __( 'Add to cart', 'woocommerce' );
	} else {
		return __( 'View', 'woocommerce' );
	}
}
add_filter( 'woocommerce_product_add_to_cart_text', 'custom_woocommerce_product_add_to_cart_text' );

// Price 
function bbloomer_grouped_price_range_delete( $price, $product, $child_prices ) {
	global $product;
	if( $product->is_type( 'grouped' ) ){
		$price = '';
	}
	return $price;
}
add_filter( 'woocommerce_grouped_price_html', 'bbloomer_grouped_price_range_delete', 10, 3 );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<header class="row entry-content cf woocommerce-products-header stove-spares-header">
	<div class="cf">
		<?php
		do_action( 'woocommerce_archive_description' );
		$custom_content = get_field('custom_content', $term);
		echo '<div class="col-12 spares-thumbs">'.$custom_content.'</div>';
		?>
	</div>
</header>
<?php
if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	?>
	<section class="entry-content row cf shop-loop spares-shop-loop"><div class="cf"><div class="col-12">
	<?php
	
	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 *
			 * @hooked WC_Structured_Data::generate_product_data() - 10
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product_spares' );
		}
	}

	woocommerce_product_loop_end();
	
	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );
	
	?>
	</div></div></section>
	<?php
	
	// Modules
	$rows = get_field('rows', $term);
	if($rows) {
		foreach($rows as $row) {
			$modules = $row['module'];
			$blog_feeds = $row['blog_feed'];
			$portfolio_feeds = $row['portfolio_feed'];
			if( isset($modules) ) {
				foreach($modules as $module) {
					$acfClass->render_modules($module['module_block']);
				}
			}
			
			if( is_array($blog_feeds) || is_object($blog_feeds) ) {
				foreach($blog_feeds as $blog_feed ) {
					$acfClass->render_blog($blog_feed);
				}
			}
			
			if( is_array($portfolio_feeds) || is_object($portfolio_feeds) ) {
				foreach($portfolio_feeds as $portfolio_feed) {
					$acfClass->render_portfolio($portfolio_feed);
				}
			}
		}
	}
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
//do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
