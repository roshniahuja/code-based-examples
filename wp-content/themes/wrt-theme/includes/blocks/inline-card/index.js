/**
 * Inline card block
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
		const { getEntityRecord, getMedia } = select('core');
		const { post_id } = props.attributes;

		const post = post_id ? getEntityRecord('postType', 'post', post_id) : undefined;

		let image;
		if (post && post.featured_media) {
			image = getMedia(post.featured_media);
		}

		return { post, image };
	})(edit),
	save,
});
