<?php
/**
 * Stay in touch block markup
 *
 * @package WRTTheme\Blocks\StayInTouch
 *
 * @var array    $attributes         Block attributes.
 * @var string   $content            Block content.
 * @var WP_Block $block              Block instance.
 * @var array    $context            Block context.
 */

$title       = $attributes['title'] ?? null;
$description = $attributes['description'] ?? null;

?>
<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore ?>>
	<?php
	get_template_part(
		'partials/post/sidebar/stay-in-touch',
		'',
		[
			'title'       => $title,
			'description' => $description,
		]
	);
	?>
</div>
