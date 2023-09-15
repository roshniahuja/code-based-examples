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
	]
);

$post_id = $options['post_id'] ?? null;

if ( ! $post_id ) {
	return;
}

?>

<article class="post-card">

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

		<div class="post-card__byline post-card__byline--justified">
			<?php
			get_template_part(
				'partials/post-card/byline',
				null,
				array(
					'post_id'         => $post_id,
					'show_time'       => false,
					'show_author'     => false,
					'show_read_count' => true,
					'container_class' => 'post-card__byline--justified',
				)
			);
			?>
		</div>

	</div>
</article>
