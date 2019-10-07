<?php
/* AJAX QUICK QUOTE SEARCH FUNCTION */

// the ajax function
function data_fetch(){
    
    $query_products = new WP_Query(
        array(
            'posts_per_page' => 2,
            'post_type' => 'product',
            's' => $_POST['keyword'],
            'post_status' => 'publish',
            'tax_query' => array( array(
                'taxonomy'         => 'product_cat',
                'field'            => 'slug',
                'terms'            => array( 'wood-burning-stoves', 'multi-fuel' )
            )),
        ) );
    
    $query_variations = new WP_Query(
        array(
            'posts_per_page' => 18,
            'post_type' => 'product_variation',
            's' => $_POST['keyword'],
            'post_status' => 'publish',
        ) );
    
    $the_query = new WP_Query();
    $the_query->posts = array_merge( $query_variations->posts, $query_products->posts );
    $the_query->post_count = $query_variations->post_count + $query_products->post_count;
    
    if( $the_query->have_posts() ) : ?>
        
        <thead>
            <tr>
                <th class="product-name">Product</th>
                <th>Description</th>
                <th>Image</th>
                <th>Quantity</th>
                <th class="cart-button"></th>
            </tr>
        </thead>
        <tbody>
        <?php
        while( $the_query->have_posts() ): $the_query->the_post();

        $product = wc_get_product( get_the_ID() );
        $description = strip_tags( $product->get_variation_description() );
        $description_array = explode(' ', $description);
        $max_words = 15;
        if( count($description_array) > $max_words && $max_words > 0 ) {
            $description = implode(' ',array_slice($description_array, 0, $max_words)).'...';
        }
        $atts = $product->get_attributes();
        ?>
      
            <tr>
                <td class="product-name">
                    <a href="<?php echo esc_url( post_permalink() ); ?>"><?php the_title();?></a>
                </td>
                
                <td>
                    <?php echo $description; ?>
                </td>
                
                <td class="product-thumbnail" width="80"><?php echo $product->get_image(); ?></td>
                
                <td width="80"><input type="number" id="qty" name="qty" value="1" min="1" max="99"></td>
                
                <td>
                    <?php
                    // Check if variation allows for blank options and divert to select options
                    if( !in_array('', $atts) ) {
                        woocommerce_template_loop_add_to_cart();
                    } else {
                        echo '<a class="button product_type_variable add_to_cart_button" href="' . esc_url( post_permalink() ) . '">Select options</a>';
                    }
                    ?>
                </td>
            </tr>

        <?php endwhile;
        ?></tbody><?php
    
		wp_reset_postdata();  
        
	else: 
		echo '<h3>No Results Found</h3>';
    endif;
    
    die();
}
add_action('wp_ajax_data_fetch' , 'data_fetch');
add_action('wp_ajax_nopriv_data_fetch','data_fetch');

// add the ajax fetch js
function ajax_fetch() {
?>
<script type="text/javascript">
function fetchResults(){
	var keyword = jQuery('#searchInput').val();
	if(keyword == ""){
		jQuery('#datafetch').html("");
	} else {
		jQuery.ajax({
			url: '<?php echo admin_url('admin-ajax.php'); ?>',
			type: 'post',
			data: { action: 'data_fetch', keyword: keyword  },
			success: function(data) {
				jQuery('#datafetch').html( data );
			}
		});
	}
    
}
jQuery(document).ready(function($){
    $('#quicksearch').submit(function(e){
        e.preventDefault();
        fetchResults();
    });
    
    $(document).ajaxComplete(function(){
        $('#datafetch #qty').on('keyup', function(){
            var quantity = $(this).val(),
                the_href = $(this).closest('tr').find('.add_to_cart_button').attr('href');
            
            $(this).closest('tr').find('.add_to_cart_button').attr('data-quantity', quantity);
        });
    });
});
</script>
<?php
}
add_action( 'wp_footer', 'ajax_fetch' );
?>