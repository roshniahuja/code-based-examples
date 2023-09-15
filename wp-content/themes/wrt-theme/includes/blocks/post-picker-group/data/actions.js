export const hydrateData = (location, posts) => {
	return {
		type: 'HYDRATE',
		data: { location, posts },
	};
};

export const reservePost = (clientId, location) => {
	return {
		type: 'RESERVE_NEXT_POST',
		data: {
			clientId,
			location,
		},
	};
};

export const releasePost = (clientId, location) => {
	return {
		type: 'RELEASE_POST',
		data: { clientId, location },
	};
};
