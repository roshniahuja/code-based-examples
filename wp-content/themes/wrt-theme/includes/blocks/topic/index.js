/**
 * Topic block
 */

/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { withSelect } from '@wordpress/data';

/**
 * Internal dependencies
 */
import edit from './edit';
import save from './save';
import block from './block.json';

/**
 * Register block
 */
registerBlockType(block, {
	edit: withSelect((select, props) => {
		return {
			media: props.attributes.icon.id
				? select('core').getMedia(props.attributes.icon.id)
				: undefined,
		};
	})(edit),
	save,
});
