<?php
$boo_studion = get_field( 'video_url' );


$arg = array(
	'posts_per_page' => '10',
	'post_type' => 'studion',
	'post_status' => 'publish',
);
$boo_studions_posts = new WP_Query( $arg );
?>

<section class="boo-studion-wrapper slider-dots-arrow">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10 col-lg-6 col-12">
				<div class="boo-studio-title">
					<h2 class="typography-h2-large">
						<?php echo esc_html__( 'Boo Studion', 'boo_energy' ); ?>
					</h2>
				</div>
				<div class="boo-studio-content">
					<p>
						<?php echo esc_html__( 'Utforska vår samling av videos och få en djupare förståelse för energi, hållbarhet och smarta lösningar för framtiden.', 'boo-energy' ); ?>
					</p>
				</div>
			</div>
		</div>


		<div
			class="<?php echo ( $boo_studions_posts->found_posts > 3 ) ? 'boo-studion-video-carousel-wrapper' : 'boo-studion-video-carousel-wrapper-normal d-flex '; ?> d-pt-32 m-pt-24">
			<?php $boo_video_non_slider_class = ( $boo_studions_posts->found_posts > 3 ) ? 'col-lg-4 col-md-6 col-12' : ''; ?>

			<?php while ( $boo_studions_posts->have_posts() ) :
				$boo_studions_posts->the_post();
				$boo_video_url = get_field( 'video_url' );
				?>
				<div class="single-boo-studion-carousel <?php echo $boo_video_non_slider_class; ?>">
					<div class="single-boo-studion-thumbnail">
						<a class="boo-video-play-btn mfp-iframe" href="<?php echo esc_url( $boo_video_url ); ?>">
							<?php the_post_thumbnail( 'large' ); ?>
							<img class="play-btn-icon" src="<?php echo BOO_THEME_IMG_DIR . 'play.svg'; ?>">
						</a>
					</div>
					<div class="single-boo-studion-title">
						<h5><?php the_title(); ?></h5>
					</div>
					<div class="single-boo-studion-desc">
						<?php the_excerpt(); ?>
					</div>
					<div class="single-boo-studion-btn">
						<?php if ( $boo_video_url ) : ?>
							<a href="<?php echo esc_url( $boo_video_url ); ?>"
								class="post-video-url boo-video-play-btn mfp-iframe d-flex">
								<img src="/app/uploads/2024/12/play-button.svg">
								<?php echo esc_html__( 'Spela video', 'boo-energy' ); ?></a>
						<?php endif; ?>
					</div>
				</div>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>


	</div>
</section>