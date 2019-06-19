<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cat_check = false;
foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
    $product = $cart_item['data'];
    if ( has_term( 'outdoor-living', 'product_cat', $product->id ) ) {
        $cat_check = true;
        break;
    }
}

if ( $cross_sells || $cat_check ) : ?>

	<div class="cross-sells">

		<h2 class="lhs"><?php _e( 'You may also be interested in&hellip;', 'woocommerce' ) ?></h2>

		<?php woocommerce_product_loop_start(); ?>
        
            <?php
                if( $cat_check ) {
                    $starter_object = get_post( 8415 );
                    setup_postdata( $GLOBALS['post'] =& $starter_object );
                    wc_get_template_part( 'content', 'product' );
                }
             ?>

			<?php foreach ( $cross_sells as $cross_sell ) : ?>

				<?php
				 	$post_object = get_post( $cross_sell->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object );

					wc_get_template_part( 'content', 'product' ); ?>

			<?php endforeach; ?>

		<?php woocommerce_product_loop_end(); ?>

	</div>

<?php endif;

wp_reset_postdata();
