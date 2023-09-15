/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { registerPlugin } = wp.plugins;
const { PluginPostStatusInfo } = wp.editPost;
const { select, withSelect, withDispatch } = wp.data;
const { compose } = wp.compose;
const { ToggleControl } = wp.components;

/**
 * Affiliate Link toggle.
 *
 * @param {boolean}  showAffiliateLink Show Affiliate Link.
 * @param {Function} onUpdate          Update post meta.
 * @returns {JSX.Element}
 */
const AffiliateLink = ({ showAffiliateLink = false, onUpdate }) => {
	return (
		<PluginPostStatusInfo>
			<ToggleControl
				label={__('Show affiliate disclosure', 'wrt-theme')}
				checked={!!showAffiliateLink}
				onChange={() => onUpdate(!showAffiliateLink)}
			/>
		</PluginPostStatusInfo>
	);
};

const render = compose([
	withSelect(() => {
		const { getEditedPostAttribute } = select('core/editor');
		return {
			showAffiliateLink: getEditedPostAttribute('meta')['show-affiliate-link'],
		};
	}),
	withDispatch((dispatch) => ({
		onUpdate(showAffiliateLink) {
			dispatch('core/editor').editPost({
				meta: { 'show-affiliate-link': !!showAffiliateLink },
			});
		},
	})),
])(AffiliateLink);

registerPlugin('wrt-affiliate-link', { render });
