import { registerPlugin } from '@wordpress/plugins';
import { PostPostTypeTemplateBackCompat } from './post-posttype-template-back-compat';
import './affiliate-link';

registerPlugin('post-posttype-template-backcompat', {
	render: PostPostTypeTemplateBackCompat,
});
