<?php
/**
 * WP Theme constants and setup functions
 *
 * @package WRTTheme
 */

// Useful global constants.
define( 'WRT_THEME_VERSION', '0.1.0' );
define( 'WRT_THEME_TEMPLATE_URL', get_template_directory_uri() );
define( 'WRT_THEME_PATH', get_template_directory() . '/' );
define( 'WRT_THEME_DIST_PATH', WRT_THEME_PATH . 'dist/' );
define( 'WRT_THEME_DIST_URL', WRT_THEME_TEMPLATE_URL . '/dist/' );
define( 'WRT_THEME_INC', WRT_THEME_PATH . 'includes/' );
define( 'WRT_THEME_BLOCK_DIR', WRT_THEME_INC . 'blocks/' );
define( 'WRT_THEME_BLOCK_DIST_DIR', WRT_THEME_PATH . 'dist/blocks/' );
define( 'WAT_URL', get_stylesheet_directory_uri() );
define( 'WAT_PATH', get_stylesheet_directory() . '/' );

$is_local_env = in_array( wp_get_environment_type(), [ 'local', 'development' ], true );
$is_local_url = strpos( home_url(), '.test' ) || strpos( home_url(), '.local' );
$is_local     = $is_local_env || $is_local_url;

if ( $is_local && file_exists( __DIR__ . '/dist/fast-refresh.php' ) ) {
	require_once __DIR__ . '/dist/fast-refresh.php';
	TenUpToolkit\set_dist_url_path( basename( __DIR__ ), WRT_THEME_DIST_URL, WRT_THEME_DIST_PATH );
}

require_once WRT_THEME_INC . 'core.php';
require_once WRT_THEME_INC . 'overrides.php';
require_once WRT_THEME_INC . 'template-tags.php';
require_once WRT_THEME_INC . 'utility.php';
require_once WRT_THEME_INC . 'blocks.php';
require_once WRT_THEME_INC . 'helpers.php';
require_once WRT_THEME_INC . 'curation-locations.php';

// Meta Box
require_once WRT_THEME_INC . 'meta-boxes/campaign.php';
require_once WRT_THEME_INC . 'meta-boxes/affiliate.php';
//require_once WRT_THEME_INC . 'meta-boxes/social-connect.php';

// Gravity Forms Support.
require_once WRT_THEME_INC . 'gravity-forms/school-field.php';
require_once WRT_THEME_INC . 'gravity-forms/user-data-sync.php';

// Rest API Support.
require_once WRT_THEME_INC . 'rest-api/schools.php';
require_once WRT_THEME_INC . 'rest-api/school-names.php';

// Run the setup functions.
WRTTheme\Core\setup();
WRTTheme\Blocks\setup();
WRTTheme\Overrides\setup();
WRTTheme\GravityForms\SchoolField\setup();
WRTTheme\GravityForms\UserDataSync\setup();
WRTTheme\RestAPI\Schools\setup();
WRTTheme\RestAPI\SchoolNames\setup();

// Meta Boxes.
WRTTheme\Meta_Boxes\Affiliate\setup();

// Require Composer autoloader if it exists.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

if ( ! function_exists( 'wp_body_open' ) ) {

	/**
	 * Shim for the new wp_body_open() function that was added in 5.2
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}
