<?php
/**
 * Filterable-post-card item
 *
 * @package wrt/theme
 */

$options = wp_parse_args(
	$args,
	[
		'post_id' => 0,
		'show_excerpt' => false,
	]
);

$post_id = $options['post_id'];

if ( ! $post_id ) {
	return;
}

$show_excerpt = $options['show_excerpt'];
?>

<article class="post-card filterable-post-card--list">
	<figure class="post-card__thumbnail">
		<?php
		if ( has_post_thumbnail( $post_id ) ) :
			echo get_the_post_thumbnail( $post_id );
		endif;
		?>
	</figure>

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

		<?php if ( $show_excerpt ) : ?>
			<div class="post-card__excerpt">
				<?php echo esc_html( get_the_excerpt( $post_id ) ); ?>
			</div>
		<?php endif; ?>

		<div class="post-card__byline post-card__byline--justified">
			<?php
			get_template_part(
				'partials/post-card/byline',
				null,
				array(
					'post_id'     => $post_id,
					'show_time'   => false,
					'show_author' => true,
					'layout'      => 'list',
				)
			);
			?>
		</div>
	</div>
</article>
