<?php
/**
 * Template Name: Section Hub
 *
 * @package WRTTheme
 */

namespace WRTTheme\Utility;

use function WRTTheme\Utility\adjust_brightness;
use function WRTTheme\Utility\get_colors;

get_header();

while ( have_posts() ) :
	the_post();
	the_content();
endwhile;

get_footer();
