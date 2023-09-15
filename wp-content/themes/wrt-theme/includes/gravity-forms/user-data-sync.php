<?php
/**
 * User Data Sync support for Gravity Forms
 *
 * @package WRTTheme
 */

namespace WRTTheme\GravityForms\UserDataSync;

/**
 * Hook into rest api init action
 *
 * @since 0.1.0
 *
 * @uses  add_action()
 *
 * @return void
 */
function setup() {
	$n = function ( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	if ( is_user_logged_in() ) {

		$fields = apply_filters( 'wat_gf_field_values', array(
			'first_name',
			'last_name',
			'title',
			'grade_level',
			'email',
			'zipcode',
			'school',
			'pid',
			'type',
			'm_street',
			'm_city',
			'm_zipcode',
			'm_ziptext',
			'm_state',
			'p_street',
			'p_city',
			'p_zipcode',
			'p_state',
			'dpbc',
			'chdigit',
		), get_current_user_id() );

		foreach ( $fields as $field ) {
			add_filter( "gform_field_value_{$field}", $n ( $field ) );
		}
	}

	add_filter( 'gform_form_settings', $n ( 'add_form_setting' ), 10, 2 );
	add_filter( 'gform_pre_form_settings_save', $n ( 'save_form_setting' ) );
	add_action( 'gform_after_submission', $n ( 'sync_user_data' ), 10, 2 );
}

/**
 * Add user sync form setting
 *
 * @param $settings
 * @param $form
 *
 * @return mixed
 */
function add_form_setting( $settings, $form ) {
	$settings['Form Basics']['wat_sync_user'] = '
        <tr>
            <th><label for="wat_sync_user">' . esc_html__( 'Sync Form to User Account', 'wat' ) . '</label></th>
            <td><input type="checkbox" value="yes" name="wat_sync_user" ' . checked( 'yes', rgar( $form, 'wat_sync_user' ), false ) . '></td>
        </tr>';

	return $settings;
}

/**
 * Save the user sync form setting
 *
 * @param $entry
 */
function save_form_setting( $form ) {
	$form['wat_sync_user'] = rgpost( 'wat_sync_user' );

	return $form;
}

/**
 * If user is logged in and the current form has the sync user
 * form setting enabled update the users school info based on their
 * form selections
 *
 * @param $entry
 * @param $form
 */
function sync_user_data( $entry, $form ) {

	$school_meta = array(
		'wat_zipcode',
		'wat_school',
		'wat_pid',
		'wat_type',
		'wat_m_street',
		'wat_m_city',
		'wat_m_zipcode',
		'wat_m_ziptext',
		'wat_m_state',
		'wat_p_street',
		'wat_p_city',
		'wat_p_zipcode',
		'wat_p_state',
		'wat_dpbc',
		'wat_chdigit',
	);

	if ( is_user_logged_in() && 'yes' === rgar( $form, 'wat_sync_user' ) ) {
		foreach ( $form['fields'] as $field ) {
			if ( 'school_finder' === $field->type ) {
				foreach ( $field->inputs as $key => $input ) {
					update_user_meta( get_current_user_id(), $school_meta[ $key ], sanitize_text_field( rgar( $entry, $input['id'] ) ) );
				}
			}
			if ( 'text' === $field->type && 'title' === $field->inputName ) {
				update_user_meta( get_current_user_id(), 'wat_title', sanitize_text_field( rgar( $entry, $field->id ) ) );
			}
			if ( 'select' === $field->type && 'grade_level' === $field->inputName ) {
				update_user_meta( get_current_user_id(), 'wat_grade_level', sanitize_text_field( rgar( $entry, $field->id ) ) );
			}
		}
	}
}

/**
 * Populate the first name field
 *
 * @param $value
 *
 * @return mixed
 */
function first_name( $value ) {
	return esc_attr( get_user_meta( get_current_user_id(), 'first_name', true ) );
}

/**
 * Populate the last name field
 *
 * @param $value
 *
 * @return mixed
 */
function last_name( $value ) {
	return esc_attr( get_user_meta( get_current_user_id(), 'last_name', true ) );
}

/**
 * Populate the title/role field
 *
 * @param $value
 *
 * @return mixed
 */
function title( $value ) {
	return esc_attr( get_user_meta( get_current_user_id(), 'wat_title', true ) );
}

/**
 * Populate the grade level field
 *
 * @param $value
 *
 * @return mixed
 */
function grade_level( $value ) {
	return esc_attr( get_user_meta( get_current_user_id(), 'wat_grade_level', true ) );
}

/**
 * Populate the email field
 *
 * @param $value
 *
 * @return mixed
 */
function email( $value ) {
	return esc_attr( get_the_author_meta( 'user_email', get_current_user_id() ) );
}

/**
 * Populate the zipcode field
 *
 * @param $value
 *
 * @return mixed
 */
function zipcode( $value ) {
	return esc_attr( get_user_meta( get_current_user_id(), 'wat_zipcode', true ) );
}

/**
 * Populate the school field
 *
 * @param $value
 *
 * @return mixed
 */
function school( $value ) {
	return esc_attr( get_user_meta( get_current_user_id(), 'wat_school', true ) );
}

/**
 * Populate the pid field
 *
 * @param $value
 *
 * @return mixed
 */
function pid( $value ) {
	return esc_attr( get_user_meta( get_current_user_id(), 'wat_pid', true ) );
}

/**
 * Populate the type field
 *
 * @param $value
 *
 * @return mixed
 */
function type( $value ) {
	return esc_attr( get_user_meta( get_current_user_id(), 'wat_type', true ) );
}

/**
 * Populate the m_street field
 *
 * @param $value
 *
 * @return mixed
 */
function m_street( $value ) {
	return esc_attr( get_user_meta( get_current_user_id(), 'wat_m_street', true ) );
}

/**
 * Populate the m_city field
 *
 * @param $value
 *
 * @return mixed
 */
function m_city( $value ) {
	return esc_attr( get_user_meta( get_current_user_id(), 'wat_m_city', true ) );
}

/**
 * Populate the m_zipcode field
 *
 * @param $value
 *
 * @return mixed
 */
function m_zipcode( $value ) {
	return esc_attr( get_user_meta( get_current_user_id(), 'wat_m_zipcode', true ) );
}

/**
 * Populate the m_ziptext field
 *
 * @param $value
 *
 * @return mixed
 */
function m_ziptext( $value ) {
	return esc_attr( get_user_meta( get_current_user_id(), 'wat_m_ziptext', true ) );
}

/**
 * Populate the m_state field
 *
 * @param $value
 *
 * @return mixed
 */
function m_state( $value ) {
	return esc_attr( get_user_meta( get_current_user_id(), 'wat_m_state', true ) );
}

/**
 * Populate the p_street field
 *
 * @param $value
 *
 * @return mixed
 */
function p_street( $value ) {
	return esc_attr( get_user_meta( get_current_user_id(), 'wat_p_street', true ) );
}

/**
 * Populate the p_city field
 *
 * @param $value
 *
 * @return mixed
 */
function p_city( $value ) {
	return esc_attr( get_user_meta( get_current_user_id(), 'wat_p_city', true ) );
}

/**
 * Populate the p_zipcode field
 *
 * @param $value
 *
 * @return mixed
 */
function p_zipcode( $value ) {
	return esc_attr( get_user_meta( get_current_user_id(), 'wat_p_zipcode', true ) );
}

/**
 * Populate the p_state field
 *
 * @param $value
 *
 * @return mixed
 */
function p_state( $value ) {
	return esc_attr( get_user_meta( get_current_user_id(), 'wat_p_state', true ) );
}

/**
 * Populate the dpbc field
 *
 * @param $value
 *
 * @return mixed
 */
function dpbc( $value ) {
	return esc_attr( get_user_meta( get_current_user_id(), 'wat_dpbc', true ) );
}

/**
 * Populate the chdigit field
 *
 * @param $value
 *
 * @return mixed
 */
function chdigit( $value ) {
	return esc_attr( get_user_meta( get_current_user_id(), 'wat_chdigit', true ) );
}
