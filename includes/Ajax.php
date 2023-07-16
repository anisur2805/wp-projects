<?php

namespace WP\Projects;

/**
 * Main Ajax class
 */
class Ajax {
    public function __construct() {

        // Projects Filter Ajax Call
        add_action( 'wp_ajax_renderBySelect', [ $this, 'renderBySelect' ] );
        add_action( 'wp_ajax_nopriv_renderBySelect', [ $this, 'renderBySelect' ] );

        // Popup Ajax Call
        add_action( 'wp_ajax_renderPopupById', [ $this, 'renderPopupById' ] );
        add_action( 'wp_ajax_nopriv_renderPopupById', [ $this, 'renderPopupById' ] );

    }

    /**
     * Projects filter callback
     *
     * @return void
     */
    public function renderBySelect() {
        // Verify nonce for security
        if (!wp_verify_nonce($_REQUEST['nonce'], 'wp-projects-nonce')) {
            wp_send_json_error([
                'message' => __( 'Nonce verify failed!', 'wp-projects' ),
            ]);
        }

        $selectedItem       = isset( $_POST['selectId'] ) ? $_POST['selectId'] : '';
        $selectedMetaItem   = $selectedItem == 'title' ? '_wp_project_title' : '';
        $order              = $selectedItem == 'date' ? 'DESC' : 'ASC';
        $selectedCategory   = isset( $_POST['category'] ) ? $_POST['category'] : '';

        $queryArgs = array(
            'post_type'      => 'project',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => $selectedItem ? 'meta_value' : 'date',
            'order'          => $order,
        );

        // Add category filter if selected
        if ( ! empty( $selectedCategory ) ) {
            $queryArgs['tax_query'] = array(
                array(
                    'taxonomy' => 'wp_project_cat',
                    'field'    => 'slug',
                    'terms'    => $selectedCategory,
                ),
            );
        }

        // Add meta key if selected
        if ( ! empty( $selectedMetaItem ) ) {
            $queryArgs['meta_key'] = $selectedMetaItem;
        }

        $projects_query = new \WP_Query( $queryArgs );
        $content = '';

        if ( $projects_query->have_posts() ) {
            while ( $projects_query->have_posts() ) {
                $projects_query->the_post();

                $terms          = get_the_terms( get_the_ID(), 'wp_project_cat' );
                $feature_image  = sanitize_url( get_post_meta( get_the_ID(), 'wp_projects_upload_image_url', true ) );

                $title          = sanitize_text_field( get_post_meta( get_the_ID(), '_wp_project_title', true ) );
                $desc           = sanitize_textarea_field( get_post_meta( get_the_ID(), '_wp_project_des', true ) );
                $external_url   = sanitize_url( get_post_meta( get_the_ID(), '_wp_project_external_url', true ) );

                ob_start();
                include __DIR__ . '/Frontend/content-template.php';
                $content .= ob_get_clean();

            }
        } else {
            $content .= $content = sprintf('<p>%s</p>', __( 'No projects found.', 'wp-projects' ) );
        }

        wp_reset_postdata();

        wp_send_json_success( array(
            'message'          => __( 'Successfully render!', 'wp-projects' ),
            'content'          => $content,
        ) );

    }

    /**
     * Render the popup based on selected ID
     *
     * @return void
     */
    public function renderPopupById() {
        // Verify nonce for security
        if (!wp_verify_nonce($_REQUEST['nonce'], 'wp-projects-popup-nonce')) {
            wp_send_json_error( [
                'message' => __( 'Nonce verify failed!', 'wp-projects' ),
            ] );
        }

        $postId  = isset( $_POST['postId'] ) ? absint( $_POST['postId'] ) : 0;
        $content = '';


        $post = get_post( $postId );
        if( $post ) {
            $title          = sanitize_text_field( get_post_meta( $postId, '_wp_project_title', true ) );
            $desc           = sanitize_textarea_field( get_post_meta( $postId, '_wp_project_des', true ) );
            $external_url   = sanitize_url( get_post_meta( $postId, '_wp_project_external_url', true ) );

            $terms          = get_the_terms( $postId, 'wp_project_cat' );

            $feature_image  = sanitize_url( get_post_meta( $postId, 'wp_projects_upload_image_url', true ) );
            $preview_images = sanitize_url( get_post_meta( $postId, 'wp_projects_preview_images_url', true ) );
            $preview_images = explode( ';', $preview_images );

            ob_start();
            include __DIR__ . '/Frontend/popup-content-template.php';
            $content .= ob_get_clean();

        }

        wp_send_json_success([
            'message' => __( 'Success', 'wp-projects' ),
            'content' => $content,
            'images'  => $preview_images,
        ]);
    }

}
