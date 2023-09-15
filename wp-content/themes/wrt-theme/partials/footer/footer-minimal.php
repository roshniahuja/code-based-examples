<?php
/**
 * Main Footer
 *
 * @package WRTTheme
 */

use WRTTheme\MenuWalker\Footer_Menu_Walker;

?>

<footer class="blank-template-site-footer site-footer alignfull">
	<div class="site-footer__main-container container">
		<?php if ( is_active_sidebar( 'footer-copyright' ) ) { ?>
			<div class="copyright-text">
				<?php dynamic_sidebar( 'footer-copyright' ); ?>
			</div>
		<?php } ?>
		<?php
		wp_nav_menu(
			array(
				'container_class' => 'site-footer__menu',
				'menu_class'      => 'site-footer__menu-items footer-links',
				'theme_location'  => 'footer-minimal',
				'depth'           => 0,
				'fallback_cb'     => false,
				'walker'          => new Footer_Menu_Walker(),
			)
		);
		?>
	</div>
</footer>
