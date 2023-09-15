<?php
/**
 * Hub Post type.
 *
 * @package WRTPlugin
 */

namespace WRTPlugin\PostTypes;

use WRTPlugin\Taxonomies\TopicTaxonomy;

/**
 * Hub Post Type.
 */
class HubPostType extends BasePostType {

	/**
	 * Post Type name.
	 *
	 * @var string
	 */
	public static $name = 'wat-hub';

	/**
	 * Gutenberg support
	 *
	 * @var boolean
	 */
	protected $gutenberg_support = true;

	/**
	 * Register the post type and any hooks you may need
	 */
	public function register() {
		parent::register();
	}

	/**
	 * Get the singular post type label.
	 *
	 * @return string
	 */
	protected function get_singular_label() {
		return __( 'Hub', 'wrt-plugin' );
	}

	/**
	 * Get the plural post type label.
	 *
	 * @return string
	 */
	protected function get_plural_label() {
		return __( 'Hubs', 'wrt-plugin' );
	}

	/**
	 * Add a custom Dashicon.
	 *
	 * @return string
	 */
	protected function get_menu_icon() {
		return 'dashicons-index-card';
	}

	/**
	 * An array of the supported taxonomies.
	 *
	 * @return array
	 */
	protected function get_supported_taxonomies() {
		return [
			TopicTaxonomy::$name,
		];
	}

	/**
	 * Get the options for the post type.
	 *
	 * @return array
	 */
	protected function get_options() {
		return array_merge(
			$this->get_default_options(),
			[
				'show_in_rest'        => true,
				'rewrite'             => [ 'slug' => 'hub' ],
				'has_archive'         => false,
				'exclude_from_search' => false,
			]
		);
	}

	/**
	 * The Editor Supports defaults.
	 *
	 * @return array
	 */
	protected function get_editor_supports() {
		return [
			'title',
			'editor',
			'revisions',
			'thumbnail',
			'excerpt',
			'custom-fields',
		];
	}
}
