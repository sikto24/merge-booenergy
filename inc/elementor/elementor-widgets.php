<?php
/**
 * Elementor Widgets
 *
 * @package Elementor_Addon
 */

function boo_custom_widget( $widgets_manager ) {

	require_once( __DIR__ . '/image-accordion.php' );
	$widgets_manager->register( new \Elementor_image_accordion() );

}
add_action( 'elementor/widgets/register', 'boo_custom_widget' );