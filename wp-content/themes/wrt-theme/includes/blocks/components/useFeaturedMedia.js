import { __ } from '@wordpress/i18n';
import { useEntityProp, store as coreStore } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { BlockIcon, MediaPlaceholder } from '@wordpress/block-editor';
import { Icon as WordPressIcon, Spinner } from '@wordpress/components';
import { postFeaturedImage } from '@wordpress/icons';

const ALLOWED_MEDIA_TYPES = ['image'];

export const FeaturedMediaPlaceholder = (props) => {
	const { onSelectImage, onUploadError, noticeUI, title, instructions } = props;
	return (
		<MediaPlaceholder
			style={{ height: '100%' }}
			icon={<BlockIcon icon={postFeaturedImage} />}
			onSelect={onSelectImage}
			notices={noticeUI}
			onError={onUploadError}
			accept="image/*"
			allowedTypes={ALLOWED_MEDIA_TYPES}
			labels={{
				title: title || __('Featured Image', 'wrt-theme'),
				instructions:
					instructions ||
					__('Upload a media file or pick one from your media library.', 'wrt-theme'),
			}}
		/>
	);
};

export const PlaceholderChip = (props) => {
	const { className } = props;
	return (
		<div
			className={className}
			style={{
				height: '100%',
				backgroundColor: '#E3E3E3',
				display: 'flex',
				alignItems: 'center',
				flexDirection: 'column',
				justifyContent: 'center',
			}}
		>
			<WordPressIcon icon={postFeaturedImage} />
			<p>{__('Loading the Featured Image', 'wrt-theme')}</p>
			<Spinner />
		</div>
	);
};

export const FeaturedMediaImage = (props) => {
	const { media, size = 'full' } = props;
	const src = media?.media_details?.sizes?.[size]?.source_url || media?.source_url || '';
	return <img className="single-post--hero-image wp-post-image" src={src} alt="logo" />;
};

export const useFeaturedMedia = ({ postType, postId, noticeUI, noticeOperations }) => {
	const [featuredImage, setFeaturedImage] = useEntityProp(
		'postType',
		postType,
		'featured_media',
		postId,
	);

	const media = useSelect(
		(select) => featuredImage && select(coreStore).getMedia(featuredImage, { context: 'view' }),
		[featuredImage],
	);

	const onSelectImage = (value) => {
		if (value && value.id) {
			setFeaturedImage(value.id);
		}
	};

	const onUploadError = (message) => {
		noticeOperations.removeAllNotices();
		noticeOperations.createErrorNotice(message);
	};

	return {
		ALLOWED_MEDIA_TYPES,
		featuredImage,
		setFeaturedImage,
		media,
		noticeUI,
		FeaturedMediaImage,
		PlaceholderChip,
		FeaturedMediaPlaceholder,
		onUploadError,
		onSelectImage,
	};
};
