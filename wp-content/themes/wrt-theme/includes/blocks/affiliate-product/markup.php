<?php
/**
 * Post Picker Markup
 *
 * @package WRT\Blocks\AffiliateProduct
 *
 * @var array    $attributes         Block attributes.
 * @var string   $content            Block content.
 * @var WP_Block $block              Block instance.
 * @var array    $context            BLock context.
 */

$post_id          = $attributes['productPostId'] ?? null;
$show_attribution = $attributes['showAttribution'] ?? null;

if ( ! $post_id ) {
	return;
}

?>

<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore ?>>
	<?php
	get_template_part(
		'partials/post-card/editors-pick',
		'',
		[
			'post_id'          => $post_id,
			'show_attribution' => $show_attribution,
			'title_tag'        => 'h2',
			'is_affiliate'     => true,
		]
	);
	?>
</div>
