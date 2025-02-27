<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Boo_Energy
 */

// Start Footer
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'header' ) ) {
	// Elementor footer is used
	elementor_theme_do_location( 'footer' );
} else {
	// Fallback to your custom Footer
	do_action( 'boo_footer' );
}
// End Footer
?>

<?php wp_footer(); ?>

</body>

</html>