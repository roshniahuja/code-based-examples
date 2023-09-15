<?php
/**
 * Section Inline Card Partial
 *
 * @package WRTTheme
 */

use function WRTTheme\Utility\get_archive_inline_card;

$inline_card_id = get_archive_inline_card();

if ( ! $inline_card_id ) {
	return;
}
?>

<div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>

<?php
get_template_part(
	'partials/post-card/post-card',
	'inline',
	array(
		'post_id' => $inline_card_id,
	)
);
