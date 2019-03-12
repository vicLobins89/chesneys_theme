<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

	<head>
		<meta charset="utf-8">

		<?php // force Internet Explorer to use the latest rendering engine available ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<title><?php wp_title('|'); ?></title>

		<?php // mobile meta (hooray!) ?>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<?php // icons & favicons ?>
		<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/library/images/apple-touch-icon.png">
		<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
		<!--[if IE]>
			<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
		<![endif]-->
		<?php // or, set /favicon.ico for IE10 win ?>
		<meta name="msapplication-TileColor" content="#f01d4f">
		<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/library/images/win8-tile-icon.png">
		<meta name="theme-color" content="#121212">

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
		
		<?php wp_head(); ?>
		<?php $options = get_option('rh_settings'); ?>

	</head>

	<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

		<div id="container">
			
			<?php //echo do_shortcode('[geoip_detect2_show_if country="US"] TEXT [/geoip_detect2_show_if]'); ?>
			<?php //echo do_shortcode('[geoip_detect2_show_if country="GB"] TEXT [/geoip_detect2_show_if]'); ?>

			<header class="header" role="banner" itemscope itemtype="http://schema.org/WPHeader">
				
				<nav role="navigation" class="socket" itemscope itemtype="http://schema.org/SiteNavigationElement">
				<?php
				wp_nav_menu(array(
					'container' => false,
					'container_class' => 'menu cf',
					'menu' => __( 'Socket Menu', 'bonestheme' ),
					'menu_class' => 'nav socket-nav cf',
					'theme_location' => 'socket-nav'
				));
				echo '<div class="rhs-links">';
				echo '<a href="'.wc_get_cart_url().'" class="menu-item basket" title="View your shopping cart">';
				if( WC()->cart->get_cart_contents_count() !== 0 ) {
					echo '<span>'.sprintf ( _n( '%d', '%d', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ).'</span>';
				}
				echo 'Basket</a>';
				echo '</div>';
				?>
				</nav>

				<div id="inner-header" class="cf">
					
					<div class="searchbox"><?php echo do_shortcode('[wpdreams_ajaxsearchlite]'); ?></div>
					
					<?php
					if($options['logo']){
						echo '<a class="logo" href="'. home_url() .'"><img src="'. $options['logo'] .'" alt="'. get_bloginfo('name') .'" /></a>';
					} else {
						echo '<p class="logo" class="h1" itemscope itemtype="http://schema.org/Organization"><a href="'. home_url() .'">'. get_bloginfo('name') .'</a></p>';
					}
					?>
					
					<a class="menu-button" title="Main Menu">Menu</a>
					<nav class="main-nav" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
					<?php
					wp_nav_menu(array(
						'container' => false,
						'container_class' => 'menu cf',
						'menu' => __( 'The Main Menu', 'bonestheme' ),
						'menu_class' => 'nav primary-nav cf',
						'theme_location' => 'main-nav',
						'after' => '<span class="separator"> | </span>'
					));
					get_sidebar('nav_widget');
					?>
					</nav>

				</div>

			</header>
