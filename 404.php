<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

					<div id="main" class="cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

						<article id="post-not-found" class="hentry cf">

							<section class="entry-content">
								
								<h1 class="h2 flair"><?php _e( 'Page Not Found', 'bonestheme' ); ?></h1>

								<p><?php _e( 'The page you were looking for was not found, but maybe try looking again!', 'bonestheme' ); ?></p>
								
								<div class="searchbox"><?php echo do_shortcode('[wpdreams_ajaxsearchlite]'); ?></div>
								
								<p>Or go back to the <a href="<?php echo home_url(); ?>">home page</a>.</p>

							</section>

						</article>

					</div>

				</div>

			</div>

<?php get_footer(); ?>
