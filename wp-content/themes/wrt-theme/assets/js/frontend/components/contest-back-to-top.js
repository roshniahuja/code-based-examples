const ContestBackToTop = () => {
	// Get the tabstrip element
	const tabsContainer = document.getElementById('wrt-tabs');
	const backToTopButton = document.getElementById('contest-rules-back-to-top');

	if (!tabsContainer || !backToTopButton) {
		return;
	}

	const handleBackToTop = (event) => {
		event.preventDefault();

		const header = document.getElementById('masthead');
		const adminBar = document.getElementById('wpadminbar');
		const noticeBar = document.querySelector('.notice-bar');

		let offsetTop = tabsContainer.offsetTop - header.clientHeight;

		if (noticeBar) {
			offsetTop -= noticeBar.clientHeight;
		}

		if (adminBar) {
			offsetTop -= adminBar.clientHeight;
		}

		// eslint-disable-next-line no-restricted-globals
		scroll({
			top: offsetTop,
			behavior: 'smooth',
		});
	};

	backToTopButton.addEventListener('click', handleBackToTop);
};

export default ContestBackToTop;
