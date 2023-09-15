<?php
/**
 * Post card list
 *
 * @package wrt/theme
 */

use WRTTheme\Utility;
$options = wp_parse_args(
	$args,
	[
		'justify_byline' => false,
		'post_id'        => 0,
		'show_excerpt'   => false,
		'show_time'      => true,
	]
);

$post_id         = $options['post_id'];
$author_id       = get_post_field( 'post_author', $post_id );
$author_name     = get_the_author_meta( 'display_name', $author_id );
$terms           = get_the_term_list( $post_id, \WRTPlugin\Taxonomies\GradeTaxonomy::$name );
$category_detail = get_the_category( $post_id );
?>

<article class="post-card post-card--list">

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

		<?php if ( $options['show_excerpt'] ) : ?>
			<div class="post-card__excerpt" data-excerpt>
				<?php echo wp_kses_post( get_the_excerpt( $post_id ) ); ?>
			</div>
		<?php endif; ?>

		<div class="post-card__byline <?php echo $options['justify_byline'] ? 'post-card__byline--justified' : ''; ?>">
			<?php
			get_template_part(
				'partials/post-card/byline',
				null,
				array(
					'post_id'     => $post_id,
					'show_time'   => $options['show_time'],
					'show_author' => true,
					'layout'      => 'list',
				)
			);
			?>
		</div>
	</div>
</article>
