import { __ } from '@wordpress/i18n';
import { useEntityProp } from '@wordpress/core-data';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import { useSponsoredCampaign } from '../../components/useSponsoredCampaign';
import { usePostTags } from '../../components/usePostTags';
import { useGrades } from '../../components/useGrades';
import { SponsorPicker } from '../../components/SponsorPicker';

export const BlockEdit = (props) => {
	const {
		context: { postId, postType },
	} = props;

	const blockProps = useBlockProps({
		className: 'single-post--hero-wrapper',
	});

	const [title, setTitle] = useEntityProp('postType', postType, 'title', postId);
	const [excerpt, setExcerpt] = useEntityProp('postType', postType, 'excerpt', postId);

	// Get the tags.
	const { PostTagsComponent } = usePostTags();

	// Get the grades.
	const { GradesComponent } = useGrades();

	// Get the sponsor.
	const { sponsoredCampaignProps } = useSponsoredCampaign();

	return (
		<div {...blockProps}>
			<div className="feature-card has-flag">
				<div className="feature-card--header">
					<PostTagsComponent />
					<GradesComponent />
				</div>
				<RichText
					value={title}
					onChange={setTitle}
					tagName="h3"
					placeholder={__('Add a title for this post...', 'wrt-theme')}
				/>
				<RichText
					value={excerpt}
					onChange={setExcerpt}
					tagName="p"
					placeholder={__('Add an excerpt for this post...', 'wrt-theme')}
				/>
				<SponsorPicker {...sponsoredCampaignProps} />
			</div>
			<style>{'.edit-post-visual-editor__post-title-wrapper { display: none; }'}</style>
		</div>
	);
};
