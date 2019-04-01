<?php
// LOAD CORE (do not remove)
require_once( 'library/rarehoney.php' );

// CUSTOMIZE THE WORDPRESS ADMIN (off by default)
 require_once( 'library/admin.php' );

/*********************
LAUNCH
*********************/

function rarehoney_init() {
	$blog_id = get_current_blog_id();
	
	//Allow editor style.
	add_editor_style( get_stylesheet_directory_uri() . '/library/css/editor-style.css' );

	// let's get language support going, if you need it
	load_theme_textdomain( 'bonestheme', get_template_directory() . '/library/translation' );

	// USE THIS TEMPLATE TO CREATE CUSTOM POST TYPES EASILY
	//require_once( 'library/custom-post-type.php' );
	require_once( 'library/blocks-post-type.php' );
	require_once( 'library/portfolio-post-type.php' );
	if ( $blog_id == 4 ) {
		require_once( 'library/artwork-post-type.php' );
	}

  // launching operation cleanup
  add_action( 'init', 'bones_head_cleanup' );
  // A better title
  add_filter( 'wp_title', 'rw_title', 10, 3 );
  // remove WP version from RSS
  add_filter( 'the_generator', 'bones_rss_version' );
  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'bones_remove_wp_widget_recent_comments_style', 1 );
  // clean up comment styles in the head
  add_action( 'wp_head', 'bones_remove_recent_comments_style', 1 );
  // clean up gallery output in wp
  add_filter( 'gallery_style', 'bones_gallery_style' );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'bones_scripts_and_styles', 999 );
  // ie conditional wrapper

  // launching this stuff after theme setup
  bones_theme_support();

  // adding sidebars to Wordpress (these are created in functions.php)
  add_action( 'widgets_init', 'bones_register_sidebars' );

  // cleaning up random code around images
  add_filter( 'the_content', 'bones_filter_ptags_on_images' );
  // cleaning up excerpt
  add_filter( 'excerpt_more', 'bones_excerpt_more' );

} /* end bones ahoy */

// let's get this party started
add_action( 'after_setup_theme', 'rarehoney_init' );

/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
	$content_width = 680;
}

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'bones-thumb-600', 600, 150, true );
add_image_size( 'bones-thumb-300', 300, 100, true );
add_image_size( 'folio-portrait', 602, 665, true );
add_image_size( 'folio-thumb', 410, 350, true );

/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 100 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 150 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>
*/

add_filter( 'image_size_names_choose', 'bones_custom_image_sizes' );

function bones_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'bones-thumb-600' => __('600px by 150px'),
        'bones-thumb-300' => __('300px by 100px'),
        'folio-portrait' => __('900px by 935px'),
        'folio-thumb' => __('410px by 350px'),
    ) );
}

/*
The function above adds the ability to use the dropdown menu to select
the new images sizes you have just created from within the media manager
when you add media to your content blocks. If you add more image sizes,
duplicate one of the lines in the array and name it according to your
new image size.
*/

/************* THEME CUSTOMIZE *********************/
function bones_theme_customizer($wp_customize) {
  // Uncomment the below lines to remove the default customize sections 
   $wp_customize->remove_section('title_tagline');
   $wp_customize->remove_section('colors');
   $wp_customize->remove_section('background_image');
   $wp_customize->remove_section('static_front_page');
   $wp_customize->remove_section('nav');

  // Uncomment the below lines to remove the default controls
  // $wp_customize->remove_control('blogdescription');
  
  // Uncomment the following to change the default section titles
  // $wp_customize->get_section('colors')->title = __( 'Theme Colors' );
  // $wp_customize->get_section('background_image')->title = __( 'Images' );
}

add_action( 'customize_register', 'bones_theme_customizer' );

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Sidebar 1', 'bonestheme' ),
		'description' => __( 'The first (primary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
	
	register_sidebar(array(
		'id' => 'nav_widget',
		'name' => __( 'Nav Widget', 'bonestheme' ),
		'description' => __( 'The widget area in the navigation.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s cf">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
	
	register_sidebar(array(
		'id' => 'news_header',
		'name' => __( 'News Header', 'bonestheme' ),
		'description' => __( 'The header area for the news page.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s cf">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="lhs">',
		'after_title' => '</h2>',
	));
	
	register_sidebar(array(
		'id' => 'folio_header',
		'name' => __( 'Portfolio Header', 'bonestheme' ),
		'description' => __( 'The header area for the portfolio page.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s cf">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="lhs">',
		'after_title' => '</h2>',
	));
} // don't remove this bracket!


/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
    <article  class="cf">
      <header class="comment-author vcard">
        <?php
        /*
          this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
          echo get_avatar($comment,$size='32',$default='<path_to_url>' );
        */
        ?>
        <?php // custom gravatar call ?>
        <?php
          // create variable
          $bgauthemail = get_comment_author_email();
        ?>
        <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=40" class="load-gravatar avatar avatar-48 photo" height="40" width="40" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
        <?php // end custom gravatar call ?>
        <?php printf(__( '<cite class="fn">%1$s</cite> %2$s', 'bonestheme' ), get_comment_author_link(), edit_comment_link(__( '(Edit)', 'bonestheme' ),'  ','') ) ?>
        <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', 'bonestheme' )); ?> </a></time>

      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert alert-info">
          <p><?php _e( 'Your comment is awaiting moderation.', 'bonestheme' ) ?></p>
        </div>
      <?php endif; ?>
      <section class="comment_content cf">
        <?php comment_text() ?>
      </section>
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </article>
  <?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!


/*
This is a modification of a function found in the
twentythirteen theme where we can declare some
external fonts. If you're using Google Fonts, you
can replace these fonts, change it in your scss files
and be up and running in seconds.
*/
function bones_fonts() {
  wp_enqueue_style('googleFonts', '//fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic');
}

add_action('wp_enqueue_scripts', 'bones_fonts');


// Page Slug Body Class
function add_slug_body_class( $classes ) {
	global $post;
	if( isset( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

// Page Excerpt
add_post_type_support( 'page', 'excerpt' );

// Is tree function
function is_tree($pid) {      // $pid = The ID of the page we're looking for pages underneath
	global $post;         // load details about this page
	if(is_page()&&($post->post_parent==$pid||is_page($pid))) 
               return true;   // we're at the page or at a sub page
	else 
               return false;  // we're elsewhere
};

// iframes
function fb_change_mce_options( $initArray ) {

    // Comma separated string od extendes tags.
    // Command separated string of extended elements.
    $ext = 'pre[id|name|class|style],iframe[align|longdesc|name|width|height|frameborder|scrolling|marginheight|marginwidth|src]';

    if ( isset( $initArray['extended_valid_elements'] ) ) {
        $ext = ',' . $ext;
    }
    $initArray['extended_valid_elements'] = $ext;

    // Maybe, set tiny parameter verify_html
    //$initArray['verify_html'] = false;

    return $initArray;
}
add_filter( 'tiny_mce_before_init', 'fb_change_mce_options' );

// WOOCOMMERCE
function mytheme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );

// US Restrict
if (function_exists('geoip_detect2_get_info_from_current_ip')) {
	$userInfo = geoip_detect2_get_info_from_current_ip();
	if ($userInfo->country->isoCode == 'US') {
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
	}
}

//STORE LOCATOR
function custom_templates( $templates ) {

    $templates[] = array (
        'id'   => 'custom',
        'name' => 'Custom template',
        'path' => get_stylesheet_directory() . '/' . 'wpsl-templates/custom.php',
    );

    return $templates;
}
add_filter( 'wpsl_templates', 'custom_templates' );

//WOOCOMMERCE Functions
require_once( 'woocommerce/checkout-functions.php' );

// Custom role
add_role( 'trade', __( 'Trade Stockist 35%' ), array('read' => true,) );
add_role( 'trade40', __( 'Trade Stockist 40%' ), array('read' => true,) );

// Gateways for user roles
function set_trade_gateways( $available_gateways ) {
	global $woocommerce;
	if( isset( $available_gateways['cod']) && !current_user_can('trade') ) {
		unset( $available_gateways['cod'] );
	} elseif ( isset( $available_gateways['epdq_checkout'] ) && current_user_can('trade') ) {
		unset( $available_gateways['epdq_checkout'] );
	} 
	return $available_gateways;
}
add_filter( 'woocommerce_available_payment_gateways', 'set_trade_gateways' ); 

// Place order button
function woo_custom_order_button_text() {
	if( !current_user_can('trade') || !current_user_can('trade40') ) {
		return __( 'Proceed to payment', 'woocommerce' );
	} else {
		return __( 'Place order', 'woocommerce' );
	}
}
add_filter( 'woocommerce_order_button_text', 'woo_custom_order_button_text' );

// Display category image on category archive
function woocommerce_category_image() {
	$cat = get_queried_object();
	if( isset($cat->term_id) ) {
		$thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
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

// Display subcat descriptions
function my_theme_woocommerce_taxonomy_archive_description($category) {
    $category_id = $category->term_id;
    echo category_description( $category_id );
}
add_action( 'woocommerce_after_subcategory_title', 'my_theme_woocommerce_taxonomy_archive_description');

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

// Inc. Vat on prices
function bbloomer_price_translatable_suffix( $html, $product ){
	if ( has_term( 'outdoor-living', 'product_cat' ) || has_term( 'heat-accessories', 'product_cat' ) || has_term( 'fuel', 'product_cat' )  ) {
	   $html .= ' ' . __( 'inc. VAT', 'bbloomer' );
	}
	
    return $html;
}
add_filter( 'woocommerce_get_price_suffix', 'bbloomer_price_translatable_suffix', 99, 4 );

// Number of rows + products
function loop_columns() {
	if ( is_product_category('gas-stoves')  ) {
		return 2;
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
		echo '<p>'.get_the_excerpt().'</p>';
	}
}
add_action( 'woocommerce_after_shop_loop_item_title', 'excerpt_in_product_archives', 7 );

function winwar_first_sentence( $string ) {
 
    $sentence = preg_split( '/(\.|!|\?)\s/', $string, 2, PREG_SPLIT_DELIM_CAPTURE );
	if( !isset($sentence['1']) ) {
		return $sentence['0'];
	} else {
		return $sentence['0'] . $sentence['1'];
	}
 
}
add_filter( 'get_the_excerpt', 'winwar_first_sentence', 10, 1 );

// Sorting
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

// Adding dealer field
function my_custom_checkout_field( $checkout ) {
    echo '<div id="dealer-code"><h3 class="h2 lhs no-after">' . __('Dealer code') . '</h3>';
    woocommerce_form_field( 'dealer_code', array(
        'type'          => 'text',
        'class'         => array('my-field-class form-row-wide'),
        'label'         => __('If you have a dealer code enter it below'),
        'placeholder'   => __('Your code'),
        ), $checkout->get_value( 'dealer_code' ));

    echo '</div>';
}
add_action( 'woocommerce_after_order_notes', 'my_custom_checkout_field' );

// Save
function my_custom_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['dealer_code'] ) ) {
        update_post_meta( $order_id, 'Dealer Code', sanitize_text_field( $_POST['dealer_code'] ) );
    }
}
add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );

// Display on order
function my_custom_checkout_field_display_admin_order_meta($order){
    echo '<p><strong>'.__('Dealer code: ').':</strong> ' . get_post_meta( $order->get_id(), 'Dealer Code', true ) . '</p>';
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );

// Revisions on products
add_filter( 'woocommerce_register_post_type_product', 'wc_modify_product_post_type' );

function wc_modify_product_post_type( $args ) {
     $args['supports'][] = 'revisions';

     return $args;
}

/* DON'T DELETE THIS CLOSING TAG */ ?>
