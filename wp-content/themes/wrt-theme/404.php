<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * Template Name: 404 Page
 *
 * @package WRTTheme
 */

get_header();
use WRTTheme\Utility;

?>

<div class="page-404 wp-block-group teal-gradient">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( '404: Page Not Found', 'wrt-theme' ); ?></h1>
	</header>

	<div class="error-404 not-found">
		<div class="page-content">
			<p><?php esc_html_e( 'It looks like nothing was found at this location. Please use the search box below to locate the content you were looking for.', 'wrt-theme' ); ?></p>

			<div class="site-header">
				<div class="header-search--container">
					<div class="search search__filterable header-search">
						<?php Utility\get_wrt_search_form( '404' ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
get_footer();
