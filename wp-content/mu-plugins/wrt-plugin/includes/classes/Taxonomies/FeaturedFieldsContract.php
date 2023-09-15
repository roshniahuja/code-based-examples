<?php
/**
 * Featured Fields for Taxonomies
 *
 * Using this trait in a class will add Fieldmanager Fields to curate featured posts.
 *
 * @package WRTPlugin
 */

namespace WRTPlugin\Taxonomies;

trait FeaturedFieldsContract {
	/**
	 * Register taxonomy hooks
	 */
	public function featured_fields_register() {
		add_action( 'fm_term_' . self::$name, array( $this, 'register_fm_fields' ) );
	}

	/**
	 * Register Fieldmanager Fields
	 *
	 * @return void
	 */
	function register_fm_fields() {
		$featured_posts  = $this->featured_posts_fieldgroup();
		$featured_inline = $this->featured_inline_card();

		$featured_posts->add_term_meta_box( 'Featured Posts', self::$name );
		$featured_inline->add_term_meta_box( 'Featured Inline Post Card', self::$name );
	}

	/**
	 * Get Featured Posts FM Group
	 *
	 * @return \Fieldmanager_Group
	 */
	function featured_posts_fieldgroup() {
		return new \Fieldmanager_Group(
			array(
				'name' => 'featured_posts',
				'children' => array(
					'featured_post_0' => self::create_autocomplete_field(),
					'featured_post_1' => self::create_autocomplete_field(),
					'featured_post_2' => self::create_autocomplete_field(),
				),
			)
		);
	}

	/**
	 * Get Inline Cards FM Group
	 *
	 * @return \Fieldmanager_Group
	 */
	function featured_inline_card() {
		return new \Fieldmanager_Group(
			array(
				'name' => 'inline_cards',
				'children' => array(
					'inline_card_0' => self::create_autocomplete_field(),
				),
			)
		);
	}

	/**
	 * Create autocomplete field.
	 *
	 * @static
	 * @return \Fieldmanager_Autocomplete
	 */
	public static function create_autocomplete_field() {
		return new \Fieldmanager_Autocomplete(
			array(
				'datasource' => new \Fieldmanager_Datasource_Post(
					array(
						'query_args' => array(
							'post_type'   => 'post',
							'post_status' => 'publish',
							'tax_query'   => array(
								'taxonomy' => self::$name,
								'field'    => 'id',
								'terms'    => array( get_queried_object_id() ),
							)
						)
					)
				)
			)
		);
	}
}
