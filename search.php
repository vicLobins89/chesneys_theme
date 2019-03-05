<?php get_header(); ?>

			<div id="content" class="row entry-content push">

				<div id="inner-content" class="cf">
					
					<h1 class="archive-title h2"><span><?php _e( 'Search Results for:', 'bonestheme' ); ?></span> <?php echo esc_attr(get_search_query()); ?></h1>
					
					<aside class="col-3 cf">
						<?php echo do_shortcode( '[searchandfilter fields="search,post_types" types=",select" headings="Search,Type,Products" submit_label="Filter"]' ); ?>
					</aside>

					<div id="main" class="col-9 cf" role="main">

						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class('cf col-4'); ?> role="article">

								<section class="search-content">
									<?php if( has_post_thumbnail() ) : ?>
										<a class="thumb" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('folio-thumb'); ?></a>
									<?php endif; ?>
									
									<h3 class="search-title entry-title flair lhs sans"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
								</section>

							</article>

						<?php endwhile; ?>

							<?php else : ?>

									<article id="post-not-found" class="hentry cf">
										<header class="article-header">
											<h1><?php _e( 'Sorry, No Results.', 'bonestheme' ); ?></h1>
										</header>
										<section class="entry-content">
											<p><?php _e( 'Try your search again.', 'bonestheme' ); ?></p>
										</section>
										<footer class="article-footer">
												<p><?php _e( 'This is the error message in the search.php template.', 'bonestheme' ); ?></p>
										</footer>
									</article>

							<?php endif; ?>

						</div>

					</div>
				
				<?php bones_page_navi(); ?>

			</div>

<?php get_footer(); ?>
