<?php
/*
* WooCommerce helper functions for theme
*/

$blog_id = get_current_blog_id();

/********************************************
* WooCommerce housekeeping (helper) functions
*********************************************/
add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true' );

function mytheme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );


// Parent class in body
function woo_custom_taxonomy_in_body_class( $classes ){
    $custom_terms = get_the_terms(0, 'product_cat');
    if ($custom_terms) {
      foreach ($custom_terms as $custom_term) {

        // Check if the parent category exists:
        if( $custom_term->parent > 0 ) {
            // Get the parent product category:
            $parent = get_term( $custom_term->parent, 'product_cat' );
            // Append the parent class:
            if ( ! is_wp_error( $parent ) )
                $classes[] = 'product_parent_cat_' . $parent->slug;   
        }

        $classes[] = 'product_cat_' . $custom_term->slug;
      }
    }
    return $classes;
}
add_filter( 'body_class', 'woo_custom_taxonomy_in_body_class' );


// Breadcrumbs
function jk_woocommerce_breadcrumbs($defaults) {
    return array(
		'delimiter'   => ' &#47;&#47; ',
		'wrap_before' => '<p class="menu-breadcrumb" itemprop="breadcrumb">',
		'wrap_after'  => '</p>',
		'before'      => '',
		'after'       => '',
		'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
	);
}
add_filter( 'woocommerce_breadcrumb_defaults', 'jk_woocommerce_breadcrumbs' );


// Make Tags heirarchial
function my_woocommerce_taxonomy_args_product_tag( $array ) {
    $array['hierarchical'] = true;
    return $array;
};
add_filter( 'woocommerce_taxonomy_args_product_tag', 'my_woocommerce_taxonomy_args_product_tag', 10, 1 );


// Revisions on products
function wc_modify_product_post_type( $args ) {
     $args['supports'][] = 'revisions';

     return $args;
}
add_filter( 'woocommerce_register_post_type_product', 'wc_modify_product_post_type' );


// Change h4 tags to h2 tags in posts 
function filter_the_content_in_the_main_loop( $content ) {
 
    // Check if we're inside the main loop in a single post page.
    if ( is_single() ) {
        $replace = array(
            '<h4>' => '<h2 class="h4">',
            '</h4>' => '</h2>'
        );
        $content = str_replace(array_keys($replace), $replace, $content);
    }
 
    return $content;
}
add_filter( 'the_content', 'filter_the_content_in_the_main_loop' );



// Iframe gmaps shortcode
function add_google_maps_shortcode( $atts = array() ) {
    // set up default parameters
    extract(shortcode_atts(array(
     'url' => 'https://www.google.com/maps/d/u/0/embed?mid=1YaXJ6Vhpsl_MNIhXMRX00r8XZVgNeuCQ'
    ), $atts));
    
    return '<div class="aspect-ratio"><iframe width="640" height="480" src="'.$url.'"></iframe></div>';
}
add_shortcode('google_maps', 'add_google_maps_shortcode');


/********************************************
* WooCommerce US specific functions
*********************************************/

// remove prices and add to cart button on US site
if(function_exists('geoip_detect2_get_info_from_current_ip')) {
    $userInfo = geoip_detect2_get_info_from_current_ip();
    if ($userInfo->country->isoCode == 'US' && $blog_id == 1) {
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
        remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
    }
}


/********************************************
* WooCommerce display/render functions
*********************************************/

// Limited Edition category order by title filter
function custom_catalog_ordering_args( $args ) {
    if( 
        is_product_category('sir-edwin-lutyens') || 
        is_product_category('sir-john-soane') ||
        is_product_category('designer') 
    ) {
        $args['orderby'] = 'title';

        if( $args['orderby'] == 'title' ) {
            $args['order'] = 'ASC';
        }
        
        return $args;
    } else {
        return $args;
    }
}
add_filter( 'woocommerce_get_catalog_ordering_args', 'custom_catalog_ordering_args', 20, 1 );


// Display category image on category archive
function woocommerce_category_image() {
	$cat = get_queried_object();
	if( isset($cat->term_id) ) {
		$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
		$image = wp_get_attachment_url( $thumbnail_id );
		$cat_desc = term_description( $cat->term_id, 'product_cat' );

		if ( is_product_category() && !is_product_category(63) && $image ){
			echo '<img src="' . $image . '" alt="' . $cat->name . '" />';

			?> <div class="featured-copy">
				<h1 class="h2 lhs"><?php woocommerce_page_title(); ?></h1>
				<?php echo $cat_desc; ?>
			</div> <?php
		} else { ?>
	  
			<h1 class="h2 center"><?php woocommerce_page_title(); ?></h1>
			<?php echo $cat_desc; ?>
		<?php }
	}
}
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
add_action( 'woocommerce_archive_description', 'woocommerce_category_image', 15 );


// Display subcategory descriptions
function my_theme_woocommerce_taxonomy_archive_description($category) {
    $category_id = $category->term_id;
    echo category_description( $category_id );
}
add_action( 'woocommerce_after_subcategory_title', 'my_theme_woocommerce_taxonomy_archive_description');


// Inc. Vat on prices (UK only)
function woo_price_translatable_suffix( $html, $product ){
    if( $blog_id == 1 ) {
        if ( has_term( 'antique', 'product_cat' )  ) {
            $html .= ' ' . __( 'ex. VAT', 'woocommerce' );
        } elseif ( has_term( 'outdoor-living', 'product_cat' )  ) {
            $html .= ' ' . __( 'inc. VAT<span class="bundle-price">, delivery<br>and white glove set up service</span>', 'woocommerce' );
        } else {
            $html .= ' ' . __( 'inc. VAT', 'woocommerce' );
        }
    }
	
    return $html;
}
add_filter( 'woocommerce_get_price_suffix', 'woo_price_translatable_suffix', 99, 4 );


// Number of rows + products
function loop_columns() {
	if( is_product_category() ) {
		$term = get_queried_object();
		$columns =get_field('column_size', $term);
		if( $columns ) {
			return $columns;
		} else {
			return 3;
		}
	} else {
		return 3;
	}
}
add_filter('loop_shop_columns', 'loop_columns', 999);


// Description on archive
function excerpt($limit) {
	$excerpt = explode(' ', get_the_excerpt(), $limit);
	if (count($excerpt)>=$limit) {
		array_pop($excerpt);
		$excerpt = implode(" ",$excerpt).'...';
	} else {
		$excerpt = implode(" ",$excerpt);
	}	
	$excerpt = preg_replace('`[[^]]*]`','',$excerpt);
	return $excerpt;
}
function excerpt_in_product_archives() {
	if( get_the_excerpt() ) {
		echo '<p>'.excerpt(12).'</p>';
	}
}
add_action( 'woocommerce_after_shop_loop_item_title', 'excerpt_in_product_archives', 7 );


// Remove sorting
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );


// Show variation
function iconic_variation_is_visible( $bool, $variation_id, $product_id, $variation ) {

    if( empty( $variation->get_price() ) ) {
		return true;
	}

    return $bool;

}
add_filter( 'woocommerce_variation_is_visible', 'iconic_variation_is_visible', 10, 4 );


// Related products args (limit to two)
function jk_related_products_args( $args ) {
	$args['posts_per_page'] = 2;
	$args['columns'] = 2;
	return $args;
}

add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args', 20 );


// Random sort
function custom_woocommerce_get_catalog_ordering_args( $args ) {
  $orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
	if ( 'random_list' == $orderby_value ) {
		$args['orderby'] = 'rand';
		$args['order'] = '';
		$args['meta_key'] = '';
	}
	return $args;
}
add_filter( 'woocommerce_get_catalog_ordering_args', 'custom_woocommerce_get_catalog_ordering_args' );

function custom_woocommerce_catalog_orderby( $sortby ) {
	$sortby['random_list'] = 'Random';
	return $sortby;
}
add_filter( 'woocommerce_default_catalog_orderby_options', 'custom_woocommerce_catalog_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'custom_woocommerce_catalog_orderby' );


/********************************************
* WooCommerce User Roles (Trade) functions
*********************************************/

// Custom trade stockist role
add_role( 'trade', __( 'Trade Stockist' ), array('read' => true,) );

// Add trade capability to admins
function add_theme_caps() {
     global $wp_roles;
    // gets the administrator role
    $role = get_role( 'administrator' );
    $role->add_cap( 'trade' ); 
}
add_action( 'admin_init', 'add_theme_caps');

// Set/unset gateways/checkout type for user roles
function set_trade_gateways( $available_gateways ) {
	global $woocommerce;
	if ( current_user_can('trade') ) {
		unset( $available_gateways['epdq_checkout'] );
	} else {
		unset( $available_gateways['cod'] );
	}
	return $available_gateways;
}
add_filter( 'woocommerce_available_payment_gateways', 'set_trade_gateways' ); 

// Place order button text filter for trade users
function woo_custom_order_button_text() {
	if( current_user_can('trade') ) {
		return __( 'Place order', 'woocommerce' );
	} else {
		return __( 'Proceed to payment', 'woocommerce' );
	}
}
add_filter( 'woocommerce_order_button_text', 'woo_custom_order_button_text' );


// Trade prices notice
function trade_notice() {
    $user = wp_get_current_user();
    if ( in_array( 'trade', (array) $user->roles ) ) {
        echo '<p class="discount-notice">The prices quoted here are at full retail price & do not include any delivery charges, your discounted price will be confirmed on your order confirmation.</p>';
    }
}
add_action( 'woocommerce_before_cart_table', 'trade_notice' );
add_action( 'woocommerce_single_product_summary', 'trade_notice', 200 );

/********************************************
* WooCommerce checkout/order functions
*********************************************/

// Detect change order status and send email to Matt => matt@rd-it.com
function send_email_on_change( $order_id, $old_status, $new_status ){
    $order = new WC_Order($order_id);
    $order_id = trim(str_replace('#', '', $order->get_order_number()));
    
    if( $old_status == "cancelled" && ( $new_status == "pending" || $new_status == "processing" ) ) {
        $body = 'Order number #' . $order_id . ' was changed to ' . $new_status;
        
        wp_mail('matt@rd-it.com', 'Order Status Change', $body);
    }
}
add_action( 'woocommerce_order_status_changed', 'send_email_on_change', 99, 3 );


// limit billing city field
function custom_override_checkout_fields( $fields ) { 
    $fields['billing']['billing_city']['maxlength'] = 30;
    $fields['shipping']['shipping_city']['maxlength'] = 30;
    return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );


// Adding dealer code and referrer fields
function my_custom_checkout_field( $checkout ) {
   echo '<div id="dealer-code"><h3 class="h2 lhs no-after">' . __('Dealer Code') . '</h3>';
    woocommerce_form_field( 'dealer_code', array(
        'type'          => 'text',
        'class'         => array('my-field-class form-row-wide'),
        'label'         => __('If you have a dealer code enter it below'),
        'placeholder'   => __('Your code'),
        ), $checkout->get_value( 'dealer_code' ));

    echo '</div>';
	
    echo '<div id="referrer-name"><h3 class="h2 lhs no-after">' . __('Referrer Reference') . '</h3>';
    woocommerce_form_field( 'referrer_name', array(
        'type'          => 'text',
        'class'         => array('my-field-class form-row-wide'),
        'label'         => __('If someone referred you, please enter their customer number below'),
        'placeholder'   => __('Referrer'),
        ), $checkout->get_value( 'referrer_name' ));

    echo '</div>';
}
add_action( 'woocommerce_after_order_notes', 'my_custom_checkout_field' );

// Save custom input box data on checkout
function my_custom_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['dealer_code'] ) ) {
        update_post_meta( $order_id, 'Dealer Code', sanitize_text_field( $_POST['dealer_code'] ) );
    }
	
    if ( ! empty( $_POST['referrer_name'] ) ) {
        update_post_meta( $order_id, 'Referrer Name', sanitize_text_field( $_POST['referrer_name'] ) );
    }
}
add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );

// Display on order
function my_custom_checkout_field_display_admin_order_meta($order){
	$userid = $order->get_customer_id();
	$user_data = get_userdata( $userid );
	
	if( !empty($user_data) ) {
		$username = $user_data->user_login;
	}
	
	echo '<p><strong>'.__('Referrer name: ').':</strong> ' . get_post_meta( $order->get_id(), 'Referrer Name', true ) . '</p>';
	
    echo '<p><strong>'.__('Dealer code: ').':</strong> ' . get_post_meta( $order->get_id(), 'Dealer Code', true ) . '</p>';
	
	if( !empty($user_data) ) {
		echo '<p><strong>'.__('Username: ').':</strong> ' . $username . '</p>';
	}
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );


// Checkbox for delivery
function woo_add_checkout_privacy_policy() {
    woocommerce_form_field( 'privacy_policy', array(
       'type'          => 'checkbox',
       'class'         => array('form-row privacy'),
       'label_class'   => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
       'input_class'   => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
       'required'      => true,
       'label'         => 'I accept and have read the <a href="/your-delivery-explained/" target="_blank"><u>Deliveries Explained</U></a> document',
    )); 
}
add_action( 'woocommerce_review_order_before_submit', 'woo_add_checkout_privacy_policy', 9 );
   
function woo_not_approved_privacy() {
    if ( ! (int) isset( $_POST['privacy_policy'] ) ) {
        wc_add_notice( __( 'Please read the Deliveries Explained document ' ), 'error' );
    }
}
add_action( 'woocommerce_checkout_process', 'woo_not_approved_privacy' );


// Customer notes maxlength
function filter_checkout_fields( $fields ) {
	$fields['order']['order_comments']['maxlength'] = 200;
	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'filter_checkout_fields' );


?>