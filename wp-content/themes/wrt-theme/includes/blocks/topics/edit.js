/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { RichText, useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import { Flex, FlexBlock, FlexItem } from '@wordpress/components';

const ALLOWED_BLOCKS = ['wrt/topic'];

const TEMPLATE = [
	['wrt/topic'],
	['wrt/topic'],
	['wrt/topic'],
	['wrt/topic'],
	['wrt/topic'],
	['wrt/topic'],
];

/**
 * Edit component.
 * See https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-edit-save/#edit
 *
 * @param {object}   props                  The block props.
 * @param {object}   props.attributes       Block attributes.
 * @param {string}   props.attributes.title Custom title to be displayed.
 * @param {string}   props.className        Class name for the block.
 * @param {Function} props.setAttributes    Sets the value for block attributes.
 * @returns {Function} Render the edit screen
 */
const TopicsBlockEdit = (props) => {
	const { attributes, setAttributes } = props;
	const { description, eyebrow, title } = attributes;

	const blockProps = useBlockProps();

	const innerBlocksProps = useInnerBlocksProps(blockProps, {
		allowedBlocks: ALLOWED_BLOCKS,
		orientation: 'horizontal',
		renderAppender: false,
		template: TEMPLATE,
		templateLock: 'insert',
	});

	return (
		<div {...blockProps}>
			<div className="topics__header">
				<Flex>
					<FlexItem>
						<RichText
							className="topics__eyebrow"
							tagName="small"
							placeholder={__('Elementary')}
							value={eyebrow}
							onChange={(eyebrow) => setAttributes({ eyebrow })}
						/>
						<RichText
							tagName="h2"
							placeholder={__('Teaching Topics')}
							value={title}
							onChange={(title) => setAttributes({ title })}
						/>
					</FlexItem>
					<FlexBlock>
						<RichText
							className="wp-block-example-block__title"
							tagName="p"
							placeholder={__(
								'This is where the teaching magic happens. These classroom ideas, teaching strategies, and actionable tips from brilliant teachers (like you) will inspire you, answer your burning questions, and help you be the best teacher you can be!',
							)}
							value={description}
							onChange={(description) => setAttributes({ description })}
						/>
					</FlexBlock>
				</Flex>
			</div>

			<div {...innerBlocksProps} className="topics__content" />
		</div>
	);
};
export default TopicsBlockEdit;
