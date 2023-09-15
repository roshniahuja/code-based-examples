<?php
/**
 * Newsletter Fields for Taxonomies
 *
 * Using this trait in a class will add Fieldmanager Fields to map newsletters to terms.
 *
 * @package WRTPlugin
 */

namespace WRTPlugin\Taxonomies;

trait NewsletterFieldsContract {
	/**
	 * Register taxonomy hooks
	 */
	public function newsletter_fields_register() {
		add_filter( 'optin_tax_supports_newsletter_assignments', array( $this, 'newsletter_opt_in' ) );
		add_action( 'fm_term_' . self::$name, array( $this, 'register_newsletter_fields' ) );
	}

	/**
	 * Register Fieldmanager Fields
	 *
	 * @return void
	 */
	function register_newsletter_fields() {
		$newsletter = $this->newsletter_field();
		$newsletter->add_term_meta_box( 'Newsletter Vars', self::$name );
	}

	/**
	 * Get Newsletter Field
	 *
	 * @return \Fieldmanager_Textfield
	 */
	function newsletter_field() {
		return new \Fieldmanager_Textfield(
			array(
				'name' => 'newsletter_vars',
			)
		);
	}

	/**
	 * Opts in to newsletter support.
	 *
	 * @param array $taxonomies An array of taxonomies that have been opted in.
	 * @return array
	 */
	function newsletter_opt_in( $taxonomies ) {
		$taxonomies[] = self::$name;

		return $taxonomies;
	}
}
