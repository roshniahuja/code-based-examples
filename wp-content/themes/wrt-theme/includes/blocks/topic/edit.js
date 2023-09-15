/**
 * Internal dependencies
 */
import { ContentPicker } from '@10up/block-components';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { Button, PanelBody, ResponsiveWrapper } from '@wordpress/components';

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
const TopicBlockEdit = (props) => {
	const { attributes, media, setAttributes } = props;
	const { description, icon, title, topic } = attributes;

	const blockProps = useBlockProps();

	const onSelectMedia = (media) => {
		setAttributes({
			icon: {
				id: media.id,
				url: media.url,
				alt: media.alt,
			},
		});
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Topic icon')} className="editor-post-featured-image">
					<MediaUploadCheck>
						<MediaUpload
							onSelect={onSelectMedia}
							allowedTypes={['image']}
							value={icon.id}
							render={({ open }) => (
								<Button
									onClick={open}
									className={
										icon.id === 0
											? 'editor-post-featured-image__toggle'
											: 'editor-post-featured-image__preview'
									}
								>
									{media !== undefined && (
										<ResponsiveWrapper
											naturalWidth={media.media_details.width}
											naturalHeight={media.media_details.height}
										>
											<img src={media.source_url} alt={icon.alt} />
										</ResponsiveWrapper>
									)}
									{icon.id === 0 && __('Choose an image')}
								</Button>
							)}
						/>
					</MediaUploadCheck>

					{icon.id !== 0 && (
						<>
							<MediaUploadCheck>
								<MediaUpload
									title={__('Replace image')}
									value={icon.id}
									onSelect={onSelectMedia}
									allowedTypes={['image']}
									render={({ open }) => (
										<Button onClick={open} variant="secondary">
											{__('Replace image')}
										</Button>
									)}
								/>
							</MediaUploadCheck>
							<MediaUploadCheck>
								<Button
									onClick={() => {
										setAttributes({
											icon: {
												id: 0,
												url: '',
												alt: '',
											},
										});
									}}
									isLink
									isDestructive
								>
									{__('Remove image')}
								</Button>
							</MediaUploadCheck>
						</>
					)}
				</PanelBody>
				<PanelBody title={__('Link to topic')}>
					<ContentPicker
						content={topic ? [topic] : []}
						contentTypes={['wat-subject']}
						label={__('Please select a topic')}
						mode="term"
						onPickChange={(topics) => setAttributes({ topic: topics[0] })}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				{icon && <img src={icon.url} alt={icon.alt} />}
				<RichText
					tagName="h4"
					placeholder={__('Arts')}
					value={title}
					onChange={(title) => setAttributes({ title })}
					allowedFormats={[]}
				/>
				<RichText
					tagName="p"
					placeholder={__(
						'Et nibh viverra sed cras. Sit eu ac ultricies vitae nisl. Commodo.',
					)}
					value={description}
					onChange={(description) => setAttributes({ description })}
					allowedFormats={[]}
				/>
			</div>
		</>
	);
};
export default TopicBlockEdit;
