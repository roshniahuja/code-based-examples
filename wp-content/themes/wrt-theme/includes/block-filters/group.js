import { registerBlockExtension } from '@10up/block-components';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, SelectControl, RangeControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const blockName = 'core/group';
/**
 * additional block attributes object
 */
const additionalAttributes = {
	allowOverlap: {
		type: 'boolean',
		default: false,
	},
	hasWhiteBars: {
		type: 'boolean',
		default: false,
	},
	whiteBarsShouldRotate: {
		type: 'boolean',
		default: false,
	},
	whiteBarsPosition: {
		type: 'string',
		default: 'center',
	},
	overlapValue: {
		type: 'number',
		default: 0,
	},
	style: {
		type: 'object',
	},
};

/**
 * BlockEdit
 *
 * A react component that will get mounted in the Editor when the block is
 * selected. It is recommended to use Slots like `BlockControls` or `InspectorControls`
 * in here to put settings into the blocks toolbar or sidebar.
 *
 * @param {object} props block props
 * @returns {string}
 */
const BlockEdit = (props) => {
	const { attributes, setAttributes } = props;
	const { allowOverlap, overlapValue, hasWhiteBars, whiteBarsPosition, whiteBarsShouldRotate } =
		attributes;

	return (
		<InspectorControls>
			<PanelBody title={__('Vertical Overlap', 'wrt-theme')} initialOpen>
				<ToggleControl
					label={__('Has White Bars?', 'wrt-theme')}
					help={__(
						'If enabled background will have white bars over the background color',
						'wrt-theme',
					)}
					checked={hasWhiteBars}
					onChange={() => setAttributes({ hasWhiteBars: !hasWhiteBars })}
				/>
				{hasWhiteBars && (
					<>
						<ToggleControl
							label={__('Rotate white bars?', 'wrt-theme')}
							help={__('If enabled white bars will be rotated', 'wrt-theme')}
							checked={whiteBarsShouldRotate}
							onChange={() =>
								setAttributes({ whiteBarsShouldRotate: !whiteBarsShouldRotate })
							}
						/>
						<SelectControl
							label={__('White bars position', 'wrt-theme')}
							value={whiteBarsPosition}
							options={[
								{ label: 'Top', value: 'top' },
								{ label: 'Center', value: 'center' },
								{ label: 'Bottom', value: 'bottom' },
							]}
							onChange={(newSize) => setAttributes({ whiteBarsPosition: newSize })}
						/>
					</>
				)}
				<ToggleControl
					label={__('Use overlap?', 'wrt-theme')}
					help={__(
						"NOTE: Using overlap will overide the block's padding-bottom and margin-bottom settings below",
						'wrt-theme',
					)}
					checked={allowOverlap}
					onChange={() => setAttributes({ allowOverlap: !allowOverlap })}
				/>
				{allowOverlap && (
					<RangeControl
						label={__('Overlap amount', 'wrt-theme')}
						value={overlapValue}
						onChange={(value) => setAttributes({ overlapValue: value })}
						min={0}
						max={1000}
						step={10}
						allowReset
						resetFallbackValue={0}
						withInputField={false}
					/>
				)}
			</PanelBody>
		</InspectorControls>
	);
};

/**
 * generateClassNames
 *
 * A function to generate the new className string that should get added to
 * the wrapping element of the block.
 *
 * @param {object} attributes block attributes
 * @returns {string}
 */
function generateClassNames(attributes) {
	const { allowOverlap, overlapValue, hasWhiteBars, whiteBarsPosition, whiteBarsShouldRotate } =
		attributes;
	let className = '';

	if (allowOverlap && overlapValue) {
		className += ' has-overlap';
	}

	if (hasWhiteBars) {
		className += ' has-white-bars';
	}

	if (whiteBarsPosition) {
		className += ` white-bars-position-${whiteBarsPosition}`;
	}

	if (whiteBarsShouldRotate) {
		className += ' white-bars-should-rotate';
	}

	return className;
}

/**
 * generateInlineStyles
 *
 * A function to generate the new inline styles object that should get added to
 * the wrapping element of the block.
 *
 * @param {object} attributes block attributes
 * @returns {object}
 */
function generateInlineStyles(attributes) {
	const { overlapValue, style } = attributes;
	let newInlineStyles = {};
	const inlineStyles = { ...style };

	newInlineStyles = {
		...inlineStyles,
		'--overlap-amount': overlapValue ? `${overlapValue}px` : 0,
	};

	return newInlineStyles;
}

registerBlockExtension(blockName, {
	extensionName: 'group-extensions',
	attributes: additionalAttributes,
	classNameGenerator: generateClassNames,
	inlineStyleGenerator: generateInlineStyles,
	Edit: BlockEdit,
});
