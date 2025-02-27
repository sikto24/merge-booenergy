<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Boo_Energy
 */

$post_id = get_the_ID();
$boo_post_layout_orders = json_decode( get_theme_mod( 'single_blog_layout_orders', '["publish_date", "content", "skolan_attached_video" , "linked_post", "extra_content" , "linked_post_skolan" ]' ) );

// Boo Videos
$boo_attached_video = get_field( 'attached_video' );
$boo_studion = get_field( 'boo_studion' );
$video_url = get_field( 'video_url' );
if ( $boo_studion ) {
	if ( is_array( $boo_studion ) ) {
		$studion_post_id = $boo_studion[0]->ID;
	} else {
		$studion_post_id = $boo_studion->ID;
	}

	$boo_video_url = get_field( 'video_url', $studion_post_id );
}


if ( $boo_studion ) {
	$boo_studion_post_id = $boo_studion->ID;
	$boo_studion_post_thumbnail = has_post_thumbnail( $boo_studion_post_id )
		? get_the_post_thumbnail_url( $boo_studion_post_id )
		: get_theme_mod( 'boo_placeholder_img_src' );
}

// Inner Section Linked For Posts
$boo_post_link_a_post = ( 'true' == get_field( 'link_a_post' ) ) ? get_field( 'link_a_post' ) : false;
$boo_link_a_post_skolan = ( 'true' == get_field( 'link_a_post_skolan' ) ) ? get_field( 'link_a_post_skolan' ) : false;
$boo_add_more_content = ( get_field( 'boo_add_more_content' ) ) ? get_field( 'boo_add_more_content' ) : false;
$boo_link_post_blog = get_field( 'boo_link_post_blog' );
$boo_link_post_skolan = get_field( 'boo_link_post_skolan' );
$link_post_heading = get_field( 'link_post_heading' );
$link_post_textarea = get_field( 'link_post_textarea' );

if ( $boo_link_post_blog ) {
	$boo_link_post_blog_id = $boo_link_post_blog->ID;
	$boo_link_post_blog_thumbnail = get_the_post_thumbnail_url( $boo_link_post_blog_id );
	$boo_link_post_blog_url = get_the_permalink( $boo_link_post_blog_id );
	$boo_link_post_blog_tags = get_tag( $boo_link_post_blog_id );
}

if ( $boo_link_post_skolan ) {
	$boo_link_post_skolan_id = $boo_link_post_skolan->ID;
	$boo_link_post_skolan_thumbnail = get_the_post_thumbnail_url( $boo_link_post_skolan_id );
	$boo_link_post_skolan_url = get_the_permalink( $boo_link_post_skolan_id );
	$boo_link_post_skolan_tags = get_tag( $boo_link_post_skolan_id );
}


?>


<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post-view-main-wrapper d-flex flex-column' ); ?>>

	<?php foreach ( $boo_post_layout_orders as $boo_post_layout_order ) : ?>

		<?php
		switch ( $boo_post_layout_order ) :
			case ( 'publish_date' ):
				?>
				<header class="entry-header">
					<?php
					if ( ( in_array( get_post_type(), [ 'post', 'skolan' ] ) ) ) :
						?>
						<div class="entry-meta">
							<?php
							echo esc_html__( 'Publicerad: ', 'boo-energy' ) . get_the_time( get_option( 'date_format' ) );
							?>
						</div><!-- .entry-meta -->
					<?php endif; ?>
				</header>
				<?php
				break;
			case ( 'content' ):
				?>

				<div class="entry-content boo-single-post">
					<?php
					the_content(
						sprintf(
							wp_kses(
								/* translators: %s: Name of current post. Only visible to screen readers */
								__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'boo-energy' ),
								array(
									'span' => array(
										'class' => array( '' ),
									),
								)
							),
							wp_kses_post( get_the_title() )
						)
					);

					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'boo-energy' ),
							'after' => '</div>',
						)
					);
					?>
				</div><!-- .entry-content -->

				<?php
				break;
			case ( 'skolan_attached_video' ):
				?>

				<?php if ( true === $boo_attached_video ) : ?>
					<div class="post-video-wrapper">
						<div class="post-video-thumbnail">
							<a class="mfp-iframe boo-video-play-btn" href="<?php echo esc_attr( $boo_video_url ); ?>">
								<img class="video-thumbnails-img" src="<?php echo esc_url( $boo_studion_post_thumbnail ) ?>"
									alt="<?php echo esc_attr( get_the_title() ); ?>">
							</a>
						</div>
						<h5 class="post-video-title">
							<?php echo esc_html__( $boo_studion->post_title, 'boo-energy' ); ?>
						</h5>
						<p class="post-video-details">
							<?php echo esc_html__( $boo_studion->post_content, 'boo-energy' ); ?>
						</p>
						<a href="<?php echo esc_attr( $boo_video_url ); ?>"
							class="d-flex flex-rowcode  post-video-url boo-video-play-btn mfp-iframe">
							<img src="/app/uploads/2024/12/play-button.svg">
							<?php echo esc_html__( 'Spela video', 'boo-energy' ); ?>
						</a>
					</div>
				<?php endif; ?>


				<?php
				break;
			case ( 'linked_post' ):
				?>

				<!-- Start Linked Post Blog -->
				<?php if ( true === $boo_post_link_a_post && ! empty( $boo_link_post_blog ) && ( false === is_elementor_edit( $post_id ) ) ) : ?>
					<div class="single-inner-linked-post-area-wrapper">
						<div class="single-inner-linked-post-thumbnail">
							<img src="<?php echo $boo_link_post_blog_thumbnail; ?>"
								alt="<?php echo esc_html__( $boo_link_post_blog->post_title, 'boo-energy' ); ?>">
						</div>
						<div class="single-inner-linked-post-content">

							<!-- Start Boo Tag -->
							<?php if ( function_exists( 'boo_tags' ) ) {
								boo_tags( 'post', $boo_link_post_blog_id );

							} ?>

							<!-- End Boo Tag -->

							<div class="single-inner-linked-post-title">
								<a href="<?php echo $boo_link_post_blog_url; ?>">
									<h5><?php echo esc_html__( $boo_link_post_blog->post_title, 'boo-energy' ); ?></h5>
								</a>
							</div>
							<div class="single-inner-linked-post-desc">
								<?php echo wp_trim_words( $boo_link_post_blog->post_content, 30 ); ?>
							</div>
							<div class="single-inner-linked-post-btn">
								<a href="<?php echo $boo_link_post_blog_url; ?>">
									<?php echo esc_html__( 'Läs mer om laddbox', 'boo-energy' ); ?><img
										src="/app/uploads/2024/10/arrow-right-.svg"></a>
							</div>
						</div>

					</div>
				<?php endif; ?>
				<!-- End Linked Post Blog -->


				<?php
				break;
			case ( 'extra_content' ):
				?>

				<!-- Start Extra Content Post Area -->
				<?php if ( true === $boo_add_more_content && ( false === is_elementor_edit( $post_id ) ) ) : ?>
					<section class="extra-post-area-wrapper">
						<div class="extra-post-section-title">
							<h4><?php echo esc_html__( $link_post_heading, 'boo-energy' ); ?></h4>
						</div>
						<div class="extra-post-section-content">
							<?php echo wpautop( $link_post_textarea ); ?>
						</div>
					</section>
				<?php endif; ?>
				<!-- End Extra Content Post Area -->

				<?php
				break;
			case ( 'linked_post_skolan' ):
				?>

				<!-- Start Linked Post Skolan -->

				<?php if ( true === $boo_link_a_post_skolan && ! empty( $boo_link_post_skolan_id ) && ( false === is_elementor_edit( $post_id ) ) ) : ?>
					<div class="single-inner-linked-post-area-wrapper boo-skolan-section-post">
						<div class="single-inner-linked-post-thumbnail">
							<img src="<?php echo $boo_link_post_skolan_thumbnail; ?>"
								alt="<?php echo esc_html__( $boo_link_post_skolan->post_title, 'boo-energy' ); ?>">
						</div>
						<div class="single-inner-linked-post-content">

							<!-- Start Boo Tag -->
							<?php if ( function_exists( 'boo_tags' ) ) {
								boo_tags( 'skolan', $boo_link_post_skolan_id );

							} ?>

							<!-- End Boo Tag -->

							<div class="single-inner-linked-post-title">
								<a href="<?php echo $boo_link_post_skolan_url; ?>">
									<h5><?php echo esc_html__( $boo_link_post_skolan->post_title, 'boo-energy' ); ?></h5>
								</a>
							</div>
							<div class="single-inner-linked-post-desc">
								<?php echo wp_trim_words( $boo_link_post_skolan->post_content, 30 ); ?>
							</div>
							<div class="single-inner-linked-post-btn">
								<a href="<?php echo $boo_link_post_skolan_url; ?>"><?php echo esc_html__( 'Läs mer', 'boo-energy' ); ?>
									<img src="/app/uploads/2024/10/arrow-right-.svg">
								</a>
							</div>
						</div>

					</div>
				<?php endif; ?>

			<?php
		endswitch; ?>

		<!-- End Linked Post Skolan -->

	<?php endforeach; ?>
</article>