const config = {
	extends: ['@10up/stylelint-config'],
	rules: {
		'at-rule-no-unknown': [
			true,
			{
				ignoreAtRules: ['svg-load', 'mixin', 'define-mixin'],
			},
		],
		'custom-property-pattern': null,
	},
};

module.exports = config;
