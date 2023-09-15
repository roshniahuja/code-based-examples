<?php
/**
 * Contest Rules.
 *
 * Must be called within a post loop.
 *
 * @package WRTTheme
 */

$contest_prizes = get_post_meta( $post->ID, 'contest-prizes', true );
$contest_rules  = get_post_meta( $post->ID, 'contest-rules', true );

if ( ! empty( $contest_prizes ) || ! empty( $contest_rules ) ) {
	?>
	<div id="wrt-tabs" class="wrt-tabs-container" data-role="wrt-tabs" role="tablist">
		<ul class="wrt-tabs">
			<li class="wrt-tab" data-key="prize-tab" role="tab" aria-controls="prize-tab" aria-selected="true"><?php esc_html_e( 'Prize Package(s)', 'wrt-theme' ); ?></li>
			<li class="wrt-tab" data-key="rules-tab" role="tab" aria-controls="rules-tab"><?php esc_html_e( 'Rules', 'wrt-theme' ); ?></li>
		</ul>
		<?php
		if ( ! empty( $contest_prizes ) ) {
			?>
			<section id="prize-tab" class="wrt-tabs-section" role="tabpanel" aria-expanded="true" aria-hidden="false">
				<div id="contest-prizes" class="contest-prizes" style="display:block;">
					<?php echo wp_kses_post( $contest_prizes ); ?>
				</div>
			</section>
			<?php
		}

		if ( ! empty( $contest_rules ) ) {
			?>
			<section id="rules-tab" class="wrt-tabs-section" role="tabpanel" aria-expanded="false" aria-hidden="true">
				<div id="contest-rules" class="contest-rules" style="display:block;">
					<?php echo wp_kses_post( $contest_rules ); ?>
					<p class="link tabtop">
						<a href="#wrt-tabs" id="contest-rules-back-to-top" title="Back to top"><?php esc_html_e( 'Back to top', 'wrt-theme' ); ?></a>
					</p>
				</div>
			</section>
			<?php
		}
		?>
	</div>
	<?php
}
