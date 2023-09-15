<?php
/**
 * Plugin specific helpers.
 *
 * @package WRTPlugin
 */

namespace WRTPlugin;

/**
 * Get an initialized class by its full class name, including namespace.
 *
 * @param string $class_name The class name including the namespace.
 *
 * @return false|Module
 */
function get_module( $class_name ) {
	return \WRTPlugin\ModuleInitialization::instance()->get_class( $class_name );
}
