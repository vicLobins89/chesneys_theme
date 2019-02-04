<?php
class CustomACF {
    function page_rows() {
		if( have_rows('rows') ) : while( have_rows('rows') ) : the_row();
		
			// Modules
			if( have_rows('module') ) : while( have_rows('module') ) : the_row();
				$post_object = get_sub_field('module_block');
				if( $post_object ) :
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
				endif;
			endwhile; endif; // Modules
		
			// Blog Cat
			if( have_rows('select_blog_feed') ) : while( have_rows('select_blog_feed') ) : the_row();
				$category = get_sub_field('choose_category');
				if( $category ) {
					global $post;
					$args = array(
						'post_type' => 'post',
						'post_status' => 'publish',
						'category' => $category,
						'posts_per_page' => 5,
					);
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
								<header class="entry-header">
									<h1 class="entry-title"><?php the_title(); ?></h1>
								</header>
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
			endwhile; endif; // Blog
			
			// Custom Content
			//print_r(get_sub_field('custom_content'));
			if( have_rows('custom_content') ) : while( have_rows('custom_content') ) : the_row();
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
			endwhile; endif; // Content
		endwhile; endif; // Row
	}
}

$acfClass = new CustomACF();
?>