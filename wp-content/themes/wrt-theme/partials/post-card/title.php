<?php
/**
 * Post card title
 *
 * @package wrt/theme
 */

$options = wp_parse_args(
	$args,
	[
		'post_id'    => 0,
		'title_tag'  => 'h3',
		'title_link' => '',
	]
);

$post_id   = $options['post_id'];
$title_tag = $options['title_tag'];
$title_link = $options['title_link'];

if ( empty( $title_link ) ) {
	$title_link = get_permalink( $post_id );
}

if ( ! empty( $title_tag ) ) {
	$title_template = '<%1$s class="post-card__title"><a href="%2$s" aria-label="Read the full story: %3$s">%4$s</a></%1$s>';

	printf(
		$title_template, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		wp_kses_post( $title_tag ),
		esc_url( $title_link ),
		esc_attr( wp_strip_all_tags( get_the_title( $post_id ), true ) ),
		wp_kses_post( get_the_title( $post_id ) ),
	);
};
