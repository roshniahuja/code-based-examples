<?php
/**
 * Grade custom taxonomy.
 */

namespace WRTPlugin\Taxonomies;

use WRTPlugin\PostTypes\PostPostType;

/**
 * Grade custom Taxonomy.
 */
class GradeTaxonomy extends BaseTaxonomy {
	use SearchableContract;
	use FeaturedFieldsContract;
	use NewsletterFieldsContract;

	/**
	 * Slug of the taxonomy.
	 *
	 * @var string
	 */
	public static $name = 'wat-grade';

	public function register() {
		parent::register();
		$this->register_additional_hooks();
		$this->featured_fields_register();
		$this->newsletter_fields_register();
	}

	/**
	 * Get the singular taxonomy label.
	 *
	 * @return string
	 */
	protected function get_singular_label() {
		return __( 'Grade', 'wrt-plugin' );
	}

	/**
	 * Get the plural taxonomy label.
	 *
	 * @return string
	 */
	protected function get_plural_label() {
		return __( 'Grades', 'wrt-plugin' );
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
				'rewrite'           => [ 'slug' => 'grades' ],
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
