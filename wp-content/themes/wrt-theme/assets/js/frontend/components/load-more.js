/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';

/**
 * Internal dependencies
 */
import postTemplate from '../templates/post';

const PER_PAGE = 10;

const LoadMore = async () => {
	const loadMoreButton = document.getElementById('js-load-more');

	// Verify we can support <template> and have required data.
	if (!('content' in document.createElement('template') && loadMoreButton)) {
		return;
	}

	// Get the element we'll be adding all posts to.
	const content = document.querySelector('.filterable-post-card-contents-items');

	// Get the post template.
	const template = document.getElementById('post-card-template');

	// Process our posts.
	const processPosts = (posts) => {
		posts.forEach((post) => {
			// Process the template into a cloned post.
			const postClone = postTemplate(template, post);

			// Add the post to the existing content.
			content.appendChild(postClone);
		});
	};

	// Update the load more button.
	const updateLoadMoreButton = (currentPage, posts) => {
		if (posts.length === PER_PAGE) {
			loadMoreButton.dataset.clear = '';
			const page = currentPage || 2;
			loadMoreButton.dataset.page = parseInt(page, 10) + 1;
		} else {
			loadMoreButton.style.display = 'none';
		}
	};

	// Load more posts from the endpoint.
	const loadPosts = async () => {
		const {
			clear,
			page,
			categories,
			excludeIds,
			authorId,
			topics,
			grades,
			taxonomy,
			termId,
			isFilterEnabled,
		} = loadMoreButton.dataset;

		let finalQuery = '_embed=true&';

		if (authorId) {
			finalQuery += `&author=${authorId}`;
		}

		if (taxonomy && termId) {
			finalQuery += `&${taxonomy}=${termId}`;
		}

		// When js filtering is enabled then we need to update the query based on selection
		if (isFilterEnabled === 'true') {
			if (categories) {
				finalQuery += `&categories=${categories}`;
			}

			if (topics) {
				finalQuery += `&wat-subject=${topics}`;
			}

			if (grades) {
				finalQuery += `&wat-grade=${grades}`;
			}
		}

		if (excludeIds) {
			finalQuery += `&exclude=${excludeIds}`;
		}

		// Set up our query with some default values.
		// let finalQuery = query;
		finalQuery += `&per_page=${PER_PAGE}`;
		finalQuery += `&page=${page || 2}`;

		// Set default values.
		let posts = [];

		try {
			loadMoreButton.classList.add('button-loading');
			content.classList.add('loading');
			posts = await apiFetch({
				path: `/wp/v2/posts?${finalQuery}`,
				method: 'get',
				headers: {
					Accept: 'application/json, text/plain, */*',
					'Content-Type': 'application/json',
				},
			});
		} catch (e) {
			console.warn('Load more error', e); // eslint-disable-line
		} finally {
			loadMoreButton.classList.remove('button-loading');
			content.classList.remove('loading');
			updateLoadMoreButton(page, posts);
			if (posts.length > 0) {
				if (clear) {
					content.innerHTML = '';
				}
				processPosts(posts);
			} else {
				content.innerHTML = 'No posts found.';
			}
		}
	};

	// Add load more button listener.
	loadMoreButton.addEventListener('click', async () => {
		await loadPosts();
	});
};

export default LoadMore;
