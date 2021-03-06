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
		
		<meta name="google-site-verification" content="OZlSgpuvVs_Skx9k5WWYm66d8atMFgT_HgbIGuEt05M" />
		<meta name="msvalidate.01" content="50DF6B110CE7268B54ACF448C9917DE2" />
		
		<!-- Hotjar Tracking Code for www.chesneys.co.uk -->
		<script>
			(function(h,o,t,j,a,r){
				h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
				h._hjSettings={hjid:388474,hjsv:6};
				a=o.getElementsByTagName('head')[0];
				r=o.createElement('script');r.async=1;
				r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
				a.appendChild(r);
			})(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
		</script>

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
		
		<?php
		wp_head();
        //
		$options = get_option('rh_settings');
		$blog_id = get_current_blog_id();
		$blog_class = ( $blog_id == 1 ) ? 'uk-site' : 'us-site';
        $term = get_queried_object();
        global $wp;
        
        
        /* */
        if( is_product_category() ) {
            $altURL = get_field('alt_url', $term);
        } else {
            $altURL = get_field('alt_url', get_the_ID());
        }
        
        /* */
        $current_url = home_url( add_query_arg($_GET, $wp->request) );
        if( $blog_id == 1 ) {
            $new_url = str_replace('.co.uk', '.com', $current_url);
            
            //
            if( 
                $altURL == 'NA' ||
                is_product_category( array( 
                    'stoves',
                    'stove-spares',
                    'fires',
                    'fuel-accessories',
                    'cast-iron'
                ) ) ||
                term_is_ancestor_of(56, $term->term_id, 'product_cat') || //stoves
                term_is_ancestor_of(52, $term->term_id, 'product_cat') || //fires
                term_is_ancestor_of(67, $term->term_id, 'product_cat') || //stove spares
                term_is_ancestor_of(68, $term->term_id, 'product_cat') || //fuel-accessories
                term_is_ancestor_of(33, $term->term_id, 'product_cat') || //cast-iron
                has_term( 'fuel-accessories', 'product_cat' ) ||
                has_term( 'cast-iron', 'product_cat' ) ||
                has_term( 'fires', 'product_cat' ) ||
                has_term( 'stoves', 'product_cat' ) ||
                has_term( 'outdoor-living', 'product_cat' ) ||
                has_term( 'stove-spares', 'product_cat' ) ) {
                echo '';
            } elseif( $altURL && $altURL !== 'NA' ) {
                echo '<link rel="alternate" href="'.$altURL.'" hreflang="en-us" />';
            } else {
                echo '<link rel="alternate" href="'.$new_url.'" hreflang="en" />';
                echo '<link rel="alternate" href="'.$new_url.'" hreflang="en-us" />';
            }
            
            echo '<link rel="alternate" href="'.$current_url.'" hreflang="en-gb" />';
        } else {
            //
            $new_url = str_replace('.com', '.co.uk', $current_url);
            echo '<link rel="alternate" href="'.$current_url.'" hreflang="en" />';
            echo '<link rel="alternate" href="'.$current_url.'" hreflang="en-us" />';
            
            if( $altURL == 'NA' ) {
                echo '';
            } elseif( $altURL && $altURL !== 'NA' ) {
                echo '<link rel="alternate" href="'.$altURL.'" hreflang="en-gb" />';
            } else {
                echo '<link rel="alternate" href="'.$new_url.'" hreflang="en-gb" />';
            }
        }
		?>

	</head>

	<body <?php body_class('wordpress '.$blog_class); ?> itemscope itemtype="http://schema.org/WebPage">
		
		<?php
		// GTM
		if ( function_exists( 'gtm4wp_the_gtm_tag' ) ) { gtm4wp_the_gtm_tag(); }
		?>

		<div id="container">

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
				if ( $blog_id == 1 ) {
					echo '<div class="rhs-links">';
					echo '<a href="'.home_url('/my-account').'" class="menu-item login" title="Dealer login">Dealer login</a>';
					echo '<a href="'.wc_get_cart_url().'" class="menu-item basket" title="View your shopping cart">';
					if( WC()->cart->get_cart_contents_count() !== 0 ) {
						echo '<span>'.sprintf ( _n( '%d', '%d', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ).'</span>';
					}
					echo 'Basket</a>';
					echo '</div>';
				}
				?>
				</nav>

				<div id="inner-header" class="cf">
					
					<div class="searchbox"><?php echo do_shortcode('[wpdreams_ajaxsearchlite]'); ?></div>
					
					<?php
					if($options['logo']){
						echo '<a class="logo" href="'. home_url() .'"><img class="logo-img" src="'. $options['logo'] .'" alt="'. get_bloginfo('name') .'" /></a>';
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
						'theme_location' => 'main-nav'
					));
					get_sidebar('nav_widget');
					?>
					</nav>

				</div>

			</header>
