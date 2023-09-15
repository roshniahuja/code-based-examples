import { useBlockProps, MediaReplaceFlow, BlockControls } from '@wordpress/block-editor';
import { useFeaturedMedia } from '../../components/useFeaturedMedia';

export const BlockEdit = (props) => {
	const {
		context: { postId, postType },
		noticeUI,
		noticeOperations,
	} = props;

	const blockProps = useBlockProps({
		className: 'single-post--hero-image-container',
	});

	// Get the featured media.
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
		postType,
		postId,
		noticeOperations,
		noticeUI,
	});

	let image;
	if (!featuredImage) {
		image = <FeaturedMediaPlaceholder {...{ onSelectImage, onUploadError, noticeUI }} />;
	} else {
		image = !media ? <PlaceholderChip /> : <FeaturedMediaImage media={media} />;
	}

	return (
		<>
			{!!media && (
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
			)}
			<div {...blockProps}>{image}</div>
		</>
	);
};
