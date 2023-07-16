<?php
namespace WP\Projects;

use WP\Projects\Admin\CPT;
use WP\Projects\Admin\MetaData;
use WP\Projects\Admin\ProjectCategory;

/**
 * Admin class 
 * for handle all admin class
 */
class Admin {
    public function __construct() {

        /**
         * Custom Post Type handler Class
         */
        $projects = new CPT();
        $projects::init();

        /**
         * Custom Metabox handler Class
         */
        $metadata = new MetaData();
        $metadata->init();

        // $project_cat = new ProjectCategory( 'wp_project_cat', 'project' );
        // $project_cat->init();
    }
}