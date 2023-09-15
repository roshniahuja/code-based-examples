<?php
/**
 * Main Footer
 *
 * @package WRTTheme
 */

use function WRTTheme\Utility\get_svg_icon;
use function WRTTheme\Template_Tags\svg_kses_allowed_html;
use WRTTheme\MenuWalker\Footer_Menu_Walker;

?>

<footer class="site-footer">
	<div class="container">
		<div class="site-footer__inner-container">
			<div class="site-footer__logo">
				<a href="<?php echo esc_url( home_url() ); ?>" aria-label="WeAreTeachers">
				<?php echo wp_kses( get_svg_icon( 'wrt-logo' ), svg_kses_allowed_html() ); ?>
				</a>
			</div>
			<?php if ( is_active_sidebar( 'footer-1' ) ) { ?>
				<div class="site-footer__textarea">
					<?php dynamic_sidebar( 'footer-1' ); ?>
				</div>
			<?php } ?>
			<?php
			wp_nav_menu(
				array(
					'container_class' => 'site-footer__menu',
					'menu_class'      => 'site-footer__menu-items',
					'theme_location'  => 'footer',
					'depth'           => 2,
					'fallback_cb'     => false,
					'walker'          => new Footer_Menu_Walker(),
				)
			);
			?>
		</div>
	</div>
</footer>
