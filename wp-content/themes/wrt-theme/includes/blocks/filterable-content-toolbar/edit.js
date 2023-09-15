/**
 * Internal dependencies
 */
import { ContentPicker } from '@10up/block-components';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';

/**
 * Edit component.
 * See https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-edit-save/#edit
 *
 * @param {object}   props                     The block props.
 * @param {object}   props.attributes          Block attributes.
 * @param {string}   props.attributes.category Custom category.
 * @param {Function} props.setAttributes       Sets the value for block attributes.
 *
 * @returns {Function} Render the edit screen
 */
const FilterableContentToolbarEdit = (props) => {
	const { attributes, setAttributes } = props;
	const { category } = attributes;

	const blockProps = useBlockProps();

	return (
		<div {...blockProps}>
			<InspectorControls>
				<PanelBody title={__('Settings')}>
					<ContentPicker
						content={category ? [category] : []}
						contentTypes={['category']}
						label={__('Please select a category')}
						mode="term"
						onPickChange={(categories) => setAttributes({ category: categories[0] })}
					/>
				</PanelBody>
			</InspectorControls>

			<div className="wp-block-wrt-filterable-content-toolbar__content">
				<div className="wp-block-wrt-filterable-content-toolbar__content__label">
					{__('Narrow by:')}
				</div>

				<div
					className="pseudo-select teaching-topic"
					aria-controls="filter-teaching-dropdown"
				>
					{__('Teaching Topic')}
				</div>
				<div
					className="pseudo-dropdown filter-teaching-dropdown"
					id="filter-teaching-dropdown"
					aria-hidden="true"
					aria-expanded="false"
				/>

				<div className="pseudo-select grade-topic" aria-controls="filter-grade-dropdown">
					{__('Grade')}
				</div>
				<div
					className="pseudo-dropdown filter-grade-dropdown"
					id="filter-grade-dropdown"
					aria-hidden="true"
					aria-expanded="false"
				/>

				<button
					className="wp-block-wrt-filterable-content-toolbar__content__button button button-secondary"
					type="button"
				>
					{__('Go!')}
				</button>
			</div>
		</div>
	);
};
export default FilterableContentToolbarEdit;
