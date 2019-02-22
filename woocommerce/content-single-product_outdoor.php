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

<?php if( get_field('featured_image') ) : ?>
<section class="row entry-content cf top featured" itemprop="articleBody">
	<div class="cf">
		<img width="1859" height="1235" src="<?php echo get_field('featured_image'); ?>" class="attachment-full size-full wp-post-image" alt="">							
		<div class="featured-copy">
			<h1 class="h2 lhs"><?php echo $product->get_name(); ?></h1>
			<p><?php echo get_field('featured_copy'); ?></p>
		</div>
		
		<h2 style="text-align: center; margin-top: 1.5rem;">Product Spec</h2>
		<p style="text-align: center;"><?php echo get_field('product_spec'); ?></p>
	</div>
</section>
<?php endif; ?>

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
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		
		echo '<div class="short-desc">';
		do_action( 'woocommerce_single_product_summary' );
		echo '</div>';
		?>
	</div></div>
</div>

<?php
$cta_module = get_post(1281);
$acfClass->render_modules($cta_module);

// Product Details + Images
echo '<section class="entry-content row cf product-info-wrapper"><div class="cf">';

echo '<div class="col-6">';
if( have_rows('product_images') ) : while( have_rows('product_images') ) : $count = 1; the_row();
	$image = get_sub_field('image');
	if( !empty($image) ) {
		if( $count <= 2 ) {
			echo '<div><img src="'.$image['url'].'" alt="'.$image['alt'].'" /></div>';
		} else {
			echo '<img src="'.$image['url'].'" alt="'.$image['alt'].'" />';
		}
	}
	$count ++;
endwhile; endif;
echo '</div>';

echo '<div class="col-6">';
echo '<div class="product-details">'.wc_get_template( 'single-product/tabs/description.php' ).'</div>';
if( get_field('delivery_info') ) {
	echo '<div class=""delivery-info">'.get_field('delivery_info').'</div>';
}
echo '</div>';

echo '</div></div></section>';

// Videos
if( have_rows('videos') ) : while( have_rows('videos') ) : the_row(); ?>
<div class="embed-container">
	<?php the_sub_field('video_url'); ?>
</div>
<?php endwhile; endif; ?>

<?php if( get_field('extra_content') ) : ?>
<?php the_field('extra_content'); ?>
<?php endif; ?>

<?php
/**
 * Hook: woocommerce_after_single_product_summary.
 *
 * @hooked woocommerce_output_product_data_tabs - 10
 * @hooked woocommerce_upsell_display - 15
 * @hooked woocommerce_output_related_products - 20
 */
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
do_action( 'woocommerce_after_single_product_summary' );
?>

<?php // Modules
$acc_module = get_post(1254);
$acfClass->render_modules($acc_module);

$help_module = get_post(986);
$acfClass->render_modules($help_module);
?>

<?php do_action( 'woocommerce_after_single_product' ); ?>