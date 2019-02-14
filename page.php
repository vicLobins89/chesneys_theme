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
								
							<?php // MAIN CONTENT ?>
							<?php if( has_post_thumbnail() && is_page() ) : ?>
								<section class="row entry-content cf top" itemprop="articleBody">
									<div class="cf">
									<?php the_post_thumbnail('full'); ?>
									<?php the_title(); ?>
									<?php the_content(); ?>
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

							</article>

							<?php endwhile; endif; ?>

						</div>

				</div>

			</div>

<?php get_footer(); ?>