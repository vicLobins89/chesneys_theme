<?php

// Flush rewrite rules for custom post types
//add_action( 'after_switch_theme', 'bones_flush_rewrite_rules' );

// Flush your rewrite rules
function bones_flush_rewrite_rules() {
	flush_rewrite_rules();
}

// let's create the function for the custom type
function blocks_post() { 
	// creating (registering) the custom type 
	register_post_type( 'content_block', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		// let's now add all the options for this post type
		array( 'labels' => array(
			'name' => __( 'Content Blocks', 'bonestheme' ), /* This is the Title of the Group */
			'singular_name' => __( 'Content Block', 'bonestheme' ), /* This is the individual type */
			'all_items' => __( 'All Content Blocks', 'bonestheme' ), /* the all items menu item */
			'add_new' => __( 'Add New', 'bonestheme' ), /* The add new menu item */
			'add_new_item' => __( 'Add New Content Block', 'bonestheme' ), /* Add New Display Title */
			'edit' => __( 'Edit', 'bonestheme' ), /* Edit Dialog */
			'edit_item' => __( 'Edit Content Block', 'bonestheme' ), /* Edit Display Title */
			'new_item' => __( 'New Content Block', 'bonestheme' ), /* New Display Title */
			'view_item' => __( 'View Content Block', 'bonestheme' ), /* View Display Title */
			'search_items' => __( 'Search Content Blocks', 'bonestheme' ), /* Search Custom Type Title */ 
			'not_found' =>  __( 'Nothing found in the Database.', 'bonestheme' ), /* This displays if there are no entries yet */ 
			'not_found_in_trash' => __( 'Nothing found in Trash', 'bonestheme' ), /* This displays if there is nothing in the trash */
			'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( 'This is a custom Content Block', 'bonestheme' ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 10, /* this is what order you want it to appear in on the left hand side menu */ 
			'menu_icon' => 'dashicons-layout', /* the icon for the custom post type menu */
			'rewrite'	=> array( 'slug' => 'content_block', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => 'content_block', /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'revisions')
		) /* end of options */
	); /* end of register post type */
	
	/* this adds your post categories to your custom post type */
//	register_taxonomy_for_object_type( 'category', 'content_block' );
	/* this adds your post tags to your custom post type */
//	register_taxonomy_for_object_type( 'post_tag', 'content_block' );
	
}

	// adding the function to the Wordpress init
	add_action( 'init', 'blocks_post');
	
	/*
	for more information on taxonomies, go here:
	http://codex.wordpress.org/Function_Reference/register_taxonomy
	*/
	
	// now let's add custom categories (these act like categories)
	register_taxonomy( 'blocks_cat', 
		array('content_block'), /* if you change the name of register_post_type( 'content_block', then you have to change this */
		array('hierarchical' => true,     /* if this is true, it acts like categories */
			'labels' => array(
				'name' => __( 'Blocks Categories', 'bonestheme' ), /* name of the custom taxonomy */
				'singular_name' => __( 'Blocks Category', 'bonestheme' ), /* single taxonomy name */
				'search_items' =>  __( 'Search Blocks Categories', 'bonestheme' ), /* search title for taxomony */
				'all_items' => __( 'All Blocks Categories', 'bonestheme' ), /* all title for taxonomies */
				'parent_item' => __( 'Parent Blocks Category', 'bonestheme' ), /* parent title for taxonomy */
				'parent_item_colon' => __( 'Parent Blocks Category:', 'bonestheme' ), /* parent taxonomy title */
				'edit_item' => __( 'Edit Blocks Category', 'bonestheme' ), /* edit custom taxonomy title */
				'update_item' => __( 'Update Blocks Category', 'bonestheme' ), /* update title for taxonomy */
				'add_new_item' => __( 'Add New Blocks Category', 'bonestheme' ), /* add new title for taxonomy */
				'new_item_name' => __( 'New Blocks Category Name', 'bonestheme' ) /* name title for taxonomy */
			),
			'show_admin_column' => true, 
			'show_ui' => false,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'blocks-cat' ),
		)
	);
	
	// now let's add custom tags (these act like categories)
	register_taxonomy( 'blocks_tag', 
		array('content_block'), /* if you change the name of register_post_type( 'content_block', then you have to change this */
		array('hierarchical' => false,    /* if this is false, it acts like tags */
			'labels' => array(
				'name' => __( 'Blocks Tags', 'bonestheme' ), /* name of the custom taxonomy */
				'singular_name' => __( 'Blocks Tag', 'bonestheme' ), /* single taxonomy name */
				'search_items' =>  __( 'Search Blocks Tags', 'bonestheme' ), /* search title for taxomony */
				'all_items' => __( 'All Blocks Tags', 'bonestheme' ), /* all title for taxonomies */
				'parent_item' => __( 'Parent Blocks Tag', 'bonestheme' ), /* parent title for taxonomy */
				'parent_item_colon' => __( 'Parent Blocks Tag:', 'bonestheme' ), /* parent taxonomy title */
				'edit_item' => __( 'Edit Blocks Tag', 'bonestheme' ), /* edit custom taxonomy title */
				'update_item' => __( 'Update Blocks Tag', 'bonestheme' ), /* update title for taxonomy */
				'add_new_item' => __( 'Add New Blocks Tag', 'bonestheme' ), /* add new title for taxonomy */
				'new_item_name' => __( 'New Blocks Tag Name', 'bonestheme' ) /* name title for taxonomy */
			),
			'show_admin_column' => true,
			'show_ui' => false,
			'query_var' => true,
		)
	);
	
	/*
		looking for custom meta boxes?
		check out this fantastic tool:
		https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
	*/
	

?>
