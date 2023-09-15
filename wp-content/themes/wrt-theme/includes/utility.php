<?php
/**
 * Utility functions for the theme.
 *
 * This file is for custom helper functions.
 * These should not be confused with WordPress template
 * tags. Template tags typically use prefixing, as opposed
 * to Namespaces.
 *
 * @link https://developer.wordpress.org/themes/basics/template-tags/
 * @package WRTTheme
 */

namespace WRTTheme\Utility;

use WP_Query;
use WRTPlugin\PostTypes\ProductPostType;
use WRTPlugin\Taxonomies\GradeTaxonomy;
use WRTTheme\ShadowTax\TDS as TDS;
use function WRTTheme\Template_Tags\svg_kses_allowed_html;

/**
 * Get asset info from extracted asset files
 *
 * @param string $slug Asset slug as defined in build/webpack configuration
 * @param string $attribute Optional attribute to get. Can be version or dependencies
 * @return string|array
 */
function get_asset_info( $slug, $attribute = null ) {
	if ( file_exists( WRT_THEME_PATH . 'dist/js/' . $slug . '.asset.php' ) ) {
		$asset = require WRT_THEME_PATH . 'dist/js/' . $slug . '.asset.php';
	} elseif ( file_exists( WRT_THEME_PATH . 'dist/css/' . $slug . '.asset.php' ) ) {
		$asset = require WRT_THEME_PATH . 'dist/css/' . $slug . '.asset.php';
	} else {
		return null;
	}

	if ( ! empty( $attribute ) && isset( $asset[ $attribute ] ) ) {
		return $asset[ $attribute ];
	}

	return $asset;
}

/**
 * Extract colors from a CSS or Sass file
 *
 * @param string $path the path to your CSS variables file
 */
function get_colors( $path ) {

	$dir = get_stylesheet_directory();

	if ( file_exists( $dir . $path ) ) {
		$css_vars = file_get_contents( $dir . $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		// HEX(A) | RGB(A) | HSL(A) - rgba & hsla alpha as decimal or percentage
		// https://regex101.com/r/l7AZ8R/
		// this is a loose match and will accept almost anything within () for rgb(a) & hsl(a)
		// a more optinionated solution is WIP here if you can improve on it https://regex101.com/r/FEtzDu/
		preg_match_all( '(#(?:[\da-f]{3}){1}\b|#(?:[\da-f]{2}){3,4}\b|(rgb|hsl)a?\((\s|\d|[a-zA-Z]+|,|-|%|\.|\/)+\))', $css_vars, $matches );

		return $matches[0];
	}

}

/**
 * Adjust the brightness of a color (HEX)
 *
 * @param string $hex The hex code for the color
 * @param number $steps amount you want to change the brightness
 * @return string new color with brightness adjusted
 */
function adjust_brightness( $hex, $steps ) {

	// Steps should be between -255 and 255. Negative = darker, positive = lighter
	$steps = max( -255, min( 255, $steps ) );

	// Normalize into a six character long hex string
	$hex = str_replace( '#', '', $hex );
	if ( 3 === strlen( $hex ) ) {
		$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
	}

	// Split into three parts: R, G and B
	$color_parts = str_split( $hex, 2 );
	$return      = '#';

	foreach ( $color_parts as $color ) {
		$color   = hexdec( $color ); // Convert to decimal
		$color   = max( 0, min( 255, $color + $steps ) ); // Adjust color
		$return .= str_pad( dechex( $color ), 2, '0', STR_PAD_LEFT ); // Make two char hex code
	}

	return $return;

}

/**
 * Get the search query arguments for a taxonomy
 *
 * @param string $taxonomy The taxonomy to get the search query arguments for
 * @return array The search query arguments
 */
function get_search_query_tax_args( $taxonomy ) {

	// Get the search query arguments
	$search_args      = array();
	$search_args['s'] = get_query_var( 's' );

	// Get the selected term IDs from the search query
	if ( isset( $_GET[ $taxonomy ] ) ) {
		$selected_terms = array_map( 'absint', $_GET[ $taxonomy ] );
	} else {
		$selected_terms = array();
	}

	// Include the selected terms in the search query arguments
	if ( ! empty( $selected_terms ) ) {
		$search_args['tax_query'] = array(
			array(
				'taxonomy' => $taxonomy,
				'field'    => 'term_id',
				'terms'    => $selected_terms,
			),
		);
	}

	return $search_args;
}

/**
 * Get the filtered, sorted and selected terms for a given taxonomy
 *
 * @param string $taxonomy     Taxonomy name.
 * @param string $selected_key Key of the selected terms in the search query arguments.
 * @param string $name_pattern Regular expression pattern to filter the terms by name.
 *
 * @return array - the filtered, sorted and selected terms
 */
function get_filtered_sorted_selected_terms( $taxonomy, $selected_key, $name_pattern ) {
	// Get all child terms of the parent category.
	$terms = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
		)
	);

	// Only show terms matching the pattern.
	$terms = array_filter(
		$terms,
		function( $term ) use ( $name_pattern ) {
			return preg_match( $name_pattern, $term->name );
		}
	);

	// Sort terms by a custom comparison function.
	usort(
		$terms,
		function( $a, $b ) {
			// Get the first character of each term's name.
			$a_first_char = substr( $a->name, 0, 1 );
			$b_first_char = substr( $b->name, 0, 1 );

			// Determine if the terms are character terms or digit terms.
			$a_is_character = ctype_alpha( $a_first_char );
			$b_is_character = ctype_alpha( $b_first_char );

			// Compare the terms based on whether they are character terms or digit terms.
			if ( $a_is_character && $b_is_character ) {
				return strnatcasecmp( $a->name, $b->name ); // Sort character terms alphabetically.
			} elseif ( $a_is_character ) {
				return -1; // Place character terms before digit terms.
			} elseif ( $b_is_character ) {
				return 1; // Place digit terms after character terms.
			} else {
				return strnatcasecmp( $a->name, $b->name ); // Sort digit terms alphabetically.
			}
		}
	);

	return $terms;
}

/**
 * Get the list of grades sorted by custom order.
 *
 * @return array
 */
function get_sorted_grades($columns = 2) {
	if ( $columns === 2 ) {
		$grade_map = [
			'k-5'          => 'All Grades K-5',
			'6-12'         => 'All Grades 6-12',
			'prek'         => 'PreK',
			'6th-grade'    => '6th Grade',
			'kindergarten' => 'Kindergarten',
			'7th-grade'    => '7th Grade',
			'1st-grade'    => '1st Grade',
			'8th-grade'    => '8th Grade',
			'2nd-grade'    => '2nd Grade',
			'9th-grade'    => '9th Grade',
			'3rd-grade'    => '3rd Grade',
			'10th-grade'   => '10th Grade',
			'4th-grade'    => '4th Grade',
			'11th-grade'   => '11th Grade',
			'5th-grade'    => '5th Grade',
			'12th-grade'   => '12th Grade',
		];
	} else {
		$grade_map = [
			'k-5'          => 'All Grades K-5',
			'2nd-grade'    => '2nd Grade',
			'6-12'         => 'All Grades 6-12',
			'9th-grade'    => '9th Grade',
			'prek'         => 'PreK',
			'3rd-grade'    => '3rd Grade',
			'6th-grade'    => '6th Grade',
			'10th-grade'   => '10th Grade',
			'kindergarten' => 'Kindergarten',
			'4th-grade'    => '4th Grade',
			'7th-grade'    => '7th Grade',
			'11th-grade'   => '11th Grade',
			'1st-grade'    => '1st Grade',
			'5th-grade'    => '5th Grade',
			'8th-grade'    => '8th Grade',
			'12th-grade'   => '12th Grade',
		];
	}

	$grade_slugs = array_keys( $grade_map );
	$grade_terms = get_terms(
		[
			'taxonomy'   => GradeTaxonomy::$name,
			'hide_empty' => false,
			'orderby'    => 'slug__in',
			'order'      => 'ASC',
			'slug'       => $grade_slugs,
		]
	);

	if ( is_wp_error( $grade_terms ) || ! is_array( $grade_terms ) ) {
		return [];
	}

	$modified_grade_terms = [];

	foreach ( $grade_terms as $grade_term ) {
		$grade_term->name = $grade_map[ $grade_term->slug ];
		$modified_grade_terms[] = $grade_term;
	}

	return $modified_grade_terms;
}

/**
 * Generates HTML elements for displaying a list of checkbox terms.
 *
 * @param {Object[]} $terms - An array of term objects to display as checkboxes.
 * @param {Number[]} $selected_terms - An array of term IDs that should be checked by default.
 * @param {String}   $taxonomyname - The name of the taxonomy to which the terms belong.
 * @returns {void} - This function does not return anything. It generates HTML elements.
 */
function generate_search_checkbox_elements( $terms, $selected_terms, $taxonomyname, $location = 'header' ) {
	$total_terms = count( $terms );
	$counter     = 0;
	$num_to_show = $total_terms > 16 ? 16 : $total_terms;

	if ( $num_to_show > 0 ) {
		echo '<div class="checkbox-container">';
		foreach ( $terms as $term ) {
			$input_value = $term->term_id;

			if ( 'page-content' === $location ) {
				$child_terms = get_term_children( $term->term_id, $term->taxonomy );

				if ( ! is_wp_error( $child_terms ) ) {
					$all_term_ids = array_merge( $child_terms, array( $term->term_id ) );
					$input_value = implode( ',', $all_term_ids );
				}

			}

			$counter ++;
			if ( $counter > $num_to_show ) {
				break;
			}
			echo '<label for="' . esc_attr( $term->slug ) . '-' . esc_attr( $location ) . '" class="single-checkbox">';
			echo '<input type="checkbox" name="' . esc_attr( $term->taxonomy ) . '[]" id="' . esc_attr( $term->slug ) . '-' . esc_attr( $location ) . '" value="' . esc_attr( $input_value ) . '" ' . checked( in_array( $term->term_id, $selected_terms ), true, false ) . '>';
			echo esc_html( $term->name );
			echo '</label>';
		}
		echo '</div>';

		if ( $total_terms > $num_to_show ) {
			echo '<div class="remaining-' . esc_attr( $terms[0]->taxonomy ) . '
				checkbox-container" id="remaining-' . esc_attr( $terms[0]->taxonomy ) . '-' . esc_attr( $location ) .
				'" aria-hidden="true">';
			for ( $i = $num_to_show + 1; $i <= $total_terms; $i ++ ) {
				$term = $terms[ $i - 1 ];
				$input_value = $term->term_id;

				if ( 'page-content' === $location ) {
					$child_terms = get_term_children( $term->term_id, $term->taxonomy );

					if ( ! is_wp_error( $child_terms ) ) {
						$all_term_ids = array_merge( $child_terms, array( $term->term_id ) );
						$input_value = implode( ',', $all_term_ids );
					}

				}
				echo '<label for="' . esc_attr( $term->slug ) . '-' . esc_attr( $location ) . '" class="single-checkbox">';
				echo '<input type="checkbox" name="' . esc_attr( $term->taxonomy ) . '[]" id="' . esc_attr( $term->slug )  . '-' . esc_attr( $location ) . '" value="' . esc_attr( $input_value ) . '" ' . checked( in_array( $term->term_id, $selected_terms ), true, false ) . '>';
				echo esc_html( $term->name );
				echo '</label>';
			}
			echo '</div>';

			echo '<a href="#" class="toggle-remaining-checkboxes expand-checkboxes" ' .
				'aria-controls="remaining-' . esc_attr( $terms[0]->taxonomy ) . '-' . esc_attr( $location ) . '">' .
				/* translators: %1$d - number of remaining terms, %2$s - taxonomy label */
				sprintf( esc_html_x( 'View all %1$d %2$s', 'label', 'wrt-theme' ), esc_html( $total_terms ), esc_html( $taxonomyname ) ) .
				'</a>';

			echo '<a href="#" class="toggle-remaining-checkboxes collapse-checkboxes" ' .
				'aria-controls="remaining-' . esc_attr( $terms[0]->taxonomy ) . '-' . esc_attr( $location ) . '">' .
				sprintf( esc_html__( 'View less %s', 'wrt-theme' ), esc_html( $taxonomyname ) ) .
				'</a>';
		}
	}
}

/**
 * An svg template helper.
 *
 * use function Endgame360\Theme\Utility\get_svg_icon;
 *
 * @param string  $name Name of SVG
 * @param boolean $echo Return SVG string if not empty
 * @param boolean $aria_hidden Accessibility purpose
 * @param string  $class optional class(es)
 * @return html Returns html of SVG
 */
function get_svg_icon( $name, $echo = false, $aria_hidden = false, $class = '' ) {
	$file_path = WRT_THEME_DIST_PATH . 'svg/' . $name . '.svg';
	$file_url  = WRT_THEME_TEMPLATE_URL . '/assets/svg/' . $name . '.svg';

	if ( ! file_exists( $file_path ) ) {
		return;
	}

	// check to see if we are on VIP.
	// If we are, we use the VIP Go function.
	// If we are not on VIP Go, we use the standard `file_get_contents` function.
	if ( defined( 'WPCOM_IS_VIP_ENV' ) && true === constant( 'WPCOM_IS_VIP_ENV' ) ) {
		if ( function_exists( 'wpcom_vip_file_get_contents' ) ) {
			$svg_icon = file_get_contents( $file_url ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			if ( ! $svg_icon ) {
				$svg_icon = file_get_contents( $file_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			}
		} else {
			$svg_icon = file_get_contents( $file_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		}
	} else {
		$svg_icon = file_get_contents( $file_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}

	$doc = new \DOMDocument();
	$doc->loadXML( $svg_icon );
	$svg = $doc->getElementsByTagName( 'svg' )[0];

	if ( ! $svg ) {
		return;
	}

	// get existing class attribute (white space included)
	$svg_class = '' !== $svg->getAttribute( 'class' ) ? $svg->getAttribute( 'class' ) . ' ' : '';

	// if aria hidden param is set to true, set attribute
	if ( $aria_hidden ) {
		$svg->setAttribute( 'aria-hidden', 'true' );
	}

	// if class param passed, set class
	if ( '' !== $class ) {
		$svg->setAttribute( 'class', $svg_class . $class );
	}

	$svg_icon = $doc->saveXML();

	if ( $echo ) {
		echo wp_kses( $svg_icon, svg_kses_allowed_html() );
		return;
	}

	return $svg_icon;
}

/**
 * Wrapper function that automatically echoes get_svg_icon
 *
 * use function Endgame360\Theme\Utility\echo_svg_icon;
 *
 * usage: echo_svg_icon( 'icon-name', true, 'your-classes go-here' );
 *
 * @param string  $name Name of SVG
 * @param boolean $aria_hidden Accessibility purpose
 * @param string  $class optional class(es)
 */
function echo_svg_icon( $name, $aria_hidden = false, $class = '' ) {
	get_svg_icon( $name, true, $aria_hidden, $class );
}

/**
 * Gets current post campaign.
 *
 * @param int|object $post         Optional. The post id or post object.
 * @param boolean    $bypass_cache Optional. Whether to bypass the cache.
 * @return array|void
 */
function get_post_campaign( $post = null, $bypass_cache = true ) {
	$post = get_post( $post );

	if ( ! $post ) {
		return;
	}

	// Has this post been assigned a term in the shadow taxonomy?
	$campaign_term = get_the_terms( $post, '_wat-campaign' );

	if ( ! $campaign_term ) {
		return array();
	}

	$campaigns = get_posts(
		array(
			'post_status'    => 'publish',
			'post_type'      => 'wat-campaign',
			'tax_query'      => array(
				array(
					'taxonomy' => '_wat-campaign',
					'field'    => 'term_id',
					'terms'    => wp_list_pluck( $campaign_term, 'term_id' ),
				),
			),
			'posts_per_page' => 100,
		)
	);

	return ! empty( $campaigns ) ? $campaigns[0] : array();
}

/**
 * Get an array of campaign sponsors for a given campaign.
 *
 * @param WP_Post|null $post Optional. WP_Post object, assumes a Campaign.
 * @return array
 */
function get_campaign_sponsors( $post = null ) {
	if ( is_null( $post ) ) {
		$post = get_post_campaign();
	}

	if ( $post && 'wat-campaign' === $post->post_type ) {
		$sponsors = get_the_terms( $post, 'wat-sponsor' );
		return $sponsors;
	}

	return array();
}

/**
 * Gets the primary category of a post, or the first category if no primary category is set.
 *
 * @param int     $post_id The ID of the post you want to get the primary category for.
 * @param mixed   $term The taxonomy to use. Defaults to category.
 * @param boolean $return_all_categories If set to true, the function will return an array of all categories.
 *
 * @return array An array with two keys: primary_category and all_categories.
 *
 * @since Yoast SEO 19.4
 */
function get_post_primary_category( $post_id, $term = 'category', $return_all_categories = false ) {
	$primary_category = null;
	$all_categories   = array();

	if ( class_exists( 'WPSEO_Primary_Term' ) ) {
		// Show Primary category by Yoast if it is enabled & set
		$wpseo_primary_term = new \WPSEO_Primary_Term( $term, $post_id );
		$primary_term       = get_term( $wpseo_primary_term->get_primary_term() );

		if ( ! is_wp_error( $primary_term ) ) {
			$primary_category = $primary_term;
		}
	}

	if ( empty( $primary_category ) ) {
		$categories_list = get_the_terms( $post_id, $term );

		if ( ! empty( $categories_list ) ) {
			$primary_category = reset( $categories_list ); // get the first category
			$all_categories   = $categories_list;

			if ( ! $return_all_categories ) {
				$all_categories = array();
			}
		}
	}

	return array(
		'primary_category' => $primary_category,
		'all_categories'   => $all_categories,
	);
}

/**
 * Get an array of terms from a GET param in URL.
 *
 * @param string $param GET param name.
 *
 * @return array
 */
function get_term_ids_from_url( string $param ): array {
	$terms = array();

	if ( isset( $_GET[ $param ] ) ) {
		$terms = filter_input( INPUT_GET, $param, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$terms = array_map( 'intval', $terms );
	}

	return $terms;
}

/**
 * Gets the primary category of a post, or the first category if no primary category is set.
 *
 * @param string $location The location of the search form.
 *
 * @return void Echoes the search form.
 */
function get_wrt_search_form( $location = '' ) {
	ob_start();
	require get_template_directory() . '/searchform.php';
	$form = ob_get_clean();

	// Update the form ID based on the location
	if ( ! empty( $location ) ) {

		// Search Form ID
		$form = str_replace( 'id="searchform"', 'id="searchform-' . $location . '"', $form );

		// Grade Dropdown and related Aria Controls
		$form = str_replace( 'id="grade-dropdown"', 'id="grade-dropdown-' . $location . '"', $form );
		$form = str_replace( 'aria-controls="grade-dropdown"', 'aria-controls="grade-dropdown-' . $location . '"', $form );

		// Topic Dropdown and related Aria Controls
		$form = str_replace( 'id="topic-dropdown"', 'id="topic-dropdown-' . $location . '"', $form );
		$form = str_replace( 'aria-controls="topic-dropdown"', 'aria-controls="topic-dropdown-' . $location . '"', $form );

		// Remaining WAT Subject for extended checkboxes
		$form = str_replace( 'id="remaining-wat-subject"', 'id="remaining-wat-subject-' . $location . '"', $form );
		$form = str_replace( 'aria-controls="remaining-wat-subject"', 'aria-controls="remaining-wat-subject-asdfasdf' . $location . '"', $form );
	}

	// Define the allowed HTML elements and attributes
	$allowed_html = array(
		'a'     => array(
			'href'          => true,
			'title'         => true,
			'class'         => true,
			'aria-controls' => true,
			'aria-expanded' => true,
			'aria-haspopup' => true,
			'aria-hidden'   => true,
		),
		'form'  => array(
			'role'   => true,
			'id'     => true,
			'class'  => true,
			'method' => true,
			'action' => true,
		),
		'input' => array(
			'type'        => true,
			'name'        => true,
			'value'       => true,
			'placeholder' => true,
			'itemprop'    => true,
		),
		'div'   => array(
			'class'         => true,
			'id'            => true,
			'role'          => true,
			'aria-controls' => true,
			'aria-expanded' => true,
			'aria-haspopup' => true,
			'aria-hidden'   => true,
		),
		// Add more elements and attributes if needed
	);

	// Merge the additional allowed tags with the existing allowed tags
	$allowed_tags = wp_kses_allowed_html( 'post' );
	$allowed_tags = array_merge( $allowed_tags, $allowed_html );

	// Escape the form with wp_kses
	echo wp_kses( $form, $allowed_tags );
}

/**
 * Get archive featured posts.
 *
 * @return array Array of post ids.
 */
function get_archive_featured_posts() {
	if ( is_admin() || ! is_archive() || ! is_main_query() ) {
		return;
	}

	// We need to backfill from, and dedupte the main query.
	global $wp_query;

	// Get the term we're operating on.
	$term_id = get_queried_object_id();

	// Get editor defined featured posts.
	$featured = array( 0, 0, 0 );
	$termmeta = get_term_meta( $term_id, 'featured_posts', true );
	if ( is_array( $termmeta ) && ! empty( $termmeta ) ) {
		$featured = array_values( $termmeta );
	}

	// Backfill featured posts with main query.
	$main_query_pointer = 0;
	foreach ( $featured as $index => $post_id ) {
		if ( $post_id ) {
			continue;
		}

		// We don't a post for this slot, backfill from main query.
		while ( ! $post_id ) {
			// We've only got so many posts to try from.
			if ( $main_query_pointer > count( $wp_query->posts ) ) {
				break;
			}

			$next_id = $wp_query->posts[ $main_query_pointer ]->ID;

			if ( ! in_array( $next_id, $featured, true ) ) {
				$featured[ $index ] = $next_id;
				break;
			}
			$main_query_pointer++;
		}
	}

	// Return the featured posts.
	return array_map( 'absint', array_values( $featured ) );
}

/**
 * Get archive inline card.
 *
 * @return int|bool Post id if set, false otherwise.
 */
function get_archive_inline_card() {
	// Get the term we're operating on.
	$term_id = get_queried_object_id();

	// Get editor defined inline cards.
	$termmeta = get_term_meta( $term_id, 'inline_cards', true );

	if ( is_array( $termmeta ) && ! empty( $termmeta ) ) {
		$inline = array_values( $termmeta );
	}

	// We support one inline card right now.
	return ! empty( $inline ) ? $inline[0] : false;
}

/**
 * Check if a post id has been featured on an archive page.
 *
 * @param int $post_id The post ID to check.
 * @return bool
 */
function archive_is_post_featured( $post_id ) {
	$featured = get_archive_featured_posts();
	$inline   = get_archive_inline_card();

	return in_array( $post_id, array_merge( $featured, array( $inline ) ), true );
}

/**
 * Returns true if an archive has queried posts that are not featured.
 *
 * @return bool
 */
function archive_has_unfeatured_posts() {
	global $wp_query;
	$queried_ids = wp_list_pluck( $wp_query->posts, 'ID' );

	return array_diff( $queried_ids, get_archive_featured_posts() );
}

/**
 * Get editors picks.
 *
 * @return false|WP_Query
 */
function get_editors_picks() {
	if ( ! is_archive() ) {
		return false;
	}

	$taxonomy = get_queried_object();

	if ( empty( $taxonomy->taxonomy ) || empty( $taxonomy->slug ) ) {
		return false;
	}

	$args = array(
		'post_type'      => ProductPostType::$name,
		'posts_per_page' => 4,
		'tax_query'      => array(
			array(
				'taxonomy' => $taxonomy->taxonomy,
				'field'    => 'slug',
				'terms'    => $taxonomy->slug,
			),
		),
	);

	return new WP_Query( $args );
}
