<?php
/* AJAX QUICK QUOTE SEARCH FUNCTION */

// Utility function to get the parent variable product IDs for a any term of a taxonomy
function get_variation_parent_ids_from_term( $term, $taxonomy, $type ){
    global $wpdb;

    return $wpdb->get_col( "
        SELECT DISTINCT p.ID
        FROM {$wpdb->prefix}posts as p
        INNER JOIN {$wpdb->prefix}posts as p2 ON p2.post_parent = p.ID
        INNER JOIN {$wpdb->prefix}term_relationships as tr ON p.ID = tr.object_id
        INNER JOIN {$wpdb->prefix}term_taxonomy as tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN {$wpdb->prefix}terms as t ON tt.term_id = t.term_id
        WHERE p.post_type = 'product'
        AND p.post_status = 'publish'
        AND p2.post_status = 'publish'
        AND tt.taxonomy = '$taxonomy'
        AND t.$type = '$term[0]' OR t.$type = '$term[1]'
    " );
}

// the ajax function
function data_fetch(){
    
    // Settings
    $cat_name = array( 'Wood Burning Stoves', 'Multi-Fuel Stoves' ); // Product category name
    
    $the_query = new WP_Query( array(
        'post_type'       => 'product_variation',
        'post_status'     => 'publish',
        'posts_per_page'  => -1,
        'post_parent__in' => get_variation_parent_ids_from_term( $cat_name, 'product_cat', 'name' ), // Variations
        's' => $_POST['keyword'],
    ) );
    
    if( $the_query->have_posts() ) : ?>
        
        <thead>
            <tr>
                <td class="product_count">Product count: <?php echo $the_query->post_count; ?></td>
            </tr>
            <tr>
                <th class="product-name">Product</th>
                <th>Fuel type</th>
                <th>Image</th>
                <th>Quantity</th>
                <th class="cart-button"></th>
            </tr>
        </thead>
        <tbody>
        <?php
        while( $the_query->have_posts() ): $the_query->the_post();

        $product = wc_get_product( get_the_ID() );
        $atts = $product->get_attributes();
        $terms = get_the_terms( $product->get_parent_id(), 'product_cat' );
        ?>
      
        <tr>
            <td class="product-name">
                <a href="<?php echo esc_url( post_permalink() ); ?>"><?php the_title();?></a>
            </td>

            <td width="200">
                <?php
                foreach( $terms as $term ) {
                    if( $term->name !== 'Stoves' ) echo $term->name . ' ';
                }
                ?>
            </td>

            <td class="product-thumbnail" width="80"><?php echo $product->get_image(); ?></td>

            <td width="80"><input type="number" id="qty" class="custom_qty" onchange="Data(this)" name="qty" value="1" min="1" max="99"></td>

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

        <?php endwhile; ?>
        </tbody>

        <?php
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
var timeout = null;
function fetchResults(){
	var keyword = jQuery('#searchInput').val();
	clearTimeout(timeout);
    
    timeout = setTimeout(function () {
        if( keyword == "" || keyword.length < 3 ) {
            jQuery('#datafetch').html('');
        } else {
            jQuery.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'post',
                data: { action: 'data_fetch', keyword: keyword  },
                beforeSend: function() {
                    jQuery('#loader').show();
                },
                complete: function(){
                    jQuery('#loader').hide();
                },
                success: function(data) {
                    jQuery('#datafetch').fadeOut( 100, function(){
                        jQuery(this).html( data );
                    }).fadeIn( 500 );
                }
            });
        }
    }, 500);
}
    
function Data(el){
    var quantity = jQuery(el).val();
    jQuery(el).closest('tr').find('.add_to_cart_button').attr('data-quantity', quantity);
}

jQuery(document).ready(function($){    
    $('#quicksearch').submit(function(e){
        e.preventDefault();
        fetchResults();
    });
});
</script>
<?php
}
add_action( 'wp_footer', 'ajax_fetch' );
?>