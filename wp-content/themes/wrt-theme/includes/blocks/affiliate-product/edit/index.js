import { __ } from '@wordpress/i18n';
import { useState, useRef } from '@wordpress/element';
import { dispatch } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';
import {
	useBlockProps,
	RichText,
	PlainText,
	BlockControls,
	InspectorControls,
	MediaReplaceFlow,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalLinkControl as LinkControl,
} from '@wordpress/block-editor';
import { Button, PanelBody, ToggleControl, Popover } from '@wordpress/components';
import { ContentSearch } from '@10up/block-components';
import { useFeaturedMedia } from '../../components/useFeaturedMedia';
import { useGrades } from '../../components/useGrades';
import { useOnClickOutside } from '../../components/useOnClickOutside';

const PRODUCT_POST_TYPE = 'wat-product';
const GRADES_TAXONOMY = 'wat-grade';

export const BlockEdit = (props) => {
	const {
		context: { postId, postType },
		attributes: { productPostId, showAttribution },
		setAttributes,
		noticeUI,
		noticeOperations,
		isSelected,
	} = props;

	const blockProps = useBlockProps();

	const [isPopoverVisible, setIsPopoverVisible] = useState(false);
	const openPopover = () => setIsPopoverVisible(true);
	const closePopover = () => setIsPopoverVisible(false);

	const linkRef = useRef();
	const popoverRef = useOnClickOutside(closePopover);

	const contextualPostId = postType === PRODUCT_POST_TYPE ? postId : productPostId;
	const isProductEditScreen = postType === PRODUCT_POST_TYPE;

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
		postType: PRODUCT_POST_TYPE,
		postId: contextualPostId,
		noticeOperations,
		noticeUI,
	});

	const { GradesComponent, selectedGrades } = useGrades();
	const [title, setTitle] = useEntityProp(
		'postType',
		PRODUCT_POST_TYPE,
		'title',
		contextualPostId,
	);
	const [meta, setMeta] = useEntityProp('postType', PRODUCT_POST_TYPE, 'meta', contextualPostId);
	const [, setSlug] = useEntityProp('postType', PRODUCT_POST_TYPE, 'slug', contextualPostId);

	let image;
	if (!featuredImage) {
		image = <FeaturedMediaPlaceholder {...{ onSelectImage, onUploadError, noticeUI }} />;
	} else {
		image = !media ? <PlaceholderChip /> : <FeaturedMediaImage media={media} size="full" />;
	}

	const onChangeTitle = (value) => {
		setTitle(value);

		// We can change the slug when the title changes because products are not public.
		setSlug(value);
	};

	const onChangeAttribution = (value) => {
		setMeta({
			attribution: value,
		});
	};

	const onChangeCTA = (value) => {
		setMeta({
			affiliate_cta_text: value,
		});
	};

	const onChangeUrl = (value) => {
		setMeta({
			affiliate_link: value?.url,
		});
	};

	const onSelectAffiliateProduct = (item) => {
		setAttributes({
			productPostId: item.id,
		});
	};

	const onAddNewAffiliateProduct = async () => {
		const newProduct = await dispatch('core').saveEntityRecord('postType', PRODUCT_POST_TYPE, {
			title: __(`New Affiliate Product`, 'wrt-theme'),
			content: '<!-- wp:wrt/affiliate-product /-->',
			status: 'publish',
			[GRADES_TAXONOMY]: selectedGrades.map(({ id }) => id),
		});
		onSelectAffiliateProduct(newProduct);
	};

	if (!contextualPostId) {
		return (
			<div>
				<ContentSearch
					onSelectItem={onSelectAffiliateProduct}
					mode="post"
					label={__('Select an affiliate product', 'wrt-theme')}
					contentTypes={[PRODUCT_POST_TYPE]}
					placeholder={__('Search for an affiliate product...', 'wrt-theme')}
				/>
				<Button variant="link" onClick={onAddNewAffiliateProduct}>
					{__('Add a New Affiliate Product', 'wrt-theme')}
				</Button>
			</div>
		);
	}

	return (
		<>
			<BlockControls>
				<MediaReplaceFlow
					mediaId={featuredImage}
					mediaURL={media?.source_url}
					allowedTypes={ALLOWED_MEDIA_TYPES}
					accept="image/*"
					onSelect={onSelectImage}
					onError={onUploadError}
				/>
			</BlockControls>
			<InspectorControls>
				<PanelBody title={__('Affiliate Product Block Settings', 'wrt-theme')}>
					<ToggleControl
						label="Show Attribution Byline"
						checked={showAttribution}
						onChange={() => {
							setAttributes({ showAttribution: !showAttribution });
						}}
					/>
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<article className="post-card">
					<figure className="post-card__thumbnail">{image}</figure>
					<div className="post-card__content">
						{isProductEditScreen ? (
							<GradesComponent />
						) : (
							<div className="post-card__grades">
								<span>{__('Grades:', 'wrt-theme')} </span>
								{selectedGrades.map(({ id, name }) => (
									<span key={id}>{name}</span>
								))}
							</div>
						)}
						<RichText
							value={title}
							onChange={onChangeTitle}
							tagName="h3"
							placeholder={__('Add a title for this product...', 'wrt-theme')}
						/>
						<div className="post-card__byline post-card__byline--justified">
							<div className="post-card__posted-by">
								{isSelected && showAttribution && (
									<RichText
										value={meta?.attribution}
										onChange={onChangeAttribution}
										tagName="span"
										placeholder={__('Add attribution byline...', 'wrt-theme')}
									/>
								)}
								{!isSelected && showAttribution && meta?.attribution}
							</div>
						</div>
						<div className="post-card__buynow">
							<span className="learn-more post-card__learn-more">
								<PlainText
									value={meta?.affiliate_cta_text}
									onChange={onChangeCTA}
									placeholder={__('Add affiliate call to action...', 'wrt-theme')}
									onClick={openPopover}
								/>
								{isPopoverVisible && (
									<Popover
										anchorRef={linkRef.current}
										anchor={linkRef.current}
										ref={popoverRef}
										focusOnMount={false}
									>
										<LinkControl
											hasTextControl
											value={{
												url: meta?.affiliate_link,
												title: meta?.affiliate_cta_text,
											}}
											showInitialSuggestions={false}
											showSuggestions={false}
											onChange={onChangeUrl}
											onRemove={() => {
												onChangeUrl('');
											}}
										/>
									</Popover>
								)}
							</span>
						</div>
					</div>
					{isProductEditScreen && (
						<style>
							{'.edit-post-visual-editor__post-title-wrapper { display: none; }'}
						</style>
					)}
				</article>
			</div>
		</>
	);
};
