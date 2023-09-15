import { useBlockProps, RichText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

export const BlockEdit = (props) => {
	const { attributes, setAttributes } = props;
	const { title, description } = attributes;
	const blockProps = useBlockProps({
		className: 'stay-in-touch has-flag',
	});

	return (
		<div {...blockProps}>
			<RichText
				allowedFormats={[]}
				onChange={(title) => setAttributes({ title })}
				placeholder={__('Add title...', 'wrt-theme')}
				className="stay-in-touch__title"
				tagName="h3"
				value={title}
			/>

			<RichText
				allowedFormats={[]}
				onChange={(description) => setAttributes({ description })}
				placeholder={__('Add description...')}
				className="stay-in-touch__description"
				tagName="p"
				value={description}
			/>

			<form action="" className="stay-in-touch__form">
				<input type="email" placeholder={__('Enter your email address', 'wrt-theme')} />
				<button type="button" className="button-secondary">
					{__('Subscribe', 'wrt-theme')}
				</button>
			</form>
		</div>
	);
};
