<?php
get_header();
require_once('classes/acf.php');
$class = new CustomACF();
?>

			<div id="content">

				<div id="inner-content" class="cf">

						<div id="main" class="cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
								
							<?php // HERO AREA ?>
							<?php if( has_post_thumbnail() && is_page() ) : ?>
							<div class="featured-image">
								<?php the_post_thumbnail('full'); ?>
							</div>
							<?php endif; ?>

							<?php // MAIN CONTENT ?>
							<?php if( get_the_content() ) : ?>
								<section class="row entry-content cf top" itemprop="articleBody">
									<div class="cf"><div class="col-12">
										<?php the_content(); ?>
									</div></div>
								</section>
							<?php endif; ?>
							
							<?php // ACF FIELDS ?>
							<?php
								$acfClass->page_rows();
							?>

							</article>

							<?php endwhile; endif; ?>

						</div>

				</div>

			</div>

<?php get_footer(); ?>