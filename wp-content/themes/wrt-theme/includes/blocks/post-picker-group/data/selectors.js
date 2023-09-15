export const getState = (state) => state;

export const getAvailablePosts = (state, location) => state?.[location]?.availablePosts || [];

export const getReservedPosts = (state, location) => state?.[location]?.reservedPosts || [];

export const getClientPost = (state, clientId, location) =>
	state?.[location]?.reservedPosts?.[clientId] || undefined;
