<?php
/**
 * Template Name: Interactive Game
 *
 * @package WRTTheme
 */

namespace WRTTheme\Utility;

get_header('minimal');

while ( have_posts() ) :
	the_post();
	the_content();
endwhile; // End of the loop.

get_footer('minimal');
