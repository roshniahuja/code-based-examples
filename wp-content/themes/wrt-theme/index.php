<?php
/**
 * The main template file
 *
 * @package WRTTheme
 */

get_header(); ?>
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php if ( ! is_front_page() ) : ?>
				<h2><?php the_title(); ?></h2>
			<?php endif; ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	<?php endif; ?>
</main>
<?php
get_footer();
