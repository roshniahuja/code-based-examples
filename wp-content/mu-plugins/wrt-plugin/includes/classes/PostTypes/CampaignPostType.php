<?php
/**
 * Campaign Post type.
 *
 * @package WRTPlugin
 */

namespace WRTPlugin\PostTypes;

use WRTPlugin\Taxonomies\SponsorTaxonomy;

/**
 * Campaign Post Type.
 */
class CampaignPostType extends BasePostType {

	/**
	 * Post Type name.
	 *
	 * @var string
	 */
	public static $name = 'wat-campaign';

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
		return __( 'Campaign', 'wrt-plugin' );
	}

	/**
	 * Get the plural post type label.
	 *
	 * @return string
	 */
	protected function get_plural_label() {
		return __( 'Campaigns', 'wrt-plugin' );
	}

	/**
	 * Add a custom Dashicon.
	 *
	 * @return string
	 */
	protected function get_menu_icon() {
		return 'dashicons-chart-area';
	}

	/**
	 * An array of the supported taxonomies.
	 *
	 * @return array
	 */
	protected function get_supported_taxonomies() {
		return [
			SponsorTaxonomy::$name,
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
				'rewrite'             => [ 'slug' => 'campaign' ],
				'has_archive'         => false,
				'exclude_from_search' => true,
				//'template_lock' => 'all',
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

	/**
	 * CPT block template. Wired to 'supports' option of
	 * register_post_type.
	 *
	 * @return array
	 */
	protected function get_block_template() {
		return [
			['wrt/post-sponsor']
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
				'name' => 'campaign-url',
				'type' => 'string',
			],
			[
				'name' => 'campaign-cta',
				'type' => 'string',
			],
		];
	}
}
