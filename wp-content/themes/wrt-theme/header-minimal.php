<?php
/**
 * The template for displaying the minimal-header.
 *
 * @package WRTTheme
 */

$post_type = get_post_type();
$is_game_template = is_page_template('templates/page-interactive-game.php');
$is_header_for_game = 'wat-interactive-game' === $post_type || $is_game_template === true;

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class('has-minimal-header'); ?>>
	<?php wp_body_open(); ?>

	<a href="#main" class="skip-to-content-link visually-hidden-focusable"><?php esc_html_e( 'Skip to main content', 'wrt-theme' ); ?></a>
	<header class="blank-template-site-header site-header">
		<div class="site-header__main-container container">
			<?php

			if ( $is_header_for_game ) {
				get_template_part( 'partials/header/header-branding--game' );
			} else {
				get_template_part( 'partials/header/header-branding' );
			}
			?>
		</div>
	</header>

	<main id="main" role="main" tabindex="-1" class="site-content is-layout-flow">
