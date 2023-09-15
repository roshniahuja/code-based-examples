<?php
/**
 * Main Footer
 *
 * @package WRTTheme
 */

?>
<div class="site-footer__colophone alignfull">
	<div class="container">
		<?php if ( is_active_sidebar( 'footer-copyright' ) ) { ?>
			<div class="copyright-text">
				<?php dynamic_sidebar( 'footer-copyright' ); ?>
			</div>
		<?php } ?>
	</div>
</div>
