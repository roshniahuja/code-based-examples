<?php
/**
 * Title: Hero section home
 * Slug: wrt-theme/hero-section-home
 * Categories: 10up-theme
 *
 * @package WRTTheme
 */

?>

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|2-xl"}}},"gradient":"linear-light-blue-to-transparent","layout":{"type":"constrained"},"allowOverlap":true,"overlapValue":120} -->
<div class="wp-block-group alignfull has-linear-light-blue-to-transparent-gradient-background has-background  has-overlap white-bars-position-center" style="padding-top:var(--wp--preset--spacing--2-xl);spacing:[object Object];--overlap-amount:120px"><!-- wp:columns {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|m","bottom":"var:preset|spacing|m"}}}} -->
<div class="wp-block-columns alignwide" style="margin-top:var(--wp--preset--spacing--m);margin-bottom:var(--wp--preset--spacing--m)"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:wrt/post-picker-group {"contentTypes":["post"],"partial":"post-card/post-card-featured","containerClass":"home-hero-featured-item","location":"homepage"} -->
<div class="wp-block-wrt-post-picker-group home-hero-featured-item"><!-- wp:wrt/post-picker {"partial":"post-card/post-card-featured","containerClass":"","isCurated":true} /--></div>
<!-- /wp:wrt/post-picker-group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:wrt/post-picker-group {"contentTypes":["post"],"partial":"post-card/post-card-list","size":4,"containerClass":"home-hero-content-items","childrenClass":"home-hero-content-item"} -->
<div class="wp-block-wrt-post-picker-group home-hero-content-items"><!-- wp:wrt/post-picker {"partial":"post-card/post-card-list","containerClass":"home-hero-content-item","isCurated":true} /-->

<!-- wp:wrt/post-picker {"partial":"post-card/post-card-list","containerClass":"home-hero-content-item"} /-->

<!-- wp:wrt/post-picker {"partial":"post-card/post-card-list","containerClass":"home-hero-content-item"} /-->

<!-- wp:wrt/post-picker {"partial":"post-card/post-card-list","containerClass":"home-hero-content-item"} /--></div>
<!-- /wp:wrt/post-picker-group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
