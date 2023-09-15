<?php
/**
 * Post Post type.
 *
 * @package WRTPlugin
 */

namespace WRTPlugin\PostTypes;

use WRTPlugin\Taxonomies\SponsorTaxonomy;
use WRTPlugin\Taxonomies\ShadowCampaignTaxonomy;


/**
 * Post Post Type.
 */
class PostPostType extends BasePostType {

	/**
	 * Post Type name.
	 *
	 * @var string
	 */
	public static $name = 'post';

	/**
	 * Is this a built-in post type?
	 *
	 * @var bool
	 */
	protected $built_in = true;

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
		return __( 'Post', 'wrt-plugin' );
	}

	/**
	 * Get the plural post type label.
	 *
	 * @return string
	 */
	protected function get_plural_label() {
		return __( 'Posts', 'wrt-plugin' );
	}

	/**
	 * An array of the supported taxonomies.
	 *
	 * @return array
	 */
	protected function get_supported_taxonomies() {
		return [
			ShadowCampaignTaxonomy::$name,
		];
	}

	/**
	 * Block template. Wired to 'supports' option of register_post_type.
	 *
	 * @return array
	 */
	protected function get_block_template() {
		return [ ['wrt/post-header'] ];
	}

	/**
	 * Define post meta fields.
	 *
	 * @return array List of meta fields.
	 */
	protected function get_meta_fields(): array {
		return [
			[
				'name' => 'show-affiliate-link',
				'type' => 'boolean',
			],
		];
	}
}
