<?php
/**
 * Sponsor custom taxonomy.
 */

namespace WRTPlugin\Taxonomies;

use WRTPlugin\PostTypes\CampaignPostType;


/**
 * Sponsor custom Taxonomy.
 */
class SponsorTaxonomy extends BaseTaxonomy {

	/**
	 * Slug of the taxonomy.
	 *
	 * @var string
	 */
	public static $name = 'wat-sponsor';

	/**
	 * Get the singular taxonomy label.
	 *
	 * @return string
	 */
	protected function get_singular_label() {
		return __( 'Sponsor', 'wrt-plugin' );
	}

	/**
	 * Get the plural taxonomy label.
	 *
	 * @return string
	 */
	protected function get_plural_label() {
		return __( 'Sponsors', 'wrt-plugin' );
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
				'rewrite'      => [ 'slug' => 'sponsor' ],
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
		];
	}
}
