<?php
/**
 * The template for displaying the main navigation.
 *
 * @package WRTTheme
 */

?>
<nav id="main-navigation" class="main-navigation" role="navigation">
	<?php
	wp_nav_menu(
		array(
			'theme_location'  => 'primary',
			'menu_id'         => 'primary-menu',
			'container_class' => 'primary-menu-container',
		)
	);
	?>
</nav><!-- #site-navigation -->
