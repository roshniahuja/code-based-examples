<?php
/**
 * Section Author Partial
 *
 * @package WRTTheme
 */

if ( ! is_author() ) {
	return;
}

$author_term = get_queried_object();

?>

<div class="archive-author">
	<h2 class="has-text-align-center"><?php echo esc_html( $author_term->display_name ); ?></h2>
	<?php echo get_avatar( get_the_author_meta( 'ID' ), $size = '150', $default = 'mystery', $alt = strip_tags( 'Posts by ' . get_the_archive_title() ) ); ?>
	<p>
		<?php echo wp_kses_post( get_the_author_meta( 'description', $author_term->ID ) ); ?>
	</p>
</div>
