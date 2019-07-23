<?php
/*
 Template Name: Drawings Page
*/

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
							<?php
							$the_content = get_the_content();
							if( has_post_thumbnail() && is_page() ) :
							?>
								<section class="row entry-content cf top featured" itemprop="articleBody">
									<div class="cf">
									<?php the_post_thumbnail('full'); ?>
									
									<div class="featured-copy">
										<h1 class="h2 lhs"><?php the_title(); ?></h1>
										<?php the_content(); ?>
									</div>
									</div>
								</section>

							<?php elseif( !empty($the_content) ) :
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
                    
                            <?php
                            $upload_dir = wp_upload_dir(); 
                            $logo_dir = ( $upload_dir['basedir'] . '/drawings/' );
                            $drawings = glob($logo_dir . "*.pdf");
                            
                            echo '<section class="row entry-content wrap cf" itemprop="articleBody">';
                            echo '<div class="cf">';
                            echo '<ul class="drawings-list">';
                            foreach($drawings as $drawing) {
                                $url = str_replace( ' ', '%20', basename($drawing) );
                                $url = $upload_dir['baseurl']."/drawings/$url";
                                
                                $name = str_replace( '.pdf', '', basename($drawing) );
                                
                                echo '<li><a target="_blank" href="'.$url.'">'.$name.'</a></li>';
                            }
                            echo '</ul></div></section>';
                            ?>

							</article>

							<?php endwhile; endif; ?>

						</div>

				</div>

			</div>

<?php get_footer(); ?>