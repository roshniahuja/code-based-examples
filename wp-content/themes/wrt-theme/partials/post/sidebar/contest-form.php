<?php
/**
 * Sidebar: Contest Form.
 *
 * Must be called within a post loop.
 *
 * @package WRTTheme
 */

$form_id = get_post_meta( $post->ID, 'contest-form', true );

// Bailout if the selected form doesn't exist and the 'wat-contest' post type is not accessed.
if ( ! is_singular( 'wat-contest' ) && ! $form_id ) {
	return;
}

$winners = get_post_meta( $post->ID, 'contest-winners', true );
$winners = array_filter( $winners );

if ( ! empty( $winners ) || ! empty( $post->post_excerpt ) ) {
	get_template_part( 'partials/post/sidebar/winner' );
} else {
	?>
	<div class="sidebar--contest-form" id="getForm">
		<h4><?php esc_html_e( 'Enter Now', 'wrt-theme' ); ?></h4>
		<?php echo do_shortcode( '[gravityform id="' . esc_attr( $form_id ) . '" title="false" description="false" ajax="true"]' ); ?>
	</div>
	<?php
}
