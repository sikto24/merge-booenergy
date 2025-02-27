<?php

/**
 * Summary of register_boo_energy_notifications_shortcode
 * Init all shortcodes
 * @return void
 */
function register_boo_energy_notifications_shortcode() {
	add_shortcode( 'boo_store', 'boo_store_shortcode' );
	add_shortcode( 'boo_social_icons', 'boo_social_icons_shortcode' );
	add_shortcode( 'boo_energy_notifications', 'boo_energy_notifications_shortcode' );
}
add_action( 'init', 'register_boo_energy_notifications_shortcode' );

/**
 *  boo_social_icons_shortcode
 * @return bool|string
 */
function boo_social_icons_shortcode() {
	ob_start(); // Start output buffering
	?>
	<div class="footer-middle-social-share">
		<?php if ( ! empty( get_theme_mod( 'boo_social_instagram_link' ) ) ) : ?>
			<span>
				<a target="_blank" href="<?php echo esc_url( get_theme_mod( 'boo_social_instagram_link' ) ); ?>">
					<img src="<?php echo BOO_THEME_IMG_DIR . 'instagram.svg'; ?>" alt="instagram">
				</a>
			</span>
		<?php endif; ?>
		<?php if ( ! empty( get_theme_mod( 'boo_social_facebook_link' ) ) ) : ?>
			<span>
				<a target="_blank" href="<?php echo esc_url( get_theme_mod( 'boo_social_facebook_link' ) ); ?>">
					<img src="<?php echo BOO_THEME_IMG_DIR . 'facebook.svg'; ?>" alt="facebook">
				</a>
			</span>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}


// Boo Store Shortcode
function boo_store_shortcode() {
	ob_start();
	?>
	<div class="footer-top-app-store d-flex flex-column">
		<?php if ( ! empty( get_theme_mod( 'boo_footer_app_store_link_text' ) ) && ! empty( get_theme_mod( 'boo_footer_app_store_url' ) ) ) : ?>
			<a target="_blank" href="<?php echo esc_url( get_theme_mod( 'boo_footer_app_store_url' ) ); ?>">
				<h4><?php echo esc_html__( get_theme_mod( 'boo_footer_app_store_link_text' ), 'boo-energy' ); ?></h4>
			</a>
		<?php endif; ?>

		<?php if ( ! empty( get_theme_mod( 'boo_footer_google_store_url' ) ) && ! empty( get_theme_mod( 'boo_footer_google_store_link_text' ) ) ) : ?>
			<a target="_blank" href="<?php echo esc_url( get_theme_mod( 'boo_footer_google_store_url' ) ); ?>">
				<h4><?php echo esc_html__( get_theme_mod( 'boo_footer_google_store_link_text' ), 'boo-energy' ); ?></h4>
			</a>
		<?php endif; ?>
	</div>

	<?php
	return ob_get_clean();
}


// Boo Notification Shortcode
function boo_energy_notifications_shortcode() {
	ob_start();

	?>
	<div class="notifications-area-main">
		<div class="notification-area">
			<div class="notification-area-filter">
				<ul class="tab-area-filter-main">
					<li>
						<a class="tab-filter tab-filter-active" data-filter="ongoing" data-status="publish" href="#">
							<?php echo esc_html__( 'Pågående', 'boo-energy' ); ?>
						</a>
					</li>
					<li>
						<a class="tab-filter" data-filter="planned" data-status="future" href="#">
							<?php echo esc_html__( 'Planerade', 'boo-energy' ); ?>
						</a>
					</li>
					<li>
						<a class="tab-filter" data-filter="history" data-status="pending" href="#">
							<?php echo esc_html__( 'Historik', 'boo-energy' ); ?>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div id="notification-results" class="notification-area-result d-flex flex-column">
			<?php
			$args = array(
				'post_type' => 'notification',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'DESC'
			);

			// Create a new WP_Query
			$future_posts = new WP_Query( $args );

			// Loop through the posts
			if ( $future_posts->have_posts() ) :
				while ( $future_posts->have_posts() ) :
					$future_posts->the_post();
					get_template_part( '/template-parts/notifications/content-notification' );
				endwhile;
			else :
				echo '<p>' . esc_html__( 'Inga notifikationer tillgängliga', 'boo-energy' ) . '</p>';
			endif;
			wp_reset_postdata();
			?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}



/**
 * Summary of boo_inner_linked_post_shortcode
 * @param mixed $atts
 * @return bool|string
 */
function boo_inner_linked_post_shortcode( $atts ) {
	$boo_post_link_a_post = ( 'true' == get_field( 'link_a_post', get_the_ID() ) ) ? get_field( 'link_a_post', get_the_ID() ) : false;
	// Extract shortcode attributes
	$atts = shortcode_atts(
		array(
			'post_id' => get_the_ID()
		),
		$atts,
		'boo_linked_post'
	);

	// Fetch the post using the passed ID
	$post_id = intval( $atts['post_id'] );
	if ( ! $post_id ) {
		return esc_html__( 'Invalid post ID.', 'boo-energy' );
	}

	$post = get_post( $post_id );
	if ( ! $post ) {
		return esc_html__( 'Post not found.', 'boo-energy' );
	}

	// Prepare variables
	$thumbnail_url = get_the_post_thumbnail_url( $post_id, 'full' );
	$post_url = get_permalink( $post_id );
	$post_title = $post->post_title;
	$post_content = $post->post_content;

	ob_start();
	?>
	<?php if ( true === $boo_post_link_a_post ) : ?>
		<div class="single-inner-linked-post-area-wrapper">
			<div class="single-inner-linked-post-thumbnail">
				<?php if ( $thumbnail_url ) : ?>
					<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( $post_title ); ?>">
				<?php endif; ?>
			</div>
			<div class="single-inner-linked-post-content">
				<!-- Start Boo Tag -->
				<?php if ( function_exists( 'boo_tags' ) ) {
					boo_tags( 'post', $post_id );
				} ?>
				<!-- End Boo Tag -->
				<div class="single-inner-linked-post-title">
					<a href="<?php echo esc_url( $post_url ); ?>">
						<h5><?php echo esc_html( $post_title ); ?></h5>
					</a>
				</div>
				<div class="single-inner-linked-post-desc">
					<?php echo wp_trim_words( wp_kses_post( $post_content ), 30 ); ?>
				</div>
				<div class="single-inner-linked-post-btn">
					<a href="<?php echo esc_url( $post_url ); ?>">
						<?php echo esc_html__( 'Läs mer om laddbox', 'boo-energy' ); ?>
						<img src="/app/uploads/2024/10/arrow-right-.svg" />
					</a>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php
	return ob_get_clean();
}
add_shortcode( 'boo_linked_post', 'boo_inner_linked_post_shortcode' );



/**
 * Summary of boo_extra_post_section_shortcode
 * Blog Post Extra Content Section
 * @param mixed $atts
 * @return bool|string
 */
function boo_extra_post_section_shortcode( $atts ) {
	$boo_add_more_content = ( get_field( 'boo_add_more_content', get_the_ID() ) ) ? get_field( 'boo_add_more_content', get_the_ID() ) : false;
	// Extract shortcode attributes
	$atts = shortcode_atts(
		array(
			'heading' => get_field( 'link_post_heading', get_the_ID() ),
			'content' => get_field( 'link_post_textarea', get_the_ID() ),
		),
		$atts,
		'boo_extra_post'
	);

	// Prepare variables
	$heading = sanitize_text_field( $atts['heading'] );
	$content = wp_kses_post( wpautop( $atts['content'] ) );

	ob_start();
	if ( $boo_add_more_content ) :
		?>
		<section class="extra-post-area-wrapper">
			<div class="extra-post-section-title">
				<h4><?php echo esc_html( $heading ); ?></h4>
			</div>
			<div class="extra-post-section-content">
				<?php echo $content; ?>
			</div>
		</section>
		<?php
	endif;
	return ob_get_clean();
}
add_shortcode( 'boo_extra_post', 'boo_extra_post_section_shortcode' );



/**
 * Summary of boo_skolan_linked_post_shortcode
 * Boo Skolan Linked Post Shortcode
 * @param mixed $atts
 * @return bool|string
 */
function boo_skolan_linked_post_shortcode( $atts ) {
	$boo_link_a_post_skolan = ( 'true' == get_field( 'link_a_post_skolan', get_the_ID() ) ) ? get_field( 'link_a_post_skolan', get_the_ID() ) : false;
	// Extract shortcode attributes
	$atts = shortcode_atts(
		array(
			'post_id' => get_the_ID(), // Post ID for fetching content
		),
		$atts,
		'boo_skolan_post'
	);

	// Validate and fetch the post using the provided ID
	$post_id = intval( $atts['post_id'] );
	if ( ! $post_id ) {
		return esc_html__( 'Invalid post ID.', 'boo-energy' );
	}

	$post = get_post( $post_id );
	if ( ! $post ) {
		return esc_html__( 'Post not found.', 'boo-energy' );
	}

	// Prepare variables
	$thumbnail_url = get_the_post_thumbnail_url( $post_id, 'full' );
	$post_title = $post->post_title;
	$post_content = $post->post_content;
	$post_url = get_permalink( $post_id );

	ob_start();
	if ( $boo_link_a_post_skolan ) :
		?>
		<div class="single-inner-linked-post-area-wrapper boo-skolan-section-post">
			<div class="single-inner-linked-post-thumbnail">
				<?php if ( $thumbnail_url ) : ?>
					<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( $post_title ); ?>">
				<?php endif; ?>
			</div>
			<div class="single-inner-linked-post-content">
				<!-- Start Boo Tag -->
				<?php if ( function_exists( 'boo_tags' ) ) {
					boo_tags( 'skolan', $post_id );
				} ?>
				<!-- End Boo Tag -->
				<div class="single-inner-linked-post-title">
					<a href="<?php echo esc_url( $post_url ); ?>">
						<h5><?php echo esc_html( $post_title ); ?></h5>
					</a>
				</div>
				<div class="single-inner-linked-post-desc">
					<?php echo wp_trim_words( wp_kses_post( $post_content ), 30 ); ?>
				</div>
				<div class="single-inner-linked-post-btn">
					<a href="<?php echo esc_url( $post_url ); ?>">
						<?php echo esc_html__( 'Läs mer', 'boo-energy' ); ?>
						<img src="/app/uploads/2024/10/arrow-right-.svg" alt="">
					</a>
				</div>
			</div>
		</div>
		<?php
	endif;
	return ob_get_clean();
}
add_shortcode( 'boo_skolan_post', 'boo_skolan_linked_post_shortcode' );


/**
 * Summary of render_purchase_flow_section
 * for Purchase Flow Section
 * @return bool|string
 */

require_once get_template_directory() . '/template-parts/flow/templates/template-purchase-flow.php';




