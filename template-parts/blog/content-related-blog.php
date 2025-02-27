<?php
$current_post_id = get_the_ID();
$tags = wp_get_post_tags( $current_post_id );
$postType = get_post_type( $current_post_id );

if ( $tags ) {
	$first_tag = $tags[0]->term_id;
	$args = array(
		'tag__in' => array( $first_tag ),
		'post__not_in' => array( $current_post_id ),
		'posts_per_page' => 3,
		'ignore_sticky_posts' => 1,
		'post_type' => $postType,
	);

	$related_query = new WP_Query( $args );

	if ( $related_query->have_posts() ) : ?>
		<section class="related-blog-posts-wrapper d-p-88">
			<div class="container">
				<div class="row">
					<div class="col">
						<h2 class="d-pb-24"><?php echo esc_html__( 'Läs vidare', 'boo-energy' ); ?></h2>
						<div class="related-blog-posts boo-postbox-wrapper">
							<?php while ( $related_query->have_posts() ) :
								$related_query->the_post(); ?>
								<?php get_template_part( 'template-parts/blog/content', 'blog' ); ?>
							<?php endwhile; ?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php wp_reset_postdata(); ?>
	<?php endif;
}
