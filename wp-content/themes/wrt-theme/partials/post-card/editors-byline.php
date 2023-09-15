<?php
/**
 * Editor's pick card byline
 *
 * @package wrt/theme
 */

$options = wp_parse_args(
	$args,
	array(
		'post_id'     => 0,
		'show_author' => true,
	)
);

$post_id     = $options['post_id'];
$show_author = $options['show_author'];
$attribution = get_post_meta( $post_id, 'attribution', true );
?>

<?php if ( ! empty( $attribution ) && $show_author ) : ?>
	<div class="post-card__posted-by">
		<?php echo esc_html( $attribution ); ?>
	</div>
<?php endif; ?>
