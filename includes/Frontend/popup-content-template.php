<?php
    printf( '<div class="project-item">' );

        printf('<h2>%s</h2>', esc_html( $title ) );

        printf('<div class="popup-project-content">%s</div>', wp_kses_post( $desc ) );

        if( $feature_image ) {
            printf('<img class="wp-projects-feature-image" src="%s" alt="" />', $feature_image );
        } else {
            printf('<img class="wp-projects-feature-image" src="%s" alt="" />', WP_Projects_ASSETS . '/images/placeholder-1.webp' );
        }

        if( is_array( $preview_images ) ) {
            printf('<div class="wp-projects-preview-images-wrapper">');
            printf( '<h3>%s</h3>', __( 'Preview Images', 'wp-projects' ) );
            printf('<div class="wp-projects-preview-images">');
            foreach( $preview_images as $image ) {
                printf('<div class="wp-projects-preview-image">');
                printf('<img src="%s" alt="" />', $image );
                printf('</div>');
            }
            printf('</div>');
            printf('</div>');
        } else {
            _e( 'Sorry, no preview images found', 'wp-projects' );
        }

        printf( '<h4>%s</h4>', __( 'External URL', 'wp-projects' ) );
        printf('<a target="_blank" href="%s" class="popup-project-external-url">%s</a>', esc_url( $external_url ), esc_html__( 'External URL', 'wp-projects' ) );


        if ($terms && !is_wp_error($terms)) {
            printf( '<div class="project-cat-items">' );
                printf( '<h4>%s</h4>', __( 'Projects Category', 'wp-projects' ) );

                foreach ($terms as $term) {
                    printf('<span>%s</span>', esc_html( $term->name ) );
                }
            printf('</div>');
        } else {
            _e( 'Sorry, no terms found', 'wp-projects' );
        }

printf( '</div>' );