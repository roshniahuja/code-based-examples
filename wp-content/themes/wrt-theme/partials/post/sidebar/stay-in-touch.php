<?php
/**
 * Sidebar: Stay in touch
 *
 * @package WRTTheme
 */

use function WRTSailthru\Newsletters\get_newsletters;

// The newsletter variables depending on the context.
$newsletter_vars = get_newsletters();
?>

<div class="sidebar--stay-in-touch has-flag wp-block-wrt-newsletter-signup" id="getForm">
	<h5><?php esc_html_e( 'Stay in touch', 'wrt-theme' ); ?></h5>
	<input type="hidden" name="wrt-newsletter" value="Master">
	<?php foreach ( $newsletter_vars  as $newsletter ) : ?>
	<input type="hidden" name="variables[]" value="<?php echo esc_attr( $newsletter ); ?>">
	<?php endforeach; ?>
	<label for="email"><?php esc_html_e( 'All the best teaching and learning ideas, straight to your inbox!', 'wrt-theme' ); ?></label>
	<input type="email" id="email" placeholder="<?php esc_attr_e( 'Enter your email address', 'wrt-theme' ); ?>">
	<button class="button-secondary" id="js-newsletter-signup">
		<span><?php esc_html_e( 'Subscribe', 'wrt-theme' ); ?></span>
	</button>
	<div class="newsletter-signup__email-input">
		<span class="newsletter-signup__status"></span>
	</div>
</div>
