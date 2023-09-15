import { controls } from '@wordpress/data-controls';
import reducer from './reducer';
import * as actions from './actions';
import * as selectors from './selectors';
import * as resolvers from './resolvers';

const STORE_NAME = 'wrt/post-picker-data';

const STORE_CONFIG = {
	selectors,
	actions,
	resolvers,
	reducer,
	controls,
};

export { STORE_NAME, STORE_CONFIG };
