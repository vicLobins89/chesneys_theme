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

if( term_is_ancestor_of(16, $term->term_id, 'product_cat') ) {
	remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 20);
	remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
}

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<header class="row entry-content cf woocommerce-products-header featured top">
	<div class="cf">
		<?php
		do_action( 'woocommerce_archive_description' );
		$custom_content = get_field('custom_content', $term);
		echo '<div class="custom-content narrow-para">'.$custom_content.'</div>';
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
	<section class="entry-content row cf shop-loop"><div class="cf"><div class="col-12">
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

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();
	?>
	</div></div></section>
	<?php
	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );
	
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
	
	// Related products
	if( get_term_meta($term->term_id, 'display_type', true) == 'products' ||  get_term_meta($term->term_id, 'display_type', true) == null) {
		echo do_shortcode('[products limit="3" columns="3" class="related-products"]');
	}
	
	// Blog / Case Study Posts (Automation)
	// Getting URL
	/*$r = $_SERVER['REQUEST_URI'];
	$r = explode('/', $r);
	$r = array_filter($r);
	$r = array_merge($r, array());
	$code = ( !empty($r[1]) ) ? $r[1] : 'fireplaces';
	
	// Getting Category Name
	$prCatId = ( !empty($term->term_id) ) ? $term->term_id : 15;
	$prCatName = ( !empty($term->slug) ) ? $term->slug : $code;
	
	if( get_cat_ID($prCatName) ) {
		$acfClass->render_blog($prCatName);
		$acfClass->render_portfolio($prCatName);
	} else {
		$parentCats = get_ancestors($prCatId, 'product_cat');
		foreach($parentCats as $parentCat){
			$category = get_term_by('id', $parentCat, 'product_cat');
			if( get_cat_ID($category->slug) ) {
				$acfClass->render_blog($category->slug);
				$acfClass->render_portfolio($category->slug);
				break;
			}
		}
	}*/
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
