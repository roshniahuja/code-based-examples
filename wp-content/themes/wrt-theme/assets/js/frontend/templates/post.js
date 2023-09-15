/*
 * Post template.
 */

/**
 * WordPress dependencies
 */
import { escapeAttribute, escapeHTML } from '@wordpress/escape-html';

/**
 * External dependencies
 */
import DOMPurify from 'dompurify';

/**
 * Generate image based of REST API response data.
 *
 * @param {object} media Media object.
 * @param {string} media.alt_text Image ALT text.
 * @param {object} media.media_details Image media details.
 * @param {string} media.source_url Image source URL.
 * @returns {HTMLImageElement}
 */
const buildImage = (media) => {
	const src = media.source_url;
	const alt = media.alt_text || '';
	const { height = 220, width = 400, sizes } = media.media_details || {};

	let srcset = '';
	if (sizes) {
		Object.keys(media.media_details.sizes).forEach((size) => {
			srcset += `${media.media_details.sizes[size].source_url} ${media.media_details.sizes[size].width}w, `;
		});
		srcset = srcset.slice(0, -2); // Remove trailing comma and space.
	}

	const image = document.createElement('img');
	image.src = src;
	image.alt = alt;
	image.width = width;
	image.height = height;
	image.srcset = srcset;

	return image;
};

/**
 * Build term link.
 *
 * @param {Array} terms Array with term object arrays.
 * @param {string} taxonomy Taxonomy name.
 * @returns {HTMLAnchorElement|null}
 */
const buildTerm = (terms, taxonomy) => {
	const termsArray = terms.find((termsArray) =>
		termsArray.find((term) => term.taxonomy === taxonomy),
	);

	if (!termsArray) {
		return null;
	}

	const term = termsArray ? termsArray.find((term) => term.taxonomy === taxonomy) : undefined;

	if (!term) {
		return null;
	}

	const link = document.createElement('a');
	link.href = term.link;
	link.innerHTML = escapeHTML(DOMPurify.sanitize(term.name));

	if (taxonomy === 'wat-grade') {
		link.rel = 'tag';
	}

	if (taxonomy === 'category') {
		link.classList.add('cat-item__link');
	}

	return link;
};

/**
 * Post template.
 *
 * @param {object} template Template.
 * @param {object} post Post object.
 * @param {object} post._embedded Embed post data.
 * @param {string} post.excerpt.rendered Post excerpt.
 * @param {number} post.featured_media Post featured image ID.
 * @param {string} post.link Post link.
 * @param {string} post.title.rendered Post title.
 * @returns {Node}
 */
const postTemplate = (template, post) => {
	const postClone = template.content.cloneNode(true);

	// Image.
	const image = postClone.querySelector('[data-image]');
	if (
		image &&
		post.featured_media &&
		post._embedded['wp:featuredmedia'] &&
		post._embedded['wp:featuredmedia'][0]
	) {
		// Image link.
		const imageLink = postClone.querySelector('[data-image-link]');
		imageLink.href = escapeAttribute(post.link);

		const image = buildImage(post._embedded['wp:featuredmedia'][0]);
		imageLink.innerHTML = DOMPurify.sanitize(image);
	}

	// Grades.
	const grades = postClone.querySelector('[data-grades]');
	if (grades && post._embedded['wp:term']) {
		const grade = buildTerm(post._embedded['wp:term'], 'wat-grade');
		const gradeLink = postClone.querySelector('[data-grades-link]');
		if (grade && gradeLink) {
			grades.replaceChild(grade, gradeLink);
		} else {
			grades.remove();
		}
	}

	// Title.
	const titleLink = postClone.querySelector('[data-title-link]');
	titleLink.href = escapeAttribute(post.link);
	titleLink.ariaLabel = escapeAttribute(DOMPurify.sanitize(post.title.rendered));
	titleLink.innerHTML = escapeHTML(DOMPurify.sanitize(post.title.rendered));

	// Excerpt
	const excerpt = postClone.querySelector('[data-excerpt]');
	if (excerpt && post.excerpt.rendered) {
		excerpt.innerHTML = DOMPurify.sanitize(post.excerpt.rendered);
	} else {
		excerpt.remove();
	}

	// Category.
	const category = postClone.querySelector('[data-category]');
	if (category && post._embedded['wp:term']) {
		const categoryLink = buildTerm(post._embedded['wp:term'], 'wat-subject');
		if (category) {
			category.innerHTML = DOMPurify.sanitize(categoryLink);
		} else {
			category.remove();
		}
	}

	// Author.
	const author = postClone.querySelector('[data-author]');
	if (author && post._embedded.author) {
		author.href = post._embedded.author[0].link;
		author.innerText = DOMPurify.sanitize(post._embedded.author[0].name);
	}

	return postClone;
};

export default postTemplate;
