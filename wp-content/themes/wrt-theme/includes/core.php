<?php
/**
 * Core setup, site hooks and filters.
 *
 * @package WRTTheme
 */

namespace WRTTheme\Core;

use WRTPlugin\ModuleInitialization;
use WRTPlugin\Utility;
use WP_Block_Type_Registry;
/**
 * Set up theme defaults and register supported WordPress features.
 *
 * @return void
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'init', $n( 'init' ), apply_filters( 'wrt_theme_init_priority', 8 ) );
	add_action( 'after_setup_theme', $n( 'i18n' ) );
	add_action( 'after_setup_theme', $n( 'theme_setup' ) );
	add_action( 'wp_enqueue_scripts', $n( 'scripts' ) );
	add_action( 'admin_enqueue_scripts', $n( 'admin_styles' ) );
	add_action( 'admin_enqueue_scripts', $n( 'admin_scripts' ) );
	add_action( 'enqueue_block_editor_assets', $n( 'core_block_overrides' ) );
	add_action( 'wp_enqueue_scripts', $n( 'styles' ) );
	add_action( 'wp_head', $n( 'js_detection' ), 0 );
	add_action( 'wp_head', $n( 'embed_ct_css' ), 0 );
	add_action( 'wp_head', $n( 'embed_gtm_head' ), 0 );
	add_action( 'wp_body_open', $n( 'embed_gtm_body' ), 0 );

	add_filter( 'script_loader_tag', $n( 'script_loader_tag' ), 10, 2 );
	add_action( 'widgets_init', $n( 'register_sidebars' ) );
	add_action( 'after_setup_theme', $n( 'add_image_sizes' ) );
	add_filter( 'allowed_block_types_all', $n( 'disable_core_blocks' ), 10, 2 );
	add_action( 'template_redirect', $n( 'redirect_to_404' ) );
}

/**
 * Initializes the theme classes and fires an action plugins can hook into.
 *
 * @return void
 */
function init() {
	do_action( 'wrt_theme_before_init' );

	// If the composer.json isn't found, trigger a warning.
	if ( ! file_exists( WRT_THEME_PATH . 'composer.json' ) ) {
		add_action(
			'admin_notices',
			function() {
				$class = 'notice notice-error';
				/* translators: %s: the path to the plugin */
				$message = sprintf( __( 'The composer.json file was not found within %s. No classes will be loaded.', 'wrt-theme' ), WRT_THEME_PATH );

				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
			}
		);
		return;
	}

	ModuleInitialization::instance()->init_classes();
	do_action( 'wrt_theme_init' );
}

/**
 * Makes Theme available for translation.
 *
 * Translations can be added to the /languages directory.
 * If you're building a theme based on "wrt-theme", change the
 * filename of '/languages/WRTTheme.pot' to the name of your project.
 *
 * @return void
 */
function i18n() {
	load_theme_textdomain( 'wrt-theme', WRT_THEME_PATH . '/languages' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function theme_setup() {
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'editor-styles' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'gallery',
		)
	);
	add_theme_support( 'custom-logo' );

	add_editor_style( 'dist/css/frontend.css' );

	remove_theme_support( 'core-block-patterns' );

	// by adding the `theme.json` file block templates automatically get enabled.
	// because the template editor will need additional QA and work to get right
	// the default is to disable this feature.
	remove_theme_support( 'block-templates' );

	// This theme uses wp_nav_menu() in three locations.
	register_nav_menus(
		array(
			'primary'   => esc_html__( 'Primary Menu', 'wrt-theme' ),
			'auxillary' => esc_html__( 'Auxillary Menu', 'wrt-theme' ),
			'footer'    => esc_html__( 'Footer Menu', 'wrt-theme' ),
			'footer-minimal'    => esc_html__( 'Footer Minimal Menu', 'wrt-theme' ),
		)
	);
}

/**
 * Enqueue scripts for front-end.
 *
 * @return void
 */
function scripts() {

	/**
	 * Enqueuing frontend.js is required to get css hot reloading working in the frontend
	 * If you're not shipping any front-end js wrap this enqueue in a SCRIPT_DEBUG check.
	 */
	wp_enqueue_script(
		'frontend',
		WRT_THEME_TEMPLATE_URL . '/dist/js/frontend.js',
		Utility\get_asset_info( 'frontend', 'dependencies', WRT_THEME_PATH ),
		Utility\get_asset_info( 'frontend', 'version', WRT_THEME_PATH ),
		true
	);

	if ( is_archive() && ! is_search() ) {
		wp_localize_script(
			'frontend',
			'wrtTheme',
			array(
				'category' => get_queried_object()->cat_ID,
			)
		);
	}

	if ( is_page_template( 'templates/page-styleguide.php' ) ) {
		wp_enqueue_script(
			'styleguide',
			WRT_THEME_TEMPLATE_URL . '/dist/js/styleguide.js',
			Utility\get_asset_info( 'styleguide', 'dependencies', WRT_THEME_PATH ),
			Utility\get_asset_info( 'styleguide', 'version', WRT_THEME_PATH ),
			true
		);
	}

	/**
	 * Enqueuing shared.js is required to get css hot reloading working in the frontend
	 * If you're not shipping any shared js wrap this enqueue in a SCRIPT_DEBUG check.
	 */

	/*
	 * Uncomment this to use the shared.js file.
		wp_enqueue_script(
			'shared',
			WRT_THEME_TEMPLATE_URL . '/dist/js/shared.js',
			Utility\get_asset_info( 'shared', 'dependencies' ),
			Utility\get_asset_info( 'shared', 'version' ),
			true
		);
	*/

}

/**
 * Enqueue scripts for admin
 *
 * @return void
 */
function admin_scripts() {
	wp_enqueue_script(
		'admin',
		WRT_THEME_TEMPLATE_URL . '/dist/js/admin.js',
		Utility\get_asset_info( 'admin', 'dependencies', WRT_THEME_PATH ),
		Utility\get_asset_info( 'admin', 'version', WRT_THEME_PATH ),
		true
	);

	/*
	 * Uncomment this to use the shared.js file.
		wp_enqueue_script(
			'shared',
			WRT_THEME_TEMPLATE_URL . '/dist/js/shared.js',
			Utility\get_asset_info( 'shared', 'dependencies' ),
			Utility\get_asset_info( 'shared', 'version' ),
			true
		);
	*/
}

/**
 * Enqueue core block filters, styles and variations.
 *
 * @return void
 */
function core_block_overrides() {
	$overrides = WRT_THEME_DIST_PATH . 'js/core-block-overrides.asset.php';
	if ( file_exists( $overrides ) ) {
		$dep = require_once $overrides;
		wp_enqueue_script(
			'core-block-overrides',
			WRT_THEME_DIST_URL . 'js/core-block-overrides.js',
			$dep['dependencies'],
			$dep['version'],
			true
		);
	}
}

/**
 * Enqueue styles for admin
 *
 * @return void
 */
function admin_styles() {
	wp_register_style(
		'wat-select2-css',
		WAT_URL . '/bower_components/select2/dist/css/select2.min.css',
		array(),
		'4.0.3'
	);

	wp_enqueue_style(
		'admin-style',
		WRT_THEME_TEMPLATE_URL . '/dist/css/admin.css',
		[],
		Utility\get_asset_info( 'admin-style', 'version', WRT_THEME_PATH )
	);

	/*
	 * Uncomment this to use the shared.css file.
		wp_enqueue_style(
			'shared-style',
			WRT_THEME_TEMPLATE_URL . '/dist/css/shared.css',
			[],
			Utility\get_asset_info( 'shared', 'version' )
		);
	*/
}

/**
 * Enqueue styles for front-end.
 *
 * @return void
 */
function styles() {

	wp_enqueue_style(
		'styles',
		WRT_THEME_TEMPLATE_URL . '/dist/css/frontend.css',
		[],
		Utility\get_asset_info( 'frontend', 'version', WRT_THEME_PATH )
	);

	if ( is_page_template( 'templates/page-styleguide.php' ) ) {
		wp_enqueue_style(
			'styleguide',
			WRT_THEME_TEMPLATE_URL . '/dist/css/styleguide.css',
			[],
			Utility\get_asset_info( 'styleguide-style', 'version', WRT_THEME_PATH )
		);
	}

}

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @return void
 */
function js_detection() {

	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
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
		return $tag;
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
 * Inlines ct.css in the head
 *
 * Embeds a diagnostic CSS file written by Harry Roberts
 * that helps diagnose render blocking resources and other
 * performance bottle necks.
 *
 * The CSS is inlined in the head of the document, only when requesting
 * a page with the query param ?debug_perf=1
 *
 * @link https://csswizardry.com/ct/
 * @return void
 */
function embed_ct_css() {

	$debug_performance = rest_sanitize_boolean( filter_input( INPUT_GET, 'debug_perf', FILTER_SANITIZE_NUMBER_INT ) );

	if ( ! $debug_performance ) {
		return;
	};

	wp_register_style( 'ct', false ); // phpcs:ignore
	wp_enqueue_style( 'ct' );
	wp_add_inline_style( 'ct', 'head{--ct-is-problematic:solid;--ct-is-affected:dashed;--ct-notify:#0bce6b;--ct-warn:#ffa400;--ct-error:#ff4e42}head,head [rel=stylesheet],head script,head script:not([src])[async],head script:not([src])[defer],head script~meta[http-equiv=content-security-policy],head style,head>meta[charset]:not(:nth-child(-n+5)){display:block}head [rel=stylesheet],head script,head script~meta[http-equiv=content-security-policy],head style,head title,head>meta[charset]:not(:nth-child(-n+5)){margin:5px;padding:5px;border-width:5px;background-color:#fff;color:#333}head ::before,head script,head style{font:16px/1.5 monospace,monospace;display:block}head ::before{font-weight:700}head link[rel=stylesheet],head script[src]{border-style:var(--ct-is-problematic);border-color:var(--ct-warn)}head script[src]::before{content:"[Blocking Script – " attr(src) "]"}head link[rel=stylesheet]::before{content:"[Blocking Stylesheet – " attr(href) "]"}head script:not(:empty),head style:not(:empty){max-height:5em;overflow:auto;background-color:#ffd;white-space:pre;border-color:var(--ct-notify);border-style:var(--ct-is-problematic)}head script:not(:empty)::before{content:"[Inline Script] "}head style:not(:empty)::before{content:"[Inline Style] "}head script:not(:empty)~title,head script[src]:not([async]):not([defer]):not([type=module])~title{display:block;border-style:var(--ct-is-affected);border-color:var(--ct-error)}head script:not(:empty)~title::before,head script[src]:not([async]):not([defer]):not([type=module])~title::before{content:"[<title> blocked by JS] "}head [rel=stylesheet]:not([media=print]):not(.ct)~script,head style:not(:empty)~script{border-style:var(--ct-is-affected);border-color:var(--ct-warn)}head [rel=stylesheet]:not([media=print]):not(.ct)~script::before,head style:not(:empty)~script::before{content:"[JS blocked by CSS – " attr(src) "]"}head script[src][src][async][defer]{display:block;border-style:var(--ct-is-problematic);border-color:var(--ct-warn)}head script[src][src][async][defer]::before{content:"[async and defer is redundant: prefer defer – " attr(src) "]"}head script:not([src])[async],head script:not([src])[defer]{border-style:var(--ct-is-problematic);border-color:var(--ct-warn)}head script:not([src])[async]::before{content:"The async attribute is redundant on inline scripts"}head script:not([src])[defer]::before{content:"The defer attribute is redundant on inline scripts"}head [rel=stylesheet][href^="//"],head [rel=stylesheet][href^=http],head script[src][src][src^="//"],head script[src][src][src^=http]{border-style:var(--ct-is-problematic);border-color:var(--ct-error)}head script[src][src][src^="//"]::before,head script[src][src][src^=http]::before{content:"[Third Party Blocking Script – " attr(src) "]"}head [rel=stylesheet][href^="//"]::before,head [rel=stylesheet][href^=http]::before{content:"[Third Party Blocking Stylesheet – " attr(href) "]"}head script~meta[http-equiv=content-security-policy]{border-style:var(--ct-is-problematic);border-color:var(--ct-error)}head script~meta[http-equiv=content-security-policy]::before{content:"[Meta CSP defined after JS]"}head>meta[charset]:not(:nth-child(-n+5)){border-style:var(--ct-is-problematic);border-color:var(--ct-warn)}head>meta[charset]:not(:nth-child(-n+5))::before{content:"[Charset should appear as early as possible]"}link[rel=stylesheet].ct,link[rel=stylesheet][media=print],script[async],script[defer],script[type=module],style.ct{display:none}' );

}

/**
 * Embeds Google Tag Manager Script.
 *
 * @return void
 */
function embed_gtm_head() {
	?>
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-P6WFWQJ');</script>
	<!-- End Google Tag Manager -->
	<?php
}

/**
 * Embeds Google Tag Manager Script.
 *
 * @return void
 */
function embed_gtm_body() {
	?>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P6WFWQJ"
	                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<?php
}

/**
 * Register widgets.
 *
 * @action widgets_init
 * @return void
 * @since 1.0.0
 */
function register_sidebars() {

	register_sidebar(
		array(
			'name'           => esc_html__( 'Footer', 'wrt-theme' ),
			'id'             => 'footer-1',
			'description'    => esc_html__( 'Add widgets here.', 'wrt-theme' ),
			'before_sidebar' => '<div id="%1$s" class="footer-1">',
			'after_sidebar'  => '</div>',
			'before_widget'  => '',
			'after_widget'   => '',
			'before_title'   => '<h2 class="widget-title">',
			'after_title'    => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'           => esc_html__( 'Footer Copyright', 'wrt-theme' ),
			'id'             => 'footer-copyright',
			'description'    => esc_html__( 'Add widgets here.', 'wrt-theme' ),
			'before_sidebar' => '<div id="%1$s" class="footer-copyright">',
			'after_sidebar'  => '</div>',
			'before_widget'  => '',
			'after_widget'   => '',
			'before_title'   => '<h2 class="widget-title">',
			'after_title'    => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'           => esc_html__( 'Header Notice Bar', 'wrt-theme' ),
			'id'             => 'notice-bar',
			'description'    => esc_html__( 'Add widgets here.', 'wrt-theme' ),
			'before_sidebar' => '<div id="%1$s" class="notice-bar-content">',
			'after_sidebar'  => '</div>',
			'before_widget'  => '',
			'after_widget'   => '',
			'before_title'   => '<h2 class="widget-title">',
			'after_title'    => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'           => esc_html__( 'Affiliate Disclosure', 'wrt-theme' ),
			'id'             => 'affiliate-disclosure',
			'description'    => esc_html__( 'Add widgets here.', 'wrt-theme' ),
			'before_sidebar' => '<div id="%1$s" class="affiliate-discloser">',
			'after_sidebar'  => '</div>',
			'before_widget'  => '',
			'after_widget'   => '',
			'before_title'   => '<h2 class="widget-title">',
			'after_title'    => '</h2>',
		)
	);
}

/**
 * Image sizes
 */
function add_image_sizes() {
	add_image_size( 'sponsor-logo', 220, 122 );
}

/**
 * This function removes specific core blocks programmatically.
 *
 * @param array $allowed_blocks Array of allowed block types.
 * @return array Modified array of allowed block types.
 */
function disable_core_blocks( $allowed_blocks ) {
	// get all the registered blocks
	$blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

	// then disable some of them
	unset( $blocks['core/code'] );
	unset( $blocks['core/preformatted'] );
	unset( $blocks['core/verse'] );
	unset( $blocks['core/navigation'] );
	unset( $blocks['core/site-logo'] );
	unset( $blocks['core/site-title'] );
	unset( $blocks['core/site-tagline'] );
	unset( $blocks['core/query'] );
	unset( $blocks['core/avatar'] );
	unset( $blocks['core/post-title'] );
	unset( $blocks['core/post-excerpt'] );
	unset( $blocks['core/post-featured-image'] );
	unset( $blocks['core/post-content'] );
	unset( $blocks['core/post-author'] );
	unset( $blocks['core/post-date'] );
	unset( $blocks['core/post-terms'] );
	unset( $blocks['core/post-navigation-link'] );
	unset( $blocks['core/read-more'] );
	unset( $blocks['core/comments-query-loop'] );
	unset( $blocks['core/post-comments-form'] );
	unset( $blocks['core/loginout'] );
	unset( $blocks['core/term-description'] );
	unset( $blocks['core/query-title'] );
	unset( $blocks['core/post-author-biography'] );
	unset( $blocks['core/archives'] );
	unset( $blocks['core/calendar'] );
	unset( $blocks['core/categories'] );
	unset( $blocks['core/latest-comments'] );
	unset( $blocks['core/latest-posts'] );
	unset( $blocks['core/page-list'] );
	unset( $blocks['core/rss'] );
	unset( $blocks['core/search'] );
	unset( $blocks['core/tag-cloud'] );

	// return the new list of allowed blocks
	return array_keys( $blocks );
}

/**
 * Redirects to 404 if author has no post.
 *
 * @return void
 */
function redirect_to_404() {
	global $wp_query;
	if ( is_author() && ! $wp_query->is_404 && empty( $wp_query->posts ) ) {
		wp_safe_redirect( esc_url( home_url( '/404' ) ) );
		exit;
	}
}
