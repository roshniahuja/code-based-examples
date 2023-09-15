<?php
/**
 * Content Card block markup
 *
 * @package WRTTheme\Blocks\ContentCard
 *
 * @var array    $attributes         Block attributes.
 * @var string   $content            Block content.
 * @var WP_Block $block              Block instance.
 * @var array    $context            Block context.
 */

$post_id                    = $attributes['postId'] ?? null;
$card_link                  = $attributes['cardLink'] ?? '';
$card_link_opens_in_new_tab = $attributes['cardLinkOpensInNewTab'] ?? false;
$headline                   = $attributes['title'] ?? '';
$imageId                    = $attributes['imgId'] ?? 0;
$description                = $attributes['description'] ?? '';
$should_insert_post         = $attributes['insertPost'] ?? false;
$should_show_caption        = $attributes['showCaption'] ?? false;
$should_show_description    = $attributes['showDescription'] ?? false;

if ( $should_insert_post && ! $post_id ) {
	return;
}

?>

<div <?php echo get_block_wrapper_attributes(); //phpcs:ignore ?>>
	<?php
	if ( $should_insert_post ) :
		get_template_part(
			'partials/post-card/post-card',
			'',
			[
				'post_id'      => $post_id,
				'show_excerpt' => $should_show_description,
				'show_byline'  => false,
				'show_caption' => $should_show_caption,
			]
		);
	else: ?>
	<div class="post-card">
		<figure class="post-card__thumbnail">
			<?php
			printf(
				'<a href="%1$s" target="%2$s">%3$s</a><figcaption>%4$s</figcaption>',
				esc_url( $card_link ),
				esc_attr( $card_link_opens_in_new_tab ),
				wp_get_attachment_image(
					$imageId,
					'full'
				),
				wp_get_attachment_caption( $imageId )
			);
			?>

		</figure>
		<div class="post-card__content">
			<?php
			if ( ! empty( $headline ) ) :
				printf(
					'<h3 class="post-card__title"><a href="%1$s" target="%2$s">%3$s</a></h3>',
					esc_url( $card_link ),
					esc_attr( $card_link_opens_in_new_tab ? '_blank' : '_self' ),
					esc_html( $headline )
				);
			endif;

			if ( $should_show_description && ! empty( $description ) ) :
				printf(
					'<p class="post-card__excerpt">%s</p>',
					wp_kses_post( $description )
				);
			endif;
			?>

		</div>
	</div>
	<?php endif; ?>
</div>
