/**
 * WordPress dependencies
 */
import { combineReducers } from '@wordpress/data';

const DEFAULT_STATE = {};

function featuresById( state = DEFAULT_STATE, action ) {
	switch ( action.type ) {
		case 'REGISTER_FEATURE':
		case 'RECEIVE_FEATURE':
			return { ...state, [ action.feature.id ]: action.feature };
		case 'UNREGISTER_FEATURE': {
			const newState = { ...state };
			delete newState[ action.feature.id ];
			return newState;
		}
		case 'RECEIVE_FEATURES': {
			const newState = { ...state };
			action.features.forEach( ( feature ) => {
				newState[ feature.id ] = feature;
			} );
			return newState;
		}
		case 'REGISTER_FEATURE_CALLBACK': {
			const feature = state[ action.id ];
			if ( ! feature ) {
				return state;
			}
			return {
				...state,
				[ action.id ]: {
					...feature,
					callback: action.callback,
				},
			};
		}
		default:
			return state;
	}
}

export default combineReducers( {
	featuresById,
} );
