import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export const BlockSave = (props) => {
	const { attributes } = props;
	const { containerClass } = attributes;
	const blockProps = useBlockProps.save({
		className: containerClass,
	});

	return (
		<div {...blockProps}>
			<InnerBlocks.Content />
		</div>
	);
};
