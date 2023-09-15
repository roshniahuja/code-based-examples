<?php
/**
 * Post card sponsor
 *
 * @package wrt/theme
 */

use WRTTheme\Utility;

$options = wp_parse_args(
	$args,
	[
		'post_id'   => 0,
		'show_logo' => true,
	]
);

$post_id           = $options['post_id'];
$show_sponsor_logo = $options['show_logo'];
$campaign          = Utility\get_post_campaign( $post_id );

if ( ! $campaign ) {
	return;
}

$campaign_terms = get_the_term_list( $campaign->ID, 'wat-sponsor', '', ',' );
$url            = get_post_meta( $campaign->ID, 'campaign-url', true );
?>

<div class="post-card__sponsor-item">
	<?php
	if ( ! is_wp_error( $campaign_terms ) && ! empty( $campaign_terms ) ) :
		$campaign_terms = str_replace( 'rel="tag"', 'class="post-card__sponsor-link" rel="tag"', $campaign_terms );
		printf(
			'<div class="post-card__sponsor-label"><span>%1$s</span>%2$s</div>',
			esc_html__( 'Sponsored by ', 'wrt-theme' ),
			wp_kses_post( $campaign_terms )
		);
	endif;
	?>

	<?php if ( $show_sponsor_logo && has_post_thumbnail( $campaign->ID ) ) : ?>
		<figure class="sponsored-by__logo">
			<?php
			if ( ! empty( $url ) ) :
				printf(
					'<a href="%1$s">%2$s</a>',
					esc_url( $url ),
					get_the_post_thumbnail( $campaign->ID, 'sponsor-logo' )
				);
			else :
				echo get_the_post_thumbnail( $campaign->ID, 'sponsor-logo' );
			endif;
			?>
		</figure>
	<?php endif; ?>
</div>
