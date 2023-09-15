<?php
/**
 * Post Picker Markup
 *
 * @package WRT\Blocks\PostPicker
 *
 * @var array    $attributes         Block attributes.
 * @var string   $content            Block content.
 * @var WP_Block $block              Block instance.
 * @var array    $context            BLock context.
 */

$post_id = $attributes['postId'] ?? null;

if ( ! $post_id ) {
	return;
}

?>

<div <?php echo get_block_wrapper_attributes( array( 'class' => $container_class ?? '' ) ); //phpcs:ignore ?>>
	<h5><?php esc_html_e( 'Just For You', 'wrt-theme' ); ?></h5>
	<?php
	get_template_part(
		'partials/post-card/post-card',
		'',
		[
			'post_id'      => $post_id,
			'show_excerpt' => true,
		]
	);
	?>
</div>
