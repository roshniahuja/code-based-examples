<?php
/**
 * Contest Post type.
 *
 * @package WRTPlugin
 */

namespace WRTPlugin\PostTypes;

use WRTPlugin\Taxonomies\TopicTaxonomy;

/**
 * Contest Post Type.
 */
class ContestPostType extends BasePostType {

	/**
	 * Post Type name.
	 *
	 * @var string
	 */
	public static $name = 'wat-contest';

	/**
	 * Gutenberg support
	 *
	 * @var boolean
	 */
	protected $gutenberg_support = false;

	/**
	 * Register the post type and any hooks you may need
	 */
	public function register() {
		parent::register();

		add_filter( 'add_meta_boxes', [ $this, 'register_meta_box' ] );
		add_action( 'save_post', [ $this, 'save_meta' ] );
	}

	/**
	 * Get the singular post type label.
	 *
	 * @return string
	 */
	protected function get_singular_label() {
		return __( 'Contests & Giveaways', 'wrt-plugin' );
	}

	/**
	 * Get the plural post type label.
	 *
	 * @return string
	 */
	protected function get_plural_label() {
		return __( 'Contests & Giveaways', 'wrt-plugin' );
	}

	/**
	 * Add a custom Dashicon.
	 *
	 * @return string
	 */
	protected function get_menu_icon() {
		return 'dashicons-tickets-alt';
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
				'show_in_rest' => true,
				'rewrite'      => [ 'slug' => 'contest' ],
				'has_archive'  => false,
			]
		);
	}

	/**
	 * The Editor Supports defaults. Wired to 'supports' option of
	 * register_post_type.
	 *
	 * @return array
	 */
	protected function get_editor_supports() {
		return [
			'title',
			'editor',
			'thumbnail',
		];
	}

	/**
	 * Register meta box for CPT.
	 *
	 * @return void
	 */
	public function register_meta_box() {
		add_meta_box(
			'wat-contest-meta',
			esc_html__( 'Contest/Giveaways details', 'wrt-plugin' ),
			[ $this, 'render_meta_box' ],
			self::$name,
			'normal',
			'high'
		);
	}

	/**
	 * Contents for the Contest/Giveaways Meta Box
	 *
	 * @param object $post The post
	 *
	 * @return void
	 * @uses  get_post_meta()
	 * @uses  wp_get_attachment_image_src()
	 *
	 * @uses  wp_nonce_field()
	 */
	function render_meta_box( $post ) {
		wp_nonce_field( 'wat-nounce-contest-meta-box', 'wat_nounce_cp_mb' );

		$forms = class_exists( 'GFAPI' ) ? \GFAPI::get_forms() : null;

		$form_in_use     = get_post_meta( $post->ID, 'contest-form', true );
		$status          = wp_get_object_terms( $post->ID, 'wat-status', [ 'fields' => 'names' ] );
		$winners         = get_post_meta( $post->ID, 'contest-winners', true );
		$start_date      = get_post_meta( $post->ID, 'contest-start-date', true );
		$end_date        = get_post_meta( $post->ID, 'contest-end-date', true );
		$winner_date     = get_post_meta( $post->ID, 'contest-winner-date', true );
		$type            = get_post_meta( $post->ID, 'contest-type', true );
		$banner_image    = get_post_meta( $post->ID, 'banner-image', true );
		$banner_subtitle = get_post_meta( $post->ID, 'banner-subtitle', true );

		if ( ! is_wp_error( $status ) ) {
			$status = reset( $status );
		}
		?>
		<table class="form-table">

			<?php // Banner image ?>
			<tr>
				<th><label for="banner-image"><?php esc_html_e( 'Banner Image', 'wrt-plugin' ); ?></label></th>
				<td>
					<input type="url" id="banner-image" name="banner-image" class="banner-image"
						value="<?php echo esc_attr( $banner_image ); ?>"
						placeholder="<?php esc_attr_e( 'Banner', 'wrt-plugin' ); ?>">
						<button type="button" class="button banner-image-upload" id="banner_image_upload_btn">
							<?php _e( 'Upload Media', 'wrt-plugin' ); ?>
						</button>
						<div class="banner-image-preview"><img src="<?php echo esc_url( $banner_image ); ; ?>" style="max-width: 100%;"></div>
				</td>
			</tr>

			<?php // Banner subtitle ?>
			<tr>
				<th><label for="banner-subtitle"><?php esc_html_e( 'Banner Subtitle', 'wrt-plugin' ); ?></label></th>
				<td>
					<p>
						<input type="text" id="banner-subtitle" name="banner-subtitle"
							value="<?php echo esc_attr( $banner_subtitle ); ?>"
							placeholder="<?php esc_attr_e( 'Banner subtitle...', 'wrt-plugin' ); ?>">
					</p>
				</td>
			</tr>

			<?php // Contest form ?>
			<tr>
				<th><label for="contest-form"><?php esc_html_e( 'Form', 'wrt-plugin' ); ?></label></th>
				<td>
					<?php if ( ! empty( $forms ) ) : ?>
						<select id="contest-form" name="contest-form">
							<?php foreach ( $forms as $form ) : ?>
								<option value="<?php echo absint( $form['id'] ); ?>" <?php selected( $form_in_use, $form['id'] ); ?>><?php echo esc_html( $form['title'] ); ?></option>
							<?php endforeach; ?>
						</select>
					<?php else : ?>
						<p class="description">
							<strong><?php esc_html_e( 'You need to install Gravity Forms and create at least one form to enable form selection', 'wrt-plugin' ); ?></strong>
						</p>
					<?php endif; ?>
				</td>
			</tr>

			<?php // Status selector ?>
			<tr>
				<th><label for="contest-status"><?php esc_html_e( 'Status', 'wrt-plugin' ); ?></label></th>
				<td>
					<select id="contest-status" name="contest-status">
						<option value="<?php esc_attr_e( 'Open', 'wrt-plugin' ); ?>" <?php selected( $status, 'Open' ); ?>><?php esc_html_e( 'Open', 'wrt-plugin' ); ?></option>
						<option value="<?php esc_attr_e( 'Closed', 'wrt-plugin' ); ?>" <?php selected( $status, 'Closed' ); ?>><?php esc_html_e( 'Closed', 'wrt-plugin' ); ?></option>
					</select>
				</td>
			</tr>
			<?php // Prizes ?>
			<tr>
				<th><label for="contest-type"><?php esc_html_e( 'Prizes', 'wrt-plugin' ); ?></label></th>
				<td>
					<p>
						<?php wp_editor( get_post_meta( $post->ID, 'contest-prizes', true ), 'contest-prizes', array( 'media_buttons' => false ) ); ?>
					</p>
				</td>
			</tr>
			<?php // Rules ?>
			<tr>
				<th><label for="contest-type"><?php esc_html_e( 'Rules', 'wrt-plugin' ); ?></label></th>
				<td>
					<p>
						<?php wp_editor( get_post_meta( $post->ID, 'contest-rules', true ), 'contest-rules', array( 'media_buttons' => false ) ); ?>
					</p>
				</td>
			</tr>

			<?php // Label ?>
			<tr>
				<th><label for="button-label"><?php esc_html_e( 'Button label', 'wrt-plugin' ); ?></label></th>
				<td>
					<p>
						<input type="text" id="button-label" name="button-label"
						       value="<?php echo esc_attr( get_post_meta( $post->ID, 'button-label', true ) ); ?>"
						       placeholder="<?php esc_attr_e( 'Rules', 'wrt-plugin' ); ?>">
					</p>
				</td>
			</tr>
			<?php //Side Rail Content ?>
			<tr>
				<th><label for="contest-rightrail-header"><?php esc_html_e( 'Right Rail Header', 'wrt-plugin' ); ?></label>
				</th>
				<td>
					<p>
						<input type="text" id="contest-rightrail-header" name="contest-rightrail-header"
						       value="<?php echo esc_attr( get_post_meta( $post->ID, 'contest-rightrail-header', true ) ); ?>"
						       placeholder="<?php esc_attr_e( 'Enter a Short Header', 'wrt-plugin' ); ?>">
					</p>
				</td>
			</tr>
			<tr>
				<th><label for="contest-rightrail-copy"><?php esc_html_e( 'Right Rail Copy', 'wrt-plugin' ); ?></label></th>
				<td>
					<p>
						<?php wp_editor( get_post_meta( $post->ID, 'contest-rightrail-copy', true ), 'contest-rightrail-copy', array( 'media_buttons' => true ) ); ?>
					</p>
				</td>
			</tr>
			<?php // Winners ?>
			<tr>
				<th><label for="contest-winners"><?php esc_html_e( 'Winner(s)', 'wrt-plugin' ); ?></label></th>
				<td>
					<ul id="contest-winners" class="contest-winners">
						<?php if ( ! empty( $winners ) ) : ?>
							<?php foreach ( $winners as $winner ) : ?>
								<li class="repeatable display">
									<span class="dashicons dashicons-sort"></span>

									<div class="winner"><?php echo esc_html( $winner ); ?></div>
									<input type="text" name="contest-winners"
									       value="<?php echo esc_attr( $winner ); ?>">
									<button class="save button"><?php esc_html_e( 'Save', 'wrt-plugin' ) ?></button>
									<button class="edit button"><?php esc_html_e( 'Edit', 'wrt-plugin' ) ?></button>
									<button class="remove button"><?php esc_html_e( 'Remove', 'wrt-plugin' ) ?></button>
								</li>
							<?php endforeach; ?>
						<?php else : ?>
							<li class="repeatable editable">
								<span class="dashicons dashicons-sort"></span>

								<div class="winner"></div>
								<input type="text" name="contest-winners">
								<button class="save button"><?php esc_html_e( 'Save', 'wrt-plugin' ) ?></button>
								<button class="edit button"><?php esc_html_e( 'Edit', 'wrt-plugin' ) ?></button>
								<button class="remove button"><?php esc_html_e( 'Remove', 'wrt-plugin' ) ?></button>
							</li>
						<?php endif; ?>
					</ul>
					<button class="add button"><?php esc_html_e( 'Add winner', 'wrt-plugin' ) ?></button>
				</td>
			</tr>

			<?php // Winners description ?>
			<tr>
				<th><label for="excerpt"><?php esc_html_e( 'Winner(s) description', 'wrt-plugin' ); ?></label></th>
				<td>
					<textarea id="excerpt" name="excerpt" rows="20"
					          cols="40"><?php echo esc_html( $post->post_excerpt ); ?></textarea>
				</td>
			</tr>

			<?php // Start date ?>
			<tr>
				<th><label for="contest-start-date"><?php esc_html_e( 'Start date', 'wrt-plugin' ); ?></label></th>
				<td>
					<input type="datetime-local" name="contest-start-date" id="contest-start-date"
					       value="<?php echo esc_attr( $start_date ); ?>"/>
				</td>
			</tr>

			<?php // End date ?>
			<tr>
				<th><label for="contest-end-date"><?php esc_html_e( 'End date', 'wrt-plugin' ); ?></label></th>
				<td>
					<input type="datetime-local" name="contest-end-date" id="contest-end-date"
					       value="<?php echo esc_attr( $end_date ); ?>"/>
				</td>
			</tr>

			<?php // Winne date ?>
			<tr>
				<th><label for="contest-winner-date"><?php esc_html_e( 'Winner date', 'wrt-plugin' ); ?></label></th>
				<td>
					<input type="datetime-local" name="contest-winner-date" id="contest-winner-date"
					       value="<?php echo esc_attr( $winner_date ); ?>"/>
				</td>
			</tr>

			<?php // Type ?>
			<tr>
				<th><label for="contest-type"><?php esc_html_e( 'Type', 'wrt-plugin' ); ?></label></th>
				<td>
					<p>
						<input id="contest-contest" type="radio" name="contest-type"
						       value="contest" <?php checked( $type, 'contest' ); ?>><label
								for="contest-contest"><?php esc_html_e( 'Contest', 'wrt-plugin' ); ?></label>
					</p>

					<p>
						<input id="contest-giveaway" type="radio" name="contest-type"
						       value="giveaway" <?php checked( $type, 'giveaway' ); ?>><label
								for="contest-giveaway"><?php esc_html_e( 'Giveaway', 'wrt-plugin' ); ?></label>
					</p>
					<p>
						<input id="contest-wyng" type="radio" name="contest-type"
						       value="wyng" <?php checked( $type, 'wyng' ); ?>><label
								for="contest-wyng"><?php esc_html_e( 'WYNG', 'wrt-plugin' ); ?></label>
					</p>
				</td>
			</tr>
		</table>

		<?php
	}

	/**
	 * Save contest Meta data
	 *
	 * @param int $post_id Id of the post to save
	 *
	 * @return void
	 *
	 * @uses  wp_is_post_autosave()
	 * @uses  wp_is_post_revision()
	 * @uses  wp_verify_nonce()
	 * @uses  delete_post_meta()
	 * @uses  update_post_meta()
	 * @uses  sanitize_text_field()
	 * @uses  absint()
	 *
	 */
	function save_meta( $post_id ) {
		if ( self::$name !== get_post_type( $post_id ) ) {
			return;
		}

		$is_autosave    = wp_is_post_autosave( $post_id );
		$is_revision    = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST['wat_nounce_cp_mb'] ) && wp_verify_nonce( $_POST['wat_nounce_cp_mb'], 'wat-nounce-contest-meta-box' ) ) ? true : false;

		// Don't run on autosave, revision, or nonce fails to validate
		if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
			return;
		}

		if ( isset( $_POST['contest-form'] ) && ! empty( $_POST['contest-form'] ) ) {
			update_post_meta( $post_id, 'contest-form', sanitize_text_field( $_POST['contest-form'] ) );
		} else {
			delete_post_meta( $post_id, 'contest-form' );
		}

		// Save contest status
		if ( isset( $_POST['contest-status'] ) ) {
			$term = term_exists( esc_attr( $_POST['contest-status'] ), 'wat-status' );
			if ( $term === 0 || $term === null ) {
				$term = wp_insert_term( esc_attr( $_POST['contest-status'] ), 'wat-status' );
			}
			wp_set_object_terms( $post_id, array( (int) $term['term_id'] ), 'wat-status', false );
		} else {
			wp_set_object_terms( $post_id, array(), 'wat-status', false );
		}

		$excerpt = isset( $_POST['excerpt'] ) && ! empty( $_POST['excerpt'] ) ? $_POST['excerpt'] : null;

		remove_action( 'save_post', [ $this, 'save_meta' ] );
		wp_update_post(
				[
						'ID'           => $post_id,
						'post_excerpt' => wp_kses_post( $excerpt ),
				]
		);
		add_action( 'save_post', [ $this, 'save_meta' ] );

		if ( isset( $_POST['contest-rules'] ) && ! empty( $_POST['contest-rules'] ) ) {
			update_post_meta( $post_id, 'contest-prizes', filter_input( INPUT_POST, 'contest-prizes', FILTER_CALLBACK, array( 'options' => '\wp_kses_post' ) ) );
		} else {
			delete_post_meta( $post_id, 'contest-prizes' );
		}

		if ( isset( $_POST['contest-rules'] ) && ! empty( $_POST['contest-rules'] ) ) {
			update_post_meta( $post_id, 'contest-rules', filter_input( INPUT_POST, 'contest-rules', FILTER_CALLBACK, array( 'options' => '\wp_kses_post' ) ) );
		} else {
			delete_post_meta( $post_id, 'contest-rules' );
		}

		if ( isset( $_POST['button-label'] ) && ! empty( $_POST['button-label'] ) ) {
			update_post_meta( $post_id, 'button-label', sanitize_text_field( $_POST['button-label'] ) );
		} else {
			delete_post_meta( $post_id, 'button-label' );
		}
		if ( isset( $_POST['contest-rightrail-header'] ) && ! empty( $_POST['contest-rightrail-header'] ) ) {
			update_post_meta( $post_id, 'contest-rightrail-header', sanitize_text_field( $_POST['contest-rightrail-header'] ) );
		} else {
			delete_post_meta( $post_id, 'contest-rightrail-header' );
		}

		if ( isset( $_POST['contest-rightrail-copy'] ) && ! empty( $_POST['contest-rightrail-copy'] ) ) {
			update_post_meta( $post_id, 'contest-rightrail-copy', filter_input( INPUT_POST, 'contest-rightrail-copy', FILTER_CALLBACK, array( 'options' => '\wp_kses_post' ) ) );
		} else {
			delete_post_meta( $post_id, 'contest-rightrail-copy' );
		}

		if ( isset( $_POST['contest-start-date'] ) && ! empty( $_POST['contest-start-date'] ) ) {
			update_post_meta( $post_id, 'contest-start-date', sanitize_text_field( $_POST['contest-start-date'] ) );
		} else {
			delete_post_meta( $post_id, 'contest-start-date' );
		}

		if ( isset( $_POST['contest-end-date'] ) && ! empty( $_POST['contest-end-date'] ) ) {
			update_post_meta( $post_id, 'contest-end-date', sanitize_text_field( $_POST['contest-end-date'] ) );
		} else {
			delete_post_meta( $post_id, 'contest-end-date' );
		}

		if ( isset( $_POST['contest-winners'] ) && ! empty( $_POST['contest-winners'] ) ) {
			update_post_meta( $post_id, 'contest-winners', array_map( 'sanitize_text_field', (array) $_POST['contest-winners'] ) );
		} else {
			delete_post_meta( $post_id, 'contest-winners' );
		}

		if ( isset( $_POST['contest-type'] ) && ! empty( $_POST['contest-type'] ) ) {
			update_post_meta( $post_id, 'contest-type', sanitize_text_field( $_POST['contest-type'] ) );
		} else {
			delete_post_meta( $post_id, 'contest-type' );
		}

		if ( isset( $_POST['contest-winner-date'] ) && ! empty( $_POST['contest-winner-date'] ) ) {
			update_post_meta( $post_id, 'contest-winner-date', sanitize_text_field( $_POST['contest-winner-date'] ) );
		} else {
			delete_post_meta( $post_id, 'contest-winner-date' );
		}

		if ( isset( $_POST['banner-image'] ) && ! empty( $_POST['banner-image'] ) ) {
			update_post_meta( $post_id, 'banner-image', sanitize_text_field( $_POST['banner-image'] ) );
		} else {
			delete_post_meta( $post_id, 'banner-image' );
		}

		if ( isset( $_POST['banner-subtitle'] ) && ! empty( $_POST['banner-subtitle'] ) ) {
			update_post_meta( $post_id, 'banner-subtitle', sanitize_text_field( $_POST['banner-subtitle'] ) );
		} else {
			delete_post_meta( $post_id, 'banner-subtitle' );
		}
	}
}
