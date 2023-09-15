<?php
/**
 * The template for displaying all hub posts
 *
 * @package WRTTheme
 */

get_header( 'minimal' );

?>
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>

		<article id="page-<?php the_ID(); ?>" <?php post_class( 'alignfull' ); ?>>

			<div class="entry-content">
				<?php
				the_content();

				wp_link_pages(
					array(
						'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'wrt-theme' ) . '">',
						'after'    => '</nav>',
						/* translators: %: Page number. */
						'pagelink' => esc_html__( 'Page %', 'wrt-theme' ),
					)
				);
				?>
			</div><!-- .entry-content -->

			<?php if ( get_edit_post_link() ) : ?>
				<footer class="entry-footer default-max-width">
					<?php
					edit_post_link(
						sprintf( /* translators: %s - ost title, only visible to screen readers. */
							esc_html__( 'Edit %s', 'wrt-theme' ),
							'<span class="screen-reader-text">' . get_the_title() . '</span>'
						),
						'<span class="edit-link">',
						'</span>'
					);
					?>
				</footer><!-- .entry-footer -->
			<?php endif; ?>
		</article><!-- #page-<?php the_ID(); ?> -->

	<?php endwhile; ?>

<?php get_footer( 'minimal' ); ?>
