<?php
/**
 * Post Picker Markup
 *
 * @package WRT\Blocks\PostPicker
 *
 * @var array    $attributes         Block attributes.
 * @var string   $content            Block content.
 * @var WP_Block $block              Block instance.
 * @var array    $context            BLock context.
 */

use WRTTheme\CurationLocations\CurationLocationFactory;

$post_id         = $attributes['postId'] ?? null;
$partial         = $attributes['partial'] ?? $context['wrt/postPickerPartial'] ?? null;
$container_class = $attributes['containerClass'] ?? '';
$show_excerpt    = $attributes['showExcerpt'] ?? false;

if ( false === $attributes['isCurated'] && false === CurationLocationFactory::util_maybe_is_ssr() ) {
	$location = $context['wrt/postPickerLocation'];
	if ( $location ) {
		$location_instance = CurationLocationFactory::factory()->get_location( $location );
		if ($location_instance !== null) {
			$next = $location_instance->get_next_item();
			if ( $next ) {
				$post_id = $next->ID;
			}
		}
	}
}

if ( ! $post_id ) {
	return;
}
?>

<div <?php echo get_block_wrapper_attributes( array( 'class' => $container_class ) ); //phpcs:ignore ?>>
	<?php
	get_template_part(
		'partials/' . $partial,
		'',
		[
			'post_id'      => $post_id,
			'show_excerpt' => $show_excerpt,
		]
	);
	?>
</div>
