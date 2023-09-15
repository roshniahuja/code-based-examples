// A11y: Make links not tabable when they're hidden
export const skipLinks = (links) => {
	links.forEach((link) => {
		link.setAttribute('tabindex', -1);
	});
};

// A11y: Make links tabable when they're displayed
export const tabLinks = (links) => {
	links.forEach((link) => {
		link.removeAttribute('tabindex');
	});
};
