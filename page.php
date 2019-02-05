<?php
get_header();
require_once('classes/acf.php');
$acfClass = new CustomACF();
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
							<?php if( get_the_content() ) :
								$layout = get_field('editor_layout');
								if( $layout === 'hide' ) {
									echo '<section class="row entry-content cf" style="display: none;">';
									echo '<div class="cf">';
								} else if( $layout === 'wrap' ) {
									echo '<section class="row entry-content wrap cf" itemprop="articleBody">';
									echo '<div class="cf">';
								} else if( $layout === 'full' ) {
									echo '<section class="row entry-content full cf" itemprop="articleBody">';
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
							<?php endif; ?>
							
							<?php // ACF FIELDS ?>
							<?php $acfClass->page_rows(); ?>

							</article>

							<?php endwhile; endif; ?>

						</div>

				</div>

			</div>

<?php get_footer(); ?>