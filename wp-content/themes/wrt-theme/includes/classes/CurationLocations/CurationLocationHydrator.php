<?php
/**
 * Curation Location Hydrator Class
 *
 * @package WRTTheme
 */

namespace WRTTheme\CurationLocations;

use \WP_REST_Server;

/**
 * Class: CurationLocationHydrator
 */
class CurationLocationHydrator {
	/**
	 * Callback
	 *
	 * @var callable
	 */
	protected $callback;

	/**
	 * Location
	 *
	 * @var string
	 */
	protected $location;

	/**
	 * Route
	 *
	 * @var string
	 */
	protected $route;

	/**
	 * CurationLocationHydrator class constructor
	 *
	 * @param string   $location The location.
	 * @param callable $callback The callback the hydrator will use to fetch items.
	 */
	public function __construct( string $location, callable $callback ) {
		$this->callback = is_callable( $callback ) ? $callback : $this->get_default_hydrator();
		$this->location = $location;

		$this->set_endpoint( $this->location );
		$this->create_endpoint();
	}

	/**
	 * Get items.
	 *
	 * @return mixed
	 */
	public function get_items() {
		return call_user_func( $this->callback );
	}

	/**
	 * Set callback.
	 *
	 * @param callable $callback Callback.
	 * @return CurationLocationHydrator
	 */
	public function set_callback( callable $callback ) : CurationLocationHydrator {
		$this->callback = $callback;

		return $this;
	}

	/**
	 * Set endpoint
	 *
	 * @param string $route The route.
	 * @return CurationLocationHydrator
	 */
	public function set_endpoint( string $route ) : CurationLocationHydrator {
		$this->route = $route;

		return $this;
	}

	/**
	 * Create endpoint
	 *
	 * @return void
	 */
	public function create_endpoint() {
		add_action(
			'rest_api_init',
			function() {
				register_rest_route(
					'wrt/v1',
					'/curation-locations/hydrators/' . $this->route,
					[
						'methods'  => WP_REST_Server::READABLE,
						'callback' => function() {
							return rest_ensure_response( call_user_func( $this->callback ) );
						},
					]
				);
			}
		);
	}

	/**
	 * Get default hydrator.
	 *
	 * @return callable
	 */
	protected function get_default_hydrator() : callable {
		return function() {
			return get_posts();
		};
	}
}
