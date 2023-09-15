<?php
/**
 * Searchable contract for taxonomies
 *
 * Using this trait in a class, will allow that taxonomy to be searchable using GET parameters on archive pages.
 *
 * @package WRTPlugin
 */

namespace WRTPlugin\Taxonomies;

use WP_Query;
use function WRTTheme\Utility\get_term_ids_from_url;

trait SearchableContract {
	/**
	 * Register taxonomy hooks
	 */
	public function register() {
		parent::register();
		$this->register_additional_hooks();
	}

	/**
	 * Register additional hooks.
	 *
	 * @return void
	 */
	protected function register_additional_hooks(): void {
		add_action( 'pre_get_posts', array( $this, 'custom_search_query' ) );
	}

	/**
	 * Allow filtering in archive pages.
	 *
	 * @param WP_Query $query
	 */
	public function custom_search_query( WP_Query $query ) {
		if ( ! $query->is_main_query() ) {
			return;
		}

		if ( ! $query->is_search() ) {
			return;
		}

		$query_vars = $query->query;

		$tax_query = [];

		foreach ( $query_vars as $key => $value ) {
			if ( $key !== self::$name ) {
				continue;
			}

			if ( empty( $value ) ) {
				continue;
			}

			$tax_query[] = [
				'taxonomy' => self::$name,
				'field'    => 'term_id',
				'terms'    => $value,
			];
		}

		if ( ! empty( $tax_query ) ) {
			$current_tax_query = $query->get( 'tax_query' );
			if ( ! empty( $current_tax_query ) ) {
				$tax_query['relation'] = 'AND';
				$tax_query = array_merge( $current_tax_query, $tax_query );
			}

			$query->set( 'tax_query', $tax_query );
			$query->set( self::$name, null );
		}
	}
}
