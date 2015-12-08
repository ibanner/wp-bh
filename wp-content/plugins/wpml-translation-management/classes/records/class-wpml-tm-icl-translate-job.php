<?php

class WPML_TM_ICL_Translate_Job extends WPML_WPDB_User {

	private $table = 'icl_translate_job';
	private $job_id = 0;

	/**
	 * WPML_TM_ICL_Translation_Status constructor.
	 *
	 * @param wpdb $wpdb
	 * @param int  $job_id
	 */
	public function __construct( &$wpdb, $job_id ) {
		parent::__construct( $wpdb );
		$job_id = (int) $job_id;
		if ( $job_id > 0 ) {
			$this->job_id = $job_id;
		} else {
			throw new InvalidArgumentException( 'Invalid Job ID: ' . $job_id );
		}
	}

	/**
	 * @param array $args in the same format used by \wpdb::update()
	 *
	 * @return $this
	 */
	public function update( $args ) {
		$this->wpdb->update(
			$this->wpdb->prefix . $this->table, $args, array( 'job_id' => $this->job_id ) );

		return $this;
	}

	/**
	 * @return bool true if this job is the most recent job for the element it
	 * belongs to and hence may be updated.
	 */
	public function is_open() {

		return $this->job_id === (int) $this->wpdb->get_var(
			$this->wpdb->prepare(
				"SELECT MAX(job_id)
				 FROM {$this->wpdb->prefix}{$this->table}
				 WHERE rid = %d",
				$this->rid() ) );
	}

	public function rid() {

		return $this->wpdb->get_var(
			$this->wpdb->prepare( " SELECT rid
									FROM {$this->wpdb->prefix}{$this->table}
									WHERE job_id = %d LIMIT 1",
				$this->job_id ) );
	}
}