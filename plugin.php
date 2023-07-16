<?php
/**
 * Plugin Name: WP Projects
 * Plugin URI:  https://github.com/anisur2805/wp-projects
 * Description: WP Projects is for showcase custom post type
 * Version:     1.0.0
 * Author:      Anisur Rahman
 * Author URI:  https://github.com/anisur2805
 * Text Domain: wp-projects
 * Domain Path: /languages/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 */

use WP\Projects\Ajax;
use WP\Projects\Installer;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

require_once __DIR__ . "/vendor/autoload.php";

final class WP_Projects {
    /**
     * plugin version
     */
    const version = '1.0';

    /**
     * class constructor
     */
    private function __construct() {
        $this->define_constants();

        register_activation_hook( __FILE__, [ $this, 'activate' ] );
        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );

    }

    /**
     * Initialize a singleton instance
     *
     * @return \WP_Projects
     */
    public static function init() {
        static $instance = false;
        if ( !$instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * define plugin require constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'WP_Projects_VERSION', self::version );
        define( 'WP_Projects_FILE', __FILE__ );
        define( 'WP_Projects_PATH', __DIR__ );
        define( 'WP_Projects_URL', plugins_url( '', WP_Projects_FILE ) );
        define( 'WP_Projects_ASSETS', WP_Projects_URL . '/assets' );
        define( 'WP_Projects_INCLUDES', WP_Projects_URL . '/includes' );
    }

    /**
     * Do staff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installer = new Installer();
        $installer->run();
    }

    public function init_plugin() {

        /**
         * Load Assets
         */
        new WP\Projects\Assets();

        if( defined( 'DOING_AJAX') && DOING_AJAX ) {
            new Ajax();
        }

        /**
         * Load Admin Class
         */
        if( is_admin()) {
            new \WP\Projects\Admin();
        } else {
            new \WP\Projects\Frontend();
        }
    }


}

/**
 * Initialize the main plugin
 *
 * @return \WP_Projects
 */
function wp_projects() {
    return WP_Projects::init();
}

wp_projects();

