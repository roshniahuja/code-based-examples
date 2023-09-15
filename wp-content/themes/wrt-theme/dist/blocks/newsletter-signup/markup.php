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

	<?php if ( ! empty( $attributes['subNewsletters'] ) ) : ?>
		<?php foreach ( $attributes['subNewsletters'] as $newsletter ) : ?>
			<div class="checkbox-container">
				<input type="checkbox" id="<?php echo esc_attr( $newsletter['id'] ); ?>" name="wrt-newsletters[]" value="<?php echo esc_attr( $newsletter['id'] ); ?>">
				<label for="<?php echo esc_attr( $newsletter['id'] ); ?>">
					<?php if ( ! empty( $newsletter['label'] ) ) : ?>
						<?php echo esc_html( $newsletter['label'] ); ?>
					<?php else : ?>
						<?php echo esc_html( $newsletter['name'] ); ?>
					<?php endif; ?>
					<?php if ( ! empty( $newsletter['description'] ) ) : ?>
						<span><?php echo esc_html( $newsletter['description'] ); ?></span>
					<?php endif; ?>
				</label>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
