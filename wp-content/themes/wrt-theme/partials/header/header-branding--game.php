<?php
/**
 * Header branding partial
 *
 * @package WRTTheme
 */

use function WRTTheme\Utility\get_svg_icon;
use function WRTTheme\Template_Tags\svg_kses_allowed_html;
?>

<div class="site-branding site-branding--game">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
	<?php echo get_svg_icon( 'logo-interactive-game' ); // phpcs:ignore ?>
	</a>
	<a href="/hub/online-activities-for-students/" class="btn btn-play-games"><?php esc_html_e( 'Play Games', 'wrt-theme' ); ?></a>
</div><!-- .site-branding--interactive-game -->
