import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import { PostTaxonomiesHierarchicalTermSelector, store as editorStore } from '@wordpress/editor';
import { useBlockProps } from '@wordpress/block-editor';
import { usePopover } from './usePopover';

const TAXONOMY_SLUG = 'wat-grade';
const ATTRIBUTE_SLUG = 'wat-grades';

export const DumbGradesComponent = (props) => {
	const blockProps = useBlockProps({
		className: 'feature-card--grade',
	});

	const { selectedGrades } = props;

	const { toggleProps, Popover } = usePopover();
	const hasSelectedGrades = selectedGrades && selectedGrades.length > 0;
	return (
		<div {...blockProps} {...toggleProps}>
			{hasSelectedGrades &&
				selectedGrades.map((grade) => <span key={grade.id}>{grade.name} Grade</span>)}
			{!hasSelectedGrades && <span>{__('Add a grade...', 'wrt-theme')}</span>}
			<Popover>
				<div style={{ minWidth: '250px' }}>
					<PostTaxonomiesHierarchicalTermSelector slug={TAXONOMY_SLUG} />
				</div>
			</Popover>
		</div>
	);
};

export const useGrades = () => {
	const gradesProps = useSelect((select) => {
		const { getEditedPostAttribute } = select(editorStore);
		const { getEntityRecords } = select('core');

		// Get the evergreen terms.
		const grades = getEntityRecords('taxonomy', TAXONOMY_SLUG, {
			per_page: 100,
		});

		const selectedGradeIds = getEditedPostAttribute(ATTRIBUTE_SLUG);

		return {
			TAXONOMY_SLUG,
			ATTRIBUTE_SLUG,
			grades,
			selectedGrades: grades?.filter((grade) => selectedGradeIds?.includes(grade.id)) || [],
		};
	});

	const GradesComponent = (props) => <DumbGradesComponent {...gradesProps} {...props} />;

	return {
		...gradesProps,
		GradesComponent,
	};
};
