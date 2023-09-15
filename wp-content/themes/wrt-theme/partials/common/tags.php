<?php
/**
 * Topic template
 *
 * Must be included withing the loop
 *
 * @package WRTTheme
 */

$primary_topic_term;

if ( function_exists( 'yoast_get_primary_term_id' ) ) {
	$primary_topic_term = get_term( yoast_get_primary_term_id( \WRTPlugin\Taxonomies\TopicTaxonomy::$name, get_the_ID() ) );
}

if ( is_wp_error( $primary_topic_term ) || empty( $primary_topic_term ) ) {
	return;
}
?>

<div class="post-tags">
	<a href="<?php echo esc_url( get_term_link( $primary_topic_term->term_id ) ); ?>" rel="primary-topic">
		<?php echo esc_html( $primary_topic_term->name ); ?>
	</a>
</div>
