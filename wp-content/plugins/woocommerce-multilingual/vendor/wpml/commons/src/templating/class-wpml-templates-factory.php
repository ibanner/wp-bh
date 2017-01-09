<?php

abstract class WPML_Templates_Factory {
	const NOTICE_GROUP = 'template_factory';

	private   $custom_filters;
	private   $custom_functions;
	protected $template_paths;
	private   $cache_directory;

	/* @var WPML_WP_API $wp_api */
	private $wp_api;
	/**
	 * @var Twig_Environment
	 */
	private $twig;

	/**
	 * WPML_Templates_Factory constructor.
	 *
	 * @param array $custom_functions
	 * @param array $custom_filters
	 * @param Twig_Environment $twig
	 * @param WPML_WP_API      $wp_api
	 */
	public function __construct( array $custom_functions = array(), array $custom_filters = array(), $twig = null, $wp_api = null ) {
		$this->init_template_base_dir();
		$this->custom_functions = $custom_functions;
		$this->custom_filters   = $custom_filters;

		if ( $twig ) {
			$this->twig = $twig;
		}

		if ( $wp_api ) {
			$this->wp_api = $wp_api;
		}
	}

	abstract protected function init_template_base_dir();

	public function show( $template = null, $model = null ) {
		echo $this->get_view( $template, $model );
	}

	/**
	 * @param $template
	 * @param $model
	 *
	 * @return string
	 * @throws \Twig_Error_Syntax
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Loader
	 */
	public function get_view( $template = null, $model = null ) {
		$output = '';
		$this->maybe_init_twig();

		if ( $model === null ) {
			$model = $this->get_model();
		}
		if ( $template === null ) {
			$template = $this->get_template();
		}

		try {
			$output = $this->twig->render( $template, $model );
		} catch( RuntimeException $e ) {
			$this->add_exception_notice( $e );
		}

		return $output;
	}

	private function maybe_init_twig() {
		if ( ! $this->twig ) {
			$loader = new Twig_Loader_Filesystem( $this->template_paths );

			$environment_args = array();

			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				$environment_args[ 'debug' ] = true;
			}

			$wpml_cache_directory  = new WPML_Cache_Directory( new WPML_WP_API() );
			$this->cache_directory = $wpml_cache_directory->get( 'twig' );

			if ( $this->cache_directory ) {
				$environment_args[ 'cache' ]       = $this->cache_directory;
				$environment_args[ 'auto_reload' ] = true;
			}

			$this->twig = new Twig_Environment( $loader, $environment_args );
			if ( $this->custom_functions && count( $this->custom_functions ) > 0 ) {
				foreach ( $this->custom_functions as $custom_function ) {
					$this->twig->addFunction( $custom_function );
				}
			}
			if ( $this->custom_filters && count( $this->custom_filters ) > 0 ) {
				foreach ( $this->custom_filters as $custom_filter ) {
					$this->twig->addFilter( $custom_filter );
				}
			}
		}
	}

	abstract public function get_template();

	abstract public function get_model();

	protected function &get_twig() {
		return $this->twig;
	}

	/**
	 * @param RuntimeException $e
	 */
	private function add_exception_notice( RuntimeException $e ) {
		if ( preg_match( '/create/', $e->getMessage() ) ) {
			$text = sprintf( __( 'WPML could not create a cache directory in %s', 'sitepress' ), $this->cache_directory );
		} else {
			$text = sprintf( __( 'WPML could not write in the cache directory: %s', 'sitepress' ), $this->cache_directory );
		}

		$notice = new WPML_Notice( 'exception', $text, self::NOTICE_GROUP );
		$notice->set_dismissible( true );
		$notice->set_css_class_types( 'notice-error' );
		$admin_notices = $this->get_wp_api()->get_admin_notices();
		$admin_notices->add_notice( $notice );
	}

	/**
	 * @return WPML_WP_API
	 */
	private function get_wp_api() {
		if ( ! $this->wp_api ) {
			$this->wp_api = new WPML_WP_API();
		}

		return $this->wp_api;
	}
}