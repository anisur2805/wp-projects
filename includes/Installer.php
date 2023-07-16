<?php
namespace WP\Projects;

/**
 * Installer class
 */
class Installer {
    public function run() {
        $this->add_version();
    }

    /**
     * Keep track of plugin installation version
     * so further take some action based on
     * installation
     */
    public function add_version() {
        $installed = get_option( 'wp_projects_installed' );
        if ( !$installed ) {
            update_option( 'wp_projects_installed', time() );
        }

        update_option( 'wp_projects_version', WP_Projects_VERSION );
    }

}