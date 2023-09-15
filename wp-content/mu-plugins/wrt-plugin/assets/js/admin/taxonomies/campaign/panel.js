import { __ } from '@wordpress/i18n';
import { RawHTML, useEffect } from '@wordpress/element';
import { useSelect, useDispatch } from '@wordpress/data';
import { PanelRow, Button } from '@wordpress/components';
import { createBlock } from '@wordpress/blocks';
import { ContentSearch } from '@10up/block-components';
import { store as editorStore } from '@wordpress/editor';
import { store as blockEditorStore } from '@wordpress/block-editor';
import { TAXONOMY_NAME } from './taxonomy';

const POST_SPONSOR_BLOCK_NAME = 'wrt/post-sponsor';

export const CampaignSelectorPanel = () => {
	const { editPost } = useDispatch(editorStore);

	const { campaignTermId, campaignTerm, postType } = useSelect((select) => {
		const campaignTermId = select(editorStore).getEditedPostAttribute(TAXONOMY_NAME)?.[0];
		const campaignTerm = select('core').getEntityRecord(
			'taxonomy',
			TAXONOMY_NAME,
			campaignTermId,
		);
		return {
			campaignTermId,
			campaignTerm,
			postType: select(editorStore).getCurrentPostType(),
		};
	});

	// Grab some references in case we want to insert a block.
	const blocks = useSelect((select) => {
		return select(blockEditorStore).getBlocks();
	});
	const { insertBlocks, removeBlock } = useDispatch(blockEditorStore);

	// If this panel is loaded, this post type supports post sponsors, manage the block.
	useEffect(() => {
		const firstBlock = blocks[0];
		// If it doesn't already exist insert the a post sponsor block.
		if (campaignTermId) {
			const blockToInsert = createBlock(POST_SPONSOR_BLOCK_NAME);
			if (firstBlock?.name !== POST_SPONSOR_BLOCK_NAME) {
				insertBlocks([blockToInsert], 0, '', false);
			}
		} else if (firstBlock && firstBlock.name === POST_SPONSOR_BLOCK_NAME) {
			removeBlock(firstBlock.clientId);
		}
	}, [campaignTermId, blocks, insertBlocks, removeBlock]);

	// Handle setting the campaign.
	const handleSelectCampaign = (item) => {
		editPost({
			[TAXONOMY_NAME]: [item.id],
		});
	};

	// Handle resetting the campaign
	const handleResetCampaign = () => {
		editPost({
			[TAXONOMY_NAME]: [],
		});
	};

	if (postType === 'wat-campaign') {
		return (
			<PanelRow>
				<div>{__('Disabled for this view.', 'wrt-plugin')}</div>
			</PanelRow>
		);
	}

	return campaignTermId && campaignTerm ? (
		<>
			<PanelRow>
				<div>
					<RawHTML>{campaignTerm.name}</RawHTML>
				</div>
			</PanelRow>
			<PanelRow>
				<Button onClick={handleResetCampaign} variant="secondary">
					{__('Reset Sponsorship', 'wrt-plugin')}
				</Button>
			</PanelRow>
		</>
	) : (
		<PanelRow>
			<ContentSearch
				onSelectItem={handleSelectCampaign}
				mode="term"
				label={__('Select a Sponsored Campaign', 'wrt-plugin')}
				contentTypes={[TAXONOMY_NAME]}
				placeholder={__('Search for a campaign...', 'wrt-plugin')}
			/>
		</PanelRow>
	);
};
