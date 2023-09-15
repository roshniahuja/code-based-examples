/**
 * Internal dependencies
 */
import { ContentPicker, Link } from '@10up/block-components';
import DOMPurify from 'dompurify';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { InspectorControls, RichText, useBlockProps } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import {
	PanelBody,
	PanelRow,
	ToggleControl,
	SelectControl,
	TextControl,
} from '@wordpress/components';
import { RawHTML, Fragment, useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import ServerSideRender from '@wordpress/server-side-render';
import { decodeEntities } from '@wordpress/html-entities';

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
const ContentListsEdit = (props) => {
	const { attributes, name, setAttributes } = props;
	const { cta_show, cta_text, cta_url, title, posts, curate_these_posts, category, topic } =
		attributes;

	const [categories, setCategories] = useState([]);
	const [topicsCategories, setTopicsCategories] = useState([]);

	const blockProps = useBlockProps();

	// Fetch categories
	const fetchCategories = () => {
		apiFetch({ path: '/wp/v2/categories' })
			.then((response) => {
				const fetchedCategories = response.map((category) => ({
					value: category.id,
					label: decodeEntities(category.name),
				}));
				setCategories(fetchedCategories);
			})
			.catch((error) => {
				console.error('Error fetching categories:', error);
			});
	};

	// Fetch topics categories
	const fetchTopicsCategories = () => {
		apiFetch({ path: '/wp/v2/wat-subject' })
			.then((response) => {
				const fetchedTopicsCategories = response.map((category) => ({
					value: category.id,
					label: decodeEntities(category.name),
				}));
				setTopicsCategories(fetchedTopicsCategories);
			})
			.catch((error) => {
				console.error('Error fetching topics categories:', error);
			});
	};

	useEffect(() => {
		fetchCategories();
		fetchTopicsCategories();
	}, []);

	useEffect(() => {
		if (!curate_these_posts) {
			setAttributes({ posts: [] });
		} else {
			setAttributes({ topic: '', category: '' });
		}
	}, [curate_these_posts, setAttributes]);

	// Get selected posts with author info
	const { selectedPosts } = useSelect(
		(select) => {
			const { getEntityRecords, getUser } = select('core');
			const postIds = posts ? posts.map((post) => post.id) : [];
			let selectedPosts = posts
				? getEntityRecords('postType', 'post', { include: postIds, orderby: 'include' })
				: [];

			if (selectedPosts) {
				selectedPosts = selectedPosts.map((post) => ({
					...post,
					author: getUser(post.author, { context: 'view' }),
				}));
			}

			return { selectedPosts };
		},
		[posts],
	);

	/**
	 * Convert the WordPress date into the `M y` format.
	 *
	 * @param {string} dateString WordPress date.
	 *
	 * @returns {string}
	 */
	const convertDate = (dateString) => {
		const date = new Date(dateString);
		return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
	};

	/**
	 * Print posts.
	 *
	 * @returns {Element|null}
	 */
	const printPosts = () => {
		if (selectedPosts) {
			return selectedPosts.map((post) => (
				<div className="wp-block-wrt-content-list-item" key={post.id}>
					<h4 className="content-list__title">{post.title.raw}</h4>
					<div className="content-list__content">
						<RawHTML>{DOMPurify.sanitize(post.excerpt.rendered)}</RawHTML>
					</div>
					<div className="content-list__meta">
						{convertDate(post.date)}
						<span>/</span>
						{__('BY')}&nbsp;
						{/* eslint-disable-next-line jsx-a11y/anchor-is-valid */}
						<a href="#">{post.author?.name}</a>
					</div>
				</div>
			));
		}

		return null;
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Curate these posts')}>
					<ToggleControl
						checked={curate_these_posts}
						label={__('Curate these posts')}
						onChange={() => setAttributes({ curate_these_posts: !curate_these_posts })}
					/>
					{curate_these_posts && (
						<PanelRow>
							<ContentPicker
								content={posts}
								contentTypes={['post']}
								isOrderable
								label={__('Please select a post')}
								maxContentItems={3}
								mode="post"
								onPickChange={(posts) => setAttributes({ posts })}
							/>
						</PanelRow>
					)}
					{!curate_these_posts && (
						<Fragment>
							<div>
								<TextControl
									label={__('Title')}
									value={decodeEntities(title)}
									onChange={(value) => {
										setAttributes({ title: value });
									}}
								/>
							</div>
							<PanelRow>
								<SelectControl
									label={__('Select a Category', 'wrt-theme')}
									value={category}
									onChange={(value) => {
										setAttributes({ category: value });
									}}
									options={[{ value: '', label: __('Any') }, ...categories]}
								/>
							</PanelRow>
							<PanelRow>
								<SelectControl
									label={__('Select a Topic', 'wrt-theme')}
									value={topic}
									onChange={(value) => {
										setAttributes({ topic: value });
									}}
									options={[{ value: '', label: __('Any') }, ...topicsCategories]}
								/>
							</PanelRow>
							{cta_show && (
								<div>
									<TextControl
										label={__('CTA Link')}
										value={cta_url}
										onChange={(value) => {
											setAttributes({ cta_url: value });
										}}
									/>
								</div>
							)}
						</Fragment>
					)}
					<PanelRow>
						<ToggleControl
							checked={cta_show}
							label={__('Enable CTA')}
							onChange={() => setAttributes({ cta_show: !cta_show })}
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				{curate_these_posts && (
					<RichText
						allowedFormats={[]}
						onChange={(title) => setAttributes({ title })}
						placeholder={__('Deals & Shopping')}
						tagName="h2"
						value={title}
					/>
				)}
				{curate_these_posts && <Fragment>{printPosts()}</Fragment>}

				{!curate_these_posts && <ServerSideRender block={name} attributes={attributes} />}

				{curate_these_posts && cta_show && (
					// eslint-disable-next-line jsx-a11y/anchor-is-valid
					<Link
						className="button-primary wp-block-wrt-content-list-cta"
						onTextChange={(cta_text) => setAttributes({ cta_text })}
						onLinkChange={(value) =>
							setAttributes({
								cta_url: value?.url,
								cta_text: value?.title ?? cta_text,
							})
						}
						placeholder={__('View More')}
						url={cta_url}
						value={cta_text}
					/>
				)}
			</div>
		</>
	);
};
export default ContentListsEdit;
