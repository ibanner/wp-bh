<?php

class WPML_TM_ICL_Translation_Status extends WPML_WPDB_User {

	private $table = 'icl_translation_status';
	private $translation_id = 0;
	private $rid = 0;

	/**
	 * WPML_TM_ICL_Translation_Status constructor.
	 *
	 * @param wpdb   $wpdb
	 * @param int    $id
	 * @param string $type
	 */
	public function __construct( &$wpdb, $id, $type = 'translation_id' ) {
		parent::__construct( $wpdb );
		if ( $id > 0 && in_array(
				$type, array(
				'translation_id',
				'rid'
			), true )
		) {
			$this->{$type} = $id;
		} else {
			throw new InvalidArgumentException( 'Unknown column: ' . $type . ' or invalid id: ' . $id );
		}
	}

	/**
	 * @param array $args in the same format used by \wpdb::update()
	 *
	 * @return $this
	 */
	public function update( $args ) {
		$this->wpdb->update(
			$this->wpdb->prefix . $this->table, $args, $this->get_args() );

		return $this;
	}

	/**
	 * @return int
	 */
	public function rid() {

		return (int) $this->wpdb->get_var(
			"SELECT rid
		     FROM {$this->wpdb->prefix}{$this->table} "
			. $this->get_where() );
	}

	/**
	 * @return int
	 */
	public function translation_id() {

		return (int) $this->wpdb->get_var(
			"SELECT translation_id
		     FROM {$this->wpdb->prefix}{$this->table} "
			. $this->get_where() );
	}

	private function get_where() {
		return " WHERE " .
		       ( $this->translation_id
			       ? $this->wpdb->prepare( " translation_id = %d ", $this->translation_id )
			       : $this->wpdb->prepare( " rid = %d ", $this->rid ) );
	}

	private function get_args() {

		return $this->translation_id
			? array( 'translation_id' => $this->translation_id )
			: array( 'rid' => $this->rid );
	}
}