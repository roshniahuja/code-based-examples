/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';
import { InspectorControls, RichText, useBlockProps } from '@wordpress/block-editor';
import { FormTokenField, PanelBody, PanelRow } from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Edit component.
 * See https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-edit-save/#edit
 *
 * @param {object}   props                  The block props.
 * @param {object}   props.attributes       Block attributes.
 * @param {string}   props.attributes.title Custom title to be displayed.
 * @param {string}   props.className        Class name for the block.
 * @param {Function} props.setAttributes    Sets the value for block attributes.
 * @returns {Function} Render the edit screen
 */
const NewsletterSignupEdit = (props) => {
	const { attributes, setAttributes } = props;
	const { button, description, label, newsletter, title, variables } = attributes;
	const [newsletters, setNewsletters] = useState([]);

	const blockProps = useBlockProps();

	useEffect(() => {
		async function fetchData() {
			try {
				const results = await apiFetch({ path: `/wrt/v1/newsletters` });
				setNewsletters(results);
			} catch (e) {
				console.warn('Newsletter fetch error', e); // eslint-disable-line
			}
		}
		fetchData();
	}, []);

	const handleItemChange = (id, key, value) => {
		setAttributes({
			variables: variables.map((item) => (item.id === id ? { ...item, [key]: value } : item)),
		});
	};

	const handleVariablesChange = (tokens) => {
		const newVariables = tokens.map((token) => {
			const [existing] = variables.filter(({ id }) => id === token);

			return (
				existing || {
					id: token,
					name: __(`Newsletter for ${token} content`, 'wrt-theme'),
					description: '',
				}
			);
		});

		setAttributes({
			variables: newVariables,
		});
	};

	const renderNewsletters = () => {
		return variables.map((variable) => {
			return (
				<div className="checkbox-container" key={variable}>
					<input type="checkbox" />
					{/* eslint-disable-next-line jsx-a11y/label-has-associated-control */}
					<label>
						<RichText
							allowedFormats={[]}
							placeholder={__(`Newsletter name for ${variable.id}...`, 'wrt-theme')}
							onChange={(name) => handleItemChange(variable.id, 'name', name)}
							tagName="span"
							value={variable.name}
						/>
						<RichText
							allowedFormats={[]}
							placeholder={__(
								`Newsletter description for ${variable.id}...`,
								'wrt-theme',
							)}
							onChange={(description) =>
								handleItemChange(variable.id, 'description', description)
							}
							tagName="span"
							value={variable.description}
						/>
					</label>
				</div>
			);
		});
	};

	return (
		<div {...blockProps}>
			<InspectorControls>
				<PanelBody title={__('Settings')}>
					<PanelRow>
						<FormTokenField
							label={__('Main newsletter')}
							maxLength={1}
							onChange={(tokens) => {
								setAttributes({
									newsletter: newsletters.filter((list) =>
										tokens.includes(list.name),
									),
								});
							}}
							placeholder={__('Search for a newsletter')}
							suggestions={newsletters.map((list) => list.name)}
							value={newsletter.map((list) => list.name)}
							__experimentalShowHowTo={false}
						/>
					</PanelRow>
					<PanelRow>
						<FormTokenField
							label={__('Newsletter Variables')}
							onChange={handleVariablesChange}
							placeholder={__('Add newsletter variables')}
							value={variables.map(({ id }) => id)}
							__experimentalShowHowTo={false}
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<RichText
				allowedFormats={[]}
				onChange={(title) => setAttributes({ title })}
				placeholder={__('Stay in touch')}
				tagName="h2"
				value={title}
			/>
			<RichText
				allowedFormats={[]}
				className="newsletter-signup__description"
				onChange={(description) => setAttributes({ description })}
				placeholder={__('All the best teaching and learning ideas, straight to your inbox')}
				tagName="p"
				value={description}
			/>
			<div className="newsletter-signup__email-input">
				<input type="email" id="email" name="" placeholder="you@example.com" />
				<RichText
					allowedFormats={[]}
					className="button-secondary"
					onChange={(button) => setAttributes({ button })}
					placeholder={__('Subscribe')}
					tagName="button"
					value={button}
				/>
			</div>
			<RichText
				allowedFormats={[]}
				onChange={(label) => setAttributes({ label })}
				placeholder={__(
					'Select one or more of the newsletters that you’d like to receive. You can update your preferences or unsubscribe at any time.',
				)}
				tagName="label"
				value={label}
			/>
			{variables.length > 0 && renderNewsletters()}
			<div className="newsletter-view-all">
				<a
					href="https://link.weareteachers.com/join/7d4/signup"
					rel="noopener noreferrer"
					target="_blank"
				>
					{__('See All Our Newsletters')}
				</a>
			</div>
		</div>
	);
};
export default NewsletterSignupEdit;
