<?php
/**
 * The template for displaying the search form.
 *
 * @package WRTTheme
 */

use WRTTheme\Utility;

// Get the filtered, sorted and selected terms for the Grades and Topics taxonomies.
$grades = Utility\get_sorted_grades();
$topics = Utility\get_filtered_sorted_selected_terms( 'wat-subject', 'wat-subject', '//' );

// Get the selected Topic IDs from the search query arguments.
$selected_grade_ids = Utility\get_term_ids_from_url( \WRTPlugin\Taxonomies\GradeTaxonomy::$name );
$selected_topics    = Utility\get_term_ids_from_url( 'wat-subject' );

?>

<div itemscope itemtype="http://schema.org/WebSite">
	<form role="search"
		id="searchform"
		class="search-form"
		method="get"
		action="<?php echo esc_url( home_url( '/' ) ); ?>">

		<meta
			itemprop="target"
			content="<?php echo esc_url( home_url() ); ?>/?s={s}" />

		<label for="search-field" class="visually-hidden">
			<?php echo esc_html_x( 'Search for:', 'label', 'wrt-theme' ); ?>
		</label>

		<!-- Submit button -->
		<input type="submit"
			value="<?php echo esc_attr_x( 'Submit', 'submit button', 'wrt-theme' ); ?>" />

		<!-- Search field -->
		<input itemprop="query-input" type="search" id="search-field" value="<?php echo get_search_query(); ?>"
				placeholder="<?php echo esc_attr_x( 'Find teaching ideas', 'placeholder', 'wrt-theme' ); ?>" name="s" />

		<!-- Grade dropdowns -->
		<div class="pseudo-select grades-select dropdowns-select" aria-controls="grade-dropdown">
			<?php echo esc_html_x( 'Grades', 'label', 'wrt-theme' ); ?>
		</div>
		<div class="pseudo-dropdown grade-dropdown" id="grade-dropdown" aria-hidden="true">
			<span class="select-title"><?php echo esc_html_x( 'Grades', 'label', 'wrt-theme' ); ?></span>
			<?php if ( $grades ) : ?>
				<?php Utility\generate_search_checkbox_elements( $grades, $selected_grade_ids, \WRTPlugin\Taxonomies\GradeTaxonomy::$name ); ?>
			<?php endif; ?>
		</div>
		<!-- .grade-dropdown -->

		<!-- Topic dropdowns -->
		<div class="pseudo-select topic-select dropdowns-select" aria-controls="topic-dropdown">
			<?php echo esc_html_x( 'Topic', 'label', 'wrt-theme' ); ?>
		</div>
		<div class="pseudo-dropdown topic-dropdown" id="topic-dropdown" aria-hidden="true">
			<span class="select-title"><?php echo esc_html_x( 'Topics', 'label', 'wrt-theme' ); ?></span>

			<?php if ( $topics ) : ?>
				<?php Utility\generate_search_checkbox_elements( $topics, $selected_topics, 'Topics' ); ?>
			<?php endif; ?>
		</div>
		<!-- .topic-dropdown -->
	</form>
</div>
<!-- div itemscope itemtype="http://schema.org/WebSite" -->
