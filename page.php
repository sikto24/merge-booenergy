<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Boo_Energy
 */

get_header();
$boo_enable_page_layout = ( true === get_field( 'boo_enable_page_layout' ) ) ? get_field( 'boo_enable_page_layout' ) : false;
$boo_select_layout = get_field( 'boo_select_layout' ) ? get_field( 'boo_select_layout' ) : '';

if ( 'Half' == $boo_select_layout ) {
	$page_layout = 'col-lg-7 col-md-12';
} else {
	$page_layout = 'col-lg-12';
}
?>


<!-- Start Page Section -->
<section id="primary" class="page-area-wrapper site-main">
	<div class="container">
		<div class="row">
			<div class="<?php echo $page_layout; ?>">
				<?php
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content', 'page' );
					endwhile;
				else :
					get_template_part( 'template-parts/content', 'none' );
				endif;
				?>
			</div>
		</div>
	</div>
</section>
<!-- End Page Section -->

<?php
get_footer();
