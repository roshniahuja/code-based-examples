/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

/**
 * Register custom Button styles.
 */
wp.domReady(() => {
	wp.blocks.registerBlockStyle('core/button', {
		name: 'secondary',
		label: __('Secondary', 'psx'),
		isDefault: false,
	});

	wp.blocks.registerBlockStyle('core/button', {
		name: 'arrow-icon',
		label: __('Arrow Icon', 'psx'),
		isDefault: false,
	});

	wp.blocks.registerBlockStyle('core/button', {
		name: 'arrow-icon-download',
		label: __('Arrow Icon (Download)', 'psx'),
		isDefault: false,
	});

	wp.blocks.unregisterBlockStyle('core/button', 'outline');
});
