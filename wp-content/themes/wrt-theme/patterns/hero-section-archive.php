<?php
/**
 * Title: Hero section archive
 * Slug: wrt-theme/hero-section-archive
 * Categories: 10up-theme
 *
 * @package WRTTheme
 */

?>

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|2-xl","bottom":"var:preset|spacing|2-xl"},"blockGap":"var:preset|spacing|xl"}},"gradient":"linear-light-blue-to-transparent","layout":{"type":"constrained"},"hasWhiteBars":true,"whiteBarsShouldRotate":true} -->
<div class="wp-block-group alignfull has-linear-light-blue-to-transparent-gradient-background has-background  has-white-bars white-bars-position-center white-bars-should-rotate" style="padding-top:var(--wp--preset--spacing--2-xl);padding-bottom:var(--wp--preset--spacing--2-xl);spacing:[object Object];--overlap-amount:0"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="has-text-align-center">Archive Title</h2>
<!-- /wp:heading -->

<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|l","left":"var:preset|spacing|l"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:wrt/post-picker-group {"contentTypes":["post"],"partial":"post-card/post-card","containerClass":"section-hero-featured-item","showExcerpt":true} -->
<div class="wp-block-wrt-post-picker-group section-hero-featured-item"><!-- wp:wrt/post-picker {"partial":"post-card/post-card","containerClass":"","showExcerpt":true,"isCurated":true} /--></div>
<!-- /wp:wrt/post-picker-group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:wrt/post-picker-group {"contentTypes":["post"],"partial":"section-hero/section-hero-item","size":2,"containerClass":"section-hero-content-items","childrenClass":"section-hero-content-item","showExcerpt":true} -->
<div class="wp-block-wrt-post-picker-group section-hero-content-items"><!-- wp:wrt/post-picker {"partial":"section-hero/section-hero-item","containerClass":"section-hero-content-item","showExcerpt":true,"isCurated":true} /-->

<!-- wp:wrt/post-picker {"partial":"section-hero/section-hero-item","containerClass":"section-hero-content-item","showExcerpt":true,"isCurated":true} /--></div>
<!-- /wp:wrt/post-picker-group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
