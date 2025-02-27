<?php

/**
 * boo_theme_scripts description
 *
 * @return [type] [description]
 */

function boo_theme_scripts() {
	/**
	 * all css
	 */
	wp_enqueue_style( 'bootstrap', BOO_THEME_CSS_DIR . 'bootstrap.min.css', null, '5.0', 'all' );
	wp_enqueue_style( 'Price-details', BOO_THEME_CSS_DIR . 'price-details.css', null, '1.0', 'all' );
	wp_enqueue_style( 'boo-aniamtion', BOO_THEME_CSS_DIR . 'boo-custom-animation.css', null, null, 'all' );
	wp_enqueue_style( 'boo-main', BOO_THEME_CSS_DIR . 'style.css', null, null, 'all' );
	wp_enqueue_style( 'boo-spacing', BOO_THEME_CSS_DIR . 'boo-spacing.css', null, null, 'all' );

	wp_enqueue_style( 'boo-megamenu', BOO_THEME_CSS_DIR . 'boo-megamenu.css', null, null, 'all' );



	wp_enqueue_style( 'boo-slick-slider', BOO_THEME_CSS_DIR . 'slick.min.css', null, '1.9.0', 'all' );
	wp_enqueue_style( 'boo-section-slider', BOO_THEME_CSS_DIR . 'boo-section-slider.css', array( 'boo-slick-slider' ), '1.0.0', 'all' );

	wp_enqueue_style( 'boo-style', get_stylesheet_uri() );

	// CSS based on Section / Page
	if ( is_page() || is_single() || is_home() || is_page_template( 'template-boo-skolan.php' ) ) {
		wp_enqueue_style( 'boo-single-post', BOO_THEME_CSS_DIR . 'boo-single-post.css', null, '1.0.0', 'all' );
		wp_enqueue_style( 'magnific-popup', BOO_THEME_CSS_DIR . 'magnific-popup.min.css', null, '1.1.0', 'all' );
	}
	if ( is_home() && ! is_front_page() || is_single() || is_page_template( 'template-boo-skolan.php' ) ) {
		wp_enqueue_style( 'boo-blog', BOO_THEME_CSS_DIR . 'boo-blog-post.css', null, '1.0.0', 'all' );
	}
	if ( is_404() ) {
		wp_enqueue_style( 'boo-error', BOO_THEME_CSS_DIR . 'boo-error.css', null, '1.0.0', 'all' );
	}
	if ( is_search() ) {
		wp_enqueue_style( 'boo-search-result', BOO_THEME_CSS_DIR . 'boo-search-result.css', null, '1.0.0', 'all' );
	}

	// all js
	wp_enqueue_script( 'bootstrap', BOO_THEME_JS_DIR . 'bootstrap.min.js', array( 'jquery' ), '5.0', true );
	if ( ! wp_is_mobile() ) {
		wp_enqueue_script( 'boo-megamenu', BOO_THEME_JS_DIR . 'boo-megamenu.js', null, '1.0.0', true );

	}
	if ( wp_is_mobile() ) {
		// wp_enqueue_script( 'boo-megamenu-mobile', BOO_THEME_JS_DIR . 'boo-megamenu-mobile.js', null, '1.0.0', true );

	}

	wp_enqueue_script( 'boo-slick-slider', BOO_THEME_JS_DIR . 'slick.min.js', array( 'jquery' ), '1.9.0', true );
	wp_enqueue_script( 'magnific-popup', BOO_THEME_JS_DIR . 'jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
	wp_enqueue_script( 'boo-main', BOO_THEME_JS_DIR . 'main.js', array( 'jquery' ), false, true );


	// JS based on Section / Page


	wp_enqueue_script( 'boo-live-search', BOO_THEME_JS_DIR . 'boo-live-search.js', array( 'jquery' ), '1.1.0', true );
	wp_localize_script( 'boo-live-search', 'boo_live_search_params', [ 
		'ajax_url' => admin_url( 'admin-ajax.php' ),
	] );


	// Boo notification ajax
	wp_enqueue_script( 'boo-notification-ajax', BOO_THEME_JS_DIR . 'boo-notification-ajax.js', array( 'jquery' ), null, true );
	wp_localize_script( 'boo-notification-ajax', 'booajaxurl', [ 
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'booNotifications' ),
	] );


	if ( is_home() && ! is_front_page() || is_page_template( 'template-boo-skolan.php' ) ) {
		wp_enqueue_script( 'boo-blog', BOO_THEME_JS_DIR . 'boo-blog-ajax.js', array( 'jquery' ), null, true );
		wp_localize_script( 'boo-blog', 'boo_blog_ajax', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'load_more_posts_nonce' ),
		) );

		wp_enqueue_script( 'boo-blog-posts', BOO_THEME_JS_DIR . 'boo-blog-posts.js', array( 'jquery' ), null, true );
		wp_localize_script( 'boo-blog-posts', 'boo_posts_ajax_params', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'blogPosts' )

		) );

	}





}
add_action( 'wp_enqueue_scripts', 'boo_theme_scripts' );



// Boo Energy admin_custom_scripts
function boo_admin_custom_scripts() {
	wp_enqueue_media();
	wp_enqueue_style( 'customizer-style', BOO_THEME_URI . '/inc/css/customizer-style.css', array() );
	wp_enqueue_script( 'boo-admin-custom', BOO_THEME_URI . '/inc/js/admin_custom.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'boo-customizer', BOO_THEME_URI . '/inc/js/customizer.js', array( 'jquery' ), '', true );
}
add_action( 'admin_enqueue_scripts', 'boo_admin_custom_scripts' );


// Scripts and Style for Admin
add_action( 'admin_enqueue_scripts', 'boo_admin_scripts' );
function boo_admin_scripts() {
	wp_enqueue_style( 'admin-style', BOO_THEME_CSS_DIR . 'boo-admin.css', null, true );
}



// Customizer Scripts
function enqueue_customizer_scripts() {

	wp_enqueue_style(
		'customizer-drag-style',
		BOO_THEME_URI . '/assets/customizer/css/boo-customizer.css'
	);

	wp_enqueue_script(
		'sortable-js',
		'//cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js',
		[],
		null,
		true
	);

	wp_enqueue_script(
		'boo-customizer-drag-script',
		BOO_THEME_URI . '/assets/customizer/js/boo-customizer-drag.js',
		array( 'jquery', 'customize-controls', 'sortable-js' ),
		null,
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'enqueue_customizer_scripts' );
