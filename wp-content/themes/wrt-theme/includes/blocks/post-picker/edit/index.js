import { useBlockProps, BlockControls } from '@wordpress/block-editor';
import { Placeholder, ToolbarGroup, ToolbarButton, Disabled, Spinner } from '@wordpress/components';
import { ContentSearch } from '@10up/block-components';
import { useState, useEffect, useMemo } from '@wordpress/element';
import { useSelect, useDispatch } from '@wordpress/data';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

export const BlockEdit = (props) => {
	const { attributes, setAttributes, name, context, clientId } = props;
	const { isCurated, containerClass = '' } = attributes;
	const blockProps = useBlockProps({
		className: containerClass,
	});

	// These contextual settings are defined in the parent block as variations.
	const contextualContentTypes = context['wrt/postPickerContentTypes'] || ['post'];
	const contextualMode = context['wrt/postPickerMode'] || ['post'];
	const contextualPartial = context['wrt/postPickerPartial'] || '';
	const contextualChildrenClass = context['wrt/postPickerClassName'] || '';
	const contextualShowExcerpt = context['wrt/postPickerShowExcerpt'] || false;
	const { hasResolved, location } = context['wrt/postPickerGroup'];

	const [isEditing, setIsEditing] = useState(false);

	// Get the dispatcher
	const { reservePost } = useDispatch('wrt/post-picker-data');

	// Set attributes when an editor curates this position.
	const handlePostSelect = (post) => {
		setAttributes({
			postId: post.id,
			partial: contextualPartial,
			containerClass: contextualChildrenClass,
			showExcerpt: contextualShowExcerpt,
			isCurated: true,
		});
		setIsEditing(false);
	};

	// Maybe reserve a post from the post pool.
	useEffect(() => {
		if (!isCurated && hasResolved) {
			reservePost(clientId, location);
		}
	}, [isCurated, hasResolved, clientId, reservePost, location]);

	// Retrieve a post to use as the preview, if reserved.
	const previewPost = useSelect((select) => {
		const previewPost = select('wrt/post-picker-data').getClientPost(clientId, location);
		return previewPost;
	});

	const removeCuration = () => {
		setAttributes({
			postId: previewPost?.ID || 0,
			partial: contextualPartial,
			containerClass: contextualChildrenClass,
			showExcerpt: contextualShowExcerpt,
			isCurated: false,
		});
		setIsEditing(false);
	};

	// Decorate the attributes for editor previewing.
	const previewAttributes = useMemo(() => {
		return {
			postId: previewPost?.ID || 0,
			partial: contextualPartial,
			containerClass: contextualChildrenClass,
			...attributes,
		};
	}, [previewPost, contextualPartial, contextualChildrenClass, attributes]);

	const shouldShowPlaceholder = isEditing;

	return (
		<>
			<BlockControls>
				<ToolbarGroup>
					<ToolbarButton
						icon="edit"
						label="Edit"
						onClick={() => setIsEditing(!isEditing)}
						isActive={isEditing}
					/>
					{isCurated && (
						<ToolbarButton
							icon="minus"
							label="Remove Curation"
							onClick={() => removeCuration()}
							isActive={isEditing}
						/>
					)}
				</ToolbarGroup>
			</BlockControls>
			<div {...blockProps}>
				{shouldShowPlaceholder ? (
					<Placeholder label={__('Select a content item', 'wrt')}>
						<ContentSearch
							onSelectItem={handlePostSelect}
							title={__('Select content item', 'wrt')}
							label={__('Search for something:', 'wrt')}
							mode={contextualMode}
							contentTypes={contextualContentTypes}
						/>
					</Placeholder>
				) : (
					<Disabled>
						{previewAttributes.postId ? (
							<ServerSideRender block={name} attributes={previewAttributes} />
						) : (
							<Spinner />
						)}
					</Disabled>
				)}
			</div>
		</>
	);
};
