import { __ } from '@wordpress/i18n';
import { useEntityProp } from '@wordpress/core-data';
import {
	useBlockProps,
	RichText,
	MediaReplaceFlow,
	BlockControls,
	InspectorControls,
} from '@wordpress/block-editor';
import { TextControl, PanelBody } from '@wordpress/components';
import { useSponsoredCampaign } from '../../components/useSponsoredCampaign';
import { useFeaturedMedia } from '../../components/useFeaturedMedia';

const CAMPAIGN_POST_TYPE = 'wat-campaign';

export const BlockEdit = (props) => {
	const {
		context: { postId, postType },
		noticeUI,
		noticeOperations,
	} = props;

	const { campaign } = useSponsoredCampaign();

	let contextualPostId = 0;
	if (postType === CAMPAIGN_POST_TYPE) {
		contextualPostId = postId;
	} else {
		contextualPostId = campaign.id;
	}

	const blockProps = useBlockProps({
		className: 'single-post--sponsor sponsor-desktop',
	});

	const [meta, setMeta] = useEntityProp('postType', CAMPAIGN_POST_TYPE, 'meta', contextualPostId);
	const [excerpt, setExcerpt] = useEntityProp(
		'postType',
		CAMPAIGN_POST_TYPE,
		'excerpt',
		contextualPostId,
	);
	const {
		ALLOWED_MEDIA_TYPES,
		media,
		FeaturedMediaImage,
		FeaturedMediaPlaceholder,
		PlaceholderChip,
		featuredImage,
		onSelectImage,
		onUploadError,
	} = useFeaturedMedia({
		postType: CAMPAIGN_POST_TYPE,
		postId: contextualPostId,
		noticeOperations,
		noticeUI,
	});

	const onChangeUrl = (value) => {
		setMeta({
			'campaign-url': value,
		});
	};

	const onChangeCTA = (value) => {
		setMeta({
			'campaign-cta': value,
		});
	};

	let image;
	if (!featuredImage) {
		image = <FeaturedMediaPlaceholder {...{ onSelectImage, onUploadError, noticeUI }} />;
	} else {
		image = !media ? (
			<PlaceholderChip />
		) : (
			<FeaturedMediaImage media={media} size="sponsor-logo" />
		);
	}

	// Return an empty block if there is a no campaign unless this is the campaign CPT.
	if (!campaign && postType !== CAMPAIGN_POST_TYPE) {
		return null;
	}

	return (
		<>
			{!!media && (
				<>
					<BlockControls>
						<MediaReplaceFlow
							mediaId={featuredImage}
							mediaURL={media.source_url}
							allowedTypes={ALLOWED_MEDIA_TYPES}
							accept="image/*"
							onSelect={onSelectImage}
							onError={onUploadError}
						/>
					</BlockControls>
					<InspectorControls>
						<PanelBody title={__('Post Sponsor Settings', 'wrt-theme')}>
							<TextControl
								label={__('Campaign Link')}
								value={meta?.['campaign-url'] || ''}
								onChange={onChangeUrl}
							/>
							<TextControl
								label={__('Campaign CTA')}
								value={meta?.['campaign-cta'] || ''}
								onChange={onChangeCTA}
							/>
						</PanelBody>
					</InspectorControls>
				</>
			)}
			<div {...blockProps}>
				<div className="post-card__sponsored-by">
					<div className="s-display-logo">{image}</div>
				</div>
				<RichText
					value={excerpt}
					onChange={setExcerpt}
					tagName="div"
					placeholder={__('Add as description for this campaign...', 'wrt-theme')}
					className="single-post--sponsor-content"
				/>
			</div>
		</>
	);
};
