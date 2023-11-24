<?php

namespace WP\Projects\Frontend;

/**
 * Shortcode class for handle
 * Archive content display
 */
class Shortcode {
	/**
	 * Kickoff the class during initialize
	 */
	public function __construct() {
		add_shortcode( 'wp_projects', array( $this, 'project_display_shortcode' ) );
	}

	/**
	 * Shortcode render method
	 */
	public function project_display_shortcode() {
		// Load necessary styles/ scripts
		// only load for this case
		wp_enqueue_script( 'wp-projects-script' );
		wp_enqueue_style( 'wp-projects-style' );

		wp_enqueue_script( 'slick-slider-js' );
		wp_enqueue_style( 'slick-slider-css' );

		// For display icons in slick slider
		wp_enqueue_style( 'dashicons' );

		ob_start();

		$projects_query = new \WP_Query(
			array(
				'post_type'      => 'project',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			)
		);

		if ( $projects_query->have_posts() ) {
			printf( '<div class="wp-projects-wrapper">' );

				printf( '<div class="wp-projects-header">' );
					printf( '<h2>%s</h2>', __( 'WP Project Archive', 'wp-projects' ) ); ?>

					<div class="wp-projects-header-select">
						<select id="project-category-filter">
							<option value=""><?php printf( 'All Categories', 'wp-projects' ); ?></option>
							<?php
							$terms = get_terms( 'wp_project_cat' );

							if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
								foreach ( $terms as $term ) {
									echo '<option value="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</option>';
								}
							}
							?>
						</select>

						<select name="orderby" id="orderby">
							<option value=""><?php printf( 'Orderby', 'wp-projects' ); ?></option>
							<option value="title"><?php printf( 'Sortby Title', 'wp-projects' ); ?></option>
							<option value="date"><?php printf( 'Sortby Latest', 'wp-projects' ); ?></option>
						</select>
					</div>

				<?php
				printf( '</div>' );

				printf( '<div class="wp-projects-inner">' );
					printf( '<div class="project-grid">' );

				while ( $projects_query->have_posts() ) {
					$projects_query->the_post();

					$terms = get_the_terms( get_the_ID(), 'wp_project_cat' );

					$feature_image  = sanitize_url( get_post_meta( get_the_ID(), 'wp_projects_upload_image_url', true ) );
					$preview_images = sanitize_url( get_post_meta( get_the_ID(), 'wp_projects_preview_images_url', true ) );

					$preview_images = explode( ';', $preview_images );

					$title        = sanitize_text_field( get_post_meta( get_the_ID(), '_wp_project_title', true ) );
					$desc         = sanitize_textarea_field( get_post_meta( get_the_ID(), '_wp_project_des', true ) );
					$external_url = sanitize_url( get_post_meta( get_the_ID(), '_wp_project_external_url', true ) );

					include __DIR__ . '/content-template.php';
				}

					printf( '</div>' );
				printf( '</div>' );
				printf( '</div>' );
		} else {
			_e( 'No projects found.', 'wp-projects' );
		}

		wp_reset_postdata();

		return ob_get_clean();
	}
}
