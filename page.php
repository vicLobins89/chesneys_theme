<?php
get_header();
require_once('classes/acf.php');
$acfClass = new CustomACF();
?>

			<div id="content">

				<div id="inner-content" class="cf">

						<div id="main" class="cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							
							<?php
							$no_breadcrumb = array( 'Basket', 'Checkout', 'my-account', 'Contact Us' );
							if( !is_front_page() && !is_tree(91) && !is_tree(93) && !is_page($no_breadcrumb) ) {
								$menu_breadcrumb = new Menu_Breadcrumb( 'main-nav' );
								$menu_breadcrumb->render( ' &sol;&sol; ', '<p class="menu-breadcrumb"><a href="'.home_url().'">Homepage</a> // ', '</p>' );
							} elseif( !is_front_page() && !is_page($no_breadcrumb) ) {
								$menu_breadcrumb = new Menu_Breadcrumb( 'socket-nav' );
								$menu_breadcrumb->render( ' &sol;&sol; ', '<p class="menu-breadcrumb"><a href="'.home_url().'">Homepage</a> // ', '</p>' );
							}
							?>

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
								
							<?php // MAIN CONTENT ?>
							<?php if( has_post_thumbnail() && is_page() ) : ?>
								<section class="row entry-content cf top featured" itemprop="articleBody">
									<div class="cf">
									<?php the_post_thumbnail('full'); ?>
									
									<div class="featured-copy">
										<h1 class="h2 lhs"><?php the_title(); ?></h1>
										<?php the_content(); ?>
									</div>
									</div>
								</section>

							<?php else :
								$layout = get_field('editor_layout');
								if( $layout === 'hide' ) {
									echo '<section class="row entry-content cf top" style="display: none;">';
									echo '<div class="cf">';
								} else if( $layout === 'wrap' ) {
									echo '<section class="row entry-content wrap cf top" itemprop="articleBody">';
									echo '<div class="cf">';
								} else if( $layout === 'full' ) {
									echo '<section class="row entry-content full cf top" itemprop="articleBody">';
									echo '<div class="cf">';
								} else {
									echo '<section class="row entry-content cf top" itemprop="articleBody">';
									echo '<div class="cf">';
								}
								?>
									<div class="col-12">
										<?php the_content(); ?>
									</div>
								</div></section>
							<?php endif; // MAIN CONTENT ?>
							
							<?php // ACF FIELDS ?>
							<?php $acfClass->page_rows(); ?>
					
							<?php if( have_rows('brochures') ) : ?>
							<section class="row entry-content cf">
							<div class="brochures-wrapper cf">
								<div class="col-4">
									<?php echo do_shortcode('[contact-form-7 id="2607" title="Brochure request"]'); ?>
								</div>

								<div class="col-8 cf">
								<?php while( have_rows('brochures') ) : the_row(); ?>
									<div class="col-4">
										<img src="<?php echo get_sub_field('cover'); ?>" alt="<?php echo get_sub_field('title'); ?>">
										<div class="text">
											<h4><?php echo get_sub_field('title'); ?></h4>
											<p class="desc"><?php echo get_sub_field('description'); ?></p>
											<input class="js-brochure-input" name="<?php echo get_sub_field('title'); ?>" type="checkbox" value="<?php echo get_sub_field('pdf'); ?>">
											<label for="<?php echo get_sub_field('title'); ?>"> Select</label>
										</div>
									</div>
								<?php endwhile; ?>
								</div>
							</div>
							</section>
							<?php endif; ?>

							</article>

							<?php endwhile; endif; ?>

						</div>

				</div>

			</div>

<?php get_footer(); ?>