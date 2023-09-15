<?php
/**
 * Rest API to fetch school names.
 *
 * @package WRTTheme
 */
namespace WRTTheme\RestAPI\SchoolNames;

/**
 * Hook into rest api init action
 *
 * @since 0.1.0
 *
 * @uses  add_action()
 *
 * @return void
 */
function setup() {
	$n = function ( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'rest_api_init', $n ( 'register_endpoint' ) );
}

/**
 * Register school REST API endpoint
 */
function register_endpoint() {
	$args = array(
		'zipcode' => array(
			'required'          => true,
			'sanitize_callback' => 'sanitize_text_field',
			'validate_callback' => __NAMESPACE__ . '\validate_zipcode',
		),
	);

	register_rest_route(
		'weareteachers',
		"/v1/schoolnames",
		array(
			'methods'  => \WP_REST_Server::READABLE,
			'callback' => __NAMESPACE__ . '\get_schools',
			'args'     => $args,
		)
	);
}

/**
 * School endpoint callback
 *
 * @param \WP_REST_Request $request
 *
 * @return mixed
 */
function get_schools( \WP_REST_Request $request ) {
	$response = array(
		'found_schools' => 0,
		'schools'       => array(),
	);

	if ( isset( $request['zipcode'] ) && ! empty( $request['zipcode'] ) ) {
		$response = get_school_by_zip( $request['zipcode'] );
	}

	return rest_ensure_response( $response );
}

/**
 * Return school by zipcode
 *
 * @param $zipcode
 *
 * @return array
 */
function get_school_by_zip( $zipcode ) {
	$query_args = array(
		'posts_per_page'         => 100,
		'post_type'              => 'wat-school',
		'post_status'            => 'publish',
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
		'orderby'                => 'post_title',
		'order'                  => 'ASC',
		'meta_query'             => array(
			array(
				'key'     => 'mdr_mzipcode',
				'value'   => ltrim($zipcode, '0'),
				'compare' => '=',
			)
		)
	);
    //if(preg_match("^(?:0|00)\d+$/", $zipcode)) {$zipcode = $zipcode }

	$key = "schools-" . ltrim($zipcode, '0');

	$response = wp_cache_get( $key );

	if ( false === $response ) {
		$results = new \WP_Query( $query_args );

		if ( $results->have_posts() ) {
			foreach ( $results->posts as $post ) {
				$school                = new \stdclass();
				$school->text          = $post->post_title;
				$school->value           = esc_js( get_post_meta( $post->ID, 'mdr_pid', true ) );
				$response['schools'][] = $school;
			}

			$response['found_schools'] = (int) count( $response['schools'] );

			wp_cache_set( $key, $response, MONTH_IN_SECONDS );
			wp_reset_postdata();
		} else {
			$response = array(
				'found_schools' => 0,
				'schools'       => array(),
			);
		}
	}

	return $response;
}

/**
 * Make sure that the zip code parameter passed matches a 5 digit number
 * g *
 *
 * @param $zip
 *
 * @return bool
 */
function validate_zipcode( $zip ) {
	return preg_match( '/^[0-9]{5}$/', $zip ) > 0;
}
