<?php

/**
 * @author OnTheGo Systems
 */
class WPML_TM_ICL20_Migration_Loader {
	private $factory;
	/** @var  WPML_TM_ICL20_Migration_Progress */
	private $progress;
	/** @var  WPML_TM_ICL20_Migration_Status */
	private $status;
	private $wp_api;

	public function __construct( WPML_WP_API $wp_api, WPML_TM_ICL20_Migration_Factory $factory ) {
		$this->wp_api  = $wp_api;
		$this->factory = $factory;
	}

	public function run() {
		$this->status   = $this->factory->create_status();
		$this->progress = $this->factory->create_progress();

		$this->factory->create_notices()->clear_migration_required();
		$requires_migration = $this->requires_migration();
		$this->factory->create_notices()->run( $requires_migration );

		if ( $requires_migration && ! $this->progress->get_user_confirmed() ) {
			$ajax = $this->factory->create_ajax();
			add_action( 'wp_ajax_' . WPML_TM_ICL20_Migration_Support::PREFIX . 'user_confirm',
			            array( $ajax, 'user_confirmation' ) );
		} elseif ( $this->is_back_end() && $requires_migration && $this->progress->get_user_confirmed() ) {
			$this->maybe_fix_preferred_service();
			$migration = $this->factory->create_migration();

			if ( $this->factory->can_rollback() ) {
				add_action( 'wpml_tm_icl20_migration_rollback',
				            array( $migration, 'migrate_project_rollback' ) );
			}

			$support = $this->factory->create_ui_support();
			$support->add_hooks();
			$support->parse_request();

			if ( ! $this->progress->are_next_automatic_attempts_locked() ) {
				$requires_migration = ! $migration->run();
				$this->factory->create_notices()->run( $requires_migration );
			}

			if ( ! $this->progress->is_migration_done() ) {
				$locks = $this->factory->create_locks();
				$locks->add_hooks();
			}
		}
	}

	/**
	 * @return bool
	 */
	private function is_back_end() {
		return $this->wp_api->is_admin()
		       && ! $this->wp_api->is_ajax()
		       && ! $this->wp_api->is_cron_job()
		       && ! $this->wp_api->is_heartbeat();
	}

	/**
	 * @return bool
	 */
	private function requires_migration() {
		if ( $this->status->has_active_legacy_icl() ) {
			return true;
		}
		if ( $this->status->has_active_icl_20() ) {
			if ( $this->progress->is_migration_incomplete() ) {
				return true;
			}
			$this->progress->set_migration_done();
		}

		return false;
	}

	private function maybe_fix_preferred_service() {
		if ( $this->status->is_preferred_service_legacy_ICL() ) {
			$this->status->set_preferred_service_to_ICL20();
		}
	}
}