import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import { useBlockProps } from '@wordpress/block-editor';
import { Button, TextControl, Popover } from '@wordpress/components';
import { ContentSearch } from '@10up/block-components';
import { usePopover } from './usePopover';

const CAMPAIGN_TAXONOMY_SLUG = '_wat-campaign';
const SPONSOR_TAXONOMY_SLUG = 'wat-sponsor';

export const SponsorPicker = (props) => {
	const blockProps = useBlockProps({
		className: 'feature-card--sponsor',
	});

	const [isAdding, setIsAdding] = useState(false);
	const [addSponsor, setAddSponsor] = useState(false);
	const [addCampaignTitle, setAddCampaignTitle] = useState('');

	const { sponsor, onSelectCampaign, onResetCampaign, onAddNewCampaign } = props;
	const { toggleProps, popoverProps, isVisible, setIsVisible } = usePopover();
	const { popoverRef } = popoverProps;

	const onAddCampaignFormSubmit = () => {
		onAddNewCampaign(addSponsor.id, addCampaignTitle);
		setIsAdding(false);
		setIsVisible(false);
		setAddSponsor(false);
		setAddCampaignTitle('');
	};

	const onChangeCampaignName = (value) => {
		setAddCampaignTitle(value);
	};

	const onSelectItemForCampaign = (item) => {
		onSelectCampaign(item);
		setIsVisible(false);
	};

	const onSelectItemForSponsor = (item) => {
		setAddSponsor(item);
	};

	return (
		<div {...blockProps} {...toggleProps}>
			{sponsor && (
				<div className="post-card__sponsored-by">
					{__('Sponsored by', 'wrt-theme')} {sponsor?.name}
				</div>
			)}
			{!sponsor && (
				<div className="post-card__sponsored-by">
					{__('Select a sponsored campaign...', 'wrt-theme')}
				</div>
			)}
			{isVisible && (
				<Popover ref={popoverRef} {...popoverProps}>
					<div style={{ minWidth: '300px', padding: '16px' }}>
						{!isAdding && (
							<>
								<ContentSearch
									onSelectItem={onSelectItemForCampaign}
									mode="term"
									label={__('Select a Sponsored Campaign', 'wrt-theme')}
									contentTypes={[CAMPAIGN_TAXONOMY_SLUG]}
									placeholder={__('Search for a campaign...', 'wrt-theme')}
								/>
								<Button variant="link" onClick={() => setIsAdding(true)}>
									{__('Add a New Campaign', 'wrt-theme')}
								</Button>
								<Button
									variant="link"
									className="is-destructive"
									onClick={onResetCampaign}
								>
									{__('Reset Sponsored Campaign', 'wrt-theme')}
								</Button>
							</>
						)}
						{isAdding && (
							<form onSubmit={onAddCampaignFormSubmit}>
								<TextControl
									className="editor-post-taxonomies__hierarchical-terms-input"
									label="Campaign Name"
									value={addCampaignTitle}
									onChange={onChangeCampaignName}
									required
								/>
								{addSponsor ? (
									<p>
										{__('Selected:', 'wrt-theme')} {addSponsor.title}
									</p>
								) : (
									<ContentSearch
										onSelectItem={onSelectItemForSponsor}
										mode="term"
										label={__(
											'Select a Sponsor for this Campaign',
											'wrt-theme',
										)}
										contentTypes={[SPONSOR_TAXONOMY_SLUG]}
										placeholder={__('Search for a sponsor...', 'wrt-theme')}
									/>
								)}

								<Button
									variant="tertiary"
									type="submit"
									className="editor-post-taxonomies__hierarchical-terms-submit"
									disabled={!addSponsor || !addCampaignTitle}
								>
									{__('Add New Campaign', 'wrt-theme')}
								</Button>
							</form>
						)}
					</div>
				</Popover>
			)}
		</div>
	);
};
