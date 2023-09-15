<?php
/**
 * HTML Game Post type.
 *
 * @package WRTPlugin
 */

namespace WRTPlugin\PostTypes;

use WRTPlugin\Taxonomies\GradeTaxonomy;
use WRTPlugin\Taxonomies\CategoryTaxonomy;

/**
 * HTMLGame Post Type.
 */
class HTMLGamePostType extends BasePostType {

	/**
	 * Post Type name.
	 *
	 * @var string
	 */
	public static $name = 'wat-interactive-game';

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
		return __( 'Iteractive Game', 'wrt-plugin' );
	}

	/**
	 * Get the plural post type label.
	 *
	 * @return string
	 */
	protected function get_plural_label() {
		return __( 'Interactive Games', 'wrt-plugin' );
	}

	/**
	 * Add a custom Dashicon.
	 *
	 * @return string
	 */
	protected function get_menu_icon() {
		return 'dashicons-games';
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
				'show_in_rest' => true,
				'rewrite'      => [ 'slug' => 'interactives' ],
				'has_archive'  => false,
				'hierarchical' => true,
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
			'author',
		];
	}

	/**
	 * The list of meta fields.
	 *
	 * @return array List of meta fields.
	 */
	protected function get_meta_fields(): array {
		return [
			[
				'name' => 'game_path',
				'type' => 'string',
			],
		];
	}

	/**
	 * CPT block template. Wired to 'supports' option of
	 * register_post_type.
	 *
	 * @return array
	 */
	protected function get_block_template() {
		return [
			['wrt/interactive-game-embed']
		];
	}
}
