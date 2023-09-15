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
use function WRTTheme\Utility\get_sorted_grades;

$tax_object = get_queried_object();

$categories      = get_filtered_sorted_selected_terms( 'category', 'category', '//' );
$grades = get_sorted_grades(4);
$teaching_topics = get_filtered_sorted_selected_terms( 'wat-subject', 'teaching-topics', '//' );
$count           = 0; // We should have 2 filters max.
?>

<div <?php echo empty( $block ) ? 'class="wp-block-wrt-filterable-content-toolbar"' : get_block_wrapper_attributes(); // phpcs:ignore ?>>
	<div class="wp-block-wrt-filterable-content-toolbar__content">
		<div class="wp-block-wrt-filterable-content-toolbar__content__label">
			<?php esc_html_e( 'Narrow by', 'wrt-theme' ); ?>
		</div>

		<?php if ( 'wat-subject' !== $tax_object->taxonomy ) : ?>
			<?php $count++; ?>
			<div class="pseudo-select teaching-topic dropdowns-select" aria-controls="filter-teaching-dropdown">
				<span class="select-title"><?php echo esc_html_x( 'Teaching Topic', 'label', 'wrt-theme' ); ?></span>
			</div>
			<div class="pseudo-dropdown filter-teaching-dropdown" id="filter-teaching-dropdown" aria-hidden="true" aria-expanded="false">
				<?php if ( $teaching_topics ) : ?>
					<?php generate_search_checkbox_elements( $teaching_topics, array(), 'Topics', 'page-content' ); ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( \WRTPlugin\Taxonomies\GradeTaxonomy::$name !== $tax_object->taxonomy ) : ?>
			<?php $count++; ?>
			<div class="pseudo-select grade-topic dropdowns-select" aria-controls="filter-grade-dropdown">
				<span class="select-title"><?php echo esc_html_x( 'Grade', 'label', 'wrt-theme' ); ?></span>
			</div>
			<div class="pseudo-dropdown filter-grade-dropdown" id="filter-grade-dropdown" aria-hidden="true" aria-expanded="false">
				<?php if ( $grades ) : ?>
					<?php generate_search_checkbox_elements( $grades, array(), 'Grades', 'page-content' ); ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( 'category' !== $tax_object->taxonomy && $count < 2 ) : ?>
			<div class="pseudo-select category-topic dropdowns-select" aria-controls="filter-category-dropdown">
				<span class="select-title"><?php echo esc_html_x( 'Category', 'label', 'wrt-theme' ); ?></span>
			</div>
			<div class="pseudo-dropdown filter-grade-dropdown" id="filter-category-dropdown" aria-hidden="true" aria-expanded="false">
				<?php if ( $categories ) : ?>
					<?php generate_search_checkbox_elements( $categories, array(), 'Categories', 'page-content' ); ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<button class="wp-block-wrt-filterable-content-toolbar__content__button button button-secondary" id="js-wrt-filter-go" disabled>
			<span><?php esc_html_e( 'Go!', 'wrt-theme' ); ?></span>
		</button>
	</div>
</div>
