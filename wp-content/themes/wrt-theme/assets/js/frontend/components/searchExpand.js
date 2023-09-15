const searchExpand = () => {
	const { body } = document;
	const dropdownsGeneric = document.querySelectorAll('.dropdowns-select');
	const remainingCheckboxes = document.querySelectorAll('.toggle-remaining-checkboxes');
	const searchForm = document.querySelector('.search-form');
	const searchField = document.getElementById('search-field');
	const dropdowns = [...remainingCheckboxes, ...dropdownsGeneric];

	// Toggle dropdowns callback
	function toggleDropdown(e, selectEl) {
		e.preventDefault();

		const isExpanded = selectEl.getAttribute('aria-expanded') === 'true';
		const remainingButton = e.target;
		const isRemaining = remainingButton.classList.contains('toggle-remaining-checkboxes');
		const isHidden = selectEl.getAttribute('aria-hidden') === 'true';

		if (!isExpanded && !isRemaining) {
			// Close all other dropdowns
			dropdowns.forEach((dropdown) => {
				if (dropdown && dropdown.getAttribute('aria-controls') !== selectEl.id) {
					const selectEl = document.getElementById(
						dropdown.getAttribute('aria-controls'),
					);
					selectEl.setAttribute('aria-expanded', false);
					selectEl.setAttribute('aria-hidden', true);
				}
			});
		}

		if (isRemaining && !isExpanded) {
			remainingButton.parentElement.classList.add('is-remaining-checkboxes-expanded');
		}

		if (isRemaining && isExpanded) {
			remainingButton.parentElement.classList.remove('is-remaining-checkboxes-expanded');
		}

		selectEl.setAttribute('aria-expanded', !isExpanded);
		selectEl.setAttribute('aria-hidden', !isHidden);
	}

	// Toggle dropdowns
	dropdowns.forEach((dropdown) => {
		if (dropdown) {
			const selectID = dropdown.getAttribute('aria-controls');
			const selectEl = document.getElementById(selectID);

			dropdown.addEventListener('click', (e) => toggleDropdown(e, selectEl));

			// Add keyboard navigation support
			dropdown.addEventListener('keydown', (e) => toggleDropdown(e, selectEl));
		}
	});

	// Close dropdowns when clicked outside the expanded section
	document.addEventListener('click', (event) => {
		// Added null check for searchForm before calling contains method
		const isClickInsideForm = searchForm ? searchForm.contains(event.target) : false;

		if (!isClickInsideForm) {
			body.classList.remove('is-advance-search-expanded');
		}

		const isClickInsideExpandedSection = dropdowns.some((dropdown) => {
			if (!dropdown) {
				return false;
			}

			const selectID = dropdown.getAttribute('aria-controls');
			const selectEl = document.getElementById(selectID);

			// Added null check for selectEl before calling contains method
			return selectEl && (selectEl.contains(event.target) || dropdown.contains(event.target));
		});

		if (!isClickInsideExpandedSection) {
			dropdowns.forEach((dropdown) => {
				if (!dropdown) {
					return;
				}

				const selectID = dropdown.getAttribute('aria-controls');
				const selectEl = document.getElementById(selectID);

				// Added null check for selectEl before calling setAttribute method
				if (selectEl) {
					selectEl.setAttribute('aria-expanded', 'false');
					selectEl.setAttribute('aria-hidden', 'true');
				}
			});
		}
	});

	// Check if the search field is focused
	function isSearchFieldFocused() {
		if (searchField) {
			return searchField.ownerDocument.activeElement === searchField;
		}
		return false;
	}

	// Hide pseudo-selects on mobile until search field is focused
	function handlePseudoSelectVisibility() {
		const isMobile = window.innerWidth <= 1023;
		const isSearchFocused = isSearchFieldFocused();

		if (isMobile && isSearchFocused) {
			body.classList.add('is-advance-search-expanded');
		} else {
			body.classList.remove('is-advance-search-expanded');
		}
	}

	// Add event listeners for screen resize and search field focus
	window.addEventListener('resize', handlePseudoSelectVisibility);

	if (searchField) {
		searchField.addEventListener('focus', handlePseudoSelectVisibility);
	}

	// Call the function on load
	handlePseudoSelectVisibility();
	// Get the form element
	const form = document.getElementById('searchform');

	// Function to submit the form
	function submitForm() {
		form.submit();
	}

	// Check if the form exists
	if (form) {
		// Get the dropdown elements
		const gradeDropdown = form.querySelector('.header-search #grade-dropdown');
		const topicDropdown = form.querySelector('.header-search #topic-dropdown');

		// Add change event listeners to the dropdowns if they exist
		if (gradeDropdown) {
			gradeDropdown.addEventListener('change', submitForm);
		}

		if (topicDropdown) {
			topicDropdown.addEventListener('change', submitForm);
		}
	}
};

export default searchExpand;
