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
						if ( function_exists('yoast_breadcrumb') ) {
							yoast_breadcrumb( '<p class="menu-breadcrumb">','</p>' );
						}
						?>

						<article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article" itemscope itemprop="blogPost" itemtype="http://schema.org/BlogPosting">
							
						<?php // MAIN CONTENT ?>
						<?php if( has_post_thumbnail() ) : ?>
							<section class="row entry-content cf top featured" itemprop="articleBody">
								<div class="cf">
								<?php the_post_thumbnail('full'); ?>

								<div class="featured-copy">
									<h1 class="h2 lhs"><?php the_title(); ?></h1>
								</div>
								</div>
							</section>
						<?php else : ?>
							<section class="row entry-content cf top" itemprop="articleBody"><div class="cf">
								<div class="col-12">
									<h1 class="h2" style="text-align: center"><?php the_title(); ?></h1>
								</div>
							</div></section>
						<?php endif; // MAIN CONTENT ?>

						<?php
						if( !empty( get_the_content() ) ) {
							echo '<section class="entry-content row cf post-content" itemprop="articleBody">';
							the_content(); 
							echo '</section>';
						}
						// end article section ?>

						<?php // ACF FIELDS ?>
						<?php $acfClass->page_rows(); ?>

					  	</article>
						<?php // end article ?>

						<?php endwhile; ?>

						<?php else : ?>

							<article id="post-not-found" class="hentry cf">
									<header class="article-header">
										<h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
									</header>
									<section class="entry-content">
										<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
									</section>
									<footer class="article-footer">
											<p><?php _e( 'This is the error message in the single.php template.', 'bonestheme' ); ?></p>
									</footer>
							</article>

						<?php endif; ?>

					</div>

				</div>

			</div>

<?php get_footer(); ?>
