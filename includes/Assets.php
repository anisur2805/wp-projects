<?php

namespace WP\Projects;

/**
 * Main Assets class 
 * for load Styles and Scripts file
 */
class Assets {
    /**
     * Kickoff the class during initialize
     */
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Register script files
     */
    public function get_scripts() {
        return [

            'slick-slider-js'     => [
                'src'     => WP_Projects_ASSETS . '/js/slick.min.js',
                'version' => filemtime(WP_Projects_PATH . '/assets/js/slick.min.js'),
                'deps'    => ['jquery'],
            ],

            'wp-projects-script'  => [
                'src'     => WP_Projects_ASSETS . '/js/frontend.js',
                'version' => filemtime(WP_Projects_PATH . '/assets/js/frontend.js'),
                'deps'    => ['jquery', 'slick-slider-js'],
            ],

            'wp-projects-metabox' => [
                'src'     => WP_Projects_ASSETS . '/js/metabox.js',
                'version' => filemtime(WP_Projects_PATH . '/assets/js/metabox.js'),
                'deps'    => ['jquery'],
            ],

            'admin-script'        => [
                'src'     => WP_Projects_ASSETS . '/js/admin.js',
                'version' => filemtime(WP_Projects_PATH . '/assets/js/admin.js'),
                'deps'    => ['jquery', 'wp-util'],
            ],

        ];
    }

    /**
     * Register style files
     */
    public function get_styles()
    {
        return [

            'slick-slider-css'  => [
                'src'     => WP_Projects_ASSETS . '/css/slick.min.css',
                'version' => filemtime(WP_Projects_PATH . '/assets/css/slick.min.css'),
            ],

            'wp-projects-style' => [
                'src'     => WP_Projects_ASSETS . '/css/frontend.css',
                'version' => filemtime(WP_Projects_PATH . '/assets/css/frontend.css'),
            ],

            'admin-style'       => [
                'src'     => WP_Projects_ASSETS . '/css/admin.css',
                'version' => filemtime(WP_Projects_PATH . '/assets/css/admin.css'),
            ],

        ];
    }

    /**
     * Handle main assets method
     */
    public function enqueue_assets() {
        $scripts = $this->get_scripts();

        foreach ($scripts as $handle => $script) {
            $deps = isset($script['deps']) ? $script['deps'] : false;
            wp_register_script($handle, $script['src'], $deps, $script['version'], true);
        }

        $styles = $this->get_styles();

        foreach ($styles as $handle => $style) {
            $deps = isset($style['deps']) ? $style['deps'] : false;
            wp_register_style( $handle, $style['src'], $deps, $style['version'] );
        }

        wp_localize_script('wp-projects-script', 'wpProjects', [
            'nonce'   => wp_create_nonce('wp-projects-nonce'),
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'error'   => __('Something went wrong', 'wp-projects'),
        ]);

        wp_localize_script('wp-projects-script', 'wpProjectsPopup', [
            'nonce'   => wp_create_nonce('wp-projects-popup-nonce'),
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'error'   => __('Something went wrong', 'wp-projects'),
        ]);
    }
}