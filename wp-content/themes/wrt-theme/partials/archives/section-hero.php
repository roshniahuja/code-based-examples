<?php
/**
 * Section Hero Partial
 *
 * @package WRTTheme
 */

use function WRTTheme\Utility\get_archive_featured_posts;

$featured_posts = get_archive_featured_posts();
$description    = term_description();
?>

<div class="is-layout-constrained wp-block-group alignfull has-linear-light-blue-to-transparent-gradient-background has-background has-white-bars white-bars-position-center white-bars-should-rotate" style="padding:0;--overlap-amount:0">
	<div style="height:64px" aria-hidden="true" class="wp-block-spacer"></div>
	<div class="section-hero-header">
		<h1 class="section-hero__title"><?php echo esc_html( single_term_title() ); ?></h1>
		<?php
		if ( ! empty( $description ) ) {
			echo wp_kses_post( $description );
		}
		?>
	</div>

	<div class="is-layout-flex wp-block-columns">
		<div class="is-layout-flow wp-block-column">
			<div class="wp-block-wrt-post-picker-group section-hero-featured-item">
				<div class="wp-block-wrt-post-picker">
					<?php
					get_template_part(
						'partials/post-card/post-card',
						'',
						array(
							'post_id' => $featured_posts[0],
						)
					);
					?>
				</div>
			</div>
		</div>

		<div class="is-layout-flow wp-block-column">
			<div class="wp-block-wrt-post-picker-group section-hero-content-items">
				<div class="section-hero-content-item wp-block-wrt-post-picker">
					<?php
					get_template_part(
						'partials/section-hero/section-hero-item',
						'',
						array(
							'post_id' => $featured_posts[1],
						)
					);
					?>
				</div>
				<div class="section-hero-content-item wp-block-wrt-post-picker">
					<?php
					get_template_part(
						'partials/section-hero/section-hero-item',
						'',
						array(
							'post_id' => $featured_posts[2],
						)
					);
					?>
				</div>
			</div>
		</div>
	</div>
</div>
