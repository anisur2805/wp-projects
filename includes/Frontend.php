<?php
namespace WP\Projects;

use WP\Projects\Frontend\Shortcode;

/**
 * Frontend class for
 * Handle frontend area class
 */
class Frontend {
	/**
	 * Kickoff the class during initialize
	 */
	public function __construct() {
		new Shortcode();

		add_action( 'wp_footer', array( $this, 'modal' ) );
	}

	/**
	 * Insert model scelaton to the body
	 */
	public function modal() {
		printf(
			'<div id="projects-modal" class="modal">
            <div class="modal-content">
                <span class="close-modal">X</span>
                <div class="modal-content-inner"></div>
            </div>
        </div>'
		);
	}
}
