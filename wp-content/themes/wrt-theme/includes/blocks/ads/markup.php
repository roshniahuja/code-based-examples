<?php
/**
 * Ads block markup
 *
 * @package WRTTheme\Blocks\Ads
 *
 * @var array    $attributes         Block attributes.
 * @var string   $content            Block content.
 * @var WP_Block $block              Block instance.
 * @var array    $context            Block context.
 */

if ( empty( $attributes['ad'] ) ) {
	return;
}

?>

<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore ?>>
	<?php do_action( 'wrt_render_ad', $attributes['ad'] ); ?>
</div>

