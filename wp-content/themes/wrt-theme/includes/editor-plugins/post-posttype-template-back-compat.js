import { store as editorStore } from '@wordpress/editor';
import { useSelect, useDispatch } from '@wordpress/data';
import { store as blockEditorStore } from '@wordpress/block-editor';
import { useEffect } from '@wordpress/element';
import { createBlock } from '@wordpress/blocks';

const PostPostTypeTemplateBackCompat = () => {
	const POST_TYPE = 'post';
	const BLOCK_NAME = 'wrt/post-header';
	const BLOCK_POSITION = 0;
	const { insertBlocks } = useDispatch(blockEditorStore);
	const { postType, blocks } = useSelect((select) => ({
		postType: select(editorStore).getCurrentPostType(),
		blocks: select(blockEditorStore).getBlocks(),
	}));

	useEffect(() => {
		if (postType !== POST_TYPE) {
			return;
		}

		const shouldInsert = blocks.length === 0 || blocks[BLOCK_POSITION]?.name !== BLOCK_NAME;

		if (shouldInsert) {
			insertBlocks(createBlock(BLOCK_NAME), 0);
		}
	}, [insertBlocks, blocks, postType]);
	return null;
};

export { PostPostTypeTemplateBackCompat };
