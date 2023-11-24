<?php


function wpdocs_create_book_taxonomies() {
	$labels = array(
		'name'              => _x( 'Project Categorys', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Project Category', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Project Categorys', 'textdomain' ),
		'all_items'         => __( 'All Project Categorys', 'textdomain' ),
		'parent_item'       => __( 'Parent Project Category', 'textdomain' ),
		'parent_item_colon' => __( 'Parent Project Category:', 'textdomain' ),
		'edit_item'         => __( 'Edit Project Category', 'textdomain' ),
		'update_item'       => __( 'Update Project Category', 'textdomain' ),
		'add_new_item'      => __( 'Add New Project Category', 'textdomain' ),
		'new_item_name'     => __( 'New Project Category Name', 'textdomain' ),
		'menu_name'         => __( 'Project Category', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'wp_project_cat' ),
	);

	register_taxonomy( 'wp_project_cat', array( 'project' ), $args );
}
add_action( 'init', 'wpdocs_create_book_taxonomies' );
