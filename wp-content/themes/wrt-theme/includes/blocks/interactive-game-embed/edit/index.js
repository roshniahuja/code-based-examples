import { useState } from '@wordpress/element';
import { useBlockProps, BlockControls } from '@wordpress/block-editor';
import { useEntityProp } from '@wordpress/core-data';
import { TextControl, Button, ToolbarGroup, ToolbarButton } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export const BlockEdit = (props) => {
	const blockProps = useBlockProps({
		className: 'alignfull',
	});

	const {
		context: { postId, postType },
	} = props;

	const [meta, setMeta] = useEntityProp('postType', postType, 'meta', postId);
	const [title] = useEntityProp('postType', postType, 'title', postId);
	const [gamePath, setGamePath] = useState('');
	const [isPreviewMode, setIsPreviewMode] = useState(false);

	const { game_path } = meta;

	const applyGamePath = () => {
		setMeta({
			game_path: gamePath,
		});
		setIsPreviewMode(true);
	};

	if (!isPreviewMode) {
		return (
			<div>
				<TextControl label="Game Path" value={gamePath} onChange={setGamePath} />
				<Button variant="secondary" onClick={applyGamePath}>
					Apply
				</Button>
			</div>
		);
	}

	return (
		<>
			<BlockControls>
				{gamePath && (
					<ToolbarGroup>
						<ToolbarButton
							icon="edit"
							label={__('Edit')}
							onClick={() => setIsPreviewMode(!isPreviewMode)}
							isActive={!isPreviewMode}
						/>
					</ToolbarGroup>
				)}
			</BlockControls>
			<div {...blockProps}>
				<iframe
					title={title}
					src={game_path}
					className="interactive-iframe"
					style={{ width: '100%', height: '80vw' }}
				/>
			</div>
		</>
	);
};
