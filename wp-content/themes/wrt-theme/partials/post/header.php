<?php
/**
 * Single post header
 *
 * @package WRTTheme
 */

use WRTTheme\Utility;
use WRTPlugin\PostTypes\ContestPostType;
$single_post_id = get_the_ID();
$grades_term    = get_the_terms( $single_post_id, \WRTPlugin\Taxonomies\GradeTaxonomy::$name );
$campaign       = Utility\get_post_campaign( $single_post_id );
$sponsors       = Utility\get_campaign_sponsors( $campaign );
?>

<header class="single-post--hero">
	<div class="single-post--hero-wrapper">
		<div class="feature-card has-flag">
			<div class="feature-card--header">
				<?php
				get_template_part(
					'partials/post-card/byline',
					null,
					array(
						'post_id'         => $single_post_id,
						'show_time'       => false,
						'show_author'     => false,
						'container_class' => 'post-card__byline--justified',
					)
				);
				?>

				<?php if ( ! is_wp_error( $grades_term ) && ! empty( $grades_term ) ) : ?>
					<div class="feature-card--grade">
						<?php
						printf( /* translators: %1$s - opening <a> tag, %2$s - term name, %3$s - closing </a> tag */
							esc_html__( 'Grades: %1$s%2$s%3$s', 'wrt-theme' ),
							'<a href="' . esc_url( get_term_link( $grades_term[0] ) ) . '">',
							esc_html( $grades_term[0]->name ),
							'</a>'
						)
						?>
					</div>
				<?php endif; ?>
			</div>
			<?php the_title( '<h1>', '</h1>' ); ?>
			<?php
			if ( get_post_type() !== ContestPostType::$name ) {
				the_excerpt();
			}
			?>
			<div class="feature-card--sponsor">
			<?php if ( ! empty( $sponsors ) && ! is_wp_error( $sponsors ) ) : ?>
				<?php foreach ( $sponsors as $sponsor ) : ?>
					<div class="post-card__sponsored-by">
						<?php
							/* Translators: %s is the sponsor name. */
							echo esc_html( sprintf( __( 'Sponsored By %s' ), $sponsor->name ) );
						?>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
			</div>
		</div>
	</div>

	<?php if ( has_post_thumbnail( $single_post_id ) ) : ?>
		<div class="single-post--hero-image-container">
			<?php
			echo get_the_post_thumbnail(
				$single_post_id,
				'full',
				array(
					'class' => 'single-post--hero-image',
				)
			);
			?>
		</div>
	<?php endif; ?>
</header>
