<?php

namespace WP\Projects;

/**
 * Main Ajax class
 */
class Ajax {
	public function __construct() {

		// Projects Filter Ajax Call
		add_action( 'wp_ajax_render_by_select', array( $this, 'render_by_select' ) );
		add_action( 'wp_ajax_nopriv_render_by_select', array( $this, 'render_by_select' ) );

		// Popup Ajax Call
		add_action( 'wp_ajax_render_popup_by_id', array( $this, 'render_popup_by_id' ) );
		add_action( 'wp_ajax_nopriv_render_popup_by_id', array( $this, 'render_popup_by_id' ) );
	}

	/**
	 * Projects filter callback
	 *
	 * @return void
	 */
	public function render_by_select() {
		// Verify nonce for security
		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'wp-projects-nonce' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nonce verify failed!', 'wp-projects' ),
				)
			);
		}

		$selected_item      = isset( $_POST['selectId'] ) ? $_POST['selectId'] : '';
		$selected_meta_item = 'title' === $selected_item ? '_wp_project_title' : '';
		$order              = 'date' === $selected_item ? 'DESC' : 'ASC';
		$selected_category  = isset( $_POST['category'] ) ? $_POST['category'] : '';

		$query_args = array(
			'post_type'      => 'project',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'orderby'        => $selected_item ? 'meta_value' : 'date',
			'order'          => $order,
		);

		// Add category filter if selected
		if ( ! empty( $selected_category ) ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'wp_project_cat',
					'field'    => 'slug',
					'terms'    => $selected_category,
				),
			);
		}

		// Add meta key if selected
		if ( ! empty( $selected_meta_item ) ) {
			$query_args['meta_key'] = $selected_meta_item;
		}

		$projects_query = new \WP_Query( $query_args );
		$content        = '';

		if ( $projects_query->have_posts() ) {
			while ( $projects_query->have_posts() ) {
				$projects_query->the_post();

				$terms         = get_the_terms( get_the_ID(), 'wp_project_cat' );
				$feature_image = sanitize_url( get_post_meta( get_the_ID(), 'wp_projects_upload_image_url', true ) );

				$title        = sanitize_text_field( get_post_meta( get_the_ID(), '_wp_project_title', true ) );
				$desc         = sanitize_textarea_field( get_post_meta( get_the_ID(), '_wp_project_des', true ) );
				$external_url = sanitize_url( get_post_meta( get_the_ID(), '_wp_project_external_url', true ) );

				ob_start();
				include __DIR__ . '/Frontend/content-template.php';
				$content .= ob_get_clean();

			}
		} else {
			$content .=
			$content  = sprintf( // phpcs:ignore
				'<p>%s</p>',
				__(
					'No projects found.',
					'wp-projects'
				)
			);
		}

		wp_reset_postdata();

		wp_send_json_success(
			array(
				'message' => __( 'Successfully render!', 'wp-projects' ),
				'content' => $content,
			)
		);
	}

	/**
	 * Render the popup based on selected ID
	 *
	 * @return void
	 */
	public function render_popup_by_id() {
		// Verify nonce for security
		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'wp-projects-popup-nonce' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nonce verify failed!', 'wp-projects' ),
				)
			);
		}

		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
		$content = '';

		$post = get_post( $post_id );
		if ( $post ) {
			$title        = sanitize_text_field( get_post_meta( $post_id, '_wp_project_title', true ) );
			$desc         = sanitize_textarea_field( get_post_meta( $post_id, '_wp_project_des', true ) );
			$external_url = sanitize_url( get_post_meta( $post_id, '_wp_project_external_url', true ) );

			$terms = get_the_terms( $post_id, 'wp_project_cat' );

			$feature_image  = sanitize_url( get_post_meta( $post_id, 'wp_projects_upload_image_url', true ) );
			$preview_images = sanitize_url( get_post_meta( $post_id, 'wp_projects_preview_images_url', true ) );
			$preview_images = explode( ';', $preview_images );

			ob_start();
			include __DIR__ . '/Frontend/popup-content-template.php';
			$content .= ob_get_clean();

		}

		wp_send_json_success(
			array(
				'message' => __( 'Success', 'wp-projects' ),
				'content' => $content,
				'images'  => $preview_images,
			)
		);
	}
}
