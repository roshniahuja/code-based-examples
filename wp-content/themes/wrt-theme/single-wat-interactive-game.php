<?php
/**
 * The template for displaying all interactive games
 *
 * @package WRTTheme
 */

namespace WRTTheme\Utility;

$post_id   = get_queried_object_id();
$game_path = get_post_meta( $post_id, 'game_path', true );

get_header( 'minimal' );
?>

<iframe
	title="<?php echo esc_attr( get_the_title( $post_id ) ); ?>"
	src="<?php echo esc_url( $game_path ); ?>"
	class="interactive-fullscreen alignfull"></iframe>

<?php
get_footer( 'minimal' );
