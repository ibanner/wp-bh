<?php

/**
 * @author OnTheGo Systems
 */
class WPML_TM_ICL20_Migration_AJAX {
	/**
	 * @var WPML_TM_ICL20_Migration_Progress
	 */
	private $progress;

	public function __construct( WPML_TM_ICL20_Migration_Progress $progress ) {
		$this->progress = $progress;
	}

	public function user_confirmation() {
		if ( $this->is_valid_request() ) {
			$this->progress->set_user_confirmed();
			wp_send_json_success();
		}
	}

	private function is_valid_request() {
		if ( ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), $_POST['action'] ) ) {
			wp_send_json_error( __( 'You have attempted to submit data in a not legit way.',
			                        'wpml-translation-management' ) );

			return false;
		}

		return true;
	}
}