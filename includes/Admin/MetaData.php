<?php

namespace WP\Projects\Admin;

/**
 * Metadata class
 */
class MetaData {

    public function __construct() {

        add_action( 'add_meta_boxes', [ $this, 'add_meta_box' ] );
		add_action( 'save_post',      [ $this, 'save' ] );
    }

    public static function init() {
        static $instance = false;
        if ( !$instance ) {
            $instance = new self();
        }

        return $instance;
    }

    public function add_meta_box( $post_type ) {

        $post_types = [ 'project' ];

        if ( in_array( $post_type, $post_types ) ) {
			add_meta_box(
				'some_meta_box_name',
				__( 'WP Projects Metabox', 'wp-projects' ),
				[ $this, 'render_meta_box_content' ],
				$post_type,
				'advanced',
				'high'
			);
		}

    }

	public function save( $post_id ) {

		if ( ! isset( $_POST['wp_projects_nonce'] ) ) {
			return $post_id;
		}

		$nonce = $_POST['wp_projects_nonce'];

		if ( ! wp_verify_nonce( $nonce, 'wpcmb_box' ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		$title = sanitize_text_field( $_POST['wp_project_title'] );
		$desc = sanitize_textarea_field( $_POST['wp_project_des'] );
		$external_url = sanitize_textarea_field( $_POST['wp_project_external_url'] );

		update_post_meta( $post_id, '_wp_project_title', $title );
		update_post_meta( $post_id, '_wp_project_des', $desc );
		update_post_meta( $post_id, '_wp_project_external_url', $external_url );


        $preview_images_id  = isset($_POST['wp_projects_preview_images_id']) ? $_POST['wp_projects_preview_images_id'] : '';
        $preview_images_url = isset($_POST['wp_projects_preview_images_url']) ? $_POST['wp_projects_preview_images_url'] : '';

        update_post_meta($post_id, 'wp_projects_preview_images_id', $preview_images_id);
        update_post_meta($post_id, 'wp_projects_preview_images_url', $preview_images_url);


        $upload_image_id  = isset($_POST['wp_projects_upload_image_id']) ? $_POST['wp_projects_upload_image_id'] : '';
        $upload_image_url = isset($_POST['wp_projects_upload_image_url']) ? $_POST['wp_projects_upload_image_url'] : '';

        update_post_meta($post_id, 'wp_projects_upload_image_id', $upload_image_id);
        update_post_meta($post_id, 'wp_projects_upload_image_url', $upload_image_url);


	}

    public function render_meta_box_content( $post ) {

        wp_enqueue_script( 'wp-projects-metabox' );
        wp_enqueue_media();
        wp_enqueue_style( 'admin-style' );

		wp_nonce_field( 'wpcmb_box', 'wp_projects_nonce' );

		$thumbnail_img      = get_post_meta( $post->ID, '_wp_project_thumbnail_image', true );
		$title              = sanitize_text_field( get_post_meta( $post->ID, '_wp_project_title', true ) );
		$description        = sanitize_textarea_field( get_post_meta( $post->ID, '_wp_project_des', true ) );
		$external_url       = sanitize_url( get_post_meta( $post->ID, '_wp_project_external_url', true ) );

        $upload_image_id    = get_post_meta($post->ID, 'wp_projects_upload_image_id', true);
        $upload_image_url   = get_post_meta($post->ID, 'wp_projects_upload_image_url', true);

        $preview_images_id  = sanitize_text_field( get_post_meta($post->ID, 'wp_projects_preview_images_id', true) );
        $preview_images_url = sanitize_url( get_post_meta($post->ID, 'wp_projects_preview_images_url', true) );

		?>

        <!-- Metaboxes -->
        <div class="__wp_projects_wrapper">

            <!-- Thumbnail images -->
            <div class="__form-group" id="wp_projects_upload_image_wrapper">
                <label for="wp_project_thumbnail_image">
                    <?php _e( 'Upload Thumbnail Image', 'wp-projects' ); ?>
                </label>
                <button class="button" id="upload_image"><?php esc_html_e('Upload Image', 'wp-projects'); ?></button>
                <button class="hidden button" name="wp_projects_upload_image_remove" id="delete_upload_image">Remove Image</button>
                <input type="hidden" name="wp_projects_upload_image_id" id="wp_projects_upload_image_id" value="<?php esc_attr_e($upload_image_id, 'wp-projects'); ?>" />
                <input type="hidden" name="wp_projects_upload_image_url" id="wp_projects_upload_image_url" value="<?php esc_attr_e($upload_image_url, 'wp-projects'); ?>" />
                <div id="upload_image_container"></div>

            </div>

            <!-- External URL -->
            <div class="__form-group">
                <label for="wp_project_external_url">
                    <?php _e( 'External URL', 'wp-projects' ); ?>
                </label>
                <input type="text" value="<?php echo esc_url( $external_url ); ?>" class="widefat" id="wp_project_external_url" name="wp_project_external_url" />
            </div>

            <!-- Title -->
            <div class="__form-group">
                <label for="wp_project_title">
                    <?php _e( 'Title', 'wp-projects' ); ?>
                </label>
                <input type="text" value="<?php echo esc_html( $title ); ?>" class="widefat" id="wp_project_title" name="wp_project_title" />
            </div>

            <!-- Description -->
            <div class="__form-group">
                <label for="wp_project_des">
                    <?php _e( 'Description', 'wp-projects' ); ?>
                </label>
                <textarea id="wp_project_des" name="wp_project_des" class="widefat" rows="5"><?php echo wp_kses_post( $description ); ?></textarea>
            </div>

            <!-- Preview Images -->
            <div class="__form-group" id="wp_project_preview_images_wrapper">
                <label for="wp_project_preview_images">
                    <?php _e( 'Preview images', 'wp-projects' ); ?>
                </label>
                <button class="button" id="upload_preview_images"> <?php _e( 'Upload Preview Images', 'wp-projects' ); ?></button>
                <button class="hidden button" name="wp_projects_preview_images_remove" id="delete_preview_images"> <?php _e( 'Remove Image', 'wp-projects' ); ?></button>
                <input type="hidden" name="wp_projects_preview_images_id" id="wp_projects_preview_images_id" value="<?php esc_attr_e( $preview_images_id, 'wp-projects'); ?>" />
                <input type="hidden" name="wp_projects_preview_images_url" id="wp_projects_preview_images_url" value=<?php esc_attr_e( $preview_images_url, 'wp-projects'); ?> />
                <div id="preview_images_container"></div>
            </div>

        </div>
		<?php
	}


}