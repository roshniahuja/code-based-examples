<?php
/**
 * Post card item
 *
 * @package wrt/theme
 */

use WRTTheme\Utility;
$options = wp_parse_args(
	$args,
	[
		'post_id'      => 0,
		'show_excerpt' => false,
		'show_byline'  => true,
		'show_caption' => false,
		'title_tag'    => 'h3',
	]
);

$post_id = $options['post_id'] ?? null;

if ( ! $post_id ) {
	return;
}

$show_excerpt = $options['show_excerpt'];
$show_byline  = $options['show_byline'];
$show_caption = $options['show_caption'];
$title_tag    = $options['title_tag'];
$campaign     = Utility\get_post_campaign( $post_id );
?>

<article class="post-card">

	<?php
	get_template_part(
		'partials/post-card/thumbnail',
		null,
		array(
			'post_id'      => $post_id,
			'show_caption' => $show_caption,
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
				'title_tag' => $title_tag,
			)
		);
		?>

		<?php if ( $show_excerpt ) : ?>
			<div class="post-card__excerpt">
				<?php echo wp_kses_post( get_the_excerpt( $post_id ) ); ?>
			</div>
		<?php endif; ?>

		<?php if ( $show_byline ) : ?>
			<div class="post-card__byline post-card__byline--justified">
				<?php
				get_template_part(
					'partials/post-card/byline',
					null,
					array(
						'post_id'         => $post_id,
						'show_time'       => false,
						'show_author'     => true,
						'container_class' => 'post-card__byline--justified',
					)
				);
				?>
			</div>
		<?php endif; ?>

		<?php if ( $campaign ) : ?>
			<div class="post-card__byline post-card__byline--justified post-card__sponsor">
				<?php
				get_template_part(
					'partials/post-card/sponsor',
					null,
					array(
						'post_id'  => $post_id,
						'show_log' => true,
					)
				);
				?>
			</div>
		<?php endif; ?>

	</div>
</article>
