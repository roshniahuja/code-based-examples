import { useSelect, useDispatch, dispatch } from '@wordpress/data';
import { store as editorStore } from '@wordpress/editor';

const CAMPAIGN_TAXONOMY_SLUG = '_wat-campaign';
const CAMPAIGN_POST_TYPE_SLUG = 'wat-campaign';
const SPONSOR_TAXONOMY_SLUG = 'wat-sponsor';

const addNewPost = (postType, postData) => {
	return dispatch('core').saveEntityRecord('postType', postType, postData);
};

const addNewTerm = (taxonomy, termData) => {
	return dispatch('core').saveEntityRecord('taxonomy', taxonomy, termData);
};

const addNewCampaign = async (sponsorId, title) => {
	const newTerm = await addNewTerm(CAMPAIGN_TAXONOMY_SLUG, {
		name: title,
	});

	await addNewPost(CAMPAIGN_POST_TYPE_SLUG, {
		title,
		status: 'publish',
		[SPONSOR_TAXONOMY_SLUG]: [sponsorId],
		[CAMPAIGN_TAXONOMY_SLUG]: [newTerm.id],
	});

	return newTerm;
};

export const useSponsoredCampaign = () => {
	const campaignProps = useSelect((select) => {
		const campaignTermId =
			select('core/editor').getEditedPostAttribute(CAMPAIGN_TAXONOMY_SLUG)?.[0] || 0;
		const campaigns = select('core').getEntityRecords('postType', CAMPAIGN_POST_TYPE_SLUG, {
			[CAMPAIGN_TAXONOMY_SLUG]: [campaignTermId],
		});

		return {
			campaign: campaigns?.[0] || false,
			sponsor: select('core').getEntityRecord(
				'taxonomy',
				SPONSOR_TAXONOMY_SLUG,
				campaigns?.[0]?.[SPONSOR_TAXONOMY_SLUG]?.[0],
			),
		};
	});

	const { editPost } = useDispatch(editorStore);
	const onSelectCampaign = (campaign) => {
		editPost({
			[CAMPAIGN_TAXONOMY_SLUG]: [campaign.id],
		});
	};

	const onAddNewCampaign = async (sponsorId, title) => {
		const campaign = await addNewCampaign(sponsorId, title);
		onSelectCampaign(campaign);
	};

	const onResetCampaign = () => {
		editPost({
			[CAMPAIGN_TAXONOMY_SLUG]: [],
		});
	};

	return {
		...campaignProps,
		sponsoredCampaignProps: {
			...campaignProps,
			onSelectCampaign,
			onResetCampaign,
			onAddNewCampaign,
		},
	};
};
