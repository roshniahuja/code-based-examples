const mobileExpand = () => {
	const { body } = document;
	const mainNavigationToggle = document.querySelector('.main-navigation-toggle');
	const mainNavigation = document.querySelector('#main-navigation');

	// If the required elements are not found, return without executing further
	if (!mainNavigation || !mainNavigationToggle) {
		return;
	}

	let isMobile = false; // Track mobile screen state

	const handleToggleClick = () => {
		const expanded = mainNavigationToggle.getAttribute('aria-expanded') === 'true' || false;

		mainNavigationToggle.setAttribute('aria-expanded', !expanded);
		mainNavigation.setAttribute('aria-hidden', expanded);
		body.classList.toggle('is-menu-expanded');
	};

	const handleDocumentClick = (event) => {
		const isClickInsideMenu = mainNavigation.contains(event.target);
		const isClickOnToggle = mainNavigationToggle.contains(event.target);

		if (!isClickInsideMenu && !isClickOnToggle) {
			mainNavigationToggle.setAttribute('aria-expanded', false);
			mainNavigation.setAttribute('aria-hidden', true);
			body.classList.remove('is-menu-expanded');
		}
	};

	const handleDocumentKeydown = (event) => {
		if (event.key === 'Escape') {
			mainNavigationToggle.setAttribute('aria-expanded', false);
			mainNavigation.setAttribute('aria-hidden', true);
			body.classList.remove('is-menu-expanded');
		}
	};

	const handleScreenSizeChange = (mediaQuery) => {
		if (mediaQuery.matches) {
			// Only set up the menu for mobile screen if it's not already in mobile mode
			if (!isMobile) {
				isMobile = true;
				mainNavigation.setAttribute('aria-hidden', true);
				mainNavigationToggle.addEventListener('click', handleToggleClick);
				document.addEventListener('click', handleDocumentClick);
				document.addEventListener('keydown', handleDocumentKeydown);
			}
		} else {
			// Remove mobile mode setup only if it's in mobile mode
			// eslint-disable-next-line no-lonely-if
			if (isMobile) {
				isMobile = false;
				mainNavigationToggle.removeEventListener('click', handleToggleClick);
				document.removeEventListener('click', handleDocumentClick);
				document.removeEventListener('keydown', handleDocumentKeydown);
				mainNavigationToggle.setAttribute('aria-expanded', false);
				mainNavigation.setAttribute('aria-hidden', false);
				body.classList.remove('is-menu-expanded');
			}
		}
	};

	const screenQuery = window.matchMedia('(max-width: 1023px)');
	handleScreenSizeChange(screenQuery); // Call initially to set up the menu based on the current screen size
	screenQuery.addEventListener('change', handleScreenSizeChange); // Listen for changes in screen size

	// Only return destroy function if the required elements exist
	if (mainNavigation && mainNavigationToggle) {
		// eslint-disable-next-line consistent-return
		return {
			destroy: () => {
				screenQuery.removeEventListener('change', handleScreenSizeChange);
				mainNavigationToggle.removeEventListener('click', handleToggleClick);
				document.removeEventListener('click', handleDocumentClick);
				document.removeEventListener('keydown', handleDocumentKeydown);
			},
		};
	}
};

export default mobileExpand;
