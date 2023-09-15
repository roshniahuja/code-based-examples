<?php
/**
 * This file contains hooks and functions that override the behavior of WP Core.
 *
 * @package WRTTheme
 */

namespace WRTTheme\Overrides;

use WRTTheme\Utility;
use WRTPlugin\PostTypes\HubPostType;

/**
 * Registers instances where we will override default WP Core behavior.
 *
 * @link https://developer.wordpress.org/reference/functions/print_emoji_detection_script/
 * @link https://developer.wordpress.org/reference/functions/print_emoji_styles/
 * @link https://developer.wordpress.org/reference/functions/wp_staticize_emoji/
 * @link https://developer.wordpress.org/reference/functions/wp_staticize_emoji_for_email/
 * @link https://developer.wordpress.org/reference/functions/wp_generator/
 * @link https://developer.wordpress.org/reference/functions/wlwmanifest_link/
 * @link https://developer.wordpress.org/reference/functions/rsd_link/
 *
 * @return void
 */
function setup() {
	// Remove the Emoji detection script.
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );

	// Remove inline Emoji detection script.
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );

	// Remove Emoji-related styles from front end and back end.
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );

	// Remove Emoji-to-static-img conversion.
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

	add_filter( 'tiny_mce_plugins', __NAMESPACE__ . '\disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', __NAMESPACE__ . '\disable_emoji_dns_prefetch', 10, 2 );

	// Remove WordPress generator meta.
	remove_action( 'wp_head', 'wp_generator' );
	// Remove Windows Live Writer manifest link.
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// Remove the link to Really Simple Discovery service endpoint.
	remove_action( 'wp_head', 'rsd_link' );

	add_filter( 'excerpt_length', __NAMESPACE__ . '\excerpt_length' );
	add_filter( 'excerpt_more', __NAMESPACE__ . '\read_more_link' );

	// Archive overrides.
	add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );
	add_action( 'admin_head', __NAMESPACE__ . '\remove_feedback_tab' );

	// Ad overrides
	add_filter( 'wrt_ads_disabled', __NAMESPACE__ . '\disable_ads_on_sponsored_content' );

	// Gravityforms gating overrides
	add_filter( 'gfsa_has_access', __NAMESPACE__ . '\handle_download_email_links' );
}

/**
 * Filter function used to remove the TinyMCE emoji plugin.
 *
 * @link https://developer.wordpress.org/reference/hooks/tiny_mce_plugins/
 *
 * @param  array $plugins An array of default TinyMCE plugins.
 * @return array          An array of TinyMCE plugins, without wpemoji.
 */
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) && in_array( 'wpemoji', $plugins, true ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	}

	return $plugins;
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @link https://developer.wordpress.org/reference/hooks/emoji_svg_url/
 *
 * @param  array  $urls          URLs to print for resource hints.
 * @param  string $relation_type The relation type the URLs are printed for.
 * @return array                 Difference betwen the two arrays.
 */
function disable_emoji_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		/** This filter is documented in wp-includes/formatting.php */
		$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );

		$urls = array_values( array_diff( $urls, array( $emoji_svg_url ) ) );
	}

	return $urls;
}

/**
 * Set custom excerpt length, depending on where it is shown.
 *
 * @param int $length Current length.
 *
 * @return int
 */
function excerpt_length( int $length ): int {
	if ( is_single() ) {
		return 20;
	}

	return $length;
}

/**
 * Custom Excerpt function
 *
 * @return void
 */
function custom_excerpt() {
	global $post;

	$excerpt = \has_excerpt() ? \get_the_excerpt() : \wp_trim_words( \get_the_content(), \apply_filters( 'excerpt_length', 55 ), '' );
	$excerpt .= '<span class="wp-block-button is-style-arrow-icon moretag"><a class="wp-element-button" href="' . \get_permalink() . '">' . \__( 'Continue Reading', 'wrt-theme' ) . '</a></span>';

	echo '<p>' . $excerpt . '</p>';
}

/**
 * Add "Continue reading" button.
 *
 * @param string $more Current more text.
 */
function read_more_link( string $more ) {
	global $post;

	if (!\has_excerpt($post->ID)) {
		ob_start();
		echo esc_html( trim( $more ) );
		?><span class="wp-block-button is-style-arrow-icon moretag"><a class="wp-element-button" href="<?php echo esc_url( \get_permalink( $post->ID ) ); ?>"><?php esc_html_e( 'Continue Reading', 'wrt-theme' ); ?></a></span><?php
		return ob_get_clean();
	}

	return $more;
}

/**
 * Remove feedback tab from the WordPress backend.
 */
function remove_feedback_tab() {
	?>
	<style>
		.block-editor-contrast-checker {
			display: none;
		}
	</style>
	<?php
}

/**
 * Disables ads on sponsored content.
 *
 * @param bool $disabled Whether ads are disabled.
 * @return bool
 */
function disable_ads_on_sponsored_content( $disabled ) {

	// Disabled ads for logged in users
	if ( is_user_logged_in() ) {
		return true;
	}

	if ( is_singular() ) {

		// Disable ads on Hubs.
		if ( is_singular( HubPostType::$name ) ) {
			return true;
		}

		$campaign = Utility\get_post_campaign( get_queried_object_id() );
		$sponsors = Utility\get_campaign_sponsors( $campaign );

		if ( $campaign && ! empty( $sponsors ) ) {
			return true;
		}
	}

	return $disabled;
}

/**
 * Handle download email links.
 *
 * @param bool $has_access Whether the request should be able to access gated confirmation pages.
 * @return bool
 */
function handle_download_email_links( $has_access ) {
	if ( $has_access ) {
		return $has_access;
	}

	$email_param = filter_input( INPUT_GET, 'email', FILTER_SANITIZE_STRING );

	if ( 'true' === $email_param ) {
		return true;
	}

	return $has_access;
}
