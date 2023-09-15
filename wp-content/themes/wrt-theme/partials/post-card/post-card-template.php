<?php
/**
 * Post card item template
 *
 * @package wrt/theme
 */

?>

<template id="post-card-template">
	<article class="post-card post-card--list">
		<figure class="post-card__thumbnail" data-image>
			<a href="#" data-image-link>
				<img width="445" height="356" loading="lazy" src="<?php echo esc_url( WRT_THEME_DIST_URL . 'images/placeholder-wrt.png' ); ?>" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" />
			</a>
		</figure>

		<div class="post-card__content">
			<div class="post-card__grades" data-grades>
				<span><?php esc_html_e( 'Grades:', 'wrt-theme' ); ?></span>
				<a href="#" rel="tag" data-grades-link>Grade</a>
			</div>

			<h3 class="post-card__title">
				<a href="#" aria-label="Title" data-title-link>Title</a>
			</h3>

			<div class="post-card__excerpt" data-excerpt>Excerpt</div>

			<div class="post-card__byline post-card__byline--justified">
				<div class="post-card__categories">
					<div class="post-card__cat-item" data-category></div>
				</div>


				<div class="post-card__posted-by">
					<span><?php esc_html_e( 'By', 'wrt-theme' ); ?></span>
					<a href="#" data-author>Author</a>
				</div>
			</div>
		</div>
	</article>
</template>
