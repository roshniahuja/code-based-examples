const ContestScrollToForm = () => {
	// Get the tabstrip element
	const formContainer = document.getElementById('getForm');
	const scrollToButtons = document.querySelectorAll('a[href="#getForm"]');

	if (!formContainer || !scrollToButtons.length) {
		return;
	}

	const handleScrollToForm = (event) => {
		event.preventDefault();

		const header = document.getElementById('masthead');
		const adminBar = document.getElementById('wpadminbar');
		const noticeBar = document.querySelector('.notice-bar');

		let offsetTop = formContainer.offsetTop - header.clientHeight;

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

	scrollToButtons.forEach((button) => {
		button.addEventListener('click', handleScrollToForm);
	});
};

export default ContestScrollToForm;
