<?php
/**
 * Curation Location Factory Class
 *
 * @package WRTTheme
 */

namespace WRTTheme\CurationLocations;

/**
 * Class: CurationLocationFactory.
 */
class CurationLocationFactory {
	/**
	 * Factory singleton instance.
	 *
	 * @static
	 * @var CurationLocationFactory
	 */
	protected static $factory;

	/**
	 * Locations
	 *
	 * @var array<CurationLocation>
	 */
	protected $locations;

	/**
	 * CurationLocationFactory class constructor.
	 */
	public function __construct() {
		$this->locations = array();
	}

	/**
	 * Create location.
	 *
	 * @param string                   $location_id The location id.
	 * @param CurationLocationHydrator $hydrator    The hydrator for that location.
	 * @return CurationLocation
	 */
	public function create_location( string $location_id, CurationLocationHydrator $hydrator ) {
		$this->locations[ $location_id ] = new CurationLocation( $location_id, $hydrator );

		return $this->get_location( $location_id );
	}

	/**
	 * Create hydrator
	 *
	 * @param string   $location_id The location id.
	 * @param callable $callback    The callback function to use to hydrate location with items.
	 * @return CurationLocationHydrator
	 */
	public function create_hydrator( string $location_id, callable $callback ) : CurationLocationHydrator {
		return new CurationLocationHydrator( $location_id, $callback );
	}

	/**
	 * Get location
	 *
	 * @param string $location_id The location id.
	 * @return CurationLocation|null
	 */
	public function get_location( string $location_id ) : ?CurationLocation {
		return $this->locations[ $location_id ];
	}

	/**
	 * Utility: Maybe is ServerSideRendering
	 *
	 * @return boolean
	 */
	public static function util_maybe_is_ssr() : bool {
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return true;
		}

		return wp_is_json_request();
	}

	/**
	 * Factory.
	 *
	 * Primary entrypoint to the CurationLocationFactory class.
	 *
	 * @return CurationLocationFactory
	 */
	public static function factory() : CurationLocationFactory {
		if ( is_null( self::$factory ) ) {
			self::$factory = new self();
		}

		return self::$factory;
	}
}
