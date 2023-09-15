/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';

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
const AdsBlockEdit = (props) => {
	const { attributes, setAttributes } = props;
	const { ad } = attributes;

	const blockProps = useBlockProps();

	const options = [
		{
			label: __('Header', 'wrt-theme'),
			value: 'weareteachers_leaderboard_atf',
			size: '320x100',
		},
		{
			label: __('Sidebar (big)', 'wrt-theme'),
			value: 'weareteachers_med_rect_atf',
			size: '300x600',
		},
		{
			label: __('Sidebar (small)', 'wrt-theme'),
			value: 'weareteachers_med_rect_btf',
			size: '300x250',
		},
		{
			label: __('Footer (sticky)', 'wrt-theme'),
			value: 'weareteachers_adhesion',
			size: '728x90',
		},
		{
			label: __('Video', 'wrt-theme'),
			value: 'weareteachers_dynamic_incontent',
			size: '468x60',
		},
	];

	const getSizeByValue = (value) => {
		const option = options.find((option) => option.value === value);
		return option ? option.size : null;
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Link to topic', 'wrt-theme')}>
					<SelectControl
						label={__('Ad', 'wrt-theme')}
						value={ad}
						options={options.map(({ size, ...rest }) => rest)}
						onChange={(ad) => setAttributes({ ad })}
						__nextHasNoMarginBottom
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				{ad && (
					<img src={`https://placehold.co/${getSizeByValue(ad)}`} alt="Ad placeholder" />
				)}
			</div>
		</>
	);
};
export default AdsBlockEdit;
