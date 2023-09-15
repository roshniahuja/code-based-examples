<?php
/**
 * ContentType custom taxonomy.
 */

namespace WRTPlugin\Taxonomies;

use WRTPlugin\PostTypes\PostPostType;


/**
 * ContentType custom Taxonomy.
 */
class ContentTypeTaxonomy extends BaseTaxonomy {

	/**
	 * Slug of the taxonomy.
	 *
	 * @var string
	 */
	public static $name = 'wat-content-type';

	/**
	 * Get the singular taxonomy label.
	 *
	 * @return string
	 */
	protected function get_singular_label() {
		return __( 'Content Type', 'wrt-plugin' );
	}

	/**
	 * Get the plural taxonomy label.
	 *
	 * @return string
	 */
	protected function get_plural_label() {
		return __( 'Content Types', 'wrt-plugin' );
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
				'rewrite'           => [ 'slug' => 'content-type' ],
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
		];
	}
}
