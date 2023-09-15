<?php
/**
 * Topic custom taxonomy.
 */

namespace WRTPlugin\Taxonomies;

use WRTPlugin\PostTypes\ContestPostType;
use WRTPlugin\PostTypes\PostPostType;

/**
 * Topic custom Taxonomy.
 */
class TopicTaxonomy extends BaseTaxonomy {
	use SearchableContract;

	/**
	 * Slug of the taxonomy.
	 *
	 * @var string
	 */
	public static $name = 'wat-subject';

	/**
	 * Get the singular taxonomy label.
	 *
	 * @return string
	 */
	protected function get_singular_label() {
		return __( 'Topic', 'wrt-plugin' );
	}

	/**
	 * Get the plural taxonomy label.
	 *
	 * @return string
	 */
	protected function get_plural_label() {
		return __( 'Topics', 'wrt-plugin' );
	}

	/**
	 * Get the taxonomy options.
	 *
	 * @return array
	 */
	protected function get_options() {
		return array_merge(
			$this->get_default_options(),
			[
				'rewrite'           => [ 'slug' => 'topics' ],
				'show_admin_column' => true,
			]
		);
	}

	/**
	 * An array for the supported post types.
	 *
	 * @return array
	 */
	protected function get_supported_post_types() {
		return [
			PostPostType::$name,
			ContestPostType::$name,
		];
	}
}
