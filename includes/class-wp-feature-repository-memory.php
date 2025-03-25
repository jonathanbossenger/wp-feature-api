<?php
/**
 * WP_Feature_Repository_Memory class file.
 *
 * @package WordPress\Features_API
 */

/**
 * Class WP_Feature_Repository_Memory
 *
 * An in-memory implementation of the WP_Feature_Repository_Interface.
 *
 * @since 0.1.0
 */
class WP_Feature_Repository_Memory implements WP_Feature_Repository_Interface {

	/**
	 * The features stored in memory.
	 *
	 * @since 0.1.0
	 * @var array
	 */
	private $features = array(
		WP_Feature::TYPE_RESOURCE => array(),
		WP_Feature::TYPE_TOOL => array(),
	);

	/**
	 * Saves a feature to the repository.
	 *
	 * @since 0.1.0
	 * @param WP_Feature $feature The feature to save.
	 * @return bool True if the feature was saved successfully, false otherwise.
	 */
	public function save( $feature ) {
		$feature = WP_Feature::make( $feature );

		if ( ! $feature ) {
			return false;
		}

		$this->features[ $feature->get_type() ][ $feature->get_id() ] = $feature;

		return true;
	}

	/**
	 * Deletes a feature from the repository.
	 *
	 * @since 0.1.0
	 * @param string|WP_Feature $feature The feature ID or feature object to delete.
	 * @return bool True if the feature was deleted successfully, false otherwise.
	 */
	public function delete( $feature ) {
		$feature = WP_Feature::make( $feature );

		if ( ! $feature ) {
			return false;
		}

		$type_index = $this->features[ $feature->get_type() ];

		if ( ! isset( $type_index[ $feature->get_id() ] ) ) {
			return false;
		}

		unset( $type_index[ $feature->get_id() ] );
		return true;
	}

	/**
	 * Finds a feature by its ID.
	 *
	 * @since 0.1.0
	 * @param string|WP_Feature $feature The feature ID or feature object to find.
	 * @param string|null       $type    The type of feature to find.
	 * @return WP_Feature|null The feature if found, null otherwise.
	 */
	public function find( $feature, $type = null ) {
		$feature = WP_Feature::make( $feature );

		if ( ! $feature ) {
			return null;
		}

		if ( $type ) {
			return isset( $this->features[ $type ][ $feature->get_id() ] ) ? $this->features[ $type ][ $feature->get_id() ] : null;
		}

		return isset( $this->features[ $feature->get_type() ][ $feature->get_id() ] ) ? $this->features[ $feature->get_type() ][ $feature->get_id() ] : null;
	}

	/**
	 * Queries features based on a query.
	 *
	 * @since 0.1.0
	 * @param WP_Feature_Query $query The query to filter features by.
	 * @return array The matching features.
	 */
	public function query( $query ) {
		if ( ! $query instanceof WP_Feature_Query ) {
			return array();
		}

		$matches = array();
		foreach ( $this->features as $type => $features ) {
			foreach ( $features as $feature ) {
				if ( $query->matches( $feature ) ) {
					$matches[] = $feature;
				}
			}
		}

		return $matches;
	}

	/**
	 * Gets all features in the repository.
	 *
	 * @since 0.1.0
	 * @return array All features.
	 */
	public function get_all() {
		return array_merge(
			array_values( $this->features[ WP_Feature::TYPE_RESOURCE ] ),
			array_values( $this->features[ WP_Feature::TYPE_TOOL ] )
		);
	}

	/**
	 * Clears all features from the repository.
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function clear() {
		$this->features = array();
	}
}
