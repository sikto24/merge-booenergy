<?php
function flow_enqueue_style() {


	// Add initial debug log
	error_log( 'Starting flow_enqueue_style function' );

	if ( is_admin() ) {
		return;
	}


	// Enqueue main stylesheet
	wp_enqueue_style( 'flow-main', BOO_THEME_URI . '/flow/css/main.css', array(), time(), 'all' );
	wp_enqueue_style( 'date-picker', '//cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker-bs5.min.css', array(), time(), 'all' );
	wp_enqueue_style( 'form-main', BOO_THEME_URI . '/flow/css/form.css', array(), time(), 'all' );

	// Enqueue main scripts
	wp_enqueue_script( 'font-awesome', '//kit.fontawesome.com/c90fabff57.js', array(), time(), true );
	wp_enqueue_script( 'flow-main', BOO_THEME_URI . '/flow/js/main.js', array( 'jquery' ), time(), true );
	wp_enqueue_script( 'flow-form', BOO_THEME_URI . '/flow/js/form.js', array( 'jquery' ), time(), true );
	wp_enqueue_script( 'date-picker', '//cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker.min.js', array( 'jquery' ), time(), true );
	wp_enqueue_script( 'klart', BOO_THEME_URI . '/flow/js/thank-you.js', array( 'jquery' ), time(), true );

	// Localize the script with new data
	wp_localize_script( 'klart', 'ajax_object', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	) );
}
add_action( 'wp_enqueue_scripts', 'flow_enqueue_style', 100 );
// Add type="module" to the 'flow-main' script
function add_type_attribute( $tag, $handle ) {
	if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
		return $tag; // Do not modify scripts inside Elementor editor
	}
	if ( 'flow-main' === $handle || 'flow-form' === $handle ) {
		return str_replace( '<script ', '<script type="module" ', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'add_type_attribute', 10, 2 );

