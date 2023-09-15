<?php
/**
 * Inline card block markup
 *
 * @package WRTTheme\Blocks\InlineCard
 *
 * @var array    $attributes         Block attributes.
 * @var string   $content            Block content.
 * @var WP_Block $block              Block instance.
 * @var array    $context            Block context.
 */

$post_id = $attributes['post_id'] ?? null;

if ( empty( $post_id ) ) {
	return;
}

$selected_post = get_post( (int) $post_id );
if ( ! $selected_post ) {
	return;
}

$post_link  = get_the_permalink( $post_id );
$post_title = get_the_title(  $post_id );
?>
<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore ?>>
	<?php if ( has_post_thumbnail( $post_id ) ) : ?>
		<a href="<?php echo esc_url( $post_link ); ?>" class="wp-block-wrt-topics--image has-flag" aria-label="<?php esc_html_e( "Read more about {$post_title}", 'wrt-theme' ); ?>">
			<?php echo get_the_post_thumbnail( $post_id ); ?>
		</a>
	<?php endif; ?>
	<div class="wp-block-wrt-topics--content">
		<a href="<?php echo esc_url( $post_link ); ?>">
			<h4><?php echo esc_html( get_the_title( $post_id ) ); ?></h4>
		</a>
		<p><?php echo wp_kses_post( get_the_excerpt( $post_id ) ); ?></p>
		<a href="<?php echo esc_url( $post_link ); ?>" class="wp-block-button is-style-arrow-icon is-dark">
			<span class="wp-element-button"><?php esc_html_e( 'Learn More', 'wrt-theme' ); ?></span>
		</a>
	</div>
</div>
