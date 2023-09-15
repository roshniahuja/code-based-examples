<?php
/**
 * Editor's pick card byline
 *
 * @package wrt/theme
 */

$options = wp_parse_args(
	$args,
	array(
		'layout'  => 'default',
		'post_id' => 0,
	)
);

$post_id            = $options['post_id'];
$affiliate_url      = get_post_meta( $post_id, 'affiliate_link', true );
$affiliate_cta_text = get_post_meta( $post_id, 'affiliate_cta_text', true );

if ( ! $affiliate_cta_text ) {
	$product_title = get_the_title( $post_id );
	$affiliate_cta_text = __( "Buy {$product_title} on Amazon", 'wrt-theme' );
}

if ( ! empty( $container_class ) ) {
	$byline_wrapper_class .= " {$container_class}";
}
?>

<?php if ( $affiliate_url ) : ?>
	<div class="post-card__buynow">
		<a href="<?php echo esc_url( $affiliate_url ); ?>"
			class="learn-more post-card__learn-more"
			target="_blank"
		>
			<?php echo esc_html( $affiliate_cta_text ); ?>
		</a>
	</div>
<?php endif; ?>
