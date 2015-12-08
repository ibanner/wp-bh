<?php

class WPML_TM_Records extends WPML_WPDB_User {

	/**
	 * @param int $translation_id
	 *
	 * @return WPML_TM_ICL_Translation_Status
	 */
	public function icl_translation_status_by_translation_id( $translation_id ) {

		return new WPML_TM_ICL_Translation_Status( $this->wpdb, $translation_id );
	}

	/**
	 * @param int $rid
	 *
	 * @return WPML_TM_ICL_Translation_Status
	 */
	public function icl_translation_status_by_rid( $rid ) {

		return new WPML_TM_ICL_Translation_Status( $this->wpdb, $rid, 'rid' );
	}

	/**
	 * @param int $job_id
	 *
	 * @return WPML_TM_ICL_Translate_Job
	 */
	public function icl_translate_job_by_job_id( $job_id ) {

		return new WPML_TM_ICL_Translate_Job( $this->wpdb, $job_id );
	}
}