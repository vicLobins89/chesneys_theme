<?php
class CustomACF {
	public function render_modules($module_block = null) {
		$post_object = (!isset($module_block) || is_null($module_block)) ? get_sub_field('module_block') : $module_block;
		if( $post_object ) {
			// override $post
			global $post;
			$post = $post_object;
			setup_postdata( $post );
			if( has_post_thumbnail() ) {
				$moduleBackground = ' style="background: url(';
				$moduleBackground .= get_the_post_thumbnail_url(get_the_ID(),'full');
				$moduleBackground .= ') center/cover no-repeat"';
			}
			?>
			<section class="row entry-content cf module module-<?php echo $post->post_name; ?>">
				<div class="cf" <?php echo (isset($moduleBackground)) ? $moduleBackground : ''; ?>>
					<div class="inner-module">
						<h5 class="h2"><?php the_title(); ?></h5>
						<?php the_content(); ?>
						<?php if( have_rows('team_members') ) : ?>
						<div class="team-members cf">
							<?php while( have_rows('team_members') ) : the_row(); ?>
							<div class="col-6">
								<div class="cf"><img src="<?php echo get_sub_field('photo'); ?>" alt="<?php echo get_sub_field('name'); ?>">
								<div class="text">
									<h6 class="h2"><?php echo get_sub_field('role'); ?></h6>
									<p class="name"><?php echo get_sub_field('name'); ?></p>
									<p><?php echo get_sub_field('bio'); ?></p>
								</div></div>
							</div>
							<?php endwhile; ?>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</section>
			<?php wp_reset_postdata();
		}
	}
	
	public function render_blog($blog_feed = null) {
		$all_cats = ($blog_feed['all_categories']) ? $blog_feed['all_categories'] : get_sub_field('all_categories');
		$post_category = ($blog_feed['choose_category']) ? $blog_feed['choose_category'] : get_sub_field('choose_category');
		$post_num = ($blog_feed['post_count']) ? $blog_feed['post_count'] : get_sub_field('post_count');
		
		global $post;
		if( $all_cats == true ) {
			$args = array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'posts_per_page' => $post_num,
			);
		} else {
			$args = array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'cat' => $post_category,
				'posts_per_page' => $post_num,
			);
		}
		$arr_posts = new WP_Query( $args );

		if ( $arr_posts->have_posts() ) : $row = 1; ?>
			<section class="blog-wrapper cf">
			<?php while ( $arr_posts->have_posts() ) :
				$arr_posts->the_post();
				if( $row == 1 ) :
					if ( has_post_thumbnail() ) {
						$thumb_bg = ' style="background: url(';
						$thumb_bg .= get_the_post_thumbnail_url(get_the_ID(),'full');
						$thumb_bg .= ') center/cover no-repeat"';
					}
					?>
					<div id="post-<?php the_ID(); ?>" class="row entry-content cf blog-module" <?php echo $thumb_bg; ?>>
						<div class="cf">
							<h2>News Post</h2>
							<h3 class="entry-title"><em><?php the_title(); ?></em></h3>
							<?php the_excerpt(); ?>
							<a href="<?php the_permalink(); ?>" class="primary-btn">Read More</a>
						</div>
					</div>
					<?php
				else : 
					echo ( $row == 2 ) ? '<h3 class="h2 more">EXPLORE MORE POSTS</h3>' : ''; ?>
					<div id="post-<?php the_ID(); ?>" class="col-4 cf post-item-<?php echo $row-1; ?>">
						<a href="<?php the_permalink(); ?>" class="thumb"><?php the_post_thumbnail('folio-thumb'); ?></a>
						<div class="text">
							<h4 class="h2">News Post</h4>
							<h5 class="entry-title p"><?php the_title(); ?></h5>
							<a href="<?php the_permalink(); ?>" class="primary-btn alt">Read More</a>
						</div>
					</div>
				<?php endif;
			$row ++; endwhile; ?>
			</section>
		<?php endif;
		wp_reset_postdata();
	}
	
	public function render_portfolio($portfolio_feed = null) {
		$all_cats = ($portfolio_feed['all_categories']) ? $portfolio_feed['all_categories'] : get_sub_field('all_categories');
		$folio_category = ($portfolio_feed['choose_portfolio']) ? $portfolio_feed['choose_portfolio'] : get_sub_field('choose_portfolio');
		$post_num = ($portfolio_feed['post_count']) ? $portfolio_feed['post_count'] : get_sub_field('post_count');
		
		global $post;
		if( $all_cats == true ) {
			$args2 = array(
				'post_type' => 'case_study',
				'post_status' => 'publish',
				'posts_per_page' => $post_num
			);
		} else {
			$args2 = array(
				'post_type' => 'case_study',
				'post_status' => 'publish',
				'posts_per_page' => $post_num,
				'tax_query' => array(
					array(
						'taxonomy' => 'portfolio_cat',
						'field'    => 'term_taxonomy_id',
						'terms'    => $folio_category
					)
				)
			);
		}

		$arr_posts2 = new WP_Query( $args2 );

		if ( $arr_posts2->have_posts() ) : $row = 1; ?>
			<section class="row entry-content cf folio-module">
			<div class="cf">
				<h2 class="title">Portfolio</h2>
				<p class="description">Learn more about some of our most recently completed projects</p>
			<?php while ( $arr_posts2->have_posts() ) :
				$arr_posts2->the_post(); ?>
				<article id="post-<?php the_ID(); ?>" class="folio-item folio-item-<?php echo $row; echo ($row == 1) ? ' col-6' : ' col-3' ; ?>">
					<?php
					if( has_post_thumbnail() ) {
						echo '<a href="'.get_the_permalink().'" class="thumb">';
						($row == 1) ? the_post_thumbnail('folio-portrait') : the_post_thumbnail('folio-thumb') ;
						echo '</a>';
					} ?>
					
					<div class="text">
						<?php $terms = get_the_terms( $post->ID , 'portfolio_cat' );
						if ( $terms != null ){
							echo '<p class="categories">';
							foreach( $terms as $term ) {
								echo $term->name . ' ';
								unset($term);
							}
							echo '</p>';
						}
						?>
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<?php ($row == 1) ? the_excerpt() : '' ; ?>
					</div>
				</article>
			<?php $row ++; endwhile; ?>
			</div>
			</section>
		<?php endif;
		wp_reset_postdata();
	}
	
	public function render_content() {
		$layout = get_sub_field('layout');
		$padding = get_sub_field('padding');
		$bgColour = get_sub_field('bg_colour');
		$bgImage = get_sub_field('bg_image');
		$customClass = get_sub_field('class');
		$customID = '';
		$addClasses = array();
		$addStyles = array();
		$styles;
		
		if( get_sub_field('row_id') ) {
			$customID = ' id="'.get_sub_field('row_id').'"';
		}

		if( $padding ) {
			if( $padding['padding_top'] ) { array_push($addStyles, "padding-top: $padding[padding_top];"); }
			if( $padding['padding_right'] ) { array_push($addStyles, "padding-right: $padding[padding_right];"); }
			if( $padding['padding_bottom'] ) { array_push($addStyles, "padding-bottom: $padding[padding_bottom];"); }
			if( $padding['padding_left'] ) { array_push($addStyles, "padding-left: $padding[padding_left];"); }
		}

		if( $customClass ) {
			array_push($addClasses, $customClass);
		}

		if( get_sub_field('bg_colour') ) {
			array_push($addClasses, "bg-colour");
			array_push($addStyles, "background-color: $bgColour;");
		}

		if( get_sub_field('bg_image') ) {
			array_push($addStyles, "background-image: url('$bgImage');");
			array_push($addStyles, "background-repeat: no-repeat;");
			array_push($addStyles, "background-size: cover;");
			array_push($addStyles, "background-position: center;");
		}

		if( isset($addClasses) || isset($addStyles) ) {
			$styles = ' style="';
			$styles .= implode(" ", $addStyles);
			$styles .= '"';
		}

		if( $layout === 'hide' ) {
			echo '<section class="row entry-content cf" style="display: none;">';
			echo '<div class="cf">';
		} else if( $layout === 'wrap' ) {
			echo '<section'.$customID.' class="row entry-content wrap cf '.implode(" ", $addClasses).'"'.$styles.'>';
			echo '<div class="cf">';
		} else if( $layout === 'full' ) {
			echo '<section'.$customID.' class="row entry-content full cf '.implode(" ", $addClasses).'"'.$styles.'>';
			echo '<div class="cf">';
		} else {
			echo '<section'.$customID.' class="row entry-content cf '.implode(" ", $addClasses).'"'.$styles.'>';
			echo '<div class="cf">';
		}

		$columns = array(
			get_sub_field('col_1'),
			get_sub_field('col_2'),
			get_sub_field('col_3'),
			get_sub_field('col_4')
		);

		$colNum = count(array_filter($columns));
		foreach($columns as $key => $column) {	
			if( $column != '' ) {
				print '<div class="col-'.(12/$colNum).'">' . $column . '</div>';
			}
		}

		echo '</div></section>';
	}
	
    function page_rows() {
		if( have_rows('rows') ) : while( have_rows('rows') ) : the_row();
			// Custom Content
			if( have_rows('custom_content') ) : while( have_rows('custom_content') ) : the_row();
				$this->render_content();
			endwhile; endif; // Content
		
			// Modules
			if( have_rows('module') ) : while( have_rows('module') ) : the_row();
				$this->render_modules();
			endwhile; endif; // Modules
		
			// Blog
			if( have_rows('blog_feed') ) : while( have_rows('blog_feed') ) : the_row();
				$this->render_blog();
			endwhile; endif; // Blog
		
			// Case Studies
			if( have_rows('portfolio_feed') ) : while( have_rows('portfolio_feed') ) : the_row();
				$this->render_portfolio();
			endwhile; endif; // Case Studies
		endwhile; endif; // Row
	}
}

$acfClass = new CustomACF();
?>