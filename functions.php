<?php

/**
 * Boo Energy functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Boo_Energy
 */


if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function boo_energy_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Boo Energy, use a find and replace
	 * to change 'boo-energy' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'boo-energy', BOO_THEME_DIR . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Add support for responsive embedded content.
	add_theme_support( 'responsive-embeds' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails', array( 'post', 'studion', 'skolan', 'team_members' ) );


	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'boo-energy' ),
			'menu-2' => esc_html__( 'Secendary', 'boo-energy' ),
			'menu-3' => esc_html__( 'Topbar Left', 'boo-energy' ),
			'menu-4' => esc_html__( 'Topbar Right', 'boo-energy' ),
			'menu-5' => esc_html__( 'Footer Copyright', 'boo-energy' ),
		)
	);

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Add Support For Post Formats

	add_theme_support(
		'post-formats',
		array(
			'image',
			'audio',
			'video',
			'gallery',
			'quote',
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height' => 250,
			'width' => 250,
			'flex-width' => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'boo_energy_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function boo_energy_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'boo_energy_content_width', 640 );
}
add_action( 'after_setup_theme', 'boo_energy_content_width', 0 );

// Wp Body Open
if ( ! function_exists( 'wp_body_open' ) ) {
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

// Support For Shortcode on Custom HTML
add_filter( 'widget_custom_html_content', 'do_shortcode' );

// Define Boo all Directory

define( 'BOO_THEME_DIR', get_template_directory() );
define( 'BOO_THEME_URI', get_template_directory_uri() );

define( 'BOO_THEME_CSS_DIR', BOO_THEME_URI . '/assets/css/' );
define( 'BOO_THEME_JS_DIR', BOO_THEME_URI . '/assets/js/' );
define( 'BOO_THEME_IMG_DIR', BOO_THEME_URI . '/assets/img/' );
define( 'BOO_THEME_INC', BOO_THEME_DIR . '/inc/' );

/**
 * Custom template tags for this theme.
 */
require BOO_THEME_INC . 'template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require BOO_THEME_INC . 'template-functions.php';

/**
 * Customizer additions.
 */
require BOO_THEME_INC . 'customizer.php';

/**
 *  Widgets
 */
require BOO_THEME_INC . 'boo-widgets.php';

/**
 * include Boo Energy functions file
 */

require_once BOO_THEME_INC . '/common/boo-scripts.php';
require_once BOO_THEME_INC . '/common/boo-breadcrumb.php';

/**
 * Include Boo Energy Shorcodes
 */

require_once BOO_THEME_INC . 'boo-shortcodes.php';

/**
 * Include Boo Energy TGM
 */
require_once BOO_THEME_INC . 'boo-tgm.php';

/**
 * include Boo Energy Customizer
 */
require_once BOO_THEME_INC . 'boo-customizer.php';


/**
 * include Boo Energy Api
 */
require_once BOO_THEME_INC . '/api/boo-api.php';

/**
 * include Boo Energy CPT
 */
require_once BOO_THEME_INC . 'boo-ctp.php';

/**
 * include the custom walker
 */
require_once BOO_THEME_INC . 'class-wp-bootstrap-navwalker.php';

/**
 * include the Header Extend using ACF & navwalker
 */
require_once BOO_THEME_INC . 'boo-header-extend.php';
/**
 * include the notification-popup
 */
require_once BOO_THEME_DIR . '/inc/notification-popup/notification-popup.php';
/**
 * include the imbox faq
 */
require_once BOO_THEME_DIR . '/inc/imbox/faq-imbox.php';
/**
 * include the purchase flow
 */
require_once BOO_THEME_DIR . '/flow/purchase-flow.php';

/**
 * include the Our Price Meta Box and Shortcode
 */
require_once BOO_THEME_INC . 'custom-meta-box/our-price-meta-box.php';


/**
 * include the Electricity Grids Meta Box and Shortcode
 */
require_once BOO_THEME_INC . 'custom-meta-box/electricity-grids-meta-box.php';


/**
 * include the Notification bar
 */
require_once BOO_THEME_INC . 'notification-bar/notification-bar.php';



/**
 * include the Email Template
 */
require_once BOO_THEME_INC . 'email-template/boo-email-template.php';

/**
 * Summary of boo_get_iphone_customizer_image
 * @return string
 */
function boo_get_iphone_customizer_image() {
	$image_url = get_theme_mod( 'boo_iphone_image_src' );
	if ( $image_url ) {
		return '<img src="' . esc_url( $image_url ) . '" alt="iPhone Image">';
	}
	return '<img src="' . esc_url( BOO_THEME_IMG_DIR . 'mobile-mockup.png' ) . '" alt="Mobile Mockup">';
}
add_shortcode( 'boo_iphone_image', 'boo_get_iphone_customizer_image' );



function boo_get_laptop_customizer_image() {
	$image_url = get_theme_mod( 'boo_laptop_image_src' );
	if ( $image_url ) {
		return '<img src="' . esc_url( $image_url ) . '" alt="Macbook Mockup">';
	}
	return '<img src="' . esc_url( BOO_THEME_IMG_DIR . 'macbook.png' ) . '" alt="Macbook Mockup">';
}
add_shortcode( 'boo_laptop_image', 'boo_get_laptop_customizer_image' );


function set_default_acf_page_menu( $value, $post_id, $field ) {
	// Ensure we are in the WordPress admin panel
	if ( is_admin() ) {
		// Only apply this logic when the post is new and not yet saved
		if ( ! metadata_exists( 'post', $post_id, 'boo_page_menu' ) ) {
			// Check if the URL contains "Företag"
			if ( isset( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], 'foretag' ) !== false ) {
				return 'business-menu'; // Set to "business"
			} else {
				return 'private-menu'; // Default to "private"
			}
		}
	}

	// Return existing value if it's already set
	return $value;
}
add_filter( 'acf/load_value/name=boo_page_menu', 'set_default_acf_page_menu', 10, 3 );



