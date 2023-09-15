import { SVG, Path } from '@wordpress/components';

export const Icon = () => {
	return (
		<SVG
			width="24"
			height="24"
			viewBox="0 0 24 24"
			fill="none"
			xmlns="http://www.w3.org/2000/svg"
		>
			<Path
				fillRule="evenodd"
				clipRule="evenodd"
				d="M5.5 5.5V18.5H18.5V5.5H5.5ZM5 4C4.44772 4 4 4.44772 4 5V19C4 19.5523 4.44772 20 5 20H19C19.5523 20 20 19.5523 20 19V5C20 4.44772 19.5523 4 19 4H5Z"
				fill="black"
			/>
			<Path fillRule="evenodd" clipRule="evenodd" d="M16.5 8H13.5V7H16.5V8Z" fill="black" />
			<Path
				fillRule="evenodd"
				clipRule="evenodd"
				d="M16.5 13.5H7.5V12.5H16.5V13.5Z"
				fill="black"
			/>
			<Path
				fillRule="evenodd"
				clipRule="evenodd"
				d="M16.5 16.5H7.5V15.5H16.5V16.5Z"
				fill="black"
			/>
			<Path
				fillRule="evenodd"
				clipRule="evenodd"
				d="M20 10.5L5 10.5L5 9.5L20 9.5L20 10.5Z"
				fill="black"
			/>
		</SVG>
	);
};
