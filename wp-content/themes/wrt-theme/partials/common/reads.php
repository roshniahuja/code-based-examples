<?php
/**
 * Reads template
 *
 * @package WRTTheme
 *
 * @param array $args {
 *     @type int $post_id Post ID.
 * } Arguments.
 */

use WRTPlugin\PostTypes\ContestPostType;
use function WRTTheme\Template_Tags\get_post_read_count;

if ( get_post_type() === ContestPostType::$name ) {
	return;
}

if ( defined( 'WRT_DISABLE_READS' ) && WRT_DISABLE_READS ) {
	return;
}

$object_id = $args['post_id'] ?? get_the_ID();
$reads     = get_post_read_count( $object_id );

if ( $reads < 1000 ) {
	return;
}

?>

<div class="single-post--reads has-icon post-card__read-counter">
	<?php
	printf( /* translators: %s - number of reads. */
		esc_html__( '%s reads', 'wrt-theme' ),
		'<strong>' . absint( $reads ) . '</strong>'
	);
	?>
</div>
