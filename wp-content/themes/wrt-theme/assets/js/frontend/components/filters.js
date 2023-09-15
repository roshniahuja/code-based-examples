/**
 * External dependencies
 */
import DOMPurify from 'dompurify';

const filters = async () => {
	const submitButton = document.getElementById('js-wrt-filter-go');

	if (!submitButton) {
		return;
	}

	const filters = ['category', 'wat-subject', 'wat-grade'];

	// Remove all the GET URL params.
	const clearUrlParams = () => {
		const windowLocation = DOMPurify.sanitize(window.location.pathname);
		window.history.pushState(null, '', windowLocation);
	};

	// Filters "Go" button listener.
	submitButton.addEventListener('click', async () => {
		const loadMoreButton = document.getElementById('js-load-more');
		if (loadMoreButton) {
			loadMoreButton.dataset.clear = true;
			loadMoreButton.dataset.page = 1;
		}
		submitButton.classList.add('disabled');

		const params = {};

		filters.forEach((filter) => {
			const checkboxes = document.querySelectorAll(`input[name="${filter}[]"]:checked`);
			const values = Array.from(checkboxes).map((cb) => cb.value);

			if (values.length > 0) {
				params[filter] = values.join(',');
			}
		});

		clearUrlParams();

		if (loadMoreButton) {
			loadMoreButton.dataset.isFilterEnabled = true;
			loadMoreButton.dataset.categories = '';
			loadMoreButton.dataset.topics = '';
			loadMoreButton.dataset.grades = '';

			if (params.category) {
				loadMoreButton.dataset.categories = params.category;
			}

			if (params['wat-subject']) {
				loadMoreButton.dataset.topics = params['wat-subject'];
			}

			if (params['wat-grade']) {
				loadMoreButton.dataset.grades = params['wat-grade'];
			}

			loadMoreButton.click();
		}
		submitButton.classList.remove('disabled');
	});

	// Get all the checkboxes inside the .checkbox-container
	const checkboxes = document.querySelectorAll('.checkbox-container input[type="checkbox"]');

	// Function to check if any checkbox is checked
	function checkIfAnyChecked() {
		const isChecked = Array.from(checkboxes).some((checkbox) => checkbox.checked);
		if (isChecked) {
			submitButton.removeAttribute('disabled');
		} else {
			submitButton.setAttribute('disabled', '');
		}
	}

	// Add event listeners to all checkboxes
	checkboxes.forEach((checkbox) => {
		checkbox.addEventListener('change', checkIfAnyChecked);
	});

	// Check the initial state of the checkboxes on page load
	checkIfAnyChecked();
};

export default filters;
