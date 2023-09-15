<?php
/**
 * ProductType custom taxonomy.
 */

namespace WRTPlugin\Taxonomies;

use WRTPlugin\PostTypes\ProductPostType;


/**
 * ProductType custom Taxonomy.
 */
class ProductTypeTaxonomy extends BaseTaxonomy {

	/**
	 * Slug of the taxonomy.
	 *
	 * @var string
	 */
	public static $name = 'wat-product-type';

	/**
	 * Get the singular taxonomy label.
	 *
	 * @return string
	 */
	protected function get_singular_label() {
		return __( 'Product Type', 'wrt-plugin' );
	}

	/**
	 * Get the plural taxonomy label.
	 *
	 * @return string
	 */
	protected function get_plural_label() {
		return __( 'Product Types', 'wrt-plugin' );
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
				'rewrite'           => [ 'slug' => 'product-type' ],
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
			ProductPostType::$name,
		];
	}
}
