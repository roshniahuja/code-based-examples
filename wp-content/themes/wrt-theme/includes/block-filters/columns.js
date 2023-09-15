import { registerBlockExtension } from '@10up/block-components';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, PanelRow, RangeControl } from '@wordpress/components';

import { __ } from '@wordpress/i18n';
import classnames from 'classnames';

const blockName = 'core/columns';

/**
 * additional block attributes object
 */
const additionalAttributes = {
	columnCountOnMediumDevices: {
		type: 'number',
		default: 0,
	},
};

/**
 * BlockEdit
 *
 * a react component that will get mounted in the Editor when the block is
 * selected. It is recommended to use Slots like `BlockControls` or `InspectorControls`
 * in here to put settings into the blocks toolbar or sidebar.
 *
 * @param {object} props block props
 * @returns {string}
 */
const BlockEdit = (props) => {
	const { attributes, setAttributes } = props;
	const { columnCountOnMediumDevices } = attributes;

	return (
		<InspectorControls>
			<PanelBody title={__('Columns Responsive Settings', 'chia-theme')}>
				<PanelRow>
					<RangeControl
						label={__('Column count for medium devices', 'chia-theme')}
						help={__(
							'This will only be applicable between 768px to 1279px screen sizes',
							'chia-theme',
						)}
						value={columnCountOnMediumDevices}
						max={6}
						min={1}
						onChange={(newValue) =>
							setAttributes({ columnCountOnMediumDevices: newValue })
						}
					/>
				</PanelRow>
			</PanelBody>
		</InspectorControls>
	);
};

/**
 * generateClassNames
 *
 * a function to generate the new className string that should get added to
 * the wrapping element of the block.
 *
 * @param {object} attributes block attributes
 * @returns {string}
 */
function generateClassNames(attributes) {
	const { columnCountOnMediumDevices } = attributes;
	let className = '';

	if (columnCountOnMediumDevices) {
		className = classnames(className, `column-count-for-medium--${columnCountOnMediumDevices}`);
	}
	return className;
}

registerBlockExtension(blockName, {
	extensionName: 'responsive-columns',
	attributes: additionalAttributes,
	classNameGenerator: generateClassNames,
	Edit: BlockEdit,
});
