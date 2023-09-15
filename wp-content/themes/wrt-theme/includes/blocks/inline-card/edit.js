/**
 * Internal dependencies
 */
import { ContentPicker } from '@10up/block-components';
import DOMPurify from 'dompurify';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { RawHTML } from '@wordpress/element';

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
const InlineCardEdit = (props) => {
	const { attributes, image, post, setAttributes } = props;
	const { post_id, post_uuid } = attributes;

	const blockProps = useBlockProps();

	const selectedPost = [];
	if (post_id) {
		selectedPost.push({
			id: post_id,
			uuid: post_uuid,
			type: 'post',
		});
	}

	return (
		<div {...blockProps} style={{ gap: '20px', paddingLeft: '20px', paddingRight: '20px' }}>
			<InspectorControls>
				<PanelBody title={__('Settings')}>
					<ContentPicker
						content={selectedPost}
						contentTypes={['post']}
						label={__('Please select a post')}
						mode="post"
						onPickChange={(post) => {
							setAttributes({
								post_id: post[0]?.id ?? 0,
								post_uuid: post[0]?.uuid ?? '',
							});
						}}
					/>
				</PanelBody>
			</InspectorControls>

			<div className="wp-block-wrt-topics--image has-flag">
				<img
					src={image?.source_url ?? 'https://placehold.co/400x300'}
					alt={image?.alt_text ?? ''}
				/>
			</div>
			<div className="wp-block-wrt-topics--content">
				<h4>
					<RawHTML>
						{DOMPurify.sanitize(post?.title.rendered ?? __('Post title'))}
					</RawHTML>
				</h4>
				<p>
					<RawHTML>
						{DOMPurify.sanitize(post?.excerpt.rendered ?? __('Post excerpt'))}
					</RawHTML>
				</p>
				{/* eslint-disable-next-line jsx-a11y/anchor-is-valid */}
				<a className="wp-block-button is-style-arrow-icon is-dark">
					<span className="wp-element-button">{__('Learn More')}</span>
				</a>
			</div>
		</div>
	);
};
export default InlineCardEdit;
