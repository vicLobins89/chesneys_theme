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
			<section class="row entry-content cf module module-<?php echo $post->post_name; ?>" <?php echo $moduleBackground; ?>>
				<div class="inner-module">
					<h2 class="lhs"><?php the_title(); ?></h2>
					<?php the_content(); ?>
				</div>
			</section>
			<?php wp_reset_postdata();
		}
	}
	
	public function render_blog($post_cat = null) {
		$post_num = get_sub_field('post_count');
		$all_cats = get_sub_field('all_categories');
		$post_category = ($all_cats) ? 'all' : get_sub_field('choose_category');
		
		global $post;
		if( $post_category == 'all' ) {
			$args = array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'posts_per_page' => $post_num,
			);
		} elseif( isset($post_cat) || !is_null($post_cat) ) {
			$args = array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'category_name' => $post_cat,
				'posts_per_page' => 1,
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
							<h2>Blog Post</h2>
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
							<h4 class="h2">Blog Post</h4>
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
	
	public function render_portfolio($folio_cat = null) {
		$post_num = get_sub_field('post_count');
		$all_cats = get_sub_field('all_categories');
		$folio_category = ($all_cats) ? 'all' : get_sub_field('choose_portfolio');
		
		global $post;
		if( $folio_category == 'all' ) {
			$args2 = array(
				'post_type' => 'case_study',
				'post_status' => 'publish',
				'posts_per_page' => $post_num
			);
		} elseif( isset($folio_cat) || !is_null($folio_cat) ) {
			$args2 = array(
				'post_type' => 'case_study',
				'post_status' => 'publish',
				'posts_per_page' => 5,
				'tax_query' => array(
					array(
						'taxonomy' => 'portfolio_cat',
						'field'    => 'slug',
						'terms'    => $folio_cat
					)
				)
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
						<h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
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
		$addClasses = array();
		$addStyles = array();
		$styles;

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
			echo '<section class="row entry-content wrap cf '.implode(" ", $addClasses).'"'.$styles.'>';
			echo '<div class="cf">';
		} else if( $layout === 'full' ) {
			echo '<section class="row entry-content full cf '.implode(" ", $addClasses).'"'.$styles.'>';
			echo '<div class="cf">';
		} else {
			echo '<section class="row entry-content cf '.implode(" ", $addClasses).'"'.$styles.'>';
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