<?php
/**
 * Sidebar: Get Freebies
 *
 * Must be called within a post loop.
 *
 * @package WRTTheme
 */

$form_id = get_post_meta( $post->ID, '_wat_selected_form', true );

// Bailout, if the selected form doesn't exists.
if ( ! $form_id ) {
	return;
}
?>
<div class="sidebar--get-freebies" id="getForm">
	<?php echo do_shortcode( '[gravityform id="' . $form_id . '" title="false" description="false" ajax="true"]' ); ?>
</div>
