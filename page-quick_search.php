<?php
/*
* Template Name: Quick Search
*/
?>
<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="cf">

					<div id="main" class="cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
                            
                            <section class="entry-content cf row wrap woocommerce quick-search" itemprop="articleBody">
                                <div class="cf">
                                    <h1 class="h3">Quick search</h1>
                                    
                                    <?php /* Called in functions.php */ ?>
                                    <form method="get" class="quicksearch" id="quicksearch" action="<?php echo esc_url( home_url('/') ); ?>">
                                        <input type="text" id="searchInput" name="s" onKeyUp="fetchResults()" placeholder="Please enter the product SKU code">
                                    </form>
                                    <table id="datafetch" class="shop_table"></table>
                                </div>
                            </section>
							
							<section class="entry-content cf row wrap" itemprop="articleBody">
								<div class="cf"><?php the_content(); ?></div>
							</section>

							<?php // PRE-FOOTER ?>
							<?php if( !empty(get_field('pre_footer')) ) : ?>
								<section class="pre-footer row cf">
									<div class="max-width cf wrap">
										<?php if( !empty(get_field('pre_footer_media')) ) : ?>
											<div class="col-6"><?php the_field('pre_footer_media') ?></div>
											<div class="col-6"><?php the_field('pre_footer') ?></div>
										<?php else : ?>
											<div class="col-12"><?php the_field('pre_footer') ?></div>
										<?php endif; ?>
									</div>
								</section>
							<?php endif; ?>

						</article>

						<?php endwhile; endif; ?>

					</div>

				</div>

			</div>

<?php get_footer(); ?>