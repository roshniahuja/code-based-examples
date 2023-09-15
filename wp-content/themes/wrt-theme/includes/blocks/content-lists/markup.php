<?php
/**
 * Content Lists block markup
 *
 * @package WRTTheme\Blocks\ContentList
 *
 * @var array    $attributes         Block attributes.
 * @var string   $content            Block content.
 * @var WP_Block $block              Block instance.
 * @var array    $context            Block context.
 */

// Set arguments for the WP_Query.
$recent_post_args = array(
	'posts_per_page'         => 4,
	'no_found_rows'          => true,
	'update_post_meta_cache' => false,
	'update_post_term_cache' => false,
);


$post_ids = ! empty( $attributes['posts'] ) ? wp_list_pluck( $attributes['posts'], 'id' ) : [ 0 ];


if ( ! empty( $attributes['posts'] ) ) {
	$recent_post_args['post__in'] = $post_ids;
	$recent_post_args['orderby']  = 'post__in';
}

if ( ! empty( $attributes['category'] ) ) {
	$recent_post_args['cat'] = $attributes['category'];
}

if ( ! empty( $attributes['topic'] ) ) {
	$recent_post_args['tax_query'] = array(
		array(
			'taxonomy' => 'wat-subject',
			'field'    => 'id',
			'terms'    => $attributes['topic'],
		),
	);
}


$list_posts = new WP_Query( $recent_post_args );
?>

<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore ?>>
	<h2>
		<?php echo wp_kses_post( $attributes['title'] ); ?>
	</h2>

	<?php while ( $list_posts->have_posts() ) : ?>
		<?php
		$list_posts->the_post();
		$author_name = get_the_author_meta( 'display_name', get_post()->post_author );
		?>
		<div class="wp-block-wrt-content-list-item">
			<?php the_title( '<h4 class="content-list__title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h4>' ); ?>
			<div class="content-list__content">
				<?php the_excerpt(); ?>
			</div>
			<div class="content-list__meta">
				<?php
				echo get_the_date( 'M d' );
				if ( ! empty( $author_name ) ) {
					?>
					<span>/</span>
					<?php esc_html_e( 'BY', 'wrt-theme' ); ?>
					<a href="<?php echo esc_url( get_author_posts_url( get_post()->post_author ) ); ?>">
						<?php echo wp_kses_post( $author_name ); ?>
					</a>
				<?php } ?>
			</div>
		</div>
	<?php endwhile; ?>
	<?php wp_reset_postdata(); ?>

	<?php if ( ! empty( $attributes['cta_show'] ) && true === $attributes['cta_show'] ) : ?>
		<a href="<?php echo esc_url( $attributes['cta_url'] ); ?>" class="button-primary wp-block-wrt-content-list-cta">
			<?php if ( ! empty( $attributes['cta_text'] ) ) : ?>
				<?php echo esc_html( $attributes['cta_text'] ); ?>
			<?php else : ?>
				<?php esc_html_e( 'View More', 'wrt-theme' ); ?>
			<?php endif; ?>
		</a>
	<?php endif; ?>
</div>
