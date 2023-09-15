<?php
/**
 * Post card thumbnail
 *
 * @package wrt/theme
 */

$options = wp_parse_args(
	$args,
	[
		'post_id'      => 0,
		'show_caption' => false,
	]
);

$post_id         = $options['post_id'];
$show_caption    = $options['show_caption'];
$caption         = wp_get_attachment_caption( get_post_thumbnail_id( $post_id ) );
$placeholder_url = WRT_THEME_DIST_URL . 'images/placeholder-wrt.png';
$post_title      = get_the_title( $post_id );
?>

<figure class="post-card__thumbnail">
	<a href="<?php echo esc_url( get_the_permalink( $post_id ) ); ?>" aria-label="<?php esc_html_e( "Read more about {$post_title}", 'wrt-theme' ); ?>">
		<?php
		if ( has_post_thumbnail( $post_id ) ) :
			echo get_the_post_thumbnail( $post_id );
		else :
			printf(
				'<img width="400" height="220" loading="lazy" src="%1$s" alt="%2$s" />',
				esc_attr( $placeholder_url ),
				esc_attr( $post_title ),
			);
		endif;
		?>
	</a>
	<?php
	if ( $show_caption && ! empty( $caption ) ) :
		printf( '<figcaption>%s</figcaption>', esc_html( $caption ) );
	endif;
	?>
</figure>
