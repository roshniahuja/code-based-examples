<?php
/**
 * School Field Support for Gravity Forms.
 *
 * @package WRTTheme
 */

namespace WRTTheme\GravityForms\SchoolField;

/**
 * Hook into the init action
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

	add_action( 'init', $n ( 'register_field' ) );
}

/**
 * Register the School Finder Gravity Forms Fields
 */
function register_field() {
	if ( ! class_exists( 'GF_Fields' ) ) {
		return;
	}
	\GF_Fields::register( new GF_School_Finder_Field() );
}

if ( class_exists( 'GF_Field' ) ) {
	class GF_School_Finder_Field extends \GF_Field {
		public $type = 'school_finder';

		/*
		 * Set edit button position and label
		 */
		public function get_form_editor_button() {
			return array(
				'group' => 'advanced_fields',
				'text'  => esc_html__( 'School Finder', 'wrt-theme' )
			);
		}

		/**
		 * Set form editor title
		 *
		 * @return string
		 */
		public function get_form_editor_field_title() {
			return esc_html__( 'School Finder', 'wrt-theme' );
		}

		/**
		 * Configure fields settings
		 *
		 * @return array
		 */
		public function get_form_editor_field_settings() {
			return array(
                'conditional_logic_field_setting',
				'prepopulate_field_setting',
				'error_message_setting',
				'label_setting',
				'rules_setting',
				'placeholder_setting',
			);
		}


		/**
		 * Validate required fields are set
		 *
		 * @param $value
		 * @param $form
		 */
		public function validate( $value, $form ) {
			if ( $this->isRequired ) {
				$zipcode    = rgpost( 'input_' . $this->id . '_1' );
				$school     = rgpost( 'input_' . $this->id . '_2' );
				$address    = rgpost( 'input_' . $this->id . '_5');
				$city       = rgpost( 'input_' . $this->id . '_6');
				$usState    = rgpost( 'input_' . $this->id . '_9' );

				if ( empty( $school ) || empty( $zipcode ) || ($school == "Other" && (empty( $address) || empty( $city ) || empty( $usState)))) {
					$this->failed_validation  = true;
					$this->validation_message = empty( $this->errorMessage ) ? __( 'This field is required. Please select your school.', 'wrt-theme' ) : $this->errorMessage;
				}
			}
		}

		/**
		 * Field markup
		 *
		 * @param        $form
		 * @param string $value
		 * @param null   $entry
		 *
		 * @return string
		 */
		public function get_field_input( $form, $value = '', $entry = null ) {
			$form_id         = $form['id'];
			$is_entry_detail = $this->is_entry_detail();
			$is_form_editor  = $this->is_form_editor();
			$id              = (int) $this->id;

			$field_id = $is_entry_detail || $is_form_editor || $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";

			$class                 = $is_entry_detail ? '_admin' : 'zip-code-'.$form_id;
			$tabindex              = $this->get_tabindex();
			$button_tabindex       = $this->get_tabindex();
			$select_tabindex       = $this->get_tabindex();
			$disabled_text         = $is_form_editor ? 'disabled="disabled"' : '';
			$placeholder_attribute = $this->get_field_placeholder_attribute();

			$zip    = \RGForms::get( $this->id . '.1', $value );
			$school = \RGForms::get( $this->id . '.2', $value );
			if ( ! empty( $school ) ) {
				$post = get_page_by_title( $school, 'OBJECT', 'wat-school' );
				if ( $post ) {
					$school = sprintf( '<option value="%s" selected>%s</option>', esc_attr( $post->post_title ), esc_html( $post->post_title ) );
				} else {
					$school = '<option value="Other" selected>Other</option>';
				}
			}
			$additional_field_id = 2;
			$additional_fields   = array(
				'pid'       => \RGForms::get( $this->id . '.3', $value ),
				'type'      => \RGForms::get( $this->id . '.4', $value ),
				'm_street'  => \RGForms::get( $this->id . '.5', $value ),
				'm_city'    => \RGForms::get( $this->id . '.6', $value ),
				'm_zipcode' => \RGForms::get( $this->id . '.7', $value ),
				'm_ziptext' => \RGForms::get( $this->id . '.8', $value ),
				'm_state'   => \RGForms::get( $this->id . '.9', $value ),
				'p_street'  => \RGForms::get( $this->id . '.11', $value ),
				'p_city'    => \RGForms::get( $this->id . '.12', $value ),
				'p_zipcode' => \RGForms::get( $this->id . '.13', $value ),
				'p_state'   => \RGForms::get( $this->id . '.14', $value ),
				'dpbc'      => \RGForms::get( $this->id . '.15', $value ),
				'chdigit'   => \RGForms::get( $this->id . '.16', $value ),
			);

			ob_start(); ?>
			<style>.showOther-<?= $form_id  ?>, .schoolOther-<?= $form_id  ?> { display: none;} .gfield_checkbox label {font-size: 12px !important; }</style>
<div class='ginput_container'>
  <input name='input_<?php echo esc_attr( $id ) ?>.1' id='<?php echo esc_attr( $field_id ) ?>_1' type='number' value='<?php echo esc_attr( $zip ) ?>' maxlength='5' pattern='[0-9]{5}' class='<?php echo esc_attr( $class ) ?>' <?php echo $tabindex ?> <?php echo $placeholder_attribute ?> <?php echo esc_attr( $disabled_text ) ?>/>
  <button id="finder-button_<?= $form_id  ?>" <?php echo $disabled_text ?> <?php echo $button_tabindex ?>>
  <?php esc_html_e( 'Find School' ); ?>
  </button>
  <div class="ginput_school_dropdown">
    <select aria-label="School Select List" name='input_<?php echo esc_attr( $id ) ?>.2' id='<?php echo esc_attr( $field_id ) ?>_2' class="school-dropdown-<?= $form_id  ?>" <?php echo $disabled_text ?> <?php echo $select_tabindex ?>>
      <option value="">
      <?php esc_html_e( 'Enter Zip Code in Field Above' ) ?>
      </option>
      <?php echo $school; ?>
    </select>
  </div>
  <div class="showOther-<?= $form_id  ?>">
    <?php foreach ( $additional_fields as $key => $hidden_value ): ?>
    <?php $additional_field_id ++; if($key == 'm_street' || $key == 'm_city' ||  $key == 'm_state' ||  $key == 'm_zipcode' ) { ?>
    <label class="gfield_label" for="<?php echo esc_attr( $field_id . '_' . $additional_field_id ) ?>">
      <?php if($key == 'm_street') { ?>
      Address
      <?php } elseif ( $key == 'm_city' ){ ?>
      City
      <?php } elseif($key == 'm_state') { ?>
      State

      <?php } elseif($key == 'm_zipcode') { ?>
      Zip Code
      <?php } ?>
      <span class="gfield_required">*</span></label>
      <div class="ginput_container ginput_container_text">
    <input type='hidden' name='input_<?php echo esc_attr( $id . '.' . $additional_field_id ) ?>' id='<?php echo esc_attr( $field_id . '_' . $additional_field_id ) ?>' class="<?php echo esc_attr( $key ) ?>-<?= $form_id  ?>" value='<?php echo esc_attr( $hidden_value ) ?>' /></div>
    <?php
					} else {
					?>
    <input type='hidden' name='input_<?php echo esc_attr( $id . '.' . $additional_field_id ) ?>' id='<?php echo esc_attr( $field_id . '_' . $additional_field_id ) ?>' class="<?php echo esc_attr( $key ) ?>-<?= $form_id  ?>" value='<?php echo esc_attr( $hidden_value ) ?>' />
    <?php }
				endforeach; ?>
  </div>
</div>
			<script>
				var school_url = <?php echo json_encode( get_home_url().'/wp-json/weareteachers/v1/schools/')  ?>;
				var select_school = <?php echo json_encode( __( '-- Select your School --', 'wrt-theme' ) ) ?>;
			'use strict';

( function ( window, $ ) {

	 function checkOther(selectedVal) {
		 if(selectedVal === 'Other' || ($( '.school-dropdown-<?= $form_id  ?>' ).val() !== "")) {
		 	jQuery(".showOther-<?= $form_id  ?>").show();
		 	jQuery('.m_street-<?= $form_id  ?>').prop('type','text');
			jQuery('.m_street-<?= $form_id  ?>').attr('aria-required','true');
			jQuery('.m_city-<?= $form_id  ?>').prop('type','text');
			jQuery('.m_state-<?= $form_id  ?>').prop('type','text');
            jQuery('.m_zipcode-<?= $form_id  ?>').prop('type','text');
			jQuery(".schoolOther-<?= $form_id  ?>").show();
			jQuery(".schoolOther-<?= $form_id  ?> input").attr('aria-required','true');

		} else {

			jQuery(".showOther-<?= $form_id  ?>").hide();
			jQuery('.m_street-<?= $form_id  ?>').prop('type','hidden');
			jQuery('.m_street-<?= $form_id  ?>').attr('aria-required','false');
			jQuery('.m_city-<?= $form_id  ?>').prop('type','hidden');
			jQuery('.m_state-<?= $form_id  ?>').prop('type','hidden');
            jQuery('.m_zipcode-<?= $form_id  ?>').prop('type','hidden');
			jQuery(".schoolOther-<?= $form_id  ?>").hide();
			jQuery(".schoolOther-<?= $form_id  ?> input").attr('aria-required','false'); }
	 	}

	    var schools = [],
		schooldropdown = $( '.school-dropdown-<?= $form_id  ?>' );
		if($( '.zip-code-<?= $form_id  ?>' ).val() === '') {
			$('.school-dropdown-<?= $form_id  ?>').hide();
		} else { $('.school-dropdown-<?= $form_id  ?>').show();
		}
		$( document ).ready(function() {
		checkOther(schooldropdown.val());
		});

	function create_option_field( value , text ){
		var option = document.createElement( 'OPTION' );
		option.setAttribute( 'value', value );
		var text = document.createTextNode( text );
		option.appendChild( text );
		return option;
	}

	function get_schools(){
		$(schooldropdown).show();
		var zip = $( '.zip-code-<?= $form_id  ?>' ).val();
		var isOther = "";
		schooldropdown.html( create_option_field( '', 'Finding Schools...' ) );
        schooldropdown.prop('disabled','disabled');
		if ( /(^\d{5}$)|(^\d{5}-\d{4}$)/.test( zip ) ) {
			$.get( window.school_url, {zipcode: zip}, function( response ) {

				if( response.found_schools > 0 ) {
                    schooldropdown.html( create_option_field( '', window.select_school ) );
					schools = response.schools;
					$.each( response.schools, function( index, value ){
						schooldropdown.append( create_option_field( value.name, value.name ) );
					} );
					schooldropdown.append( create_option_field( 'Other', 'Other' ) );
				} else {
					schooldropdown.html( create_option_field( 'Other', 'School Not Found' ) );
					isOther = "Other";
				}

                schooldropdown.prop('disabled',false);
				checkOther(isOther);
			} );
		} else {
			schooldropdown.html( create_option_field( 'Other', 'Other' ) );
			//dropdown.append( create_option_field( 'Other', 'Other' ) );
            schooldropdown.prop('disabled',false);
		}
	}

	$( document.getElementById( 'finder-button_<?= $form_id  ?>' ) ).on( 'click', function( e ) {
		e.preventDefault( );
		get_schools();
	} );

	$( '.zip-code-<?= $form_id  ?>' ).keypress( function( e ) {
		var keycode = ( e.keyCode ? e.keyCode : e.which );
		if( keycode == '13' ){
			get_schools();
		}
	} );

	$( schooldropdown ).on( 'change', function( e ) {
		e.preventDefault( );
		var selected = $(this).val();



		checkOther(selected);

		$.each( schools, function( index, value ){
			if( selected === value.name ){
				$.each( value, function( parameter, parameter_value){
					$( '.'+ parameter+'-<?= $form_id ?>' ).val( parameter_value );
				} );
			}
		} );
		if (selected === 'Other') { $('.m_zipcode-<?= $form_id  ?>').val($('.zip-code-<?= $form_id  ?>').val()); }

	} );

} )( this, jQuery );

			</script>
			<?php
			return ob_get_clean();
		}

		/**
		 * Register additional inputs for the field
		 *
		 * @return string
		 */
		public function get_form_editor_inline_script_on_page_render() {
			$label = json_encode( __( 'Enter Zip Code and click Find School', 'wrt-theme' ) );

			return "function SetDefaultValues_school_finder(field) {
					field.label = {$label}
					field.inputs = [
						new Input(field.id + '.1', 'Zipcode'),
						new Input(field.id + '.2', 'School'),
						new Input(field.id + '.3', 'pid'),
						new Input(field.id + '.4', 'type'),
						new Input(field.id + '.5', 'm_street'),
						new Input(field.id + '.6', 'm_city'),
						new Input(field.id + '.7', 'm_zipcode'),
						new Input(field.id + '.8', 'm_ziptext'),
						new Input(field.id + '.9', 'm_state'),
						new Input(field.id + '.11', 'p_street'),
						new Input(field.id + '.12', 'p_city'),
						new Input(field.id + '.13', 'p_zipcode'),
						new Input(field.id + '.14', 'p_state'),
						new Input(field.id + '.15', 'dpbc'),
						new Input(field.id + '.16', 'chdigit'),
					];
			}";

		}

		/**
		 * Format the saved data for display in WordPress Admin
		 *
		 * @param        $value
		 * @param string $currency
		 * @param bool   $use_text
		 * @param string $format
		 * @param string $media
		 *
		 * @return string
		 */
		public function get_value_entry_detail( $value, $currency = '', $use_text = false, $format = 'html', $media = 'screen' ) {
			if ( is_array( $value ) ) {
				$school_data = "";
				$data        = array(
					'zipcode'   => trim( rgget( $this->id . '.1', $value ) ),
					'school'    => trim( rgget( $this->id . '.2', $value ) ),
					'pid'       => trim( rgget( $this->id . '.3', $value ) ),
					'type'      => trim( rgget( $this->id . '.4', $value ) ),
					'm_street'  => trim( rgget( $this->id . '.5', $value ) ),
					'm_city'    => trim( rgget( $this->id . '.6', $value ) ),
					'm_zipcode' => trim( rgget( $this->id . '.7', $value ) ),
					'm_ziptext' => trim( rgget( $this->id . '.8', $value ) ),
					'm_state'   => trim( rgget( $this->id . '.9', $value ) ),
					'p_street'  => trim( rgget( $this->id . '.11', $value ) ),
					'p_city'    => trim( rgget( $this->id . '.12', $value ) ),
					'p_zipcode' => trim( rgget( $this->id . '.13', $value ) ),
					'p_state'   => trim( rgget( $this->id . '.14', $value ) ),
					'dpbc'      => trim( rgget( $this->id . '.15', $value ) ),
					'chdigit'   => trim( rgget( $this->id . '.16', $value ) )
				);

				$school_data = "<ul>";
				foreach ( $data as $key => $value ) {
					$school_data .= "<li></li><strong>" . $key . ": </strong> " . $value . "</li>";
				}
				$school_data .= "</ul>";

				return $school_data;
			} else {
				return $value;
			}
		}
	}
}
