<?php
/** * Template Name: Boo Skolan */
/** * The main template file for Boo Energy theme with custom loop structure *
 * @package Boo_Energy */
get_header();
$enable_boo_energi_app = get_field( 'enable_boo_energi_app' ) ? get_field( 'enable_boo_energi_app' ) : false;
$energy_app_heading = get_field( 'energi_app_heading' );
$energy_app_details = get_field( 'energi_app_details_blog', get_the_ID() );

global $wp_query;
$args = array(
	'post_type' => 'skolan',
	'posts_per_page' => get_option( 'posts_per_page' ),
);
$custom_query = new WP_Query( $args );
$max_pages = $custom_query->found_posts;

$taxonomy = 'skolan_category';
$categories = get_terms( array(
	'taxonomy' => $taxonomy,
	'hide_empty' => true,
	'object_ids' => get_posts( array(
		'post_type' => 'skolan',
		'posts_per_page' => -1,
		'fields' => 'ids',
	) ),
) );

$post_type = 'skolan';
?>


<section class="blog-area-wrapper m-p-32 d-p-120">
	<div class="container">
		<div class="row">
			<div class="col">
				<?php if ( count( $categories ) > 0 ) : ?>
					<div data-post-type="<?php echo $post_type; ?>" class="post-filter-wrapper d-pb-32 m-pb-24">
						<ul>
							<li><a href="#" data-slug="all"><?php echo esc_html__( 'Allt om el', 'boo-energy' ); ?></a>
							</li>
							<?php foreach ( $categories as $category ) : ?>
								<li><a href="#"
										data-slug="<?php echo esc_attr( $category->slug ); ?>"><?php echo esc_html( $category->name ); ?></a>
								</li>
							<?php endforeach; ?>


						</ul>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<div class="row">
			<div class="col-12 blog-post-items blog-padding">
				<div id="blog-postbox-main" class="boo-postbox-wrapper blog-featured-posts">
					<?php if ( $custom_query->have_posts() ) : ?>
						<?php if ( is_home() && ! is_front_page() ) : ?>
						<?php endif; ?>

						<?php
						// Custom Loop for Non-Single Pages
						if ( ! is_single() ) :
							$post_count = 0;
							while ( $custom_query->have_posts() ) :
								$custom_query->the_post();
								$post_count++;

								if ( $post_count <= 5 ) :
									get_template_part( 'template-parts/blog/content-blog' );
								endif;
							endwhile;
							?>

						</div>

						<!-- Section that needs to be included -->
						<div class="boo-postbox-pagination load-more-btn-posts-frist text-center d-pt-32">
							<a href="#" id="load-more-post-cat" class="boo-btn link-large">
								<?php echo esc_html__( 'Ladda fler', 'boo-energy' ); ?>
							</a>
						</div>

						<section aria-labelledby="Boo-skolan" class="boo-post-inner-section-wrapper d-p-88">
							<div class="row">
								<div class="col-lg-7 col-md-11 col-sm-12">
									<div class="boo-post-inner-section-top">
										<h2 class="typography-h2-large">
											<?php echo esc_html__( 'Tre enkla tips för att sänka elpriserna i vinter', 'boo-energy' ); ?>
										</h2>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<div id="boo-posts-inner-section "
										class="boo-posts-inner-section-skolan boo-post-inner-section-posts boo-postbox-wrapper">
										<div class="single-boo-posts-inner-section">
											<div class="single-boo-details-top">

												<div class="single-boo-blog-title">
													<h5><?php echo esc_html__( 'Släck lamporna', 'boo-energy' ); ?>
													</h5>
												</div>
												<div class="single-boo-blog-details">
													<p>
														<?php echo esc_html__( 'Att vara noga med att släcka lamporna kan göra en stor skillnad i den elförbrukning, men också miljön!', 'boo-energy' ); ?>
													</p>
												</div>
											</div>
										</div>
										<div class="single-boo-posts-inner-section">
											<div class="single-boo-details-top">

												<div class="single-boo-blog-title">
													<h5><?php echo esc_html__( 'Använd raggsockor', 'boo-energy' ); ?>
													</h5>
												</div>
												<div class="single-boo-blog-details">
													<p>
														<?php echo esc_html__( 'Kanske blir det möjligt att dra ner lite på uppvärmningen om man inversterar i ett par riktigt goa raggsockor eller ulltofflor?', 'boo-energy' ); ?>
													</p>
												</div>
											</div>
										</div>
										<div class="single-boo-posts-inner-section">
											<div class="single-boo-details-top">

												<div class="single-boo-blog-title">
													<h5><?php echo esc_html__( 'Använd raggsockor', 'boo-energy' ); ?>
													</h5>
												</div>
												<div class="single-boo-blog-details">
													<p>
														<?php echo esc_html__( 'Kanske blir det möjligt att dra ner lite på uppvärmningen om man inversterar i ett par riktigt goa raggsockor eller ulltofflor?', 'boo-energy' ); ?>
													</p>
												</div>
											</div>
										</div>

									</div>
								</div>
							</div>

						</section>

						<!-- Continue the loop for the rest of the posts -->
						<div id="boo-load-more-posts" class="boo-postbox-wrapper">
							<?php
							// Reset the query to start from post 6
							$custom_query->rewind_posts();
							$post_count = 0;

							while ( $custom_query->have_posts() ) :
								$custom_query->the_post();
								$post_count++;

								if ( $post_count > 5 ) :
									get_template_part( 'template-parts/blog/content-blog' );
								endif;
							endwhile;
							?>
						</div>
						<?php if ( $post_count >= 10 ) : ?>
							<div class="boo-postbox-pagination load-more-btn-posts-second text-center d-pt-32">
								<a href="#" id="load-more-post-btn" class="boo-btn link-large"
									data-post-type="<?php echo $post_type; ?>" data-total-posts="<?php echo $max_pages; ?>">
									<?php echo esc_html__( 'Ladda fler', 'boo-energy' ); ?>
								</a>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				<?php else :
						// Inga inlägg hittades. Template
						get_template_part( 'template-parts/content', 'none' );
					endif; ?>
			</div>
		</div>

	</div>
</section>

<?php get_template_part( 'template-parts/blog/section-boo-studion' ); ?>
<?php if ( $enable_boo_energi_app ) : ?>
	<div class="boo-energy-app-wrapper boo-skolans-page">
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
<?php
get_footer();