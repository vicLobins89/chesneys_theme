<?php
class CustomACF {
    function image_links() {
		if( have_rows('image_links') ) : ?>
		<section class="row entry-content image-links-wrapper cf"
				 <?php echo (get_field('img_links_background') ? ' style="background:'.get_field('img_links_background').'"' : ''); ?>>
			<div class="cf">
				<?php
					if( get_field('img_links_title') ) {
						echo '<h2>'.get_field('img_links_title').'</h2>';
					}
				?>
				<div class="thumbs-inner">
				<?php while( have_rows('image_links') ): the_row(); ?>
					<div class="col-3"<?php echo ' style="width: calc(100% / '.get_field('img_links_col').')"' ; ?>>
						<a target="_blank" href="<?php the_sub_field('link'); ?>" class="image-links">
							<?php 
							$image = get_sub_field('image');
							$size = 'image-links';

							if( $image ) {
								echo wp_get_attachment_image( $image, $size, false, 'alt="'.get_sub_field('text').'"' );
							}

							?>
							<p><?php the_sub_field('text'); ?></p>
						</a>
					</div>
				<?php endwhile; ?>
				</div>
			</div>
		</section>
		<?php endif;
	}
	
	function page_rows() {
		if( have_rows('rows') ) :
//		$rowNum = 0;
		while( have_rows('rows') ) : the_row();
		
			if( have_rows('content') ) :
			$rowNum = 0;
			while( have_rows('content') ) : the_row();
			
		$rowNum ++;
		$layout = get_sub_field('layout');
		$padding = get_sub_field('padding');
		$bgColour = get_sub_field('bg_colour');
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
			array_push($addStyles, "background: $bgColour");
		}
		
		if( get_sub_field('border_bottom') ) {
			array_push($addClasses, "border-bottom");
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
		
		foreach($columns as $key => $column) { ?>
			<div class="col-12"><?php the_sub_field('col_1'); ?></div>
		<?php }
		?>

		<?php if( get_sub_field('col_1') ) : ?>

			<div class="col-12"><?php the_sub_field('col_1'); ?></div>

		<?php elseif( get_sub_field('col_2') ) : ?>

			<div class="cf col-6">
				<?php the_sub_field('col_1'); ?>
			</div>

			<div class="cf col-6">
				<?php the_sub_field('col_2'); ?>
			</div>

		<?php elseif( get_sub_field('col_3') ) : ?>

			<div class="col-4">
				<?php the_sub_field('col_1'); ?>
			</div>

			<div class="col-4">
				<?php the_sub_field('col_2'); ?>
			</div>

			<div class="col-4">
				<?php the_sub_field('col_3'); ?>
			</div>

		<?php elseif( get_sub_field('col_4') ) : ?>

			<div class="col-3">
				<?php the_sub_field('col_1'); ?>
			</div>

			<div class="col-3">
				<?php the_sub_field('col_2'); ?>
			</div>

			<div class="col-3">
				<?php the_sub_field('col_3'); ?>
			</div>

			<div class="col-3">
				<?php the_sub_field('col_4'); ?>
			</div>

		<?php endif; ?>

		</section>
		<?php endwhile; endif;
		endwhile; endif;
	}
	
	function pre_footer() {
		if( get_field('pre_footer') ) : ?>
		<section class="pre-footer row cf">
			<div class="max-width cf wrap">
				<?php if( get_field('pre_footer_media') ) : ?>
					<div class="col-6"><?php the_field('pre_footer_media') ?></div>
					<div class="col-6"><?php the_field('pre_footer') ?></div>
				<?php else : ?>
					<div class="col-12"><?php the_field('pre_footer') ?></div>
				<?php endif; ?>
			</div>
		</section>
		<?php endif;
	}
}

$class = new CustomACF();
?>