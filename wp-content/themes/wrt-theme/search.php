<?php
/**
 * The template for displaying search results pages.
 *
 * @package WRTTheme
 */

use WRTPlugin\Taxonomies\GradeTaxonomy;
use WRTPlugin\Taxonomies\TopicTaxonomy;
use WRTTheme\Utility;

$selected_grade_ids = Utility\get_term_ids_from_url( GradeTaxonomy::$name );
$selected_grades = [];

if ( ! empty( $selected_grade_ids ) ) {
	$selected_grades = get_terms(
		[
			'taxonomy'   => GradeTaxonomy::$name,
			'hide_empty' => false,
			'include'    => $selected_grade_ids,
			'fields'     => 'names',
		]
	);

	if ( is_wp_error( $selected_grades ) ) {
		$selected_grades = [];
	}
}

$selected_topic_ids = Utility\get_term_ids_from_url( TopicTaxonomy::$name );
$selected_topics = [];

if ( ! empty( $selected_topic_ids ) ) {
	$selected_topics = get_terms(
		[
			'taxonomy'   => TopicTaxonomy::$name,
			'hide_empty' => false,
			'include'    => $selected_topic_ids,
			'fields'     => 'names',
		]
	);

	if ( is_wp_error( $selected_topics ) ) {
		$selected_topics = [];
	}
}

$selected_terms = array_merge( $selected_grades, $selected_topics );

get_header(); ?>
	<?php if ( have_posts() ) : ?>
		<section class="search-results-section wp-block-group" itemscope itemtype="https://schema.org/SearchResultsPage">
			<h1 class="search-result__headline">
				<?php
				/* translators: the search query */
				esc_html_e( 'Here are the resources we have for: ', 'wrt-theme' );
				?>
				<span>
					<?php
					echo esc_html( get_search_query() );
					if ( ! empty( get_search_query() ) && ! empty( $selected_terms ) ) {
						echo ', ';
					}
					echo esc_html( implode( ', ', $selected_terms ) );
					?>
				</span>
			</h1>
			<p>
				<?php
				/* translators: 1: link to contact page. */

				printf( wp_kses( __( 'Don\'t see what you\'re looking for? <a href="%1$s">Send us an email</a> and we\'ll add your idea to our list!', 'wrt-theme' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( get_permalink( get_page_by_path( 'contact-weareteachers' ) ) ) );
				?>
			</p>


			<div class="content-collection-items archive-cards">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<?php
					$should_show_byline = true;
					$post_type = get_post_type( get_the_ID() );
					if ( 'wat-hub' === $post_type ) :
						$should_show_byline = false;
					endif;

					get_template_part(
						'partials/post-card/post-card',
						'',
						[
							'post_id'      => get_the_ID(),
							'show_excerpt' => true,
							'title_tag'    => 'h2',
							'show_byline'  => $should_show_byline,
						]
					);
					?>
				<?php endwhile; ?>
			</div>
			<?php
				the_posts_navigation(array(
					'prev_text' => '<div class="nav-previous button is-style-arrow-icon"><span class="wp-element-button">' . __('Older posts') . '</span></div>',
					'next_text' => '<div class="nav-next button is-style-arrow-icon"><span class="wp-element-button">' . __('Newer posts') . '</span></div>',
				));
			?>
		</section>
	<?php else : ?>
		<section class="search-results-section search-results-section_no-results wp-block-group teal-gradient" itemscope itemtype="https://schema.org/SearchResultsPage">
			<header class="page-header">
				<h1 class="page-title">
					<?php
					/* translators: the empty search result text */
					esc_html_e( 'No Results found for: ', 'wrt-theme' );
					?>
					<span>
						<?php
						echo esc_html( get_search_query() );
						if ( ! empty( get_search_query() ) && ! empty( $selected_terms ) ) {
							echo ', ';
						}
						echo esc_html( implode( ', ', $selected_terms ) );
						?>
					</span>
				</h1>
			</header>

			<div class="no-results-found not-found">
				<div class="page-content">
					<p><?php esc_html_e( 'It looks like nothing was found for this search query. Please use the search box below to locate the content you were looking for.', 'wrt-theme' ); ?></p>

					<div class="site-header">
						<div class="header-search--container">
							<div class="search search__filterable header-search">
								<?php Utility\get_wrt_search_form( '404' ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

<?php
get_footer();
