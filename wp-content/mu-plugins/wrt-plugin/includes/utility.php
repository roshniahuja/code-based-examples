<?php
/**
 * Utility functions for the plugin.
 *
 * This file is for custom helper functions.
 * These should not be confused with WordPress template
 * tags. Template tags typically use prefixing, as opposed
 * to Namespaces.
 *
 * @link https://developer.wordpress.org/themes/basics/template-tags/
 * @package WRTPlugin
 */

namespace WRTPlugin\Utility;

/**
 * Get asset info from extracted asset files
 *
 * @param string $slug Asset slug as defined in build/webpack configuration
 * @param string $attribute Optional attribute to get. Can be version or dependencies
 * @param string $path Default path to assets.
 * @return string|array
 */
function get_asset_info( $slug, $attribute = null, $path = WRT_PLUGIN_PATH ) {
	if ( file_exists( $path . 'dist/js/' . $slug . '.asset.php' ) ) {
		$asset = require $path . 'dist/js/' . $slug . '.asset.php';
	} elseif ( file_exists( $path . 'dist/css/' . $slug . '.asset.php' ) ) {
		$asset = require $path . 'dist/css/' . $slug . '.asset.php';
	} else {
		return null;
	}

	if ( ! empty( $attribute ) && isset( $asset[ $attribute ] ) ) {
		return $asset[ $attribute ];
	}

	return $asset;
}
