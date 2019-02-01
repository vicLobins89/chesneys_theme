<?php
class CustomACF {
    function page_rows() {
		if( have_rows('rows') ) :
		while( have_rows('rows') ) : the_row();
		
			echo '<pre>';
				print_r( get_field('module')  );
			echo '</pre>';
			die;
		
			$post_object = get_field('module');

			if( $post_object ): 

				// override $post
				$post = $post_object;
				setup_postdata( $post ); 

				?>
				<div>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				</div>
				<?php wp_reset_postdata(); 
			endif;
		
			if( have_rows('content') ) :
			$rowNum = 0;
			while( have_rows('content') ) : the_row();
			
			$rowNum ++;
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

			array_push($addClasses, "row-$rowNum");

			if( get_sub_field('bg_colour') ) {
				array_push($addClasses, "bg-colour");
				array_push($addClasses, "bg-colour$rowNum");
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
			?>

			</section>
		<?php endwhile; endif;
		endwhile; endif;
	}
}

$class = new CustomACF();
?>