<?php
/**
 * Header branding partial
 *
 * @package WRTTheme
 */

?>

<div class="site-branding">
	<?php
	if ( has_custom_logo() ) {
		the_custom_logo();
	} else {
		?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php bloginfo( 'name' ); ?>
		</a>
		<?php
	}
	?>
	<div class="site-branding__tagline">
		<?php bloginfo( 'description' ); ?>
	</div>
</div><!-- .site-branding -->
