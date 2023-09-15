<?php
/**
 * Curation Location Class
 *
 * @package WRTTheme
 */

namespace WRTTheme\CurationLocations;

use ArrayIterator;

/**
 * Curation Location
 */
class CurationLocation {
	/**
	 * Location ID.
	 *
	 * @var string
	 */
	protected $location_id;

	/**
	 * Hydrator.
	 *
	 * @var CurationLocationHydrator
	 */
	protected $hydrator;

	/**
	 * Items.
	 *
	 * @var \ArrayIterator
	 */
	protected $items;

	/**
	 * Deduper
	 *
	 * @var CurationDeduper
	 */
	protected $deduper;

	/**
	 * CurationLocation class constructor.
	 *
	 * @param string                   $location_id The location id.
	 * @param CurationLocationHydrator $hydrator    The hydrator.
	 */
	public function __construct( string $location_id, CurationLocationHydrator $hydrator ) {
		$this->location_id = sanitize_key( $location_id );
		$this->hydrator    = $hydrator;
		$this->deduper     = CurationDeduper::get_instance();

		$this->hydrate();
	}

	/**
	 * Get location id.
	 *
	 * @return string
	 */
	public function get_location() : string {
		return $this->location_id;
	}

	/**
	 * Get items.
	 *
	 * @return array
	 */
	public function get_items() : array {
		return $this->items->getArrayCopy();
	}

	/**
	 * Get next item.
	 *
	 * @return mixed
	 */
	public function get_next_item() {
		$current = $this->items->current();
		$this->items->next();

		// Dedupe WordPress Posts, skipping posts pulled from Sailthru.
		if ( $current instanceof \WP_Post ) {
			while ( $this->deduper::was_used( $current->ID ) ) {
				$current = $this->items->current();
				$this->items->next();
			}
			$this->deduper::use( $current->ID );
		}

		return $current;
	}

	/**
	 * Get the current item.
	 *
	 * @return mixed
	 */
	public function get_current_item() {
		return $this->items->current();
	}

	/**
	 * Hydrate the location with items.
	 *
	 * @return CurationLocation
	 */
	protected function hydrate() : CurationLocation {
		$items = $this->hydrator->get_items();

		if ( ! is_array( $items ) ) {
			$this->items = new ArrayIterator( [] );
		}

		$this->items = new ArrayIterator( $items );

		return $this;
	}

	/**
	 * Expiremental: Get the main query as the hydrator.
	 *
	 * @static
	 * @return array
	 */
	public static function get_main_query_hydrator() {
		global $wp_query;

		return $wp_query->posts();
	}
}
