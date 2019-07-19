<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;
global $product;
require_once(__DIR__.'/../classes/acf.php');
$acfClass = new CustomACF();
$blog_id = get_current_blog_id();

// Removing image link
function wc_remove_link_on_thumbnails( $html ) {
     return strip_tags( $html,'<img>' );
}
add_filter('woocommerce_single_product_image_thumbnail_html','wc_remove_link_on_thumbnails' );

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class('entry-content row cf outdoor-product-single '); ?>>

	<div class="cf"><div class="col-12">
		<?php
		/**
		 * Hook: woocommerce_before_single_product_summary.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
		
		/**
		 * Hook: woocommerce_single_product_summary.
		 *
		 * @hooked woocommerce_template_single_title - 5
		 * @hooked woocommerce_template_single_rating - 10
		 * @hooked woocommerce_template_single_price - 10
		 * @hooked woocommerce_template_single_excerpt - 20
		 * @hooked woocommerce_template_single_add_to_cart - 30
		 * @hooked woocommerce_template_single_meta - 40
		 * @hooked woocommerce_template_single_sharing - 50
		 * @hooked WC_Structured_Data::generate_product_data() - 60
		 */
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		if( $product->is_type( 'variable' ) ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		}
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25 );
		
		echo '<div class="short-desc">';
		do_action( 'woocommerce_single_product_summary' );
		echo '</div>';
		?>
	</div></div>
</div>

<?php
echo '<div class="overlay"><a href="#" class="close-overlay">Close</a>';
echo do_shortcode('[contact-form-7 id="4091" title="Request a callback"]');
echo '</div>';
?>

<?php
// CTAs Module 1
$cta_module = get_post(1409);
$acfClass->render_modules($cta_module);

// Product Details + Images
echo '<section class="entry-content row cf product-info-wrapper"><div class="cf">';

if( have_rows('product_images') ) {
	echo '<div class="col-6">';

	while( have_rows('product_images') ) : the_row();
	$image = get_sub_field('image');
	echo '<img src="'.$image['url'].'" alt="'.$image['alt'].'" />';
	endwhile; 
	
	echo '</div>';
}

function render_variation(){
	add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
}

if( !empty(get_the_content()) ) {
	if( have_rows('product_images') ) {
		echo '<div class="col-6"><div class="details-inner">';
	} else {
		echo '<div class="col-12"><div class="details-inner">';
	}
	echo '<div class="product-details">';
	if( $product->is_type( 'variable' ) && $blog_id == 1 ) {
		woocommerce_template_single_add_to_cart();
	} else if( $product->is_type( 'variable' ) && $blog_id == 5 ) {
        woocommerce_template_single_add_to_cart();
        echo '<div class="drawings-link hidden">';
        wc_get_template( 'single-product/tabs/description.php' );
        echo '</div>';
    } else {
		wc_get_template( 'single-product/tabs/description.php' );
	}
	echo '</div>';
}

echo '</div></div></div>'; // close inner

echo '</div></section>'; // close section

// Videos + extras
if( have_rows('videos') || get_field('extra_content') ) : 
$row = 1;
echo '<section class="entry-content row cf wrap vids-wrapper"><div class="cf">';
while( have_rows('videos') ) : the_row();

if( $row == 1 ) : ?>

<div class="aspect-ratio main-window">
	<?php the_sub_field('video_url'); ?>
</div>

<?php else : ?>

<div class="vid-thumb">
	<div class="play">Play</div>
	<div class="aspect-ratio vid-gallery">
		<?php the_sub_field('video_url'); ?>
	</div>
</div>

<?php endif;

$row ++;
endwhile;

// the_field('extra_content');

echo '</div></div></section>';
endif;  // close section ?>

<?php // Modules
$acc_module = get_post(73);
$acfClass->render_modules($acc_module);
?>

<?php
/**
 * Hook: woocommerce_after_single_product_summary.
 *
 * @hooked woocommerce_output_product_data_tabs - 10
 * @hooked woocommerce_upsell_display - 15
 * @hooked woocommerce_output_related_products - 20
 */

$cat = get_the_terms( $product->get_id(), 'product_cat' );
$parent_cat;
$current_cat = end($cat);
foreach ($cat as $category) {
	if($category->parent == 0){
	   $parent_cat = $category->slug;
	}
}

echo '<section class="row entry-content cf related-products"><div class="cf"><div class="col-12">';
if( $product->get_upsell_ids() ) {
	woocommerce_upsell_display(3, 3, 'menu_order', 'asc');
} else if( $blog_id == 5 ) {
    echo '<h2>Mantels you might like</h2><p></p>';
	echo do_shortcode('[products orderby="rand" category="'.$current_cat->slug.'" limit="3" columns="3" class="related-products"]');
} else {
	echo '<h2>You might also like</h2><p></p>';
	echo do_shortcode('[products orderby="rand" category="'.$parent_cat.'" limit="3" columns="3" class="related-products"]');
}
echo '</section></div></div>';

//do_action( 'woocommerce_after_single_product_summary' );
?>

<?php do_action( 'woocommerce_after_single_product' ); ?>