<?php
/**
 * The archive template file
 *
 * @package WRTTheme
 */

get_header(); ?>
	<?php
	if ( is_author() ) :
		get_template_part( 'partials/archives/section-author' );
	else :
		get_template_part( 'partials/archives/section-hero' );
	endif;
	?>

	<?php get_template_part( 'partials/common/filters' ); ?>

	<div class="is-layout-flex wp-block-columns">
		<div class="is-layout-flow wp-block-column" style="flex-basis:66.66%">
			<?php get_template_part( 'partials/archives/section-post-list' ); ?>
		</div>

		<div class="is-layout-flow wp-block-column" style="flex-basis:33.33%">
			<?php get_template_part( 'partials/post/sidebar/stay-in-touch' ); ?>
			<?php do_action( 'wrt_render_ad', 'weareteachers_med_rect_atf' ); ?>
		</div>

	</div>

	<?php get_template_part( 'partials/archives/section-inline-card' ); ?>

	<?php get_template_part( 'partials/archives/section-editor-picks' ); ?>

<?php
get_footer();
