import { CampaignSelectorPanel } from './panel';
import { TAXONOMY_NAME } from './taxonomy';

const customCampaignSelector = (OriginalComponent) => (props) => {
	const { slug } = props;
	if (slug === TAXONOMY_NAME) {
		return <CampaignSelectorPanel {...props} />;
	}
	return <OriginalComponent {...props} />;
};

export const register = () =>
	wp.hooks.addFilter(
		'editor.PostTaxonomyType',
		'wrt-plugin/set-custom-term-selector',
		customCampaignSelector,
	);
