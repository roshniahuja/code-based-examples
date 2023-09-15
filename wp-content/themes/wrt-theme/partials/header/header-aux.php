<?php
/**
 * The template for displaying the Aux Navigation (as well as mobile button and search).
 *
 * @package WRTTheme
 */

?>

<div class="site-header__supplemental-container">
	<div class="container">

		<button type="button" class="main-navigation-toggle" aria-controls="main-navigation" aria-expanded="false" aria-haspopup="true" data-mq-disable="(min-width: 64em)">
			<span class="close visually-hidden">Close</span>
			<span class="open visually-hidden">Open</span>
			<span class="screen-reader-text"><?php esc_html_e( 'Toggle menu', 'wrt-theme' ); ?></span>
		</button>
		<!-- .main-navigation-toggle -->

		<div class="auxillary-navigation">
			<span><?php echo esc_html_x( 'Choose my view:', 'label', 'wrt-theme' ); ?></span>
			<?php
			wp_nav_menu(
				array(
					'theme_location'  => 'auxillary',
					'menu_id'         => 'auxillary-menu',
					'container_class' => 'menu-aux-container',
				)
			);
			?>
		</div>
		<!-- .auxillary-navigation -->

		<div class="header-search--container">
			<div class="search search__filterable header-search">
				<?php get_search_form(); ?>
			</div>
		</div>
		<!-- .header-search--container -->

	</div>
	<!-- .container -->
</div>
<!-- .site-header__supplemental-container -->
