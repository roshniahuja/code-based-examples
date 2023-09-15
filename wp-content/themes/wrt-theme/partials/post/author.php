<?php
/**
 * Single post author bio
 *
 * Must be included within the posts query loop.
 *
 * @package WRTTheme
 */

use WRTPlugin\PostTypes\ContestPostType;
$author_id = get_the_author_meta( 'ID' );
?>

<header class="single-post--meta">
	<div class="single-post--author">
		<?php
		if ( ! empty( $author_id ) ) {
			echo get_avatar( $author_id, 52 );
		}
		?>
		<div class="single-post--author-details">
			<?php get_template_part( 'partials/common/author' ); ?>
			<?php if ( get_post_type() !== ContestPostType::$name ) { ?>
				<div class="single-post--meta-date"><?php the_date( 'M j, Y' ); ?></div>
			<?php } ?>
		</div>
	</div>
	<?php if ( get_post_type() !== ContestPostType::$name ) { ?>
		<?php get_template_part( 'partials/common/reads' ); ?>
	<?php } ?>
</header>
