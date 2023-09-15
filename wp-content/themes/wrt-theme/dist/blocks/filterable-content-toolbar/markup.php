<?php
/**
 * Filterable Content block markup
 *
 * @package WRTTheme\Blocks\FilterableContentToolbar
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 * @var array    $context    Block context.
 */

use function WRTTheme\Utility\generate_search_checkbox_elements;
use function WRTTheme\Utility\get_filtered_sorted_selected_terms;

$grades          = get_filtered_sorted_selected_terms( \WRTPlugin\Taxonomies\GradeTaxonomy::$name, 'grades', '/^(PreK|[K\d])/' );
$teaching_topics = get_filtered_sorted_selected_terms( 'wat-subject', 'teaching-topics', '//' );

// @TODO: Verify form action is compatible with archive changes (or implement ajax filtering)
?>

<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore ?>>
	<form action="/" class="wp-block-wrt-filterable-content-toolbar__content">
		<div class="wp-block-wrt-filterable-content-toolbar__content__label">
			<?php esc_html_e( 'Narrow by', 'wrt-theme' ); ?>
		</div>

		<div class="pseudo-select teaching-topic dropdowns-select" aria-controls="filter-teaching-dropdown">
			<span class="select-title"><?php echo esc_html_x( 'Teaching Topic', 'label', 'wrt-theme' ); ?></span>
		</div>
		<div class="pseudo-dropdown filter-teaching-dropdown" id='filter-teaching-dropdown' aria-hidden="true" aria-expanded="false">
			<?php if ( $teaching_topics ) : ?>
				<?php generate_search_checkbox_elements( $teaching_topics, array(), 'Grades' ); ?>
			<?php endif; ?>
		</div>


		<div class="pseudo-select grade-topic dropdowns-select" aria-controls="filter-grade-dropdown">
			<span class="select-title"><?php echo esc_html_x( 'Grade', 'label', 'wrt-theme' ); ?></span>
		</div>
		<div class="pseudo-dropdown filter-grade-dropdown" id='filter-grade-dropdown' aria-hidden="true" aria-expanded="false">
			<?php if ( $grades ) : ?>
				<?php generate_search_checkbox_elements( $grades, array(), 'Grades' ); ?>
			<?php endif; ?>
		</div>

		<button class="wp-block-wrt-filterable-content-toolbar__content__button button button-secondary" id="js-wrt-filter-go" disabled>
			<?php esc_html_e( 'Go!', 'wrt-theme' ); ?>
		</button>
	</form>
</div>
