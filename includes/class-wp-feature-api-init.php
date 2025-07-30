<?php
/**
 * WordPress Feature API Initialization
 *
 * @package WordPress\Feature_API
 */

/**
 * Handles the initialization of WordPress Feature API components.
 */
class WP_Feature_API_Init {

	/**
	 * Initializes the WordPress Feature API core components.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public static function initialize() {

		// Run the wp_feature_api_init action after the regular REST routes are registered.
		add_action( 'rest_api_init', array( __CLASS__, 'run_init_action' ), 110 );
		add_action( 'parse_request', array( __CLASS__, 'run_init_action' ), 11 );

		// Register REST routes after the regular REST routes are registered
		// (at rest_api_init:99 action which is triggered by parse_request:10 action).
		// This ensures that the Feature API won't trigger creating the global WP rest api server
		// too early (during the `init` action).
		add_action( 'rest_api_init', array( __CLASS__, 'register_rest_routes' ), 120 );

		// enqueue admin scripts.
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_scripts' ) );
	}

	/**
	 * Enqueues admin scripts.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public static function enqueue_admin_scripts() {
		if ( ! is_admin() ) {
			return;
		}
		// Check for the file before requiring it.
		if ( ! file_exists( WP_FEATURE_API_PLUGIN_DIR . 'build/index.asset.php' ) ) {
			if ( WP_DEBUG ) {
				wp_trigger_error( '', 'Assets file not found, please run the build for the WordPress Feature API plugin.' );
			}
			return;
		}
		$assets = require WP_FEATURE_API_PLUGIN_DIR . 'build/index.asset.php';
		wp_enqueue_script( 'wp-features', WP_FEATURE_API_PLUGIN_URL . 'build/index.js', $assets['dependencies'], $assets['version'], true );

	}

	/**
	 * Runs the wp_feature_api_init action.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public static function run_init_action() {
		static $has_run = false;

		if ( ! $has_run ) {
			$has_run = true;
			do_action( 'wp_feature_api_init' );
		}
	}

	/**
	 * Registers the REST API routes for the Feature API.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public static function register_rest_routes() {
		$controller = new WP_REST_Feature_Controller();
		$controller->register_routes();
	}

}

WP_Feature_API_Init::initialize();
