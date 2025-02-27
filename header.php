<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Boo_Energy
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text"
			href="#primary"><?php esc_html_e( 'Skip to content', 'boo-energy' ); ?></a>


		<!-- header start -->
		<?php
		if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'header' ) ) {
			// Elementor Header
			elementor_theme_do_location( 'header' );
		} else {
			// Fallback Custom header
			do_action( 'boo_header' );
		}
		?>
		<!-- header end -->

		<!-- Start  Wrapper -->

		<?php do_action( 'boo_before_main_content' ); ?>

		<!-- End Wrapper -->