<?php 
global $wpsl_settings, $wpsl;

$output         = $this->get_custom_css(); 
$autoload_class = ( !$wpsl_settings['autoload'] ) ? 'class="wpsl-not-loaded"' : '';

$output .= '<div id="wpsl-wrap">' . "\r\n";
$output .= "\t" . '<div class="wpsl-search wpsl-clearfix ' . $this->get_css_classes() . '">' . "\r\n";
$output .= "\t\t" . '<div id="wpsl-search-wrap">' . "\r\n";
$output .= "\t\t\t" . '<form autocomplete="off">' . "\r\n";
$output .= "\t\t\t" . '<div class="wpsl-input">' . "\r\n";
$output .= "\t\t\t\t" . '<div><label for="wpsl-search-input">' . esc_html( $wpsl->i18n->get_translation( 'search_label', __( 'Your location', 'wpsl' ) ) ) . '</label></div>' . "\r\n";
$output .= "\t\t\t\t" . '<input id="wpsl-search-input" type="text" value="' . apply_filters( 'wpsl_search_input', '' ) . '" name="wpsl-search-input" placeholder="" aria-required="true" />' . "\r\n";
$output .= "\t\t\t" . '</div>' . "\r\n";

if ( $wpsl_settings['radius_dropdown'] || $wpsl_settings['results_dropdown']  ) {
    $output .= "\t\t\t" . '<div class="wpsl-select-wrap">' . "\r\n";

    if ( $wpsl_settings['radius_dropdown'] ) {
        $output .= "\t\t\t\t" . '<div id="wpsl-radius">' . "\r\n";
        $output .= "\t\t\t\t\t" . '<label for="wpsl-radius-dropdown">' . esc_html( $wpsl->i18n->get_translation( 'radius_label', __( 'Search radius', 'wpsl' ) ) ) . '</label>' . "\r\n";
        $output .= "\t\t\t\t\t" . '<select id="wpsl-radius-dropdown" class="wpsl-dropdown" name="wpsl-radius">' . "\r\n";
        $output .= "\t\t\t\t\t\t" . $this->get_dropdown_list( 'search_radius' ) . "\r\n";
        $output .= "\t\t\t\t\t" . '</select>' . "\r\n";
        $output .= "\t\t\t\t" . '</div>' . "\r\n";
    }

    if ( $wpsl_settings['results_dropdown'] ) {
        $output .= "\t\t\t\t" . '<div id="wpsl-results">' . "\r\n";
        $output .= "\t\t\t\t\t" . '<label for="wpsl-results-dropdown">' . esc_html( $wpsl->i18n->get_translation( 'results_label', __( 'Results', 'wpsl' ) ) ) . '</label>' . "\r\n";
        $output .= "\t\t\t\t\t" . '<select id="wpsl-results-dropdown" class="wpsl-dropdown" name="wpsl-results">' . "\r\n";
        $output .= "\t\t\t\t\t\t" . $this->get_dropdown_list( 'max_results' ) . "\r\n";
        $output .= "\t\t\t\t\t" . '</select>' . "\r\n";
        $output .= "\t\t\t\t" . '</div>' . "\r\n";
    } 

    $output .= "\t\t\t" . '</div>' . "\r\n";
}

if ( $this->use_category_filter() ) {
    $output .= $this->create_category_filter();
}

$output .= "\t\t\t\t" . '<div class="wpsl-search-btn-wrap"><input id="wpsl-search-btn" type="submit" value="' . esc_attr( $wpsl->i18n->get_translation( 'search_btn_label', __( 'Search', 'wpsl' ) ) ) . '"></div>' . "\r\n";

$output .= "\t\t" . '</form>' . "\r\n";
$output .= "\t\t" . '</div>' . "\r\n";
$output .= "\t" . '</div>' . "\r\n";
    
$output .= "\t" . '<div id="wpsl-gmap" class="wpsl-gmap-canvas"></div>' . "\r\n";

//$output .= "\t" . '<div id="wpsl-result-list">' . "\r\n";
//$output .= "\t\t" . '<div id="wpsl-stores" '. $autoload_class .'>' . "\r\n";
//$output .= "\t\t\t" . '<ul></ul>' . "\r\n";
//$output .= "\t\t" . '</div>' . "\r\n";
//$output .= "\t\t" . '<div id="wpsl-direction-details">' . "\r\n";
//$output .= "\t\t\t" . '<ul></ul>' . "\r\n";
//$output .= "\t\t" . '</div>' . "\r\n";
//$output .= "\t" . '</div>' . "\r\n";

$output .= '<div id="wpsl-result-list-full" class="wpsl-full-list">' . "\r\n";

$output .= '<div id="wpsl-stores-full" '. $autoload_class .'>' . "\r\n";
$output .= '<ul>' . "\r\n";

$query = new WP_Query(array(
    'post_type' => 'wpsl_stores',
    'post_status' => 'publish',
	'posts_per_page' => -1
));


while ($query->have_posts()) : $query->the_post();
    $post_id = get_the_ID();
	
	$output .= '<li data-store-id="'.$post_id.'">
	
		<div class="wpsl-store-location">
			<p>
				<strong><a target="_blank" href="'.get_post_meta( $post_id, 'wpsl_url', true ).'">'.get_the_title().'</a></strong>
				
				<span class="wpsl-street">'.get_post_meta( $post_id, 'wpsl_address', true ).'</span>
				
				<span class="wpsl-street">'.get_post_meta( $post_id, 'wpsl_address2', true ).'</span>
				
				<span>'.get_post_meta( $post_id, 'wpsl_city', true ).'  '.get_post_meta( $post_id, 'wpsl_zip', true ).'</span>
				<span class="wpsl-country">'.get_post_meta( $post_id, 'wpsl_country', true ).'</span>
			</p>
			<p class="wpsl-contact-details">
			
			<span><strong>Phone</strong>: <a href="tel:'.get_post_meta( $post_id, 'wpsl_phone', true ).'">'.get_post_meta( $post_id, 'wpsl_phone', true ).'</a></span>
			
			
			
			<span><strong>Email</strong>: <a href="mailto:'.get_post_meta( $post_id, 'wpsl_email', true ).'">'.get_post_meta( $post_id, 'wpsl_email', true ).'</a></span>
			
			</p>
			<p><a class="wpsl-store-details" href="#">More info</a></p>
		</div>
	</li>' . "\r\n";

endwhile;

wp_reset_query();

$output .= '</ul>' . "\r\n";
$output .= '</div>' . "\r\n";

$output .= '</div>' . "\r\n";


if ( $wpsl_settings['show_credits'] ) { 
    $output .= "\t" . '<div class="wpsl-provided-by">'. sprintf( __( "Search provided by %sWP Store Locator%s", "wpsl" ), "<a target='_blank' href='https://wpstorelocator.co'>", "</a>" ) .'</div>' . "\r\n";
}

$output .= '</div>' . "\r\n";

return $output;