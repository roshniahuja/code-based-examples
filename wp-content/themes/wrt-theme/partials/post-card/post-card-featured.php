<?php
/**
 * Post card featured
 *
 * @package wrt/theme
 */

use WRTTheme\Utility;

$options = wp_parse_args(
	$args,
	[
		'post_id' => 0,
	]
);

$post_id = $options['post_id'] ?? null;

if ( ! $post_id ) {
	return;
}
?>

<article class="post-card post-card--featured">
	<div class="post-card__header post-card__byline">
		<?php
		// Sponsor
		get_template_part(
			'partials/post-card/sponsor',
			null,
			array(
				'post_id'   => $post_id,
				'show_logo' => false,
			)
		);
		?>

		<?php
		get_template_part(
			'partials/post-card/byline',
			null,
			array(
				'post_id'     => $post_id,
				'show_time'   => false,
				'show_author' => false,
			)
		);
		?>

	</div>

	<?php
	get_template_part(
		'partials/post-card/thumbnail',
		null,
		array(
			'post_id' => $post_id,
		)
	);
	?>

	<div class="post-card__content">
		<?php

		// Grades
		get_template_part(
			'partials/post-card/grades',
			null,
			array(
				'post_id' => $post_id,
			)
		);

		// Title
		get_template_part(
			'partials/post-card/title',
			null,
			array(
				'post_id'   => $post_id,
				'title_tag' => 'h3',
			)
		);
		?>

		<a href="<?php echo esc_url( get_the_permalink( $post_id ) ); ?>" class="learn-more post-card__learn-more">
			<?php esc_html_e( 'Learn more', 'wrt-theme' ); ?>
		</a>
	</div>
</article>
