<?php
/**
 * Category builtin taxonomy.
 */

namespace WRTPlugin\Taxonomies;

use WRTPlugin\PostTypes\PostPostType;

/**
 * Category Taxonomy.
 */
class CategoryTaxonomy extends BaseTaxonomy {
	use SearchableContract;
	use FeaturedFieldsContract;
	use NewsletterFieldsContract;

	/**
	 * Slug of the taxonomy.
	 *
	 * @var string
	 */
	public static $name = 'category';

	/**
	 * Get the singular taxonomy label.
	 *
	 * @return string
	 */
	protected function get_singular_label() {
		return __( 'Category', 'wrt-plugin' );
	}

	/**
	 * Get the plural taxonomy label.
	 *
	 * @return string
	 */
	protected function get_plural_label() {
		return __( 'Categories', 'wrt-plugin' );
	}

	/**
	 * Registers a taxonomy with it's related post types.
	 *
	 * @return void
	 */
	public function register() {
		$this->featured_fields_register();
		$this->newsletter_fields_register();
	}
}
