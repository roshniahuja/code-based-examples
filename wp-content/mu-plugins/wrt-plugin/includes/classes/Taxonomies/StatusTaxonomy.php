<?php
/**
 * Status Taxonomy
 */

namespace WRTPlugin\Taxonomies;

use WP_Query;
use WRTPlugin\PostTypes\ContestPostType;

class StatusTaxonomy extends BaseTaxonomy {

	/**
	 * Slug of the taxonomy.
	 *
	 * @var string
	 */
	public static $name = 'wat-status';

	/**
	 * Get the singular taxonomy label.
	 *
	 * @return string
	 */
	protected function get_singular_label() {
		return __( 'Status', 'wrt-plugin' );
	}

	/**
	 * Get the plural taxonomy label.
	 *
	 * @return string
	 */
	protected function get_plural_label() {
		return __( 'Statuses', 'wrt-plugin' );
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
				'hierarchical'      => true,
				'rewrite'           => [ 'slug' => 'Statuses' ],
				'show_in_menu'      => false,
				'show_ui'           => false,
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
			ContestPostType::$name,
		];
	}

	/**
	 * Register the taxonomy.
	 */
	public function register() {
		parent::register();
		add_action( 'pre_get_posts', array( $this, 'exclude_closed' ) );
	}

	/**
	 * Exclude posts with closed status from appearing in search.
	 *
	 * @param WP_Query $query
	 */
	public function exclude_closed( WP_Query $query ) {
		if ( ! $query->is_search() ) {
			return;
		}

		$tax_query = [
			[
				'taxonomy' => self::$name,
				'field'    => 'slug',
				'terms'    => 'closed',
				'operator' => 'NOT IN',
			],
		];

		$current_tax_query = $query->get( 'tax_query' );
		if ( ! empty( $current_tax_query ) ) {
			$tax_query['relation'] = 'AND';
			$tax_query = array_merge( $current_tax_query, $tax_query );
		}

		$query->set( 'tax_query', $tax_query );
	}
}
