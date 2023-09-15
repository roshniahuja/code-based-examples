import { register, createReduxStore } from '@wordpress/data';
import { registerBlockType } from '@wordpress/blocks';
import { layout } from '@wordpress/icons';
import { BlockEdit } from './edit/index';
import { BlockSave } from './save/index';
import metadata from './block.json';
import { STORE_NAME, STORE_CONFIG } from './data';

const store = createReduxStore(STORE_NAME, STORE_CONFIG);
register(store);

registerBlockType(metadata, {
	icon: layout,
	edit: BlockEdit,
	save: BlockSave,
});
