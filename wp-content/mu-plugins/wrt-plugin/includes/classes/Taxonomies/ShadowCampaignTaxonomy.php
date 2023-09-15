<?php
/**
 * Shadow Campaign custom taxonomy.
 */

namespace WRTPlugin\Taxonomies;

use WRTPlugin\PostTypes\CampaignPostType;
use WRTPlugin\PostTypes\PostPostType;

/**
 * Shadow Campaign custom Taxonomy.
 */
class ShadowCampaignTaxonomy extends BaseTaxonomy {

	use ShadowTaxonomy;

	/**
	 * Slug of the taxonomy.
	 *
	 * @var string
	 */
	public static $name = '_wat-campaign';

	/**
	 * Get the shadow post type
	 *
	 * @return string Shadow post type.
	 */
	protected function get_shadow_post_type(): string {
		return CampaignPostType::$name;
	}

	/**
	 * Get the singular taxonomy label.
	 *
	 * @return string
	 */
	protected function get_singular_label() {
		return __( 'Campaign', 'hillel-plugin' );
	}

	/**
	 * Get the plural taxonomy label.
	 *
	 * @return string
	 */
	protected function get_plural_label() {
		return __( 'Campaigns', 'hillel-plugin' );
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
				'show_ui'           => false,
				'show_in_menu'      => false,
				'show_admin_column' => true,
				'rewrite'           => [ 'slug' => 'campaigns' ],
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
			CampaignPostType::$name,
			PostPostType::$name,
		];
	}
}
