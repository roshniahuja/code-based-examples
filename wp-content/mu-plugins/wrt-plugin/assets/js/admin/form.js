import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { useState, useEffect } from '@wordpress/element';
import { SelectControl } from '@wordpress/components';
import { useDispatch, useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

const FormSelector = () => {
	const [formOptions, setFormOptions] = useState([
		{
			value: '',
			label: __('Select a form', 'wrt-plugin'),
		},
	]);
	const meta = useSelect((select) => select('core/editor').getEditedPostAttribute('meta'), []);
	const formId = meta?._wat_selected_form;
	const { editPost } = useDispatch('core/editor');

	useEffect(() => {
		fetch('/wp-json/gf/v2/forms', {
			credentials: 'include',
		})
			.then((response) => response.json())
			.then((forms) => {
				const options = Object.values(forms).map((form) => ({
					label: form.title,
					value: form.id,
				}));

				// Set Form Options.
				setFormOptions((prevOptions) => [...prevOptions, ...options]);
			});
	}, []);

	const updateFormId = (selectedForm) => {
		editPost({ meta: { ...meta, _wat_selected_form: selectedForm } });
	};

	return (
		<PluginDocumentSettingPanel name="form-selector" title={__('Form Selector', 'wrt-plugin')}>
			<SelectControl
				label={__('Form', 'wrt-plugin')}
				value={formId}
				options={formOptions}
				onChange={updateFormId}
			/>
		</PluginDocumentSettingPanel>
	);
};

registerPlugin('form-selector', { render: FormSelector });
