import { useBlockProps, useInnerBlocksProps, BlockContextProvider } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { Spinner } from '@wordpress/components';

export const BlockEdit = (props) => {
	const {
		attributes: { size = 1, containerClass = '', location },
	} = props;
	const blockProps = useBlockProps({
		className: containerClass,
	});

	const { hasResolved } = useSelect((select) => {
		const selectorArgs = [location];
		return {
			posts: select('wrt/post-picker-data').getAvailablePosts(...selectorArgs),
			hasResolved: select('wrt/post-picker-data').hasFinishedResolution(
				'getAvailablePosts',
				selectorArgs,
			),
		};
	});

	const postPickerGroupInnerBlockProps = useInnerBlocksProps(blockProps, {
		allowedBlocks: ['wrt/post-picker'],
		template: Array(size).fill(['wrt/post-picker', { isCurated: false }]),
	});

	// If we don't have posts yet, just spin until we do.
	if (!hasResolved) {
		return (
			<p {...blockProps}>
				<Spinner />
			</p>
		);
	}

	return (
		<BlockContextProvider
			value={{
				'wrt/postPickerGroup': {
					hasResolved,
					location,
				},
			}}
		>
			<div {...postPickerGroupInnerBlockProps} />
		</BlockContextProvider>
	);
};
