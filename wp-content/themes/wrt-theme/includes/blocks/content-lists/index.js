/**
 * Content Lists block
 */

/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { withSelect } from '@wordpress/data';

/**
 * Internal dependencies
 */
import ContentListsEdit from './edit';
import save from './save';
import block from './block.json';

/**
 * Register block
 */
registerBlockType(block, {
	edit: ContentListsEdit,
  	save,
});
