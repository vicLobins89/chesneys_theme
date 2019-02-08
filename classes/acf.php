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
			<section class="module module-<?php echo $post->post_name; ?>" <?php echo $moduleBackground; ?>>
				<div class="inner-module"><?php the_content(); ?></div>
			</section>
			<?php wp_reset_postdata();
		}
	}
	
	public function render_blog($post_cat = null) {
		$post_num = get_sub_field('post_count');
//		$all_cats = get_sub_field('all_categories');
		$post_category = get_sub_field('choose_category');
		if( $post_category || $post_cat ) {
			global $post;
			
			if (!isset($post_cat) || is_null($post_cat) ) {
				$args = array(
					'post_type' => 'post',
					'post_status' => 'publish',
					'cat' => $post_category,
					'posts_per_page' => $post_num,
				);
			} else {
				$args = array(
					'post_type' => 'post',
					'post_status' => 'publish',
					'category_name' => $post_cat,
					'posts_per_page' => 1,
				);
			}
			$arr_posts = new WP_Query( $args );

			if ( $arr_posts->have_posts() ) :
				while ( $arr_posts->have_posts() ) :
					$arr_posts->the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<?php
						if ( has_post_thumbnail() ) :
							the_post_thumbnail();
						endif;
						?>
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<div class="entry-content">
							<?php the_excerpt(); ?>
							<a href="<?php the_permalink(); ?>">Read More</a>
						</div>
					</article>
					<?php
				endwhile;
			endif;
			wp_reset_postdata();
		}
	}
	
	public function render_portfolio($folio_cat = null) {
		$post_num = get_sub_field('post_count');
		$folio_category = get_sub_field('choose_portfolio');
		if( $folio_category || $folio_cat ) {
			global $post;
			
			if (!isset($folio_cat) || is_null($folio_cat) ) {
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
			} else {
				$args2 = array(
					'post_type' => 'case_study',
					'post_status' => 'publish',
					'posts_per_page' => 1,
					'tax_query' => array(
						array(
							'taxonomy' => 'portfolio_cat',
							'field'    => 'slug',
							'terms'    => $folio_cat
						)
					)
				);
			}
			
			$arr_posts2 = new WP_Query( $args2 );

			if ( $arr_posts2->have_posts() ) :

				while ( $arr_posts2->have_posts() ) :
					$arr_posts2->the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<?php
						if ( has_post_thumbnail() ) :
							the_post_thumbnail();
						endif;
						?>
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<div class="entry-content">
							<?php the_excerpt(); ?>
							<a href="<?php the_permalink(); ?>">Read More</a>
						</div>
					</article>
					<?php
				endwhile;
			endif;
			wp_reset_postdata();
		}
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