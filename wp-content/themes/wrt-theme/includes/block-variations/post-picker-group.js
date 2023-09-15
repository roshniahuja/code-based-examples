import { registerBlockVariation } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

const BLOCK_NAME = 'wrt/post-picker-group';

const postPickerGroupVariations = [
	{
		name: 'home-hero-featured',
		title: __('Homepage Hero Featured Item', 'wrt-theme'),
		isDefault: true,
		attributes: {
			mode: 'post',
			contentTypes: ['post'],
			partial: 'post-card/post-card-featured',
			size: 1,
			containerClass: 'home-hero-featured-item',
			location: 'homepage',
		},
	},
	{
		name: 'home-hero-item',
		title: __('Homepage Hero Items', 'wrt-theme'),
		attributes: {
			mode: 'post',
			contentTypes: ['post'],
			partial: 'post-card/post-card-list',
			size: 4,
			containerClass: 'home-hero-content-items',
			childrenClass: 'home-hero-content-item',
			location: 'homepage',
		},
	},
	{
		name: 'section-hero-featured',
		title: __('Section Hero Featured Item', 'wrt-theme'),
		attributes: {
			mode: 'post',
			contentTypes: ['post'],
			partial: 'post-card/post-card',
			size: 1,
			containerClass: 'section-hero-featured-item',
			showExcerpt: true,
			location: 'classroom_ideas',
		},
	},
	{
		name: 'section-hero-items',
		title: __('Section Hero Items', 'wrt-theme'),
		attributes: {
			mode: 'post',
			contentTypes: ['post'],
			partial: 'section-hero/section-hero-item',
			size: 2,
			containerClass: 'section-hero-content-items',
			childrenClass: 'section-hero-content-item',
			showExcerpt: true,
			location: 'classroom_ideas',
		},
	},
	{
		name: 'three-card-display',
		title: __('Three card display', 'wrt-theme'),
		attributes: {
			mode: 'post',
			contentTypes: ['post'],
			partial: 'post-card/post-card',
			size: 3,
			containerClass: 'three-card-display',
			showExcerpt: true,
			location: 'homepage',
		},
	},
	{
		name: 'most-popular',
		title: __('Most Popular', 'wrt-theme'),
		attributes: {
			mode: 'post',
			contentTypes: ['post'],
			partial: 'post-card/post-card-popular',
			size: 4,
			containerClass: 'most-popular',
			location: 'most_popular',
			childrenClass: 'most-popular-item',
		},
	},
	{
		name: 'content-collection',
		title: __('Homepage Classroom Ideas Collection', 'wrt-theme'),
		attributes: {
			mode: 'post',
			contentTypes: ['post'],
			partial: 'post-card/post-card',
			size: 6,
			containerClass: 'content-collection-items',
			showExcerpt: true,
			location: 'classroom_ideas',
		},
	},
	{
		name: 'home-printables',
		title: __('Homepage Printables Collection', 'wrt-theme'),
		attributes: {
			mode: 'post',
			contentTypes: ['post'],
			partial: 'classroom-ideas/classroom-idea',
			size: 4,
			containerClass: 'classroom-ideas-items',
			childrenClass: 'classroom-ideas-item',
			showExcerpt: true,
			location: 'printables',
		},
	},
	{
		name: 'teacher-life',
		title: __('Homepage Life & Wellbeing Collection', 'wrt-theme'),
		attributes: {
			mode: 'post',
			contentTypes: ['post'],
			partial: 'post-card/post-card',
			size: 6,
			containerClass: 'content-collection-items',
			showExcerpt: true,
			location: 'life_wellbeing',
		},
	},
	{
		name: 'editors-picks',
		title: __('Editor’s Picks', 'wrt-theme'),
		attributes: {
			mode: 'post',
			contentTypes: ['wat-product'],
			partial: 'post-card/editors-pick',
			size: 4,
			containerClass: 'editors-picks-items',
			childrenClass: 'editors-picks-item',
			showExcerpt: true,
			location: 'classroom_ideas',
		},
	},
	{
		name: 'filterable-content-block',
		title: __('Filterable Content Block', 'wrt-theme'),
		attributes: {
			mode: 'post',
			contentTypes: ['post'],
			partial: 'post-card/filterable-post-card',
			size: 7,
			containerClass: 'filterable-post-card-contents-items',
			childrenClass: 'filterable-post-card-contents-item',
			showExcerpt: true,
			location: 'classroom_ideas',
		},
	},
];

postPickerGroupVariations.map((variation) => registerBlockVariation(BLOCK_NAME, variation));
