<?php
/**
 * Post card byline
 *
 * @package wrt/theme
 */

use WRTTheme\Utility;

$options = wp_parse_args(
	$args,
	[
		'layout'          => 'default',
		'post_id'         => 0,
		'show_time'       => false,
		'show_author'     => true,
		'show_read_count' => false,
	]
);

$post_id         = $options['post_id'];
$show_time       = $options['show_time'];
$show_author     = $options['show_author'];
$layout          = $options['layout'];
$show_read_count = $options['show_read_count'];
$author_id       = get_post_field( 'post_author', $post_id );
$author_name     = get_the_author_meta( 'display_name', $author_id );
$campaign        = Utility\get_post_campaign( $post_id );
if ( ! empty( $container_class ) ) {
	$byline_wrapper_class .= " {$container_class}";
}

?>

<div class="post-card__categories">
	<div class="post-card__cat-item">
		<?php
		if ( $campaign && 'list' === $layout ) {
			printf(
				'<div class="post-card__sponsored-by">%s</div>',
				esc_html__( ' Sponsored', 'wrt-theme' ),
			);
		} else {
			$topics        = Utility\get_post_primary_category( $post_id, 'wat-subject' );
			$primary_topic = $topics['primary_category'];

			if ( ! empty( $primary_topic ) ) {
				printf(
					'<a href="%1$s" class="cat-item__link">%2$s</a>',
					esc_url( get_category_link( $primary_topic->term_id ) ),
					wp_kses_post( $primary_topic->name )
				);
			}
		};
		?>
	</div>
</div>

<?php
if ( $show_time ) :
	printf(
		'<time class="post-card__time" datetime="%1$s" itemprop="datePublished">%2$s</time>',
		wp_kses_post( get_the_date( 'c', $post_id ) ),
		wp_kses_post( get_the_date( 'M j', $post_id ) )
	);
endif;
?>

<?php if ( $show_author && ! empty( $author_id ) && ! empty( $author_name ) ) : ?>
	<div class="post-card__posted-by">
		<?php
		printf(
			'<span>%1$s</span><a href="%2$s">%3$s</a>',
			esc_html__( 'By', 'wrt-theme' ),
			esc_url( get_author_posts_url( $author_id ) ),
			wp_kses_post( $author_name )
		);
		?>
	</div>
<?php endif; ?>

<?php
if ( $show_read_count ) :
	get_template_part( 'partials/common/reads', null, array( 'post_id' => $post_id ) );
endif;
?>
