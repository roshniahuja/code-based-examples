import { useBlockProps, BlockControls } from '@wordpress/block-editor';
import { Placeholder, ToolbarGroup, ToolbarButton, Disabled } from '@wordpress/components';
import { ContentSearch } from '@10up/block-components';
import { useState } from '@wordpress/element';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

export const BlockEdit = (props) => {
	const { attributes, setAttributes, name } = props;
	const { postId, containerClass = '' } = attributes;
	const blockProps = useBlockProps({
		className: containerClass,
	});
	const hasPost = !!postId;

	const [isEditing, setIsEditing] = useState(false);

	const handlePostSelect = (post) => {
		setAttributes({
			postId: post.id,
		});
		setIsEditing(false);
	};

	const shouldShowPlaceholder = isEditing || !hasPost;

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
				</ToolbarGroup>
			</BlockControls>
			<div {...blockProps}>
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
			</div>
		</>
	);
};
