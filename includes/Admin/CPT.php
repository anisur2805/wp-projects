<?php

namespace WP\Projects\Admin;

/**
 * Projects class
 */
class CPT {

	public function __construct() {
		add_action( 'init', array( $this, 'register_project_post' ) );
	}

	public static function init() {
		static $instance = false;
		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}


	public function register_project_post() {
		$labels = array(
			'name'               => _x( 'Projects', 'Post type general name', 'wp-projects' ),
			'singular_name'      => _x( 'Project', 'Post type singular name', 'wp-projects' ),
			'menu_name'          => _x( 'Projects', 'Admin Menu text', 'wp-projects' ),
			'name_admin_bar'     => _x( 'Project', 'Add New on Toolbar', 'wp-projects' ),
			'add_new'            => __( 'Add New', 'wp-projects' ),
			'add_new_item'       => __( 'Add New Project', 'wp-projects' ),
			'new_item'           => __( 'New Project', 'wp-projects' ),
			'edit_item'          => __( 'Edit Project', 'wp-projects' ),
			'view_item'          => __( 'View Project', 'wp-projects' ),
			'all_items'          => __( 'All Projects', 'wp-projects' ),
			'search_items'       => __( 'Search Projects', 'wp-projects' ),
			'parent_item_colon'  => __( 'Parent Projects:', 'wp-projects' ),
			'not_found'          => __( 'No Projects found.', 'wp-projects' ),
			'not_found_in_trash' => __( 'No Projects found in Trash.', 'wp-projects' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'project' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' ),
		);

		register_post_type( 'project', $args );
	}
}
