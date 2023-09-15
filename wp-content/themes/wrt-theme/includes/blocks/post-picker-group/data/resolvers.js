import { apiFetch } from '@wordpress/data-controls';
import { hydrateData } from './actions';

export function* getAvailablePosts(location) {
	const path = `/wrt/v1/curation-locations/hydrators/${location}`;
	const posts = yield apiFetch({ path });
	if (posts) {
		return hydrateData(location, posts);
	}

	return hydrateData(location, []);
}
