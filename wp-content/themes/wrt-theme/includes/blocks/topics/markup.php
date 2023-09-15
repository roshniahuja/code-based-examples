<?php
/**
 * Topics block markup
 *
 * @package WRTTheme\Blocks\Topics
 *
 * @var array    $attributes         Block attributes.
 * @var string   $content            Block content.
 * @var WP_Block $block              Block instance.
 * @var array    $context            Block context.
 */

?>

<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore ?>>
	<div class="topics__header">
		<div>
			<span class="topics__eyebrow"><?php echo esc_html( $attributes['eyebrow'] ); ?></span>
			<h2><?php echo esc_html( $attributes['title'] ); ?></h2>
		</div>
		<p><?php echo wp_kses_post( $attributes['description'] ); ?></p>
	</div>
	<div class="topics__content">
		<?php echo wp_kses_post( $content ); ?>
	</div>
</div>

