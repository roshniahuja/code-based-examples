<?php
/**
 * Post card list
 *
 * @package wrt/theme
 */

use function WRTTheme\Utility\get_post_campaign;

$options = wp_parse_args(
	$args,
	[
		'post_id' => 0,
	]
);

$post_id = $options['post_id'];

if ( ! $post_id ) {
	return;
}

$author_id       = get_post_field( 'post_author', $post_id );
$author_name     = get_the_author_meta( 'display_name', $author_id );
$terms           = get_the_term_list( $post_id, \WRTPlugin\Taxonomies\GradeTaxonomy::$name );
$category_detail = get_the_category( $post_id );
$campaign        = get_post_campaign( $post_id );

?>

<article class="post-card post-card--list has-background">
	<div class="post-card-body">
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

			<div class="post-card__excerpt">
				<?php echo wp_kses_post( get_the_excerpt( $post_id ) ); ?>
			</div>
		</div>
	</div>

	<div class="post-card-footer">
		<div class="post-card__byline post-card__byline--justified">
			<?php
			get_template_part(
				'partials/post-card/byline',
				null,
				array(
					'post_id'     => $post_id,
					'show_time'   => false,
					'show_author' => true,
				)
			);
			?>
		</div>
	</div>

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

</article>
