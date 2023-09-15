<?php
/**
 * Sidebar: Teaching aids
 *
 * @package WRTTheme
 */

use function WRTSailthru\Recommendations\get_recommendations;
use function WRTSailthru\Recommendations\create_vars_from_post;
use function WRTSailthru\Recommendations\get_sailthru_section_id;

// This can be configured in Sailthru.
$sailthru_section_id   = get_sailthru_section_id( 'single-post-sidebar' );
$sailthru_section_vars = create_vars_from_post( get_queried_object() );
$recommendations       = get_recommendations( $sailthru_section_id, $sailthru_section_vars );

if ( empty( $recommendations ) ) {
	return;
}

// Only show 3 recommendations
$recommendations = array_slice( $recommendations, 0, 3 );
?>

<div class="sidebar--teaching-aids">
	<h4><?php esc_html_e( 'Recommended', 'wrt-theme' ); ?></h4>
	<?php foreach ( $recommendations as $recommendation ) : ?>
	<div class="teaching-aids--item">
		<h5><?php echo esc_html( $recommendation['post_title'] ); ?></h5>
		<p><?php echo wp_kses_post( $recommendation['excerpt'] ); ?></p>
		<a href="<?php echo esc_url( $recommendation['permalink'] ); ?>" class="wp-block-button is-style-arrow-icon">
			<span class="wp-element-button">Read More</span>
		</a>
	</div>
	<?php endforeach; ?>
</div>
