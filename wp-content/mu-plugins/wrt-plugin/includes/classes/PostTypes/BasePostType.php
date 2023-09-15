<?php
/**
 * Post type abstraction.
 */

namespace WRTPlugin\PostTypes;

use WRTPlugin\Module;

/**
 * Abstract class for post types.
 */
abstract class BasePostType extends Module {
	/**
	 * Post Type name.
	 *
	 * @var string
	 */
	public static $name; // set in sub-class

	/**
	 * Is this a built-in post type?
	 *
	 * register_post_type is not called for built-in post types.
	 *
	 * @var bool
	 */
	protected $built_in = false;

	/**
	 * Should CPT support Gutenberg editor.
	 *
	 * @var bool $gutenberg_support
	 */
	protected $gutenberg_support = true;

	/**
	 * Get the singular post type label.
	 *
	 * @return string
	 */
	abstract protected function get_singular_label();

	/**
	 * Get the plural post type label.
	 *
	 * @return string
	 */
	abstract protected function get_plural_label();

	/**
	 * Get the options for the post type.
	 *
	 * @return array
	 */
	protected function get_options() {
		return $this->get_default_options();
	}

	/**
	 * Get the default post type options.
	 *
	 * @return array
	 */
	protected function get_default_options() {
		return [
			'labels'            => $this->get_labels(),
			'public'            => true,
			'has_archive'       => true,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_in_rest'      => false,
			'show_in_nav_menus' => false,
			'hierarchical'      => false,
			'menu_icon'         => $this->get_menu_icon(),
			'supports'          => $this->get_editor_supports(),
			'template'          => $this->get_block_template(),
		];
	}

	/**
	 * Get the labels for the post type.
	 *
	 * @return array
	 */
	protected function get_labels() {
		$plural_label   = $this->get_plural_label();
		$singular_label = $this->get_singular_label();

		$labels = [
			'name'                  => $plural_label,   // Already translated via get_plural_label().
			'singular_name'         => $singular_label, // Already translated via get_singular_label().
			'all_items'             => sprintf( __( 'All %s', 'wrt-plugin' ), $plural_label ),
			'add_new_item'          => sprintf( __( 'Add New %s', 'wrt-plugin' ), $singular_label ),
			'add_new'               => sprintf( __( 'Add New %s', 'wrt-plugin' ), $singular_label ),
			'edit_item'             => sprintf( __( 'Edit %s', 'wrt-plugin' ), $singular_label ),
			'new_item'              => sprintf( __( 'New %s', 'wrt-plugin' ), $singular_label ),
			'view_item'             => sprintf( __( 'View %s', 'wrt-plugin' ), $singular_label ),
			'search_items'          => sprintf( __( 'Search %s', 'wrt-plugin' ), $plural_label ),
			'not_found'             => sprintf( __( 'No %s found.', 'wrt-plugin' ), strtolower( $plural_label ) ),
			'not_found_in_trash'    => sprintf( __( 'No %s found in Trash.', 'wrt-plugin' ), strtolower( $plural_label ) ),
			'parent_item_colon'     => sprintf( __( 'Parent %s:', 'wrt-plugin' ), $plural_label ),
			'insert_into_item'      => sprintf( __( 'Insert into %s:', 'wrt-plugin' ), $singular_label ),
			'uploaded_to_this_item' => sprintf( __( 'Uploaded to this %s:', 'wrt-plugin' ), $singular_label ),
			'featured_image'        => __( 'Featured Image', 'wrt-plugin' ),
			'set_featured_image'    => __( 'Set featured image', 'wrt-plugin' ),
			'menu_name'             => $plural_label,
			'name_admin_bar'        => $plural_label,
			'item_updated'          => sprintf( __( '%s updated:', 'wrt-plugin' ), $singular_label ),
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
	 * Registers a post type and associates its taxonomies.
	 */
	public function register() {
		$name = static::$name;

		if ( $this->built_in ) {
			add_filter( 'post_type_labels_' . $name, [ $this, 'change_labels' ] );
		} else {
			$this->register_post_type();
		}

		/**
		 * Since Gutenberg is active by default, we only bind the filter if
		 * $gutenberg_support is false, and it's in the admin.
		 */
		if ( ! $this->gutenberg_support && is_admin() ) {
			add_filter( 'use_block_editor_for_post_type', [ $this, 'disable_block_editor' ], 10, 2 );
		}

		$this->register_taxonomies();
		$this->register_meta_fields();

		add_action( 'rest_api_init', [ $this, 'rest_api_functionality' ] );

		if ( $this->built_in ) {
			add_action( 'init', [ $this, 'register_template' ] );
		}
	}

	/**
	 * Change the labels of the built-in post type.
	 *
	 * @param object $labels Labels.
	 *
	 * @return object Modified labels.
	 */
	public function change_labels( $labels ) {
		return (object) $this->get_labels();
	}

	/**
	 * Registers the current post type with WordPress.
	 */
	protected function register_post_type() {
		register_post_type(
			static::$name,
			$this->get_options()
		);
	}

	/**
	 * Remove support for block editor.
	 *
	 * @param bool   $current_status Current Gutenberg Support.
	 * @param string $post_type      Current CPT.
	 *
	 * @return bool
	 */
	public function disable_block_editor( $current_status, $post_type ) {
		if ( $post_type === static::$name ) {
			return false;
		}

		return $current_status;
	}

	/**
	 * Registers the taxonomies declared with the current post type.
	 */
	protected function register_taxonomies() {
		$taxonomies  = $this->get_supported_taxonomies();
		$object_type = static::$name;

		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				register_taxonomy_for_object_type(
					$taxonomy,
					$object_type
				);
			}
		}
	}

	/**
	 * Register Meta fields.
	 */
	protected function register_meta_fields() {
		$meta_fields = $this->get_meta_fields();

		if ( empty( $meta_fields ) ) {
			return;
		}

		foreach ( $meta_fields as $meta_field ) {
			$this->register_meta_field( $meta_field );
		}
	}

	/**
	 * Register a specific meta field.
	 *
	 * @param array $meta_field Meta field attributes.
	 */
	protected function register_meta_field( array $meta_field ) {
		$show_in_rest = true;
		if ( isset( $meta_field['schema'] ) ) {
			$show_in_rest = [ 'schema' => $meta_field['schema'] ];
		}

		$args = [
			'type'         => $meta_field['type'] ?? 'string',
			'single'       => true,
			'show_in_rest' => $show_in_rest,
		];

		register_post_meta( static::$name, $meta_field['name'], $args );
	}

	/**
	 * The Editor Supports defaults. Wired to 'supports' option of
	 * register_post_type.
	 *
	 * @return array
	 */
	protected function get_editor_supports() {
		return [];
	}

	/**
	 * CPT block template. Wired to 'supports' option of
	 * register_post_type.
	 *
	 * @return array
	 */
	protected function get_block_template() {
		return [];
	}

	/**
	 * Adds the ability to add a custom Dashicon to your CPT.
	 * Icons can be found here - https://developer.wordpress.org/resource/dashicons/#awards
	 *
	 * @return string
	 */
	protected function get_menu_icon() {
		return 'dashicons-admin-post';
	}

	/**
	 * Pass an empty array if no taxonomies require registration.
	 *
	 * @return array
	 */
	protected function get_supported_taxonomies() {
		return [];
	}

	/**
	 * The list of meta fields.
	 *
	 * This function should be overridden in the child class to define the meta fields.
	 *
	 * @return array List of meta fields.
	 */
	protected function get_meta_fields(): array {
		return [];
	}

	/**
	 * Rest API related functionality, like exposing custom fields, etc.
	 */
	public function rest_api_functionality() {
		$this->expose_rest_fields();
	}

	/**
	 * Expose custom fields for REST API.
	 */
	protected function expose_rest_fields() {
		$meta_fields = $this->get_meta_fields();

		if ( empty( $meta_fields ) ) {
			return;
		}

		foreach ( $meta_fields as $meta_field ) {
			$this->expose_rest_field( $meta_field );
		}
	}

	/**
	 * Expose a single meta field.
	 *
	 * @param array $meta_field Meta field attributes.
	 */
	protected function expose_rest_field( array $meta_field ) {
		$description = $meta_field['description'] ?? '';
		$type        = $meta_field['type'] ?? 'string';
		$context     = $meta_field['context'] ?? [ 'view', 'edit' ];

		$get_callback    = $meta_field['get_callback'] ?? [ $this, 'get_post_meta_for_api' ];
		$update_callback = $meta_field['update_callback'] ?? [ $this, 'update_post_meta_from_api' ];

		register_rest_field(
			static::$name,
			$meta_field['name'],
			[
				'get_callback'    => $get_callback,
				'update_callback' => $update_callback,
				'schema'          => [
					'description' => $description,
					'type'        => $type,
					'context'     => $context,
				],
			]
		);
	}

	/**
	 * Built in post types can't register a template via the normal way, so register it here instead.
	 */
	public function register_template() {
		$post_type_object           = get_post_type_object( static::$name );
		$post_type_object->template = $this->get_block_template();
	}

	/**
	 * Get Post Meta for REST API Response.
	 *
	 * @param array  $post       Post data.
	 * @param string $field_name Field name.
	 *
	 * @return mixed Meta value.
	 */
	public function get_post_meta_for_api( $post, $field_name ) {
		return get_post_meta( $post['id'], $field_name, true );
	}

	/**
	 * Update post meta from API.
	 *
	 * @param mixed    $value      Meta value.
	 * @param \WP_Post $post       Post.
	 * @param string   $field_name Field name.
	 *
	 * @return bool|int
	 */
	public function update_post_meta_from_api( $value, $post, $field_name ) {
		return update_post_meta( $post->ID, $field_name, $value );
	}
}
