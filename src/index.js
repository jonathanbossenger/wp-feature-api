/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { getBlockTypes } from '@wordpress/blocks';
import './client/index';

alert(getBlockTypes().length);
