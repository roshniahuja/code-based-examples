<?php
/**
 * Section Editor Picks Partial
 *
 * @package WRTTheme
 */

use function WRTTheme\Utility\get_editors_picks;

$featured_posts = get_editors_picks();
if ( ! $featured_posts || ! $featured_posts->have_posts() ) {
	return;
}
?>

<h2><?php esc_html_e( 'Editor’s Picks', 'wrt-theme' ); ?></h2>
<div class="editors-picks-items-wrapper">
	<div class="editors-picks-items">
		<?php while ( $featured_posts->have_posts() ) : ?>
			<?php $featured_posts->the_post(); ?>
			<div class="editors-picks-item wp-block-wrt-post-picker">
				<?php
				get_template_part(
					'partials/post-card/editors-pick',
					'',
					array(
						'post_id'      => get_the_ID(),
						'show_excerpt' => false,
					)
				);
				?>
			</div>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	</div>
</div>
