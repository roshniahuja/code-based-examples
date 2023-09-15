<?php
/**
 * Core plugin functionality.
 *
 * @package WRTPlugin
 *
 * @phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
 */

namespace WRTPlugin\Core;

use WRTPlugin\ModuleInitialization;
use \WP_Error;
use WRTPlugin\Utility;
use WRTPlugin\PostTypes\ProductPostType;
use WRTPlugin\Taxonomies\ProductTypeTaxonomy;

/**
 * Default setup routine
 *
 * @return void
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'init', $n( 'i18n' ) );
	add_action( 'init', $n( 'init' ), apply_filters( 'tenup_plugin_init_priority', 8 ) );
	add_action( 'wp_enqueue_scripts', $n( 'scripts' ) );
	add_action( 'wp_enqueue_scripts', $n( 'styles' ) );
	add_action( 'admin_enqueue_scripts', $n( 'admin_scripts' ) );
	add_action( 'admin_enqueue_scripts', $n( 'admin_styles' ) );

	// Editor styles. add_editor_style() doesn't work outside of a theme.
	add_filter( 'mce_css', $n( 'mce_css' ) );
	// Hook to allow async or defer on asset loading.
	add_filter( 'script_loader_tag', $n( 'script_loader_tag' ), 10, 2 );
	add_action('restrict_manage_posts', $n( 'wrt_filter_post_type_by_taxonomy' ) );

	add_action( 'pre_get_posts', $n( 'exclude_gated_content_posts_from_search' ) );
	do_action( 'tenup_plugin_loaded' );
}

/**
 * Registers the default textdomain.
 *
 * @return void
 */
function i18n() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'wrt-plugin' );
	load_textdomain( 'wrt-plugin', WP_LANG_DIR . '/wrt-plugin/wrt-plugin-' . $locale . '.mo' );
	load_plugin_textdomain( 'wrt-plugin', false, plugin_basename( WRT_PLUGIN_PATH ) . '/languages/' );
}

/**
 * Initializes the plugin and fires an action other plugins can hook into.
 *
 * @return void
 */
function init() {
	do_action( 'tenup_plugin_before_init' );

	// If the composer.json isn't found, trigger a warning.
	if ( ! file_exists( WRT_PLUGIN_PATH . 'composer.json' ) ) {
		add_action(
			'admin_notices',
			function() {
				$class = 'notice notice-error';
				/* translators: %s: the path to the plugin */
				$message = sprintf( __( 'The composer.json file was not found within %s. No classes will be loaded.', 'wrt-plugin' ), WRT_PLUGIN_PATH );

				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
			}
		);
		return;
	}

	ModuleInitialization::instance()->init_classes();

	// Register post meta.
	register_post_meta(
		'post',
		'_wat_selected_form',
		[
			'show_in_rest' => true,
			'single' => true,
			'type' => 'string',
			'auth_callback' => function() {
				return current_user_can( 'edit_posts' );
			},
		]
	);

	do_action( 'tenup_plugin_init' );
}

/**
 * Activate the plugin
 *
 * @return void
 */
function activate() {
	// First load the init scripts in case any rewrite functionality is being loaded
	init();
	flush_rewrite_rules();
}

/**
 * Deactivate the plugin
 *
 * Uninstall routines should be in uninstall.php
 *
 * @return void
 */
function deactivate() {

}


/**
 * The list of knows contexts for enqueuing scripts/styles.
 *
 * @return array
 */
function get_enqueue_contexts() {
	return [ 'admin', 'frontend', 'shared' ];
}

/**
 * Generate an URL to a script, taking into account whether SCRIPT_DEBUG is enabled.
 *
 * @param string $script Script file name (no .js extension)
 * @param string $context Context for the script ('admin', 'frontend', or 'shared')
 *
 * @return string|WP_Error URL
 */
function script_url( $script, $context ) {

	if ( ! in_array( $context, get_enqueue_contexts(), true ) ) {
		return new WP_Error( 'invalid_enqueue_context', 'Invalid $context specified in WRTPlugin script loader.' );
	}

	return WRT_PLUGIN_URL . "dist/js/${script}.js";

}

/**
 * Generate an URL to a stylesheet, taking into account whether SCRIPT_DEBUG is enabled.
 *
 * @param string $stylesheet Stylesheet file name (no .css extension)
 * @param string $context Context for the script ('admin', 'frontend', or 'shared')
 *
 * @return string URL
 */
function style_url( $stylesheet, $context ) {

	if ( ! in_array( $context, get_enqueue_contexts(), true ) ) {
		return new WP_Error( 'invalid_enqueue_context', 'Invalid $context specified in WRTPlugin stylesheet loader.' );
	}

	return WRT_PLUGIN_URL . "dist/css/${stylesheet}.css";

}

/**
 * Enqueue scripts for front-end.
 *
 * @return void
 */
function scripts() {

	wp_enqueue_script(
		'tenup_plugin_shared',
		script_url( 'shared', 'shared' ),
		Utility\get_asset_info( 'shared', 'dependencies' ),
		Utility\get_asset_info( 'shared', 'version' ),
		true
	);

	wp_enqueue_script(
		'tenup_plugin_frontend',
		script_url( 'frontend', 'frontend' ),
		Utility\get_asset_info( 'frontend', 'dependencies' ),
		Utility\get_asset_info( 'frontend', 'version' ),
		true
	);

}

/**
 * Enqueue scripts for admin.
 *
 * @return void
 */
function admin_scripts() {

	wp_enqueue_script(
		'tenup_plugin_shared',
		script_url( 'shared', 'shared' ),
		Utility\get_asset_info( 'shared', 'dependencies' ),
		Utility\get_asset_info( 'shared', 'version' ),
		true
	);

	wp_enqueue_script(
		'tenup_plugin_admin',
		script_url( 'admin', 'admin' ),
		Utility\get_asset_info( 'admin', 'dependencies' ),
		Utility\get_asset_info( 'admin', 'version' ),
		true
	);

}

/**
 * Enqueue styles for front-end.
 *
 * @return void
 */
function styles() {

	wp_enqueue_style(
		'tenup_plugin_shared',
		style_url( 'shared', 'shared' ),
		[],
		Utility\get_asset_info( 'shared', 'version' ),
	);

	if ( is_admin() ) {
		wp_enqueue_style(
			'tenup_plugin_admin',
			style_url( 'admin', 'admin' ),
			[],
			Utility\get_asset_info( 'admin', 'version' ),
		);
	} else {
		wp_enqueue_style(
			'tenup_plugin_frontend',
			style_url( 'frontend', 'frontend' ),
			[],
			Utility\get_asset_info( 'frontend', 'version' ),
		);
	}

}

/**
 * Enqueue styles for admin.
 *
 * @return void
 */
function admin_styles() {

	wp_enqueue_style(
		'tenup_plugin_shared',
		style_url( 'shared', 'shared' ),
		[],
		Utility\get_asset_info( 'shared', 'version' ),
	);

	wp_enqueue_style(
		'tenup_plugin_admin',
		style_url( 'admin', 'admin' ),
		[],
		Utility\get_asset_info( 'admin', 'version' ),
	);

}

/**
 * Enqueue editor styles. Filters the comma-delimited list of stylesheets to load in TinyMCE.
 *
 * @param string $stylesheets Comma-delimited list of stylesheets.
 * @return string
 */
function mce_css( $stylesheets ) {
	if ( ! empty( $stylesheets ) ) {
		$stylesheets .= ',';
	}

	return $stylesheets . WRT_PLUGIN_URL . ( ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ?
			'assets/css/frontend/editor-style.css' :
			'dist/css/editor-style.min.css' );
}

/**
 * Add async/defer attributes to enqueued scripts that have the specified script_execution flag.
 *
 * @link https://core.trac.wordpress.org/ticket/12009
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string
 */
function script_loader_tag( $tag, $handle ) {
	$script_execution = wp_scripts()->get_data( $handle, 'script_execution' );

	if ( ! $script_execution ) {
		return $tag;
	}

	if ( 'async' !== $script_execution && 'defer' !== $script_execution ) {
		return $tag; // _doing_it_wrong()?
	}

	// Abort adding async/defer for scripts that have this script as a dependency. _doing_it_wrong()?
	foreach ( wp_scripts()->registered as $script ) {
		if ( in_array( $handle, $script->deps, true ) ) {
			return $tag;
		}
	}

	// Add the attribute if it hasn't already been added.
	if ( ! preg_match( ":\s$script_execution(=|>|\s):", $tag ) ) {
		$tag = preg_replace( ':(?=></script>):', " $script_execution", $tag, 1 );
	}

	return $tag;
}

/**
 * Display a custom taxonomy dropdown in admin
 */
function wrt_filter_post_type_by_taxonomy( $post_type ) {
	$post_typename = ProductPostType::$name;
	$taxonomy      = ProductTypeTaxonomy::$name;
	if ( $post_typename !== $post_type ) {
		return;
	}

	$tax_data      = filter_input( INPUT_GET, 'wat-product-type', FILTER_SANITIZE_STRING );
	$selected      = isset( $tax_data ) ? $tax_data : '';
	$info_taxonomy = get_taxonomy( $taxonomy );

	if ( ! $info_taxonomy ) {
		return;
	};

	wp_dropdown_categories(
		array(
			'show_option_all' => sprintf( __( 'Show all %s', 'wrt-theme' ), $info_taxonomy->label ),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'value_field'     => 'slug',
			'selected'        => $selected,
			'hierarchical'    => true,
		)
	);
}

/**
 * Exclude gated content posts from search.
 *
 * @param \WP_Query $query
 *
 * @return void
 */
function exclude_gated_content_posts_from_search(\WP_Query $query ) {
	if ( ! $query->is_main_query() ) {
		return;
	}

	if ( is_admin() ) {
		return;
	}

	if ( ! $query->is_search() ) {
		return;
	}

	$meta_query = [
		'relation' => 'AND',
		[
			'relation' => 'OR',
			[
				'key'     => 'gwsa_require_submission',
				'compare' => 'NOT EXISTS',
			],
			[
				'key'     => 'gwsa_require_submission',
				'value'   => '1',
				'compare' => '!='
			],
		],
		[
			'relation' => 'OR',
			[
				'key'     => '_yoast_wpseo_meta-robots-noindex',
				'compare' => 'NOT EXISTS',
			],
			[
				'key'     => '_yoast_wpseo_meta-robots-noindex',
				'value'   => '1',
				'compare' => '!='
			],
		]
	];

	$current_meta_query = $query->get( 'meta_query' );
	if ( ! empty($current_meta_query) ) {
		$meta_query['relation'] = 'AND';
		$meta_query = array_merge( $current_meta_query, $meta_query );
	}

	$query->set( 'meta_query', $meta_query );
}
