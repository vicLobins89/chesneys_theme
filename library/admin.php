<?php
/*
This file handles the admin area and functions.
Make changes to the dashboard.


	- removing some default WordPress dashboard widgets
	- an example custom dashboard widget
	- adding custom login css
	- changing text in footer of admin


*/

/************* DASHBOARD WIDGETS *****************/

// disable default dashboard widgets
function disable_default_dashboard_widgets() {
	global $wp_meta_boxes;
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);    // Right Now Widget
//	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);        // Activity Widget
//	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // Comments Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);  // Incoming Links Widget
//	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);         // Plugins Widget

	// unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);    // Quick Press Widget
//	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);     // Recent Drafts Widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);           //
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);         //

	// remove plugin dashboard boxes
	unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);           // Yoast's SEO Plugin Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);        // Gravity Forms Plugin Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);   // bbPress Plugin Widget
}

// RSS Dashboard Widget
function bones_rss_dashboard_widget() {
	if ( function_exists( 'fetch_feed' ) ) {
		// include_once( ABSPATH . WPINC . '/feed.php' );               // include the required file
		$feed = fetch_feed( 'https://www.macrotraveller.com/feed/' );        // specify the source feed
		if (is_wp_error($feed)) {
			$limit = 0;
			$items = 0;
		} else {
			$limit = $feed->get_item_quantity(4);                        // specify number of items
			$items = $feed->get_items(0, $limit);                        // create an array of items
		}
	}
	if ($limit == 0) echo '<div>The RSS Feed is either empty or unavailable.</div>';   // fallback message
	else foreach ($items as $item) { ?>

	<h4 style="margin-bottom: 0;">
		<a href="<?php echo $item->get_permalink(); ?>" title="<?php echo mysql2date( __( 'j F Y @ g:i a', 'bonestheme' ), $item->get_date( 'Y-m-d H:i:s' ) ); ?>" target="_blank">
			<?php echo $item->get_title(); ?>
		</a>
	</h4>
	<p style="margin-top: 0.5em;">
		<?php echo substr($item->get_description(), 0, 200); ?>
	</p>
	<?php }
}

// calling all custom dashboard widgets
function bones_custom_dashboard_widgets() {
	wp_add_dashboard_widget( 'bones_rss_dashboard_widget', __( 'Macrotraveller', 'bonestheme' ), 'bones_rss_dashboard_widget' );
}


// removing the dashboard widgets
add_action( 'wp_dashboard_setup', 'disable_default_dashboard_widgets' );
// adding any custom widgets
add_action( 'wp_dashboard_setup', 'bones_custom_dashboard_widgets' );


/************* CUSTOM LOGIN PAGE *****************/

//Updated to proper 'enqueue' method
function bones_login_css() {
	wp_enqueue_style( 'bones_login_css', get_template_directory_uri() . '/library/css/login.css', false );
}

// changing the logo link from wordpress.org to your site
function bones_login_url() {  return home_url(); }

// changing the alt text on the logo to show your site name
function bones_login_title() { return get_option( 'blogname' ); }

// calling it only on the login page
add_action( 'login_enqueue_scripts', 'bones_login_css', 10 );
add_filter( 'login_headerurl', 'bones_login_url' );
add_filter( 'login_headertitle', 'bones_login_title' );


/************* CUSTOMIZE ADMIN *******************/

function bones_admin_css() {
	wp_enqueue_style( 'bones_admin_css', get_template_directory_uri() . '/library/css/admin.css', false );
}

add_action( 'admin_enqueue_scripts', 'bones_admin_css', 10 );

// Custom Backend Footer
function rh_custom_admin_footer() {
	_e( '<span id="footer-thankyou">© Copyright Honey Creative ' . date('Y') . '</span>', 'bonestheme' );
}

// adding it to the admin area
add_filter( 'admin_footer_text', 'rh_custom_admin_footer' );


// Admin Functions
function add_theme_menu() {
	add_menu_page('Honey Theme Settings', 'Theme Settings', 'manage_options', 'honey-theme-settings', 'theme_settings_page', null, 60);
}

function enqueue_media_uploader() {
    wp_enqueue_media();
}

function display_theme_panel_fields() {
	register_setting('rh-settings-page', 'rh_settings');
	
	add_settings_section(
		'rh-settings-page_section',
		'All Settings',
		null,
		'rh-settings-page'
	);
	
	add_settings_field(
		'logo',
		'Logo',
		'logo_display',
		'rh-settings-page',
		'rh-settings-page_section'
	);
	
	/*add_settings_field(
		'logo_alt',
		'Alternative Logo',
		'logo_display_alt',
		'rh-settings-page',
		'rh-settings-page_section'
	);
	
	add_settings_field(
		'twitter_url',
		'Twitter Profile URL',
		'display_twitter_el',
		'rh-settings-page',
		'rh-settings-page_section'
	);
	
	add_settings_field(
		'facebook_url',
		'Facebook Profile URL',
		'display_facebook_el',
		'rh-settings-page',
		'rh-settings-page_section'
	);
	
	add_settings_field(
		'instagram_url',
		'Instagram Profile URL',
		'display_instagram_el',
		'rh-settings-page',
		'rh-settings-page_section'
	);
	
	add_settings_field(
		'youtube_url',
		'YouTube Profile URL',
		'display_youtube_el',
		'rh-settings-page',
		'rh-settings-page_section'
	);
	
	add_settings_field(
		'linkedin_url',
		'LinkedIn Profile URL',
		'display_linkedin_el',
		'rh-settings-page',
		'rh-settings-page_section'
	);
	
	add_settings_field(
		'case_studies_switch',
		'Enable News Feed',
		'display_case_studies_el',
		'rh-settings-page',
		'rh-settings-page_section'
	);
	
	add_settings_field(
		'max_width_switch',
		'Enable Max Width',
		'display_max_width_el',
		'rh-settings-page',
		'rh-settings-page_section'
	);
	
	add_settings_field(
		'copyright_txt',
		'Copyright Text',
		'display_copyright_el',
		'rh-settings-page',
		'rh-settings-page_section'
	);*/
}

function logo_display() {
	$options = get_option('rh_settings');
	?>
	<input type="text" name="rh_settings[logo]" id="image_url" class="regular-text" value="<?php echo $options['logo']; ?>">
    <input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Upload Image"><br><br>
    <img  class="current-image" src="<?php echo $options['logo']; ?>" style="max-width: 120px;" />
    <script type="text/javascript">
		jQuery(document).ready(function($){
			$('#upload-btn').click(function(e){
				e.preventDefault();
				var image = wp.media({
					title: 'Upload Image',
					multiple: false
				}).open()
				.on('select', function(e){
					var uploaded_image = image.state().get('selection').first();
					var image_url = uploaded_image.toJSON().url;
					$('#image_url').val(image_url);
				});
			});
		});
	</script>
	<?php
}

function logo_display_alt() {
	$options = get_option('rh_settings');
	?>
	<input type="text" name="rh_settings[logo_alt]" id="image_url_alt" class="regular-text" value="<?php echo $options['logo_alt']; ?>">
    <input type="button" name="upload-btn_alt" id="upload-btn_alt" class="button-secondary" value="Upload Image"><br><br>
    <img  class="current-image" src="<?php echo $options['logo_alt']; ?>" style="max-width: 80px;" />
    <script type="text/javascript">
		jQuery(document).ready(function($){
			$('#upload-btn_alt').click(function(e){
				e.preventDefault();
				var image = wp.media({
					title: 'Upload Image',
					multiple: false
				}).open()
				.on('select', function(e){
					var uploaded_image = image.state().get('selection').first();
					var image_url = uploaded_image.toJSON().url;
					$('#image_url_alt').val(image_url);
				});
			});
		});
	</script>
	<?php
}

function display_twitter_el() {
	$options = get_option('rh_settings');
	?>
	<input type='text' name='rh_settings[twitter_url]' value='<?php echo $options['twitter_url']; ?>'>
	<?php
}

function display_facebook_el() {
	$options = get_option('rh_settings');
	?>
	<input type='text' name='rh_settings[facebook_url]' value='<?php echo $options['facebook_url']; ?>'>
	<?php
}

function display_instagram_el() {
	$options = get_option('rh_settings');
	?>
	<input type='text' name='rh_settings[instagram_url]' value='<?php echo $options['instagram_url']; ?>'>
	<?php
}

function display_youtube_el() {
	$options = get_option('rh_settings');
	?>
	<input type='text' name='rh_settings[youtube_url]' value='<?php echo $options['youtube_url']; ?>'>
	<?php
}

function display_linkedin_el() {
	$options = get_option('rh_settings');
	?>
	<input type='text' name='rh_settings[linkedin_url]' value='<?php echo $options['linkedin_url']; ?>'>
	<?php
}

function display_copyright_el() {
	$options = get_option('rh_settings');
	?>
	<input type='text' name='rh_settings[copyright_txt]' value='<?php echo $options['copyright_txt']; ?>'>
	<?php
}

function display_max_width_el() {
	$options = get_option('rh_settings');
	?>
	<label class="switch">
		<input type='checkbox' name='rh_settings[max_width_switch]' <?php checked( $options['max_width_switch'], 1 ); ?> value='1'>
		<span class="slider round"></span>
	</label>
	<?php
}

function display_case_studies_el() {
	$options = get_option('rh_settings');
	?>
	<label class="switch">
		<input type='checkbox' name='rh_settings[case_studies_switch]' <?php checked( $options['case_studies_switch'], 1 ); ?> value='1'>
		<span class="slider round"></span>
	</label>
	<?php
}

function theme_settings_page() {
	?>
	<div class="wrap">
		<h1>Honey Theme Settings</h1>
		<form method="post" action="options.php" enctype="multipart/form-data">
			<?php
				settings_fields('rh-settings-page');
				do_settings_sections('rh-settings-page');
				submit_button();
			?>
		</form>
	</div>
	<?php
}

add_action("admin_enqueue_scripts", "enqueue_media_uploader");
add_action('admin_menu', 'add_theme_menu');
add_action('admin_init', 'display_theme_panel_fields');

?>
