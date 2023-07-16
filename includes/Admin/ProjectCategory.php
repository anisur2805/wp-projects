<?php

namespace WP\Projects\Admin;

// ProjectCategory class
class ProjectCategory {

    public function __construct() {
        add_action('init', array($this, 'register_taxonomy'));
    }


    public static function init() {
        static $instance = false;
        if ( !$instance ) {
            $instance = new self();
        }

        return $instance;
    }

    function register_taxonomy() {
        $labels = array(
            'name'              => _x( 'Genres', 'taxonomy general name', 'textdomain' ),
            'singular_name'     => _x( 'Genre', 'taxonomy singular name', 'textdomain' ),
            'search_items'      => __( 'Search Genres', 'textdomain' ),
            'all_items'         => __( 'All Genres', 'textdomain' ),
            'parent_item'       => __( 'Parent Genre', 'textdomain' ),
            'parent_item_colon' => __( 'Parent Genre:', 'textdomain' ),
            'edit_item'         => __( 'Edit Genre', 'textdomain' ),
            'update_item'       => __( 'Update Genre', 'textdomain' ),
            'add_new_item'      => __( 'Add New Genre', 'textdomain' ),
            'new_item_name'     => __( 'New Genre Name', 'textdomain' ),
            'menu_name'         => __( 'Genre', 'textdomain' ),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'wp_project_cat' ),
        );

        register_taxonomy( 'wp_project_cat', 'project', $args );
    }
}