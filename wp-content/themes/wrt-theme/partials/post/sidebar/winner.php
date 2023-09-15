<?php
/**
 * Sidebar: Winner
 *
 * @package WRTTheme
 */

$post_id          = $post->ID;
$description      = $post->post_excerpt;
$winners          = get_post_meta( $post_id, 'contest-winners', true );
$winners          = array_filter( $winners );
$rightrail_header = get_post_meta( $post_id, 'contest-rightrail-header', true );
$rightrail_copy   = get_post_meta( $post_id, 'contest-rightrail-copy', true );
?>
<?php if ( ! empty( $winners ) || ! empty( $description ) ) : ?>
<div class="winner-box">
	<h2>
		<?php
		// Count the number of winners
		if ( is_countable( $winners ) ) {
			$num_winners = count( $winners );
		}

		// Display the appropriate translation based on the number of winners
		printf(
			esc_html(
				_n(
					'Congratulations to Our Winner',
					'Congratulations to Our Winners',
					$num_winners,
					'wrt-theme'
				)
			),
			absint( $num_winners )
		);
		?>
	</h2>
	<?php echo wp_kses_post( apply_filters( 'the_excerpt', $description ) ); ?>

	<?php if ( ! empty( $winners ) ) : ?>
		<ul class="winner-list">
			<?php foreach ( $winners as $winner ) : ?>
				<li class="winner">
					<?php echo esc_html( $winner ); ?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
<?php endif; ?>

<?php if ( ! empty( $rightrail_header ) || ! empty( $rightrail_copy ) ) : ?>
	<div class="contest-rightrail sidebar--stay-in-touch">
		<?php if ( ! empty( $rightrail_header ) ) : ?>
			<div class="rightrail-header">
				<?php printf( '<h2>%s</h2>', esc_html( $rightrail_header ) ); ?>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $rightrail_copy ) ) : ?>
			<div class="rightrail-copy">
				<?php echo wp_kses_post( $rightrail_copy ); ?>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>
