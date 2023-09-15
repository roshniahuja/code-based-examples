<?php
/**
 * Single post related posts section
 *
 * @package WRTTheme
 */

use function WRTTheme\Template_Tags\get_related_posts;
use function WRTTheme\Overrides\custom_excerpt;
use function WRTTheme\Template_Tags\svg_kses_allowed_html;
use function WRTTheme\Utility\get_svg_icon;
use function WRTSailthru\Recommendations\get_recommendations;
use function WRTSailthru\Recommendations\create_vars_from_post;
use function WRTSailthru\Recommendations\get_sailthru_section_id;

$sailthru_section_id   = get_sailthru_section_id( 'you-might-also-like' );
$sailthru_section_vars = create_vars_from_post( get_queried_object() );
$recommendations       = get_recommendations( $sailthru_section_id, $sailthru_section_vars );
$contextual_post_id    = get_queried_object_id();

if ( empty( $recommendations ) || ! $contextual_post_id ) {
	return;
}

// Dedupe from current post.
$recommendation = array_reduce(
	$recommendations,
	function( $acc, $curr ) use ( $contextual_post_id ) {
		if ( $acc ) {
			return $acc;
		}

		return absint( $curr['ID'] ) === absint( $contextual_post_id ) ? false : $curr;
	},
	false
);

$related_posts = new \WP_Query(
	array(
		'p'              => $recommendation['ID'],
		'posts_per_page' => 1,
	)
);

if ( ! $related_posts->have_posts() ) {
	return;
}
?>

<section class="single-post--related related-post alignfull">
	<div class="single-article container">
		<?php while ( $related_posts->have_posts() ) : ?>
			<?php $related_posts->the_post(); ?>
			<?php $related_post_id = get_the_ID(); ?>
			<article>
				<h2><?php esc_html_e( 'You Might Also Like', 'wrt-theme' ); ?></h2>
				<div class="single-post--related-header">
					<?php if ( has_post_thumbnail( $related_post_id ) ) : ?>
						<div class="single-post--related-image has-flag">
							<?php echo get_the_post_thumbnail( $related_post_id, 'medium_large' ); ?>
						</div>
					<?php endif; ?>

					<div>
						<?php $grades_term = get_the_terms( $related_post_id, \WRTPlugin\Taxonomies\GradeTaxonomy::$name ); ?>
						<?php if ( ! is_wp_error( $grades_term ) && ! empty( $grades_term ) ) : ?>
							<div class="single-post--related-grade">
								<?php
								printf( /* translators: %1$s - opening <a> tag, %2$s - term name, %3$s - closing </a> tag */
									esc_html__( 'Grades: %1$s%2$s%3$s', 'wrt-theme' ),
									'<a href="' . esc_url( get_term_link( $grades_term[0] ) ) . '">',
									esc_html( $grades_term[0]->name ),
									'</a>'
								)
								?>
							</div>
						<?php endif; ?>
						<?php the_title( sprintf( '<h3><a href="%s">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
						<div class="single-post--meta">
							<div class="single-post--author">
								<?php get_template_part( 'partials/common/author' ); ?>
								<div class="single-post--meta-date"><?php echo get_the_date( 'M j, Y' ); ?></div>
							</div>
						</div>

						<?php get_template_part( 'partials/common/tags' ); ?>
					</div>
				</div>

				<div class="single-post--related-content">
					<?php custom_excerpt(); ?>
				</div>
			</article>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	</div>
</section>
