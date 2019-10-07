<?php
// LOAD CORE (do not remove)
require_once( 'library/rarehoney.php' );

// CUSTOMIZE THE WORDPRESS ADMIN (off by default)
require_once( 'library/admin.php' );

/*********************
LAUNCH
*********************/

function rarehoney_init() {
	//Allow editor style.
	add_editor_style( get_stylesheet_directory_uri() . '/library/css/editor-style.css' );

	// let's get language support going, if you need it
	load_theme_textdomain( 'bonestheme', get_template_directory() . '/library/translation' );

	// USE THIS TEMPLATE TO CREATE CUSTOM POST TYPES EASILY
	//require_once( 'library/custom-post-type.php' );
	require_once( 'library/blocks-post-type.php' );
	require_once( 'library/portfolio-post-type.php' );

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



/************* CURL *************/
// Force certificate validation. You need a valid certificate in the site, self generated certificates are NOT VALID. 
add_filter( 'https_local_ssl_verify', '__return_true' );

// Setting a custom timeout value for cURL. Using a high value for priority to ensure the function runs after any other added to the same action hook.
add_action('http_api_curl', 'sar_custom_curl_timeout', 9999, 1);
function sar_custom_curl_timeout( $handle ){
	curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, 30 ); // 30 seconds. Too much for production, only for testing.
	curl_setopt( $handle, CURLOPT_TIMEOUT, 30 ); // 30 seconds. Too much for production, only for testing.
}

// Setting custom timeout for the HTTP request
add_filter( 'http_request_timeout', 'sar_custom_http_request_timeout', 9999 );
function sar_custom_http_request_timeout( $timeout_value ) {
	return 30; // 30 seconds. Too much for production, only for testing.
}

// Setting custom timeout in HTTP request args
add_filter('http_request_args', 'sar_custom_http_request_args', 9999, 1);
function sar_custom_http_request_args( $r ){
	$r['timeout'] = 30; // 30 seconds. Too much for production, only for testing.
	return $r;
}


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

add_filter( 'image_size_names_choose', 'bones_custom_image_sizes' );

function bones_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'bones-thumb-600' => __('600px by 150px'),
        'bones-thumb-300' => __('300px by 100px'),
        'folio-portrait' => __('900px by 935px'),
        'folio-thumb' => __('410px by 350px'),
    ) );
}

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
		'id' => 'filter',
		'name' => __( 'Filter', 'bonestheme' ),
		'description' => __( 'Filter.', 'bonestheme' ),
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
	
	register_sidebar(array(
		'id' => 'geo_popup',
		'name' => __( 'Geolocation Popup', 'bonestheme' ),
		'description' => __( 'The popup if user is visiting from other country.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s cf">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="white">',
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
	if(is_page()&&($post->post_parent==$pid||is_page($pid))) {
        return true;   // we're at the page or at a sub page
    } else {
        return false;  // we're elsewhere
    }
};

// allow  iframes in editor
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


// WOOCOMMERCE Functions require
require( 'woocommerce/woocommerce-functions.php' );


// WOOCOMMERCE Checkout Functions require
require( 'woocommerce/checkout-functions.php' );


// Ajax search functions for quick search
require( 'woocommerce/ajax-search.php' )


/* DON'T DELETE THIS CLOSING TAG */ ?>