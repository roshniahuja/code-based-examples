<?php
/**
 * Taxonomy abstraction.
 *
 * @package WRTPlugin
 */

namespace WRTPlugin\Taxonomies;
use WRTPlugin\Module;

/**
 * Abstract class for taxonomies.
 */
abstract class BaseTaxonomy extends Module {

	/**
	 * Taxonomy name.
	 *
	 * @var string
	 */
	public static $name; // will be overridden in sub-class

	/**
	 * Load order.
	 *
	 * @var integer
	 */
	public $load_order = 9;

	/**
	 * Get the singular taxonomy label.
	 *
	 * @return string
	 */
	abstract protected function get_singular_label();

	/**
	 * Get the plural taxonomy label.
	 *
	 * @return string
	 */
	abstract protected function get_plural_label();

	/**
	 * Get the taxonomy options.
	 *
	 * @return array
	 */
	protected function get_options() {
		return $this->get_default_options();
	}

	/**
	 * Get the default taxonomy options.
	 *
	 * @return array
	 */
	protected function get_default_options() {

		return [
			'labels'            => $this->get_labels(),
			'public'            => true,
			'show_admin_column' => false,
			'show_ui'           => true,
			'hierarchical'      => true,
			'show_in_nav_menus' => false,
			'show_in_rest'      => true,
		];
	}

	/**
	 * Get the labels for the taxonomy.
	 *
	 * @return array
	 */
	protected function get_labels() {

		$plural_label   = $this->get_plural_label();
		$singular_label = $this->get_singular_label();

		$labels = [
			'name'              => $plural_label,
			'singular_name'     => $singular_label,
			'all_items'         => sprintf( 'All %s', $plural_label ),
			'edit_item'         => sprintf( 'Edit %s', $singular_label ),
			'view_item'         => sprintf( 'View %s', $singular_label ),
			'update_item'       => sprintf( 'Update %s', $singular_label ),
			'add_new_item'      => sprintf( 'Add New %s', $singular_label ),
			'new_item_name'     => sprintf( 'New %s Name', $singular_label ),
			'parent_item'       => sprintf( 'Parent %s', $singular_label ),
			'parent_item_colon' => sprintf( 'Parent %s:', $singular_label ),
			'search_items'      => sprintf( 'Search %s', $plural_label ),
			'popular_items'     => sprintf( 'Popular %s', $plural_label ),
			'not_found'         => sprintf( 'No %s found.', strtolower( $plural_label ) ),
		];

		return $labels;
	}

	/**
	 * Can register.
	 *
	 * @return boolean
	 */
	public function can_register() {
		return true;
	}

	/**
	 * Registers a taxonomy with it's related post types.
	 */
	public function register() {
		register_taxonomy( static::$name, $this->get_supported_post_types(), $this->get_options() );
	}

	/**
	 * An array for the supported post types for the current taxonomy.
	 *
	 * @return array
	 */
	protected function get_supported_post_types() {
		return [];
	}
}
