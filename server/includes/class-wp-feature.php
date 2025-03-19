<?php
/**
 * WP_Feature class file.
 *
 * @package WordPress\Features_API
 */

/**
 * Class WP_Feature
 *
 * Represents a feature in the WordPress Features API.
 *
 * @since 0.1.0
 */
class WP_Feature {

	/**
	 * The feature ID, unique identifier.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	private $id;

	/**
	 * The feature name, human readable.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	private $name;

	/**
	 * The feature description.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	private $description;

	/**
	 * The feature type.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	private $type;

	/**
	 * The feature metadata.
	 *
	 * @since 0.1.0
	 * @var array
	 */
	private $meta;

	/**
	 * The feature categories.
	 *
	 * @since 0.1.0
	 * @var array
	 */
	private $categories;

	/**
	 * The feature input schema.
	 *
	 * @since 0.1.0
	 * @var array
	 */
	private $input_schema;

	/**
	 * The feature output schema.
	 *
	 * @since 0.1.0
	 * @var array
	 */
	private $output_schema;

	/**
	 * The feature callback.
	 *
	 * @since 0.1.0
	 * @var callable
	 */
	private $callback;

	/**
	 * The feature permissions.
	 *
	 * @since 0.1.0
	 * @var string|array|callable
	 */
	private $permissions;

	/**
	 * The feature filter.
	 *
	 * @since 0.1.0
	 * @var callable
	 */
	private $filter;

	/**
	 * The feature location.
	 *
	 * @since 0.1.0
	 * @var array
	 */
	private $location;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 * @param array $args The feature arguments.
	 */
	public function __construct( $args ) {
		$this->id            = isset( $args['id'] ) ? $args['id'] : '';
		$this->name          = isset( $args['name'] ) ? $args['name'] : '';
		$this->description   = isset( $args['description'] ) ? $args['description'] : '';
		$this->type          = isset( $args['type'] ) ? $args['type'] : 'resource';
		$this->meta          = isset( $args['meta'] ) ? $args['meta'] : array();
		$this->categories    = isset( $args['categories'] ) ? $args['categories'] : array();
		$this->input_schema  = isset( $args['input_schema'] ) ? $args['input_schema'] : array();
		$this->output_schema = isset( $args['output_schema'] ) ? $args['output_schema'] : array();
		$this->callback      = isset( $args['callback'] ) ? $args['callback'] : null;
		$this->permissions   = isset( $args['permissions'] ) ? $args['permissions'] : '';
		$this->filter        = isset( $args['filter'] ) ? $args['filter'] : null;
		$this->location      = isset( $args['_location'] ) ? $args['_location'] : array( 'server' );
	}

	/**
	 * Gets the feature ID.
	 *
	 * @since 0.1.0
	 * @return string The feature ID.
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Gets the feature name.
	 *
	 * @since 0.1.0
	 * @return string The feature name.
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Gets the feature description.
	 *
	 * @since 0.1.0
	 * @return string The feature description.
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Gets the feature type.
	 *
	 * @since 0.1.0
	 * @return string The feature type.
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * Gets the feature metadata.
	 *
	 * @since 0.1.0
	 * @return array The feature metadata.
	 */
	public function get_meta() {
		return $this->meta;
	}

	/**
	 * Gets the feature categories.
	 *
	 * @since 0.1.0
	 * @return array The feature categories.
	 */
	public function get_categories() {
		return $this->categories;
	}

	/**
	 * Gets the feature input schema.
	 *
	 * @since 0.1.0
	 * @return array The feature input schema.
	 */
	public function get_input_schema() {
		return $this->input_schema;
	}

	/**
	 * Gets the feature output schema.
	 *
	 * @since 0.1.0
	 * @return array The feature output schema.
	 */
	public function get_output_schema() {
		return $this->output_schema;
	}

	/**
	 * Gets the feature callback.
	 *
	 * @since 0.1.0
	 * @return callable|null The feature callback.
	 */
	public function get_callback() {
		return $this->callback;
	}

	/**
	 * Gets the feature permissions.
	 *
	 * @since 0.1.0
	 * @return string|array|callable The feature permissions.
	 */
	public function get_permissions() {
		return $this->permissions;
	}

	/**
	 * Gets the feature filter.
	 *
	 * @since 0.1.0
	 * @return callable|null The feature filter.
	 */
	public function get_filter() {
		return $this->filter;
	}

	/**
	 * Gets the feature location.
	 *
	 * @since 0.1.0
	 * @return array The feature location.
	 */
	public function get_location() {
		return $this->location;
	}

	/**
	 * Runs the feature.
	 *
	 * @since 0.1.0
	 * @param array $context The context to run the feature with.
	 * @return mixed The result of running the feature.
	 */
	public function run( $context = array() ) {
		// Validate the input against the schema if available.
		if ( ! empty( $this->input_schema ) ) {
			$valid = $this->validate_input( $context );
			if ( is_wp_error( $valid ) ) {
				return $valid;
			}
		}

		// Check if the feature has a callback.
		if ( ! is_callable( $this->callback ) ) {
			return new WP_Error(
				'feature_no_callback',
				__( 'The feature does not have a callback.', 'wp-features-api' )
			);
		}

		// Run the feature callback.
		$result = call_user_func( $this->callback, $context );

		// Validate the output against the schema if available.
		if ( ! empty( $this->output_schema ) ) {
			$valid = $this->validate_output( $result );
			if ( is_wp_error( $valid ) ) {
				return $valid;
			}
		}

		return $result;
	}

	/**
	 * Validates the input against the schema.
	 *
	 * @since 0.1.0
	 * @param array $input The input to validate.
	 * @return true|WP_Error True if valid, WP_Error otherwise.
	 */
	private function validate_input( $input ) {
		// TODO: Implement input validation against schema.
		return true;
	}

	/**
	 * Validates the output against the schema.
	 *
	 * @since 0.1.0
	 * @param mixed $output The output to validate.
	 * @return true|WP_Error True if valid, WP_Error otherwise.
	 */
	private function validate_output( $output ) {
		// TODO: Implement output validation against schema.
		return true;
	}

	/**
	 * Converts the feature to an array.
	 *
	 * @since 0.1.0
	 * @return array The feature as an array.
	 */
	public function to_array() {
		return array(
			'id'            => $this->id,
			'name'          => $this->name,
			'description'   => $this->description,
			'type'          => $this->type,
			'meta'          => $this->meta,
			'categories'    => $this->categories,
			'input_schema'  => $this->input_schema,
			'output_schema' => $this->output_schema,
			'permissions'   => $this->permissions,
			'_location'     => $this->location,
		);
	}
}
