<?php
/**
 * Post card inline
 *
 * @package WRTTheme
 */

use WRTTheme\Utility;

$options = wp_parse_args(
	$args,
	[
		'post_id' => 0,
	]
);

$post_id = $options['post_id'] ?? null;

if ( ! $post_id ) {
	return;
}

$post_link  = get_the_permalink( $post_id );
$post_title = get_the_title( $post_id );
?>
<div class="wp-block-wrt-inline-card">
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
