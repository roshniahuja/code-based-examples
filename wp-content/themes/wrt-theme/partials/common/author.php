<?php
/**
 * Author template
 *
 * Must be included inside the loop.
 *
 * @package WRTTheme
 *
 * @var array $args {
 *     @type string $label Label to display before the author name.
 * } Arguments.
 */

$author_id = get_the_author_meta( 'ID' );
if ( empty( $author_id ) ) {
	return;
}
?>

<div class="single-post--author-name">
	<?php
	printf( /* translators: %1$s - label, %2$s - opening <a> tag, %3$s - author name, %4$s - closing </a> tag. */
		'<span>%1$s</span>%2$s%3$s%4$s',
		isset( $args['label'] ) ? esc_html( $args['label'] ) : esc_html__( 'By', 'wrt-theme' ),
		'&nbsp;<a href="' . esc_url( get_author_posts_url( $author_id ) ) . '">',
		esc_html( get_the_author() ),
		'</a>'
	);
	?>
</div>
