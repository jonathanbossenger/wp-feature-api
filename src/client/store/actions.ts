/**
 * Internal dependencies
 */
import {
	REGISTER_FEATURE,
	RECEIVE_FEATURE,
	UNREGISTER_FEATURE,
	RECEIVE_FEATURES,
	REGISTER_FEATURE_CALLBACK,
} from './constants';

import { store } from './index';

// Action Creators
export function registerFeature( feature ) {
	return {
		type: REGISTER_FEATURE,
		feature,
	};
}

export function receiveFeature( feature ) {
	return {
		type: RECEIVE_FEATURE,
		feature,
	};
}

export function unregisterFeature( featureId ) {
	return {
		type: UNREGISTER_FEATURE,
		feature: { id: featureId },
	};
}

export function receiveFeatures( features ) {
	return {
		type: RECEIVE_FEATURES,
		features,
	};
}

export function registerFeatureCallback( id, callback ) {
	return async ( { registry, dispatch } ) => {
		const feature = await registry
			.resolveSelect( store )
			.getRegisteredFeature( id );
		if ( ! feature ) {
			return;
		}
		dispatch( {
			type: REGISTER_FEATURE_CALLBACK,
			id,
			callback,
		} );
	};
}
