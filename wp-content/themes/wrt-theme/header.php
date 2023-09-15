<?php
/**
 * The template for displaying the header.
 *
 * @package WRTTheme
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php wp_body_open(); ?>

		<a href="#main" class="skip-to-content-link visually-hidden-focusable"><?php esc_html_e( 'Skip to main content', 'wrt-theme' ); ?></a>
		<header id="masthead" class="site-header" role="banner">

			<?php get_template_part( 'partials/header/header', 'aux' ); ?>

			<div class="site-header__main-container container">
				<?php get_template_part( 'partials/header/header', 'branding' ); ?>

				<?php get_template_part( 'partials/header/header', 'navigation' ); ?>
			</div>

			<div class="header__bottom"></div>

		</header><!-- #masthead -->
		<?php get_template_part( 'partials/header/header', 'notice-bar' ); ?>
		<?php get_template_part( 'partials/post/sidebar/affiliate-disclosure' ); ?>

		<main id="main" role="main" tabindex="-1" class="site-content is-layout-flow">
