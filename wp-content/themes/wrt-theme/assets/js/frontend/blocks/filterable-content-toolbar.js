const filterableContentToolbar = () => {
	const handleToggleClick = (e) => {
		e.preventDefault();
	};

	const submitBtn = document.getElementById('js-wrt-filter-go');
	if (submitBtn === null) {
		return;
	}
	submitBtn.addEventListener('click', handleToggleClick);
};

export default filterableContentToolbar;
