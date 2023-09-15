<?php

namespace WRTPlugin\Taxonomies;

/**
 * Trait to convert a taxonomy into a shadow taxonomy.
 */
trait ShadowTaxonomy {
	/**
	 * Get the shadow post type
	 *
	 * @return string Shadow post type.
	 */
	abstract protected function get_shadow_post_type(): string;

	/**
	 * Register additional hooks.
	 */
	public function register() {
		parent::register();

		add_action( 'save_post', [ $this, 'update_shadow_taxonomy_term' ], 10, 2 );
		add_action( 'before_delete_post', [ $this, 'delete_shadow_taxonomy_term' ] );
	}

	/**
	 * When the shadow CPT entry is saved, automatically create an entry in the shadow taxonomy.
	 *
	 * @param int $post_id ID of the post that is getting saved.
	 * @param \WP_Post $post Post object.
	 *
	 * @uses  get_post()
	 * @uses  get_term_by()
	 * @uses  wp_insert_term()
	 *
	 * @const DOING_AUTOSAVE
	 *
	 * @uses  get_post_type()
	 */
	public function update_shadow_taxonomy_term( int $post_id, $post ) {
		if ( $this->get_shadow_post_type() !== get_post_type( $post_id ) ) {
			return false;
		}

		$term = $this->get_shadow_taxonomy_term( $post_id );

		if ( $term instanceof \WP_Term ) {
			wp_set_post_terms( $post_id, $term->term_id, static::$name );
			return;
		}

		if ( 'Auto Draft' === $post->post_title ) {
			return;
		}

		$new_term = wp_insert_term( $post->post_title, static::$name );

		wp_set_post_terms( $post_id, $new_term['term_id'], static::$name );
	}

	/**
	 * Remove a term from the shadow taxonomy upon post delete.
	 *
	 * @param int $post_id
	 *
	 * @uses  get_post()
	 * @uses  get_term_by()
	 * @uses  wp_delete_term()
	 *
	 * @const DOING_AUTOSAVE
	 *
	 * @uses  get_post_type()
	 */
	public function delete_shadow_taxonomy_term( $post_id ) {
		$term = $this->get_shadow_taxonomy_term( $post_id );

		if ( ! $term instanceof \WP_Term ) {
			return;
		}

		wp_delete_term( $term->term_id, static::$name );
	}

	/**
	 * Get shadow taxonomy term from post id.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return array|false|\WP_Error|\WP_Term|null Term object if present.
	 */
	protected function get_shadow_taxonomy_term( int $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

		if ( $this->get_shadow_post_type() !== get_post_type( $post_id ) ) {
			return false;
		}

		$post = get_post( $post_id );
		if ( ! $post instanceof \WP_Post ) {
			return false;
		}

		return get_term_by( 'name', $post->post_title, static::$name );
	}

	/**
	 * Get the taxonomy options.
	 *
	 * Change the capabilities to prevents users from creating new terms.
	 *
	 * @return array
	 */
	protected function get_options() {
		$options = $this->get_default_options();

		// $options['capabilities'] = [
		// 	'manage_terms' => '',
		// 	'edit_terms'   => '',
		// 	'delete_terms' => '',
		// 	'assign_terms' => 'manage_options'
		// ];

		return $options;
	}
}
