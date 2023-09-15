import { useBlockProps, RichText, InspectorControls, BlockControls } from '@wordpress/block-editor';
import {
	PanelBody,
	ToggleControl,
	ToolbarGroup,
	ToolbarButton,
	Placeholder,
	Disabled,
} from '@wordpress/components';
import { Optional, Image, Link, MediaToolbar, ContentSearch } from '@10up/block-components';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';

import './editor.css';

const BlockEdit = (props) => {
	const { attributes, setAttributes, name } = props;
	const {
		imgId,
		title,
		description,
		showCaption,
		cardLink,
		cardLinkOpensInNewTab,
		postId,
		insertPost,
		showDescription,
	} = attributes;

	const blockProps = useBlockProps();

	const hasPost = !!postId;

	const [isEditing, setIsEditing] = useState(false);

	const handlePostSelect = (post) => {
		setAttributes({
			postId: post.id,
		});
		setIsEditing(false);
	};

	const shouldShowPlaceholder = isEditing || !hasPost;

	const handleImageSelect = (media) => {
		setAttributes({
			imgId: media.id,
		});
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Settings')}>
					<ToggleControl
						label={__('Insert Post')}
						checked={insertPost}
						onChange={() => setAttributes({ insertPost: !insertPost })}
						help={__(
							'If enabled then post picker will be displayed instead of manual content entry.',
							'wrt-theme',
						)}
					/>
					<ToggleControl
						label={__('Show Caption')}
						checked={showCaption}
						onChange={() => setAttributes({ showCaption: !showCaption })}
						help={__('If enabled caption will be shown bellow the thumbnail.')}
					/>
					<ToggleControl
						label={__('Show Description')}
						checked={showDescription}
						onChange={() => setAttributes({ showDescription: !showDescription })}
						help={__('If enabled description will be shown bellow the title.')}
					/>
				</PanelBody>
			</InspectorControls>
			<BlockControls>
				{insertPost ? (
					<ToolbarGroup>
						<ToolbarButton
							icon="edit"
							label={__('Edit')}
							onClick={() => setIsEditing(!isEditing)}
							isActive={isEditing}
						/>
					</ToolbarGroup>
				) : (
					<MediaToolbar
						id={imgId}
						onSelect={handleImageSelect}
						allowedFormats={['image']}
					/>
				)}
			</BlockControls>
			<div {...blockProps}>
				{insertPost ? (
					<>
						{/* eslint-disable react/jsx-no-useless-fragment */}
						{shouldShowPlaceholder ? (
							<Placeholder label={__('Select a content item', 'wrt')}>
								<ContentSearch
									onSelectItem={handlePostSelect}
									title={__('Select content item', 'wrt')}
									label={__('Search for something:', 'wrt')}
								/>
							</Placeholder>
						) : (
							<Disabled>
								<ServerSideRender block={name} attributes={attributes} />
							</Disabled>
						)}
					</>
				) : (
					<div className="post-card">
						<figure className="post-card__thumbnail">
							<Image
								id={imgId}
								className="wp-block-wrt-content-card__image"
								onSelect={handleImageSelect}
							/>
						</figure>
						<div className="post-card__content">
							<h3 className="post-card__title">
								{/* eslint-disable jsx-a11y/anchor-is-valid */}
								<Link
									allowedFormats={[]}
									placeholder={__('Headline')}
									value={title}
									url={cardLink}
									opensInNewTab={cardLinkOpensInNewTab}
									onTextChange={(value) => setAttributes({ title: value })}
									onLinkRemove={() => {
										setAttributes({
											cardLink: null,
											cardLinkOpensInNewTab: null,
											title: '',
										});
									}}
									onLinkChange={(value) =>
										setAttributes({
											cardLink: value.url,
											cardLinkOpensInNewTab: value.opensInNewTab,
											title: value.title,
										})
									}
								/>
							</h3>
							{showDescription && (
								<Optional value={description}>
									<RichText
										tagName="p"
										className="post-card__description"
										value={description}
										onChange={(value) => setAttributes({ description: value })}
										placeholder={__('Description')}
									/>
								</Optional>
							)}
						</div>
					</div>
				)}
			</div>
		</>
	);
};

export default BlockEdit;
