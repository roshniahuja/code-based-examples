<?php
/**
 * Single post sponsor
 *
 * @package WRTTheme
 *
 * @var string $args Optional. Class name for the sponsor. Default 'desktop'.
 */

use WRTTheme\Utility;


$campaign = Utility\get_post_campaign( get_the_ID() );
$sponsors = Utility\get_campaign_sponsors( $campaign );

if ( null === $campaign || empty( $sponsors ) ) {
	return;
}

$sponsor     = $sponsors[0]; // Use the first sponsor.
$url         = get_post_meta( $campaign->ID, 'campaign-url', true );
$cta         = get_post_meta( $campaign->ID, 'campaign-cta', true );
$description = get_the_excerpt( $campaign->ID );
$name        = $sponsor->name;
?>

<div class="single-post--sponsor sponsor-<?php echo esc_attr( $args ?? 'desktop' ); ?>">
	<?php if ( has_post_thumbnail( $campaign->ID ) ) : ?>
		<div class="post-card__sponsored-by">
			<div class="s-display-logo">
				<?php if ( ! empty( $url ) ) : ?>
					<a
						href="<?php echo esc_url( $url ); ?>"
						title="Visit <?php echo esc_html( $name ); ?>"
						style="border: none;"
					>
				<?php endif; ?>
					<?php
						echo get_the_post_thumbnail( $campaign->ID, 'sponsor-logo' );
					?>
				<?php if ( ! empty( $url ) ) : ?>
				</a>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
	<div class="single-post--sponsor-content">
		<div class="single-post--sponsor-name">
			<?php esc_html_e( 'Brought to you by', 'wrt-theme' ); ?>
			<?php if ( ! empty( $url ) ) : ?>
				<a href="<?php echo esc_url( $url ); ?>">
					<?php esc_html_e( $name ); ?>
				</a>
			<?php else : ?>
				<?php esc_html_e( $name ); ?>
			<?php endif; ?>
		</div>
		<?php echo wp_kses_post( apply_filters( 'the_excerpt', $description ) ); ?>
	</div>
</div>
