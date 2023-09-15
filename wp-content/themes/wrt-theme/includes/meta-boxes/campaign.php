<?php
/**
 * Campaign Meta Box
 *
 * @package WRTTheme
 */

namespace WRTTheme\Meta_Boxes\Campaign;

/**
 * Hook into init action
 *
 * @since 0.1.0
 *
 * @uses add_filter()
 * @uses add_action()
 *
 * @return void
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_filter( 'add_meta_boxes', $n( 'campaign' ) );
	add_action( 'admin_enqueue_scripts', $n( 'admin_style' ) );
	add_action( 'save_post', $n( 'save_meta' ) );
	add_action( 'do_meta_boxes', $n( 'rename_featured_image_mb' ) );
	add_filter( 'admin_post_thumbnail_html', $n( 'rename_featured_image_links' ) );
}

/**
 * Register the wat-campaign-meta Meta Box
 *
 * @since 0.1.0
 *
 * @uses add_meta_box()
 *
 * @return void
 */
function campaign() {
	add_meta_box( 'wat-campaign-meta', esc_html__( 'Campaign details', 'wat' ), __NAMESPACE__ . '\wat_campaign_meta_cb', 'wat-campaign', 'normal', 'high' );
}

/**
 * Contents for the Campaign Meta Box
 *
 * @since 0.1.0
 *
 * @uses wp_nonce_field()
 * @uses get_post_meta()
 * @uses wp_get_attachment_image_src()
 *
 * @param  object $post The post
 *
 * @return void
 */
function wat_campaign_meta_cb( $post ) {
	wp_nonce_field( 'wat_nounce_campaign_meta_box', 'wat_nounce_cp_mb' );

	$campaign_url = get_post_meta( $post->ID, 'campaign-url', true );
	$campaign_cta = get_post_meta( $post->ID, 'campaign-cta', true );
	?>

	<table class="form-table">

		<?php // URL. ?>
		<tr>
			<th><label for="campaign-url"><?php esc_html_e( 'URL', 'wat' ); ?></label></th>
			<td>
				<input type="url" name="campaign-url" id="campaign-url" class="regular-text full-width" value="<?php echo esc_attr( $campaign_url ); ?>" />
				<p class="description"><?php esc_html_e( 'Company website url.', 'wat' ); ?></p>
			</td>
		</tr>

		<?php // CTA. ?>
		<tr>
			<th><label for="campaign-cta"><?php esc_html_e( 'CTA', 'wat' ); ?></label></th>
			<td>
				<input type="text" name="campaign-cta" id="campaign-cta" class="regular-text full-width" value="<?php echo esc_attr( $campaign_cta ); ?>" />
				<p class="description"><?php esc_html_e( 'Company CTA.', 'wat' ); ?></p>
			</td>
		</tr>

		<?php // Description. ?>
		<tr>
			<th><label for="excerpt"><?php esc_html_e( 'Description', 'wat' ); ?></label></th>
			<td>
				<?php wp_editor( html_entity_decode( stripslashes( $post->post_excerpt ) ), 'excerpt', array( 'wpautop' => false ) ); ?>
			</td>
		</tr>
	</table>

	<?php
}

/**
 * Save Campaign Meta data
 *
 * @since 0.1.0
 *
 * @uses wp_is_post_autosave()
 * @uses wp_is_post_revision()
 * @uses wp_verify_nonce()
 * @uses delete_post_meta()
 * @uses update_post_meta()
 * @uses sanitize_text_field()
 * @uses absint()
 *
 * @param  int $post_id Id of the post to save
 *
 * @return void
 */
function save_meta( $post_id ) {
	if ( 'wat-campaign' !== get_post_type( $post_id ) ) {
		return;
	}

	$is_autosave    = wp_is_post_autosave( $post_id );
	$is_revision    = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST['wat_nounce_cp_mb'] ) && wp_verify_nonce( $_POST['wat_nounce_cp_mb'], 'wat_nounce_campaign_meta_box' ) ) ? true : false;

	// Don't run on autosave, revision, or nonce fails to validate.
	if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
		return;
	}

	// Write new data.
	if ( isset( $_POST['campaign-url'] ) && ! empty( $_POST['campaign-url'] ) ) {
		update_post_meta( $post_id, 'campaign-url', sanitize_text_field( $_POST['campaign-url'] ) );
	} else {
		delete_post_meta( $post_id, 'campaign-url' );
	}

	if ( isset( $_POST['campaign-cta'] ) && ! empty( $_POST['campaign-cta'] ) ) {
		update_post_meta( $post_id, 'campaign-cta', sanitize_text_field( $_POST['campaign-cta'] ) );
	} else {
		delete_post_meta( $post_id, 'campaign-cta' );
	}
	$excerpt = isset( $_POST['excerpt'] ) && ! empty( $_POST['excerpt'] ) ? $_POST['excerpt'] : null;

	remove_action( 'save_post', __NAMESPACE__ . '\save_meta' );
	wp_update_post(
		array(
			'ID'           => $post_id,
			'post_excerpt' => wp_kses_post( $excerpt ),
		)
	);

	// Refresh campaign cache.
	\WRTTheme\Meta_Boxes\Campaign_Associations\get_campaigns( true );
	add_action( 'save_post', __NAMESPACE__ . '\save_meta' );
}

/**
 * Rename featured image meta box
 *
 * @since 0.1.0
 *
 * @uses remove_meta_box()
 * @uses add_meta_box()
 *
 * @return void
 */
function rename_featured_image_mb() {
	remove_meta_box( 'postimagediv', 'wat-campaign', 'side' );
	add_meta_box( 'postimagediv', esc_html__( 'Campaign logo' ), 'post_thumbnail_meta_box', 'wat-campaign', 'side', 'low' );
}

/**
 * Admin stylesheet enqueue
 *
 * @since 0.1.0
 *
 * @uses apply_filters()
 * @uses wp_enqueue_style()
 *
 * @return void
 */
function admin_style() {
	global $typenow;

	if ( 'wat-campaign' === $typenow ) {
		$debug = apply_filters( 'wat_script_debug', false );
		$min   = ( $debug || defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		wp_enqueue_style(
			'wat-admin',
			WAT_URL . "/assets/css/admin/campaign{$min}.css",
			array(),
			filemtime( WAT_PATH . "assets/css/admin/campaign{$min}.css" )
		);
	}
}


/**
 * Rename featured image links
 *
 * @since 0.1.0
 *
 * @uses get_post_type()
 *
 * @param  string $content Html string containing link
 * @return string          Html string with replaced message
 */
function rename_featured_image_links( $content ) {
	if ( 'wat-campaign' === get_post_type() ) {
		$content = str_replace( __( 'Set featured image' ), __( 'Set campaign logo', 'wat' ), $content );
		$content = str_replace( __( 'Remove featured image' ), __( 'Remove campaign logo', 'wat' ), $content );
	}
	return $content;
}
