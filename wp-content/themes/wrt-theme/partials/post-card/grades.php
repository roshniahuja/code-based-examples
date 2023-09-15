<?php
/**
 * Post card grades
 *
 * @package wrt/theme
 */

use WRTPlugin\Taxonomies\GradeTaxonomy;

$options = wp_parse_args(
	$args,
	[
		'post_id' => 0,
	]
);

$post_id = $options['post_id'];
$terms   = wp_get_post_terms( $post_id, GradeTaxonomy::$name );

if ( is_wp_error( $terms ) || empty( $terms ) ) {
	return;
}


$parents     = wp_list_pluck( $terms, 'term_id' );
$parents     = array_filter( $parents );
$parent_term = get_term( $parents[0] ); // Get the parent term.
$term_link   = get_term_link( $parent_term );
if ( is_wp_error( $term_link ) ) {
	return;
}
?>

<div class="post-card__grades">
	<span><?php esc_html_e( 'Grades:', 'wrt-theme' ); ?></span>
	<span>
		<a
			href="<?php echo esc_url( $term_link ); ?>"
			title="<?php echo esc_attr( $parent_term->name ); ?>"
		><?php echo esc_html( $parent_term->name ); ?></a>
	</span>
</div>
