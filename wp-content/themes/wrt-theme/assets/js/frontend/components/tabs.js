const Tabs = () => {
	// Get the tabstrip element
	const tabstrip = document.getElementById('wrt-tabs');

	// Get the tabstrip items
	const tabItems = Array.from(tabstrip.getElementsByClassName('wrt-tab'));

	// Get the tab content elements
	const tabContents = Array.from(document.getElementsByClassName('wrt-tabs-section'));

	// Add click event listeners to each tab item
	tabItems.forEach((tabItem) => {
		tabItem.addEventListener('click', () => {
			// Hide all the tab contents.
			tabContents.forEach((tabContent) => {
				// tabContent.classList.remove('show');
				tabContent.setAttribute('aria-expanded', false);
				tabContent.setAttribute('aria-hidden', true);
			});

			tabItems.forEach((element) => {
				// element.classList.remove('active');
				element.removeAttribute('aria-selected', false);
			});
			// tabItem.classList.add('active');
			tabItem.setAttribute('aria-selected', true);

			// Display only the tab content that is clicked.
			const key = tabItem.getAttribute('data-key');
			const tabContentToShow = document.getElementById(key);
			// tabContentToShow.classList.add('show');
			tabContentToShow.setAttribute('aria-expanded', true);
			tabContentToShow.setAttribute('aria-hidden', false);
		});
	});
};

export default Tabs;
