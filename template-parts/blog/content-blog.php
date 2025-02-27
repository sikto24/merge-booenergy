<?php
$post_id = get_the_ID();
$get_post_type = get_post_type( $post_id );

// Get the thumbnail or placeholder image
$blog_thumbnail_url = has_post_thumbnail( $post_id )
	? get_the_post_thumbnail_url( $post_id )
	: get_theme_mod( 'boo_placeholder_img_src' );
?>
<div <?php post_class( 'single-boo-blog-wrapper' ); ?>>
	<div class="single-boo-blog-thumbnail">
		<img src="<?php echo esc_url( $blog_thumbnail_url ); ?>" alt="<?php the_title_attribute(); ?>">
	</div>
	<div class="single-boo-details">
		<div class="single-boo-details-top">
			<!-- Display Tags -->
			<div class="single-boo-blog-tag">
				<?php boo_tags( $get_post_type, $post_id ); ?>
			</div>
			<!-- Display Title -->
			<div class="single-boo-blog-title">
				<a href="<?php the_permalink(); ?>">
					<h5><?php the_title(); ?></h5>
				</a>
			</div>
			<!-- Display Excerpt -->
			<div class="single-boo-blog-details">
				<?php the_excerpt(); ?>
			</div>
		</div>
		<!-- Read More Button -->
		<div class="single-boo-blog-btn">
			<a href="<?php the_permalink(); ?>">
				<?php echo esc_html__( 'Läs mer', 'boo-energy' ); ?>
				<img src="/app/uploads/2024/10/arrow-right-.svg" alt="Arrow Right">
			</a>
		</div>
	</div>
</div>