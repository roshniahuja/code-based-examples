<?php
/**
 * Contest banner
 *
 * Must be included within the posts query loop.
 *
 * @package WRTTheme
 */

$banner_image    = get_post_meta( $post->ID, 'banner-image', true );
$banner_title    = get_the_title();
$banner_subtitle = get_post_meta( $post->ID, 'banner-subtitle', true );

$banner_img_class = 'contest-banner__image';

if ( empty( $banner_image ) ) {
	if ( has_post_thumbnail( $post->ID ) ) {
		$banner_image = get_the_post_thumbnail_url( $post->ID );
		$banner_img_class .= 'contest-banner--featured-image';
	} else {
		// @TODO Replace this fallback image with brand placeholder.
		$banner_image = 'https://via.placeholder.com/1920x1080/eee?text=';
		$banner_img_class .= 'contest-banner--placeholder-image';
	}
}

?>

<div class="contest-banner alignfull">
	<div class="contest-banner__container">
		<?php
		printf(
			'<img class="%1$s" rel="preload" src="%2$s" />',
			esc_attr( $banner_img_class ),
			esc_url( $banner_image ),
		);
		?>

		<div class="contest-banner__content">
			<?php
			if ( ! empty( $banner_title ) ) :
				printf( '<h1 class="contest-banner__title">%s</h1>', wp_kses_post( $banner_title ) );
			endif;

			if ( ! empty( $banner_subtitle ) ) :
				printf( '<p class="contest-banner__subtitle has-m-font-size">%s</p>', wp_kses_post( $banner_subtitle ) );
			endif;
			?>
		</div>
	</div>
</div>
