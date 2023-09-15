import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

export const BlockEdit = () => {
	const blockProps = useBlockProps({
		className: 'alignfull',
	});

	const postHeaderInnerBlocksProps = useInnerBlocksProps(blockProps, {
		allowedBlocks: [
			'core/group',
			'wrt/post-header-title-card',
			'wrt/post-header-featured-media',
			'wrt/post-sponsor',
		],
		templateLock: 'all',
		template: [
			[
				'core/group',
				{
					tagName: 'header',
					className: 'single-post--hero alignfull',
				},
				[['wrt/post-header-title-card'], ['wrt/post-header-featured-media']],
			],
			[
				'core/group',
				{
					layout: {
						type: 'constrained',
					},
				},
				[['wrt/post-sponsor'], ['core/separator', { backgroundColor: 'gray-light' }]],
			],
		],
	});

	return <div {...postHeaderInnerBlocksProps} />;
};
