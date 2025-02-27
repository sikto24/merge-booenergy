<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package boo-energy
 */

get_header();
$enable_boo_energi_app = get_field( 'enable_boo_energi_app' ) ? get_field( 'enable_boo_energi_app' ) : false;
$energy_app_heading = get_field( 'energi_app_heading' );
$energy_app_details = get_field( 'energi_app_details' );

?>

<section class="boo-post-area-wrapper single-blog-view-main">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-xl-7 col-lg-8 col-md-12 col-12 ">
				<div class="boo-postbox-wrapper">
					<div class="boo_postbox__wrapper  blog__wrapper postbox__details">
						<?php
						while ( have_posts() ) :
							the_post();

							get_template_part( 'template-parts/content', get_post_format() );

							?>
							<?php
							if ( get_previous_post_link() and get_next_post_link() ) :
								?>

								<div class="blog-details-border d-none">
									<div class="row align-items-center">
										<?php
										if ( get_previous_post_link() ) :
											?>
											<div class="col-lg-6 col-md-6">
												<div class="theme-navigation b-next-post text-left">
													<span><?php echo esc_html__( 'Prev Post', 'boo-energy' ); ?></span>
													<h4><?php echo get_previous_post_link( '%link ', '%title' ); ?></h4>
												</div>
											</div>
											<?php
										endif;
										?>

										<?php
										if ( get_next_post_link() ) :
											?>
											<div class="col-lg-6 col-md-6">
												<div class="theme-navigation b-next-post text-left text-md-right">
													<span><?php print esc_html__( 'Next Post', 'boo-energy' ); ?></span>
													<h4><?php print get_next_post_link( '%link ', '%title' ); ?></h4>
												</div>
											</div>
											<?php
										endif;
										?>

									</div>
								</div>

								<?php
							endif;
							?>
							<?php

							// get_template_part( 'template-parts/biography' );
						
							// If comments are open or we have at least one comment, load up the comment template.
							// if ( comments_open() || get_comments_number() ) :
							// 	comments_template();
							// endif;
						
						endwhile; // End of the loop.
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php if ( 'skolan' == get_post_type() && $enable_boo_energi_app ) : ?>
	<div class="boo-energy-app-wrapper">
		<div class="container">
			<div class="boo-energy-app-main">
				<div class="row">
					<div class="col-lg-7">
						<div
							class="boo-energy-app-main-content d-flex flex-column justify-content-between  align-items-start">
							<div class="boo-energy-app-content-inner-top">
								<h2>
									<?php echo esc_html__( $energy_app_heading, 'boo-energy' ); ?>
								</h2>
								<p>
									<?php echo esc_html__( $energy_app_details, 'boo-energy' ); ?>
								</p>
							</div>
							<div class="boo-energy-content-inner-bottom ">
								<h6><?php echo esc_html__( 'Ladda ner', 'boo-energy' ); ?></h6>
								<?php echo do_shortcode( ' [boo_store]' ); ?>
							</div>
						</div>
					</div>
					<div class="col-lg-5">
						<div class="boo-energy-app-main-img d-flex flex-column justify-content-center align-items-center">
							<img src="<?php echo BOO_THEME_IMG_DIR . 'App.svg'; ?>" alt="">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
<!-- Start Related Blog Posts -->
<?php
get_template_part( 'template-parts/blog/content-related-blog' );
?>
<!-- END Related Blog Posts -->

<?php
get_footer();