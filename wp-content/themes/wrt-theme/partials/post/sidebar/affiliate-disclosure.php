<?php
/**
 * Sidebar: Affiliate disclosure
 *
 * Must be called within a post loop.
 *
 * @package WRTTheme
 */

$has_affiliate_product_block = has_block( 'wrt/affiliate-product' );
$show_affiliate_link         = get_post_meta( get_the_ID(), 'show-affiliate-link', true );

if ( ! $show_affiliate_link && ( ! $has_affiliate_product_block || ! is_single() ) ) {
	return;
}

if ( ! is_active_sidebar( 'affiliate-disclosure' ) ) {
	return;
}

dynamic_sidebar( 'affiliate-disclosure' );
