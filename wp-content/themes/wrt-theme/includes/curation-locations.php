<?php
/**
 * Curation Locations.
 *
 * @package WRTTheme
 */

namespace WRTTheme\CurationLocations;

use WRTPlugin\ModuleInitialization;
use WRTPlugin\Utility;
use WRTTheme\CurationLocations\CurationLocationFactory;
use function WRTSailthru\Recommendations\get_recommendations;
use function WRTSailthru\Recommendations\create_vars_from_post;
use function WRTSailthru\Recommendations\get_sailthru_section_id;


/**
 * Set up curation locations.
 *
 * @return void
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'init', $n( 'register_curation_locations' ) );
}

/**
 * Register curation locations.
 *
 * @return void
 */
function register_curation_locations() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	// Retrieve the factory.
	$factory   = CurationLocationFactory::factory();
	$locations = array(
		'homepage'        => $n( 'get_homepage_hydrator_cb' ),
		'classroom_ideas' => $n( 'get_classroom_ideas_hydrator_cb' ),
		'life_wellbeing'  => $n( 'get_life_wellbeing_hydrator_cb' ),
		'most_popular'    => $n( 'get_most_popular_hydrator_cb' ),
		'printables'      => $n( 'get_printables_hydrator_cb' ),
	);

	foreach ( $locations as $location_id => $callback ) {
		$factory->create_location( $location_id, $factory->create_hydrator( $location_id, $callback ) );
	}
}

/**
 * Homepage location hydrator callback
 *
 * @return array
 */
function get_homepage_hydrator_cb() : array {
	$posts = get_posts(
		[
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 100,
		]
	);

	return $posts;
}

/**
 * Classroom Ideas location hydrator callback
 *
 * @return array
 */
function get_classroom_ideas_hydrator_cb() : array {
	$posts = get_posts(
		[
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 100,
			'tax_query'      => [
				[
					'taxonomy' => 'category',
					'field'    => 'slug',
					'terms'    => 'teaching-strategies',
				],
			],
		]
	);

	return $posts;
}

/**
 * Homepage location hydrator callback
 *
 * @return array
 */
function get_life_wellbeing_hydrator_cb() : array {
	$posts = get_posts(
		[
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 100,
			'tax_query'      => [
				[
					'taxonomy' => 'category',
					'field'    => 'slug',
					'terms'    => 'teacher-life',
				],
			],
		]
	);

	return $posts;
}

/**
 * Homepage printables hydrator callback.
 *
 * @return array
 */
function get_printables_hydrator_cb() : array {
	$posts = get_posts(
		[
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 100,
			'tax_query'      => [
				[
					'taxonomy' => 'category',
					'field'    => 'slug',
					'terms'    => 'free-printables-for-teachers',
				],
			],
		]
	);

	return $posts;
}

/**
 * Most popular location hydrator callback
 *
 * @todo This is a temporary query.
 * @return array
 */
function get_most_popular_hydrator_cb() : array {
	$sailthru_section_id = get_sailthru_section_id( 'hp-most-popular' );
	$recommendations     = get_recommendations( $sailthru_section_id, array() );
	$recommended_ids     = wp_list_pluck( $recommendations, 'ID' );

	if ( empty( $recommended_ids ) ) {
		return [];
	}

	$posts = get_posts(
		[
			'post_type'   => 'post',
			'post_status' => 'publish',
			'post__in'    => $recommended_ids,
		]
	);

	return $posts;
}
