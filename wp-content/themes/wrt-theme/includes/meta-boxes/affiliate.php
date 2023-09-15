<?php
/**
 * Affiliate Meta Box
 *
 * @package WRTTheme
 */

namespace WRTTheme\Meta_Boxes\Affiliate;

/**
 * Hook into admin_init action
 *
 * @uses  add_action()
 *
 * @return void
 */
function setup() {

	$n = function ( $function ) {

		return __NAMESPACE__ . "\\$function";
	};

	// register metaboxes for selected post types.
	add_action( 'add_meta_boxes', $n( 'register_metabox' ) );

	// hook into save action.
	add_action( 'save_post', $n( 'save_metabox' ), 10, 3 );

}

/**
 * Register metabox to all opt-in post types
 */
function register_metabox() {

	$post_types = get_post_types();

	add_meta_box(
		'wat-affiliate',
		esc_html__( 'Affiliate', 'wrt-theme' ),
		__NAMESPACE__ . '\display_metabox',
		'wat-product',
		'side',
		'default'
	);
}

/**
 * Display the markup for the metabox
 *
 * @param object $post Post data.
 */
function display_metabox( $post ) {

	wp_nonce_field( 'wat-affiliate', 'wat-affiliate-nonce' );
	$affiliate_url = get_post_meta( $post->ID, 'affiliate-url', true );
	?>
	<input type="url" name="affiliate-url" id="affiliate-url" placeholder="<?php esc_attr_e( 'Add affiliate url here.', 'wrt-theme' ); ?>" value="<?php echo esc_attr( $affiliate_url ); ?>" />

	<?php
}

/**
 * Save the affiliate associated to the post
 *
 * @param int    $post_id Post id.
 * @param object $post Post Data.
 * @param string $update Update Data.
 */
function save_metabox( $post_id, $post, $update ) {

	// Validates that we can run the save_post action for this post.
	if (
		( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
		! in_array( $post->post_type, get_post_types(), true ) ||
		! current_user_can( 'edit_post', $post_id ) ||
		! isset( $_POST['wat-affiliate-nonce'] ) || // WPCS: input var ok. sanitization ok.
		! wp_verify_nonce( $_POST['wat-affiliate-nonce'], 'wat-affiliate' ) // WPCS: input var ok. sanitization ok.
	) {
		return;
	}

	// Write new data.
	if ( isset( $_POST['affiliate-url'] ) && ! empty( $_POST['affiliate-url'] ) ) {
		$affiliate_url = filter_input( INPUT_POST, 'affiliate-url', FILTER_SANITIZE_URL );
		update_post_meta( $post_id, 'affiliate-url', sanitize_text_field( $affiliate_url ) );
	} else {
		delete_post_meta( $post_id, 'affiliate-url' );
	}

}
