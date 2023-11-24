<?php
	printf( '<div class="project-item">' );
if ( $feature_image ) {
	printf( '<img src="%s" alt="" />', $feature_image );
} else {
	printf( '<img src="%s" alt="" />', WP_Projects_ASSETS . '/images/placeholder-1.webp' );
}

		printf( '<h2>%s</h2>', esc_html( $title ) );

if ( $terms && ! is_wp_error( $terms ) ) {
	printf( '<div class="project-cat-items">' );
	foreach ( $terms as $term ) {
		printf( '<span>%s</span>', esc_html( $term->name ) );
	}
	printf( '</div>' );
}

		printf( '<button data-id="%s" class="view-details">%s</button>', get_the_ID(), __( 'View Details', 'wp-projects' ) );

	printf( '</div>' );
