<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Boo_Energy
 */

get_header();
?>

<section class="error-404 not-found-wrapper">
	<div class="container-fluid">
		<div class="row ">
			<div class="col-lg-7 col-md-12 align-items-center d-flex">
				<div class="not-found-area-left">
					<div class="not-found-content">
						<h1>
							<?php echo esc_html__( 'Sidan kunde inte hittas', 'boo-ernergy' ); ?>
						</h1>
						<p class="typography-bread-large">
							<?php echo esc_html__( 'Kunde inte hitta sidan du söker. Försök igen eller gå tillbaka till startsidan.', 'boo-energy' ); ?>
						</p>
					</div>
					<div class="not-found-btn">
						<a href="<?php echo home_url( '/' ) ?>" class="boo-btn link-large">
							<?php echo esc_html__( 'Utforska våra elavtal', 'boo-energy' ); ?>
						</a>
					</div>
				</div>
			</div>
			<div class="col-lg-5  col-md-12 justify-content-end d-flex align-items-lg-center">
				<div class="not-found-area-right">
					<img src="<?php echo BOO_THEME_IMG_DIR . 'boo-error.svg'; ?>" alt="">
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
