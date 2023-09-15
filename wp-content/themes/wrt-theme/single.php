<?php
/**
 * The template for displaying all single posts
 *
 * @package WRTTheme
 */

get_header();
use WRTPlugin\PostTypes\ContestPostType;

$form_id = get_post_meta( get_the_ID(), '_wat_selected_form', true );

/* Start the Loop */
while ( have_posts() ) :
	the_post();
	?>
	<?php
	if ( get_post_type() === ContestPostType::$name ) {
		get_template_part( 'partials/post/contest', 'banner' );
	} else {
		get_template_part( 'partials/post/header' );
	}
	?>
	<?php do_action( 'wrt_render_ad', 'weareteachers_leaderboard_atf' ); ?>
	<main <?php post_class( 'single-article' ); ?>>
		<article id="post-<?php the_ID(); ?>">
			<?php get_template_part( 'partials/post/sponsor', '', 'mobile' ); ?>
			<?php
			if ( get_post_type() !== ContestPostType::$name ) {
				get_template_part( 'partials/post/author' );
			}
			?>
			<?php get_template_part( 'partials/post/sponsor', '', 'desktop' ); ?>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
			<?php get_template_part( 'partials/post/contest-rules' ); ?>
			<?php get_template_part( 'partials/post/share' ); ?>
		</article>
		<aside>
			<div class="sidebar-content">
				<?php
				if ( get_post_type() === ContestPostType::$name ) {
					get_template_part( 'partials/post/sidebar/contest-form' );
				} else {
					get_template_part( 'partials/post/sidebar/affiliate-disclosure' );
					get_template_part( 'partials/post/sidebar/get-freebies' );

					if ( ! $form_id ) {
						get_template_part( 'partials/post/sidebar/stay-in-touch' );
						get_template_part( 'partials/post/sidebar/teaching-aids' );
						get_template_part( 'partials/post/sidebar/author-spotlight' );
					}
				}
				?>
				<?php do_action( 'wrt_render_ad', 'weareteachers_med_rect_atf' ); ?>
				<?php do_action( 'wrt_render_ad', 'weareteachers_med_rect_btf' ); ?>
			</div>
		</aside>
	</main>
	<?php do_action( 'wrt_render_ad', 'weareteachers_adhesion', true ); ?>

	<?php if ( get_post_type() !== ContestPostType::$name ) : ?>
		<?php get_template_part( 'partials/post/related' ); ?>
	<?php endif; ?>

	<?php
endwhile; // End of the loop.

get_footer();
