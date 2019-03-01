<?php
get_header();
require_once('classes/acf.php');
$acfClass = new CustomACF();
?>

			<div id="content">

				<div id="inner-content" class="cf">

						<div id="main" class="cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">
							
							<?php get_sidebar('folio_header'); ?>
							
							<?php
							$categories = get_terms( array(
								'taxonomy' => 'portfolio_cat',
								'hide_empty' => true,
							) );
							if( isset($categories) ) {
								echo '<div class="cat-list">';
								foreach($categories as $category) {
								   echo '<a href="' . get_term_link($category->term_id) . '">' . $category->name . '</a>';
								}
								echo '</div>';
							}
							?>asda
							
							<section class="entry-content row post-content cf"><div class="cf"><div class="col-12">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf col-4' ); ?> role="article">
								
								<a class="thumb" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('folio-thumb'); ?></a>

								<?php printf( '<h3 class="h2 lhs sans">' . __('', 'bonestheme' ) . '%1$s</h3>' , get_the_term_list($post->ID, 'portfolio_cat', '', ', ', '') ); ?>

								<a class="copy-l" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
								
							</article>

							<?php endwhile; ?>

									<?php bones_page_navi(); ?>

							<?php else : ?>

									<article id="post-not-found" class="hentry cf">
											<header class="article-header">
												<h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
										</header>
											<section class="entry-content">
												<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
										</section>
									</article>

							<?php endif; ?>
								
							</div></div></section>
							
							<?php
							$help_module = get_post(986);
							$design_module = get_post(968);
							$acfClass->render_modules($help_module);
							$acfClass->render_modules($design_module);
							?>

						</div>

				</div>

			</div>


<?php get_footer(); ?>
