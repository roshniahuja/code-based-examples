<?php
/**
 * Topic block markup
 *
 * @package WRTTheme\Blocks\Topic
 *
 * @var array    $attributes         Block attributes.
 * @var string   $content            Block content.
 * @var WP_Block $block              Block instance.
 * @var array    $context            Block context.
 */

$topic_link = '#';
if ( ! empty( $attributes['topic'] ) ) {
	$topic_term = get_term( $attributes['topic']['id'], $attributes['topic']['type'] );
	if ( ! is_wp_error( $topic_term ) ) {
		$topic_link = get_term_link( $topic_term );
	}
}
?>
<a href="<?php echo esc_url( $topic_link ); ?>" <?php echo get_block_wrapper_attributes(); // phpcs:ignore ?>>
	<?php if ( ! empty( $attributes['icon']['url'] ) ) : ?>
		<img src="<?php echo esc_url( $attributes['icon']['url'] ); ?>" alt="<?php echo esc_attr( $attributes['icon']['alt'] ); ?>" width="60" height="60" />
	<?php endif; ?>
	<?php if ( ! empty( $attributes['title'] ) ) : ?>
		<h3 class="topic__title"><?php echo esc_html( $attributes['title'] ); ?></h3>
	<?php endif; ?>
	<?php if ( ! empty( $attributes['description'] ) ) : ?>
		<p class="topic__description"><?php echo esc_html( $attributes['description'] ); ?></p>
	<?php endif; ?>
</a>
