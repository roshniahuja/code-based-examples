<?php
/**
 * Template Name: Thank you
 *
 * @package WRTTheme
 */

namespace WRTTheme\Utility;

get_header();

while ( have_posts() ) :
	the_post();
	?>

	<div <?php post_class( 'single-article thank-you-article' ); ?>>
		<article id="post-<?php the_ID(); ?>">
			<?php
			the_title( '<h1>', '</h1>' );
			the_post_thumbnail( get_the_ID(), 'full' );
			the_content();
			?>
		</article>
		<aside>
			<div class="sidebar-content">
				<?php get_template_part( 'partials/post/sidebar/stay-in-touch' ); ?>
			</div>
		</aside>
	</div>

	<?php
	endwhile; // End of the loop.
get_footer();
