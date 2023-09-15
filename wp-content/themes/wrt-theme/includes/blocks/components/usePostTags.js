import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import { PostTaxonomiesFlatTermSelector, store as editorStore } from '@wordpress/editor';
import { useBlockProps } from '@wordpress/block-editor';
import { usePopover } from './usePopover';

const TAXONOMY_SLUG = 'post_tag';
const ATTRIBUTE_SLUG = 'tags';

export const DumbPostTagComponent = (props) => {
	const blockProps = useBlockProps({
		className: 'post-tags',
	});

	const { selectedTags } = props;

	const { toggleProps, Popover } = usePopover();
	const hasSelectedTags = selectedTags && selectedTags.length > 0;
	return (
		<div className="post-tags" {...blockProps} {...toggleProps}>
			{hasSelectedTags && selectedTags.map((tag) => <span key={tag.id}>{tag.name}</span>)}
			{!hasSelectedTags && <span>{__('Add a tag...', 'wrt-theme')}</span>}
			<Popover>
				<div style={{ minWidth: '250px' }}>
					<PostTaxonomiesFlatTermSelector slug="post_tag" />
				</div>
			</Popover>
		</div>
	);
};

export const usePostTags = () => {
	const tagsProps = useSelect((select) => {
		const { getEditedPostAttribute } = select(editorStore);
		const { getEntityRecords } = select('core');

		// Get the evergreen terms.
		const tags = getEntityRecords('taxonomy', TAXONOMY_SLUG, {
			per_page: -1,
		});

		const selectedTagIds = getEditedPostAttribute(ATTRIBUTE_SLUG);

		return {
			TAXONOMY_SLUG,
			ATTRIBUTE_SLUG,
			tags,
			selectedTags: tags?.filter((tag) => selectedTagIds?.includes(tag.id)) || [],
		};
	});

	const PostTagsComponent = (props) => <DumbPostTagComponent {...tagsProps} {...props} />;

	return {
		...tagsProps,
		PostTagsComponent,
	};
};
