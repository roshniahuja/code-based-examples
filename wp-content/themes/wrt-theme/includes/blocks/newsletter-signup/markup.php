<?php
/**
 * Newsletter sign up block markup
 *
 * @package WRTTheme\Blocks\NewsletterSignup
 *
 * @var array    $attributes         Block attributes.
 * @var string   $content            Block content.
 * @var WP_Block $block              Block instance.
 * @var array    $context            Block context.
 */

?>

<div <?php echo get_block_wrapper_attributes( array( 'class' => 'has-flag' ) ); // phpcs:ignore ?>>
	<?php if ( ! empty( $attributes['title'] ) ) : ?>
		<h2><?php echo esc_html( $attributes['title'] ); ?></h2>
	<?php endif; ?>

	<?php if ( ! empty( $attributes['description'] ) ) : ?>
		<p class="newsletter-signup__description"><?php echo esc_html( $attributes['description'] ); ?></p>
	<?php endif; ?>

	<div class="newsletter-signup__email-input">
		<?php if ( ! empty( $attributes['newsletter'] ) ) : ?>
			<input type="hidden" value="<?php echo esc_attr( $attributes['newsletter'][0]['id'] ); ?>" name="wrt-newsletter">
		<?php endif; ?>
		<input type="email" id="email" name="email" placeholder="you@example.com" required>
		<button class="button-secondary" id="js-newsletter-signup">
			<span><?php echo esc_html( $attributes['button'] ); ?></span>
		</button>
		<span class="newsletter-signup__status"></span>
	</div>
	<label for="email">
		<?php if ( ! empty( $attributes['label'] ) ) : ?>
			<?php echo esc_html( $attributes['label'] ); ?>
		<?php endif; ?>
	</label>
	<?php if ( ! empty( $attributes['variables'] ) ) : ?>
		<?php foreach ( $attributes['variables'] as $variable ) : ?>
			<div class="checkbox-container">
				<input type="checkbox" id="<?php echo esc_attr( $variable['id'] ); ?>" name="variables[]" value="<?php echo esc_attr( $variable['id'] ); ?>">
				<label for="<?php echo esc_attr( $variable['id'] ); ?>">
					<?php if ( ! empty( $variable['name'] ) ) : ?>
						<?php echo esc_html( $variable['name'] ); ?>
					<?php endif; ?>
					<?php if ( ! empty( $variable['description'] ) ) : ?>
						<span><?php echo esc_html( $variable['description'] ); ?></span>
					<?php endif; ?>
				</label>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
	<div class="newsletter-view-all">
		<a href="https://link.weareteachers.com/join/7d4/signup"
			rel="noopener noferrer"
			target="_blank"
		>
			<?php esc_html_e( 'See All Our Newsletters', 'wrt-theme' ); ?>
		</a>
	</div>
</div>
