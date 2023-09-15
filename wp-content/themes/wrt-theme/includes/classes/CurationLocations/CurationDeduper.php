<?php
/**
 * Curation Deduper Class
 *
 * @package WRTTheme
 */

namespace WRTTheme\CurationLocations;

/**
 * Curation Location
 */
class CurationDeduper {
	/**
	 * Instance of this class.
	 *
	 * @var CurationDeduper
	 * @static
	 */
	public static $instance;

	/**
	 * Used posts.
	 *
	 * @var array
	 * @static
	 */
	public static $used = array();

	/**
	 * Mark a post id as used.
	 *
	 * @param int $post_id The post id.
	 * @static
	 * @return void
	 */
	public static function use( $post_id ) {
		self::$used[] = absint( $post_id );
	}

	/**
	 * Returns a boolean indicating whether a post was used.
	 *
	 * @param int $post_id The post id to check.
	 * @static
	 * @return boolean
	 */
	public static function was_used( $post_id ) : bool {
		return in_array( absint( $post_id ), self::$used, true );
	}

	/**
	 * Returns an array of all used post ids.
	 *
	 * @static
	 * @return array
	 */
	public static function get_used() : array {
		return self::$used;
	}

	/**
	 * Get instance of the CurationDeduper class.
	 *
	 * @static
	 * @return CurationDeduper
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}
