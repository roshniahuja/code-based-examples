<?php
/**
 * Editor's Pick list
 *
 * @package wrt/theme
 */

use WRTTheme\Utility;
$options = wp_parse_args(
	$args,
	[
		'post_id'          => 0,
		'show_excerpt'     => false,
		'show_attribution' => true,
		'title_tag'        => 'h3',
		'is_affiliate'     => false,
	]
);

$post_id = $options['post_id'] ?? null;

if ( ! $post_id ) {
	return;
}

$show_excerpt = $options['show_excerpt'];
$title_tag    = $options['title_tag'];
$is_affiliate = $options['is_affiliate'];
$campaign     = Utility\get_post_campaign( $post_id );
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

		// Grades.
		get_template_part(
			'partials/post-card/grades',
			null,
			array(
				'post_id' => $post_id,
			)
		);

		// Title.
		get_template_part(
			'partials/post-card/title',
			null,
			array(
				'post_id'    => $post_id,
				'title_tag'  => $title_tag,
				'title_link' => $is_affiliate ? get_post_meta( $post_id, 'affiliate_link', true ) : get_permalink( $post_id ),
			)
		);
		?>

		<?php if ( $options['show_attribution'] ) : ?>
		<div class="post-card__byline post-card__byline--justified">
			<?php
			get_template_part(
				'partials/post-card/editors-byline',
				null,
				array(
					'post_id'     => $post_id,
					'show_author' => true,
				)
			);
			?>
		</div>
		<?php endif; ?>

		<?php
		// Affiliate link.
		get_template_part(
			'partials/post-card/editors-buynow',
			null,
			array(
				'post_id'         => $post_id,
				'container_class' => 'post-card__buynow',
			)
		);
		?>

	</div>
</article>
