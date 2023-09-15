import '../../css/frontend/style.css';

import filterableContentToolbar from './blocks/filterable-content-toolbar';
// import foo from './components/bar';
import { initializeDynamicViewportUnitsPolyfill } from './helper/dynamic-viewport-units-polyfill';
import mobileExpand from './components/mobileExpand';
import searchExpand from './components/searchExpand';

filterableContentToolbar();
if (document.getElementById('js-load-more')) {
	import('./components/load-more')
		.then(({ default: LoadMore }) => LoadMore())
		.catch(console.error); // eslint-disable-line
}

if (document.getElementById('js-wrt-filter-go')) {
	import('./components/filters')
		.then(({ default: filters }) => filters())
		.catch(console.error); // eslint-disable-line
}

document.addEventListener('DOMContentLoaded', () => {
	initializeDynamicViewportUnitsPolyfill();
	mobileExpand();
	searchExpand();
});

if (document.querySelector('.wp-block-wrt-newsletter-signup')) {
	import('./components/newsletter-signup').then(({ default: NewsletterSignup }) => {
		NewsletterSignup();
	});
}

if (document.getElementById('js-load-more')) {
	import('./components/load-more')
		.then(({ default: LoadMore }) => LoadMore())
		.catch(console.error); // eslint-disable-line
}

if (document.getElementById('js-wrt-filter-go')) {
	import('./components/filters')
		.then(({ default: filters }) => filters())
		.catch(console.error); // eslint-disable-line
}

if (document.getElementById('wrt-tabs')) {
	import('./components/tabs')
		.then(({ default: tabs }) => tabs())
		.catch(console.error); // eslint-disable-line
}

if (document.getElementById('contest-rules-back-to-top')) {
	import('./components/contest-back-to-top')
		.then(({ default: contestBackToTop }) => contestBackToTop())
		.catch(console.error); // eslint-disable-line
}

if (document.getElementById('getForm')) {
	import('./components/contest-scroll-to-form')
		.then(({ default: contestScrollToForm }) => contestScrollToForm())
		.catch(console.error); // eslint-disable-line
}
