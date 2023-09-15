<?php
/**
 * Sidebar: Author spotlight
 *
 * Must be called within a post loop.
 *
 * @package WRTTheme
 */

$author_id          = get_the_author_meta( 'ID' );
$author_description = get_the_author_meta( 'description' );
?>

<div class="sidebar--author-spotlight">
	<h4><?php esc_html_e( 'Author spotlight', 'wrt-theme' ); ?></h4>
	<div class="has-flag">
		<?php echo get_avatar( $author_id, 155 ); ?>
	</div>

	<?php get_template_part( 'partials/common/author', '', array( 'label' => __( 'Posted By', 'wrt-theme' ) ) ); ?>

	<?php if ( ! empty( $author_description ) ) : ?>
		<p class="author-description"><?php echo wp_kses_post( $author_description ); ?></p>
	<?php endif; ?>

	<?php
	printf(
		'<a class="button-primary" href="%1$s" rel="author">%2$s</a>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		sprintf( /* translators: %s - author name. */
			esc_html__( 'All posts by %s', 'twentytwentyone' ),
			get_the_author()
		)
	);
	?>
</div>
