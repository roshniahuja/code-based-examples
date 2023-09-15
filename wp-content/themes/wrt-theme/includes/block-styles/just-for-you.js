/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

/**
 * Register custom Button styles.
 */
wp.domReady(() => {
	wp.blocks.registerBlockStyle('wrt/post-picker-group', {
		name: 'secondary',
		label: __('Secondary', 'wrt'),
		isDefault: false,
	});
});
