<?php
/**
 * Header Notice Bar - The template partial for the header notice bar.
 *
 * @package WRTTheme
 */

?>
<div class="notice-bar">
	<div class="container">
		<?php if ( is_active_sidebar( 'notice-bar' ) ) { ?>
			<?php dynamic_sidebar( 'notice-bar' ); ?>
		<?php } ?>
	</div>
</div>
