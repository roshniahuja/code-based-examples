<?php
/**
 * Custom template tags for this theme.
 *
 * This file is for custom template tags only and it should not contain
 * functions that will be used for filtering or adding an action.
 *
 * All functions should be prefixed with Endgame360 in order to prevent
 * pollution of the global namespace and potential conflicts with functions
 * from plugins.
 * Example: `WRT_THEME_function()`
 *
 * @package WRTTheme\Template_Tags
 */

namespace WRTTheme\Template_Tags;

use WP_Query;
use WRTSailthru\API\Api;

/**
 * Standard SVG settings for escaping through `wp_kses()` function.
 *
 * @return array Array of allowed HTML tags and their allowed attributes.
 */
function svg_kses_allowed_html() {
	$kses_defaults = wp_kses_allowed_html( 'post' );

	$svg_args = array(
		'svg'            => array(
			'version'           => true,
			'class'             => true,
			'fill'              => true,
			'height'            => true,
			'xml:space'         => true,
			'xmlns'             => true,
			'xmlns:xlink'       => true,
			'viewbox'           => true,
			'enable-background' => true,
			'width'             => true,
			'x'                 => true,
			'y'                 => true,
		),
		'path'           => array(
			'clip-rule'       => true,
			'd'               => true,
			'fill'            => true,
			'fill-rule'       => true,
			'stroke'          => true,
			'stroke-width'    => true,
			'stroke-linecap'  => true,
			'stroke-linejoin' => true,
			'class'           => true,
		),
		'g'              => array(
			'clip-rule'    => true,
			'd'            => true,
			'transform'    => true,
			'fill'         => true,
			'fill-rule'    => true,
			'stroke'       => true,
			'stroke-width' => true,
			'class'        => true,
		),
		'rect'           => array(
			'clip-rule'    => true,
			'd'            => true,
			'transform'    => true,
			'fill'         => true,
			'fill-rule'    => true,
			'stroke'       => true,
			'stroke-width' => true,
			'width'        => true,
			'height'       => true,
			'rx'           => true,
			'ry'           => true,
			'x'            => true,
			'y'            => true,
			'class'        => true,
		),
		'polygon'        => array(
			'clip-rule'    => true,
			'd'            => true,
			'fill'         => true,
			'fill-rule'    => true,
			'stroke'       => true,
			'stroke-width' => true,
			'points'       => true,
			'class'        => true,
		),
		'circle'         => array(
			'clip-rule'    => true,
			'd'            => true,
			'fill'         => true,
			'fill-rule'    => true,
			'stroke'       => true,
			'stroke-width' => true,
			'cx'           => true,
			'cy'           => true,
			'r'            => true,
			'class'        => true,
		),
		'lineargradient' => array(
			'id'                => true,
			'gradientunits'     => true,
			'x'                 => true,
			'y'                 => true,
			'x2'                => true,
			'y2'                => true,
			'gradienttransform' => true,
		),
		'stop'           => array(
			'offset' => true,
			'style'  => true,
		),
		'image'          => array(
			'height'     => true,
			'width'      => true,
			'xlink:href' => true,
		),
		'defs'           => array(
			'clipPath' => true,
		),
		'style'          => true,
	);

	return array_merge( $kses_defaults, $svg_args );
}

/**
 * Get related posts for a specified post.
 *
 * @return WP_Query
 */
function get_related_posts(): WP_Query {
	// Get the current post ID
	global $post;
	return new WP_Query(
		array(
			'post_type'              => 'post',
			'posts_per_page'         => 2,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'category__in'           => wp_get_post_categories( $post->ID ),
			'post__not_in'           => array( $post->ID ),
		)
	);
}

/**
 * Get share URL.
 *
 * @param string $type Social network type. Accepts: facebook, twitter, linkedin, pinterest, email.
 *
 * @return string
 */
function get_share_url( string $type ): string {
	$current_url = rawurlencode( get_permalink() );

	switch ( $type ) {
		case 'facebook':
			return 'https://www.facebook.com/sharer/sharer.php?u=' . $current_url;
		case 'twitter':
			return 'https://twitter.com/intent/tweet?url=' . $current_url;
		case 'linkedin':
			return 'https://www.linkedin.com/sharing/share-offsite/?url=' . $current_url;
		case 'pinterest':
			return 'https://pinterest.com/pin/create/button/?url=' . $current_url;
		case 'email':
			$subject = rawurlencode( 'Check out this We Are Teachers Article I found!' );
			$body    = rawurlencode( 'I thought you might be interested in this article: ' . $current_url );
			return 'mailto:?subject=' . $subject . '&body=' . $body;
		default:
			return '#'; // Return a default value or handle unsupported share types.
	}
}

/**
 * Get the post read count.
 *
 * @param int  $post_id  Post ID.
 * @param bool $call_api Call the Sailthru API.
 *
 * @return int
 */
function get_post_read_count( int $post_id, bool $call_api = false ): int {
	if ( ! $post_id ) {
		return 0;
	}

	$reads = wp_cache_get( 'post_reads_' . $post_id, 'sailthru_content' );
	if ( false === $reads && $call_api ) {
		$url = get_permalink( $post_id );

		// TODO: remove debug code START.
		$host = wp_parse_url( $url, PHP_URL_HOST );
		$url  = str_replace( $host, 'www.weareteachers.com', $url );
		// TODO: remove debug code END.

		$data = Api::instance()->get_content( $url );

		if ( is_wp_error( $data ) ) {
			$reads = 0;
		} else {
			$reads = $data['views'] ?? 0;
		}

		$cache_time = 0 === $reads ? MINUTE_IN_SECONDS * 5 : HOUR_IN_SECONDS;
		wp_cache_add( 'post_reads_' . $post_id, $reads, 'sailthru_content', $cache_time );
	}

	return $reads;
}
