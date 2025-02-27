<?php
/**
 * The template for displaying search results pages
 *
 * @package Boo_Energy
 */

get_header();

// Get the search query and sanitize it
$search_query = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
$post_types = [ 'post', 'page', 'skolan' ];
$posts_per_page = get_option( 'posts_per_page' );

// Count posts for merged and separate types
$post_type_counts = [];
foreach ( $post_types as $post_type ) {
	$args = [ 
		's' => $search_query,
		'post_type' => $post_type,
		'posts_per_page' => $posts_per_page,
		'fields' => 'ids',
	];
	$query = new WP_Query( $args );
	$post_type_counts[ $post_type ] = $query->found_posts;
	wp_reset_postdata();
}

// Combine counts for 'post' and 'skolan'
$merged_post_types_count = $post_type_counts['post'] + $post_type_counts['skolan'];
$countFindPosts = array_sum( $post_type_counts );
$selected_post_type = isset( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] ) : 'all';
$valid_post_types = [ 'all', 'merged', 'page' ]; // "merged" represents 'post' + 'skolan'
$selected_post_type = in_array( $selected_post_type, $valid_post_types, true ) ? $selected_post_type : 'all';

// Fetch search results
$args = [ 
	's' => $search_query,
	'post_type' => $selected_post_type === 'all' ? $post_types : ( $selected_post_type === 'merged' ? [ 'post', 'skolan' ] : $selected_post_type ),
	'posts_per_page' => $posts_per_page,
	'paged' => max( 1, get_query_var( 'paged' ) ),
];
$search_results = new WP_Query( $args );

if ( get_query_var( 'post_type' ) === 'faq' ) {
	get_template_part( 'search', 'faq' );
	return;
}
?>

<section class="search-result-wrapper">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8">
				<div class="search-breadcrumb-area">
					<h2 class="typography-h2-large"><?php echo esc_html__( 'Sök på sidan', 'boo-energy' ); ?></h2>
				</div>
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col-lg-8">
				<section class="search-area-result-main-wrapper">
					<div class="search-form-wrapper-main boo-search-bar">
						<p><?php echo esc_html__( 'Vad söker du efter?', 'boo-energy' ); ?></p>
						<?php get_search_form(); ?>
					</div>
					<header class="page-header">
						<p class="typography-bread-large">
							<?php
							printf(
								esc_html( _n( 'Din sökning "%s" gav %s träff', 'Din sökning "%s" gav %s träffar', $countFindPosts, 'boo-energy' ) ),
								'<span>"' . esc_html( $search_query ) . '"</span>',
								esc_html( $countFindPosts )
							);
							?>
						</p>
					</header>
					<?php if ( $countFindPosts > 0 ) : ?>
						<section class="search-filter-tab-area-wrapper">
							<ul class="filter-tab-section" id="filter-tabs">
								<li>
									<a class="<?php echo $selected_post_type === 'all' ? 'search-filter-active' : ''; ?>"
										href="<?php echo esc_url( add_query_arg( [ 's' => $search_query ], get_search_link() ) ); ?>">
										<?php echo esc_html__( 'Alla', 'boo-energy' ) . ' (' . esc_html( $countFindPosts ) . ')'; ?>
									</a>
								</li>
								<li>
									<a class="<?php echo $selected_post_type === 'merged' ? 'search-filter-active' : ''; ?>"
										href="<?php echo esc_url( add_query_arg( [ 's' => $search_query, 'post_type' => 'merged' ], get_search_link() ) ); ?>">
										<?php echo esc_html__( 'Sidor', 'boo-energy' ) . ' (' . esc_html( $merged_post_types_count ) . ')'; ?>
									</a>
								</li>
								<li>
									<a class="<?php echo $selected_post_type === 'page' ? 'search-filter-active' : ''; ?>"
										href="<?php echo esc_url( add_query_arg( [ 's' => $search_query, 'post_type' => 'page' ], get_search_link() ) ); ?>">
										<?php echo esc_html__( 'Artiklar', 'boo-energy' ) . ' (' . esc_html( $post_type_counts['page'] ) . ')'; ?>
									</a>
								</li>
							</ul>
						</section>
						<section class="search-result-area-wrapper" id="search-results">
							<div id="search-results-container">
								<?php
								if ( $search_results->have_posts() ) :
									while ( $search_results->have_posts() ) :
										$search_results->the_post();
										get_template_part( 'template-parts/content', 'search' );
									endwhile;

									// Pagination
									if ( $search_results->max_num_pages > 1 ) :
										echo '<div class="boo-basic-pagination-wrapper-main boo-basic-pagination">';
										echo paginate_links( [ 
											'total' => $search_results->max_num_pages,
											'current' => max( 1, get_query_var( 'paged' ) ),
											'prev_text' => '<svg width="19" height="17" viewBox="0 0 19 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.64658 16.2349C7.78775 16.0959 7.78775 15.8684 7.64658 15.7294L1.07967 9.26195C0.998391 9.1819 0.938498 9.08921 0.895716 8.9923L18.3034 8.9923C18.5044 8.9923 18.667 8.8322 18.667 8.63417C18.667 8.43615 18.5044 8.27604 18.3034 8.27604L0.891436 8.27604C0.934218 8.17913 0.994113 8.08644 1.0754 8.00639L7.6423 1.53899C7.78348 1.39995 7.78348 1.17243 7.6423 1.03339C7.50112 0.894356 7.2701 0.894356 7.12893 1.03339L0.562024 7.5008C0.245444 7.81258 0.082875 8.22548 0.082875 8.63838C0.0828749 9.05129 0.241166 9.45998 0.562024 9.77597L7.12893 16.2434C7.2701 16.3824 7.50112 16.3824 7.6423 16.2434" fill="#FFEDE5"/></svg>',
											'next_text' => '<svg width="19" height="17" viewBox="0 0 19 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.3534 0.944739C11.2122 1.08378 11.2122 1.31129 11.3534 1.45033L17.9203 7.91773C18.0016 7.99779 18.0615 8.09048 18.1043 8.18738H0.696647C0.495576 8.18738 0.333008 8.34749 0.333008 8.54552C0.333008 8.74354 0.495576 8.90365 0.696647 8.90365H18.1086C18.0658 9.00055 18.0059 9.09324 17.9246 9.1733L11.3577 15.6407C11.2165 15.7797 11.2165 16.0073 11.3577 16.1463C11.4989 16.2853 11.7299 16.2853 11.8711 16.1463L18.438 9.67889C18.7546 9.36711 18.9171 8.9542 18.9171 8.5413C18.9171 8.1284 18.7588 7.71971 18.438 7.40371L11.8711 0.93631C11.7299 0.797272 11.4989 0.797272 11.3577 0.93631" fill="#FFEDE5"/></svg>',
										] );
										echo '</div>';
									endif;
									echo '<div class="boo-basic-pagination-wrapper-main-bottom">';

									wp_reset_postdata();
								else :
									get_template_part( 'template-parts/content', 'none' );
								endif;
								?>
							</div>
						</section>
					<?php else : ?>
						<p><?php echo esc_html__( 'Inga inlägg hittades.', 'boo-energy' ); ?></p>
					<?php endif; ?>
				</section>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>