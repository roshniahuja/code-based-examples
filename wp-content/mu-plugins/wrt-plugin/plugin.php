<?php

// Useful global constants.
define( 'WRT_PLUGIN_VERSION', '0.1.0' );
define( 'WRT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WRT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WRT_PLUGIN_INC', WRT_PLUGIN_PATH . 'includes/' );

$is_local_env = in_array( wp_get_environment_type(), [ 'local', 'development' ], true );
$is_local_url = strpos( home_url(), '.test' ) || strpos( home_url(), '.local' );
$is_local     = $is_local_env || $is_local_url;

if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG && file_exists( __DIR__ . '/dist/fast-refresh.php' ) ) {
	require_once __DIR__ . '/dist/fast-refresh.php';
	TenUpToolkit\set_dist_url_path( basename( __DIR__ ), WRT_PLUGIN_URL . 'dist/', WRT_PLUGIN_PATH . 'dist/' );
}

// Require Composer autoloader if it exists.
if ( file_exists( WRT_PLUGIN_PATH . 'vendor/autoload.php' ) ) {
	require_once WRT_PLUGIN_PATH . 'vendor/autoload.php';
}

// Include files.
require_once WRT_PLUGIN_INC . '/utility.php';
require_once WRT_PLUGIN_INC . '/core.php';

// Activation/Deactivation.
register_activation_hook( __FILE__, '\WRTPlugin\Core\activate' );
register_deactivation_hook( __FILE__, '\WRTPlugin\Core\deactivate' );

// Bootstrap.
WRTPlugin\Core\setup();
