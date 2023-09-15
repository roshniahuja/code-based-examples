/* global grecaptcha */
/* global reCAPTCHA */

/**
 * WordPress dependencies.
 */
import apiFetch from '@wordpress/api-fetch';
import { escapeHTML } from '@wordpress/escape-html';

/**
 * Handle single newsletter signup form.
 */
const NewsletterSignup = async () => {
	const submitBtn = document.getElementById('js-newsletter-signup');
	const emailInput = document.getElementById('email');
	const newsletter = document.querySelector('input[name="wrt-newsletter"]');

	if (!submitBtn || !emailInput || !newsletter) {
		return;
	}

	const noticeDiv = document.querySelector('.newsletter-signup__status');

	/**
	 * Reset notice.
	 */
	const resetNotice = () => {
		if (!noticeDiv) {
			return;
		}

		noticeDiv.classList.remove('error');
		noticeDiv.classList.remove('success');
		noticeDiv.style.display = 'none';
		noticeDiv.textContent = '';
		emailInput.classList.remove('is-error');
	};

	/**
	 * Show notice.
	 *
	 * @param {string} message Message.
	 * @param {string} type    Message type. Accepts: success, error.
	 */
	const showNotice = (message, type = 'success') => {
		if (!noticeDiv) {
			return;
		}

		noticeDiv.classList.add(type);
		noticeDiv.style.display = 'block';
		noticeDiv.textContent = escapeHTML(message);

		if (type === 'error') {
			emailInput.classList.add('is-error');
		}
	};

	/**
	 * Process "Subscribe" button click.
	 */
	submitBtn.addEventListener('click', async (e) => {
		e.preventDefault();
		e.currentTarget.classList.add('button-loading');
		resetNotice();

		if (!emailInput.value) {
			showNotice('Please enter an email address.', 'error');
			return;
		}

		const vars = [];
		const variables = document.querySelectorAll('input[name="variables[]"]:checked');
		Array.from(variables).forEach((el) => {
			vars.push(el.value);
		});

		const hidden = document.querySelectorAll('input[name="variables[]"][type="hidden"]');
		Array.from(hidden).forEach((el) => {
			vars.push(el.value);
		});

		grecaptcha.ready(() => {
			grecaptcha.execute(reCAPTCHA.siteKey, { action: 'submit' }).then(async (token) => {
				try {
					const response = await apiFetch({
						path: '/wrt/v1/newsletters/subscribe',
						method: 'POST',
						parse: false,
						data: {
							newsletter: newsletter.value,
							email: emailInput.value,
							variables: vars,
							token,
						},
					});

					if (response.ok) {
						showNotice('Success! You have been added to our newsletter.');
					}
				} catch (error) {
					console.warn('Signup error', e); // eslint-disable-line
					showNotice('Error! Unable to subscribe to newsletter.', 'error');
				}
			});
		});
		e.currentTarget.classList.remove('button-loading');
	});
};

export default NewsletterSignup;
