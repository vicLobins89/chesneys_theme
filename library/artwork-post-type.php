<?php

// Flush rewrite rules for custom post types
//add_action( 'after_switch_theme', 'bones_flush_rewrite_rules' );

// Flush your rewrite rules
//function bones_flush_rewrite_rules() {
//	flush_rewrite_rules();
//}

// let's create the function for the custom type
function artwork_post() { 
	// creating (registering) the custom type 
	register_post_type( 'artworks', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		// let's now add all the options for this post type
		array( 'labels' => array(
			'name' => __( 'Artworks', 'bonestheme' ), /* This is the Title of the Group */
			'singular_name' => __( 'Artwork', 'bonestheme' ), /* This is the individual type */
			'all_items' => __( 'All Artworks', 'bonestheme' ), /* the all items menu item */
			'add_new' => __( 'Add New', 'bonestheme' ), /* The add new menu item */
			'add_new_item' => __( 'Add New Artwork', 'bonestheme' ), /* Add New Display Title */
			'edit' => __( 'Edit', 'bonestheme' ), /* Edit Dialog */
			'edit_item' => __( 'Edit Artwork', 'bonestheme' ), /* Edit Display Title */
			'new_item' => __( 'New Artwork', 'bonestheme' ), /* New Display Title */
			'view_item' => __( 'View Artwork', 'bonestheme' ), /* View Display Title */
			'search_items' => __( 'Search Artworks', 'bonestheme' ), /* Search Custom Type Title */ 
			'not_found' =>  __( 'Nothing found in the Database.', 'bonestheme' ), /* This displays if there are no entries yet */ 
			'not_found_in_trash' => __( 'Nothing found in Trash', 'bonestheme' ), /* This displays if there is nothing in the trash */
			'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( 'This is an Artwork', 'bonestheme' ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 12, /* this is what order you want it to appear in on the left hand side menu */ 
			'menu_icon' => 'dashicons-admin-customizer', /* the icon for the custom post type menu */
			'rewrite'	=> array( 'slug' => 'artworks', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => 'artworks', /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'revisions')
		) /* end of options */
	); /* end of register post type */
	
	/* this adds your post categories to your custom post type */
	//register_taxonomy_for_object_type( 'category', 'case_study' );
	/* this adds your post tags to your custom post type */
	//register_taxonomy_for_object_type( 'post_tag', 'case_study' );
	
}

	// adding the function to the Wordpress init
	add_action( 'init', 'artwork_post');
	
	/*
	for more information on taxonomies, go here:
	http://codex.wordpress.org/Function_Reference/register_taxonomy
	*/
	
	// now let's add custom categories (these act like categories)
	register_taxonomy( 'artists',
		array('artworks'), /* if you change the name of register_post_type( 'content_block', then you have to change this */
		array('hierarchical' => true,     /* if this is true, it acts like categories */
			'labels' => array(
				'name' => __( 'Artists', 'bonestheme' ), /* name of the custom taxonomy */
				'singular_name' => __( 'Artist', 'bonestheme' ), /* single taxonomy name */
				'search_items' =>  __( 'Search Artists', 'bonestheme' ), /* search title for taxomony */
				'all_items' => __( 'All Artists', 'bonestheme' ), /* all title for taxonomies */
				'parent_item' => __( 'Parent Artist', 'bonestheme' ), /* parent title for taxonomy */
				'parent_item_colon' => __( 'Parent Artist:', 'bonestheme' ), /* parent taxonomy title */
				'edit_item' => __( 'Edit Artist', 'bonestheme' ), /* edit custom taxonomy title */
				'update_item' => __( 'Update Artist', 'bonestheme' ), /* update title for taxonomy */
				'add_new_item' => __( 'Add New Artist', 'bonestheme' ), /* add new title for taxonomy */
				'new_item_name' => __( 'New Artist Name', 'bonestheme' ) /* name title for taxonomy */
			),
			'show_admin_column' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'artists' ),
			'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'revisions')
		)
	);
	
	// now let's add custom tags (these act like categories)
	register_taxonomy( 'artists_tag', 
		array('artworks'), /* if you change the name of register_post_type( 'content_block', then you have to change this */
		array('hierarchical' => false,    /* if this is false, it acts like tags */
			'labels' => array(
				'name' => __( 'Artist Tags', 'bonestheme' ), /* name of the custom taxonomy */
				'singular_name' => __( 'Artist Tag', 'bonestheme' ), /* single taxonomy name */
				'search_items' =>  __( 'Search Artist Tags', 'bonestheme' ), /* search title for taxomony */
				'all_items' => __( 'All Artist Tags', 'bonestheme' ), /* all title for taxonomies */
				'parent_item' => __( 'Parent Artist Tag', 'bonestheme' ), /* parent title for taxonomy */
				'parent_item_colon' => __( 'Parent Artist Tag:', 'bonestheme' ), /* parent taxonomy title */
				'edit_item' => __( 'Edit Artist Tag', 'bonestheme' ), /* edit custom taxonomy title */
				'update_item' => __( 'Update Artist Tag', 'bonestheme' ), /* update title for taxonomy */
				'add_new_item' => __( 'Add New Artist Tag', 'bonestheme' ), /* add new title for taxonomy */
				'new_item_name' => __( 'New Artist Tag Name', 'bonestheme' ) /* name title for taxonomy */
			),
			'show_admin_column' => true,
			'show_ui' => true,
			'query_var' => true,
		)
	);
	
	/*
		looking for custom meta boxes?
		check out this fantastic tool:
		https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
	*/
	

?>
