import { addFilter } from '@wordpress/hooks';

function modifyQuoteBlockOptions(settings, name) {
	if (name !== 'core/quote') {
		return settings;
	}

	return {
		...settings,
		supports: {
			...settings.supports,
			align: true,
		},
	};
}

addFilter('blocks.registerBlockType', 'modify-quote-block-options', modifyQuoteBlockOptions);
