<?php
/**
 * Single post sharing
 *
 * Must be included within the posts query loop.
 *
 * @package WRTTheme
 */

use function WRTTheme\Template_Tags\get_share_url;

?>

<footer class="single-post--meta">
	<?php get_template_part( 'partials/common/reads' ); ?>

	<div class="single-post--share">
		<p><?php esc_html_e( 'Share this article', 'wrt-theme' ); ?></p>
		<div class="single-post--share-icons">
			<a href="<?php echo esc_url( get_share_url( 'facebook' ) ); ?>" class="share-icon share-facebook" target="_blank"></a>
			<a href="<?php echo esc_url( get_share_url( 'twitter' ) ); ?>" class="share-icon share-twitter" target="_blank"></a>
			<a href="<?php echo esc_url( get_share_url( 'linkedin' ) ); ?>" class="share-icon share-linkedin" target="_blank"></a>
			<a href="<?php echo esc_url( get_share_url( 'pintrest' ) ); ?>" class="share-icon share-pintrest" target="_blank"></a>
			<a href="<?php echo esc_url( get_share_url( 'email' ) ); ?>" class="share-icon share-email" target="_blank"></a>
		</div>
	</div>
</footer>
