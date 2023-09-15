<?php
/**
 * Section Post List Partial
 *
 * @package WRTTheme
 */

use function WRTTheme\Utility\get_archive_featured_posts;

$taxonomy    = get_queried_object()->taxonomy;
$term_id     = get_queried_object()->term_id;
$featured_posts = get_archive_featured_posts();
$exclude_ids = array_slice( $featured_posts, 0, 3 );
$all_term_ids = array( $term_id );

$args = array(
	'post_type'    => get_post_type(),
	'post_status'  => 'publish',
	'posts_per_page' => 10,
);

if ( ! empty( $exclude_ids ) ) {
	$args['post__not_in'] = $exclude_ids;
}

if ( is_author() ) {
	$args['author'] = (int) get_the_author_meta( 'ID' );
} else {
	$child_terms = get_term_children( $term_id, $taxonomy );
	$all_term_ids = array_merge( $all_term_ids, $child_terms );
	$args['tax_query'] = array(
		array(
			'taxonomy' => $taxonomy,
			'field' => 'term_id',
			'terms' => $term_id,
		),
	);
}

$posts_query = new WP_Query( $args );
?>

<div class="wp-block-wrt-post-picker-group filterable-post-card-contents-items">
<?php
if ( $posts_query->have_posts() ) :
	while ( $posts_query->have_posts() ) :
		$posts_query->the_post();

		get_template_part(
			'partials/post-card/post-card-list',
			'',
			array(
				'justify_byline' => true,
				'post_id'        => get_the_ID(),
				'show_excerpt'   => true,
				'show_time'      => false,
			)
		);
	endwhile;
	wp_reset_postdata();
endif;
?>
</div>

<?php
$should_show_load_more_button = true;
if ( $posts_query->max_num_pages < 2 ) {
	$should_show_load_more_button = false;
}
?>
<div class="pagination">
	<?php get_template_part( 'partials/post-card/post-card-template' ); ?>
	<?php
	$tax_name = $taxonomy;
	switch ( $taxonomy ) {
		case 'category':
			$tax_name = 'categories';
			break;
		case 'post_tag':
			$tax_name = 'tags';
			break;
	};

	printf(
		'<button class="load-more-button button-primary %1$s"
			id="js-load-more"
			data-author-id="%2$s"
			data-taxonomy="%3$s"
			data-term-id="%4$s"
			data-exclude-ids="%5$s"
			data-is-filter-enabled="">
			<span>%6$s</span>
		</button>',
		esc_attr( $should_show_load_more_button ? 'show' : 'hide' ),
		esc_attr( is_author() ? (int) get_the_author_meta( 'ID' ) : '' ),
		esc_attr( $tax_name ),
		esc_attr( implode( ',', $all_term_ids ) ),
		esc_attr( implode( ',', $exclude_ids ) ),
		esc_html__( 'Load More', 'wrt-theme' )
	);
	?>
</div>
