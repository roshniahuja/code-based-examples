const INITIAL_STATE = {
	default: {
		availablePosts: {},
		reservedPosts: {},
	},
};

const moveToReserved = (state, data) => {
	const { location, clientId } = data;
	const postToMove = state[location].availablePosts.shift();
	return {
		...state,
		[location]: {
			availablePosts: [...state[location].availablePosts],
			reservedPosts: {
				...state[location].reservedPosts,
				[clientId]: postToMove,
			},
		},
	};
};

const reducer = (state = INITIAL_STATE, { type, data }) => {
	console.log(`Reducer: ${type}`, data);
	switch (type) {
		case 'HYDRATE':
			return {
				...state,
				[data.location]: {
					availablePosts: data.posts,
					reservedPosts: {},
				},
			};
		case 'RESERVE_NEXT_POST':
			return state?.[data.location]?.availablePosts
				? moveToReserved(state, data)
				: { ...state };
		default:
			return {
				...state,
			};
	}
};

export default reducer;
