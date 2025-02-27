<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Boo_Energy
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */

// Boo Energy Logo
function boo_header_logo() {
	if ( function_exists( 'the_custom_logo' ) ) {
		$custom_logo_id = get_theme_mod( 'custom_logo' );

		if ( $custom_logo_id ) {
			$site_logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
			$site_logo = sprintf(
				'<a href="%s"><img src="%s" alt="%s" /></a>',
				esc_url( home_url() ),
				esc_url( $site_logo_url ),
				esc_attr( get_bloginfo( 'name' ) )
			);
		} else {

			$site_logo = sprintf(
				'<a href="%s"><h1>%s</h1></a>',
				esc_url( home_url() ),
				esc_html( get_bloginfo( 'name' ) )
			);
		}
	}

	// Output the logo or site name
	return $site_logo;
}

// Boo Energy Main Menu

function boo_header_menu() {
	if ( function_exists( 'boo_header_menu' ) ) {
		global $post;

		$boo_main_menu = 'menu-1'; // Default to private menu
		if ( $post ) {
			$main_page = $post;

			// If current page has a parent, get the top-level parent
			if ( $post->post_parent ) {
				$ancestors = get_post_ancestors( $post->ID );
				$main_page = get_post( end( $ancestors ) ); // Get top-level page
			}

			// Check if the URL contains "foretag" (force business menu)
			if ( strpos( get_permalink(), 'foretag' ) !== false ) {
				$boo_main_menu = 'menu-2';
				update_field( 'enable_breadcrumb_secendary_color', true, get_the_ID() );

			}
			// Otherwise, use ACF selection if available
			elseif ( class_exists( 'acf' ) ) {
				$boo_select_page_menu = get_field( 'boo_page_menu', $main_page->ID );
				if ( $boo_select_page_menu === 'business-menu' ) {
					$boo_main_menu = 'menu-2';
				}
			}
		}

		if ( has_nav_menu( $boo_main_menu ) ) {
			wp_nav_menu(
				array(
					'theme_location' => $boo_main_menu,
					'menu_id' => 'primary-menu',
					'menu_class' => 'primary-menu d-flex boo-reset-ul',
					'container' => 'nav',
					'container_class' => 'main-menu-wrapper boo-main-menu',
					'container_id' => 'boo-main-menu-wrapper',
					'walker' => new WP_Bootstrap_Navwalker_Custom(),
				)
			);
		}
	}
}





// Boo Energy Top Left Menu
function booTopMenuLeft() {
	wp_nav_menu(
		array(
			'theme_location' => 'menu-3',
			'menu_class' => 'top-bar-left-menu d-flex boo-reset-ul',
		)
	);
}

// Boo Energy Top Right Menu
function booTopMenuRight() {
	wp_nav_menu(
		array(
			'theme_location' => 'menu-4',
			'menu_class' => 'd-flex justify-content-end boo-reset-ul',
		)
	);
}

// Assign Header

function boo_header_wrapper() {
	get_template_part( 'template-parts/header/header' );
}
add_action( 'boo_header', 'boo_header_wrapper', 10 );


// Assign Footer

function boo_footer_wrapper() {
	get_template_part( 'template-parts/footer/footer' );
}
add_action( 'boo_footer', 'boo_footer_wrapper', 10 );

// Custom Exceprt More ...

function custom_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'custom_excerpt_more' );


// Register Elementor locations for both header and footer
function boo_register_elementor_locations( $elementor_theme_manager ) {
	$elementor_theme_manager->register_location( 'header' );
	$elementor_theme_manager->register_location( 'footer' );
}
add_action( 'elementor/theme/register_locations', 'boo_register_elementor_locations' );

/**
 *
 * pagination
 */
if ( ! function_exists( 'boo_pagination' ) ) {

	function boo_pagination() {
		$paginations = paginate_links(
			array(
				'type' => 'array',
				'prev_text' => '<i class="fa-regular fa-arrow-left"></i>',
				'next_text' => '<i class="fa-regular fa-arrow-right"></i>',
			)
		);
		if ( $paginations ) {
			echo '<div class="boo-basic-pagination"><nav><ul>';
			foreach ( $paginations as $pagination ) {
				echo "<li>$pagination</li>";
			}
			echo '</ul></nav></div>';
		}
	}
}


// Load Elementor Kits on Other page where Elementor Not loaded
function load_elementor_global_styles() {
	// Check if Elementor is active
	if ( did_action( 'elementor/loaded' ) ) {

		// Get the active global kit ID
		$global_kit_id = get_option( 'elementor_active_kit' );

		// If a global kit is active, enqueue its global styles
		if ( $global_kit_id ) {
			// Construct the URL for the global stylesheet
			$global_styles_url = wp_upload_dir()['baseurl'] . '/elementor/css/post-' . $global_kit_id . '.css';

			// Enqueue the global stylesheet
			wp_enqueue_style( 'elementor-global-styles', $global_styles_url, array(), null );
		}

		// Optionally, enqueue Elementor's frontend styles for consistent styling
		wp_enqueue_style( 'elementor-frontend' );
	}
}
add_action( 'wp_enqueue_scripts', 'load_elementor_global_styles' );


// Boo Comments
if ( ! function_exists( 'boo_energy_comment' ) ) {
	function boo_energy_comment( $comment, $args, $depth ) {
		$GLOBAL['comment'] = $comment;
		extract( $args, EXTR_SKIP );
		$args['reply_text'] = '<div class="boo-postbox-comment-reply"><span>Reply</span>
    </div>';
		$replayClass = 'comment-depth-' . esc_attr( $depth );
		?>


		<li id="comment-<?php comment_ID(); ?>" class="comment-list">
			<div class="boo-postbox-comment-box border-mr p-relative">
				<div class="boo-postbox-comment-box-inner d-flex">
					<div class="boo-postbox-comment-avater">
						<?php print get_avatar( $comment, 102, null, null, array( 'class' => array() ) ); ?>
					</div>
					<div class="boo-postbox-comment-content">
						<div class="boo-postbox-comment-author d-flex align-items-center">
							<h5 class="boo-postbox-comment-name"><?php print get_comment_author_link(); ?></h5>
							<p class="boo-postbox-comment-date"><?php the_time( get_option( 'date_format' ) ); ?></p>
						</div>
						<?php comment_text(); ?>
						<?php
						comment_reply_link(
							array_merge(
								$args,
								array(
									'depth' => $depth,
									'max_depth' => $args['max_depth'],
								)
							)
						);
						?>
					</div>
				</div>
			</div>
			<?php
	}
}

// Boo Tag
function boo_tags( $postType = '', $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	// Determine the taxonomy based on the post type
	$tag_taxonomy = ( 'skolan' === $postType ) ? 'skolan-tag' : 'post_tag';

	// Fetch tags for the specified taxonomy
	$tags = wp_get_post_terms( $post_id, $tag_taxonomy );

	// Determine the CSS class based on the post type
	$single_boo_tag_class = ( 'post' === get_post_type( $post_id ) ) ? 'boo-tag-post' : 'boo-tag-skolan';

	// Check if there are tags and output the first one
	if ( ! empty( $tags ) && get_post_type( $post_id ) === $postType ) : ?>
			<div class="boo-tag <?php echo esc_attr( $single_boo_tag_class ); ?>">
				<h5><?php echo esc_html( $tags[0]->name ); ?></h5>
			</div>
		<?php endif;
}


function sboo_tags( $postType = '', $post_id = null ) {


	// Get tags for the given taxonomy
	$tags = wp_get_post_terms( $post_id, $tag_taxonomy );

	// Define a CSS class based on the post type
	$tag_class = ( 'post' === $postType ) ? 'boo-tag-post' : 'boo-tag-skolan';

	// Check if the post has tags
	if ( ! empty( $tags ) && get_post_type( $post_id ) === $postType ) : ?>
			<div class="boo-tag <?php echo esc_attr( $tag_class ); ?>">
				<h5><?php echo esc_html( $tags[0]->name ); ?></h5>
			</div>
		<?php endif;
}




// Count Notification In Header
function boo_get_notification_count() {
	$arg = array(
		'post_type' => 'notification',
		'posts_per_page' => -1,
	);
	$notificationQuery = new WP_Query( $arg );
	$booNotificationCount = $notificationQuery->found_posts;
	wp_reset_postdata();

	// Localize the script with the notification count
	$localization_script = 'var booNotificationData = ' . json_encode( array(
		'count' => $booNotificationCount,
	) ) . ';';

	// Add inline script to load after boo-main
	wp_add_inline_script( 'boo-main', $localization_script, 'after' );
}
add_action( 'wp_footer', 'boo_get_notification_count' );



/**
 * Summary of boo_load_more_posts
 * @return void
 * Load More posts With Out Filter
 */
function boo_load_more_posts() {
	// Validate nonce
	check_ajax_referer( 'load_more_posts_nonce', 'nonce' );


	$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : 'post';
	$paged = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
	$category_slug = isset( $_POST['category_slug'] ) ? sanitize_text_field( $_POST['category_slug'] ) : '';

	$args = array(
		'post_type' => array( $post_type ),
		'posts_per_page' => 5,
		'post_status' => 'publish',
		'paged' => $paged,
	);

	// If a category slug is provided, add it to the query
	if ( ! empty( $category_slug ) && $category_slug !== 'all' ) {
		$args['category_name'] = $category_slug; // Filter by category slug
	}

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'template-parts/blog/content-blog' );
		}
		wp_reset_postdata();
	} else {
		wp_send_json_error( 'No more posts to load.' );
	}

	wp_die();
}

// Load More Post Ajax
add_action( 'wp_ajax_load_more_posts', 'boo_load_more_posts' );
add_action( 'wp_ajax_nopriv_load_more_posts', 'boo_load_more_posts' );



/**
 * Filter Posts
 * Summary of filter_posts_by_category
 * @return void
 */
function filter_posts_by_category() {
	check_ajax_referer( 'blogPosts', 'nonce' );

	if ( isset( $_POST['category_slug'] ) ) {
		$category_slug = sanitize_text_field( $_POST['category_slug'] );
		$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : 'post';
		$args = array(
			'post_type' => $post_type,
			'posts_per_page' => 5,
			'post_status' => 'publish',
		);

		if ( ! empty( $category_slug ) ) {
			$taxonomy = $post_type === 'post' ? 'category' : 'skolan_category';
			$args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy,
					'field' => 'slug',
					'terms' => $category_slug,
				),
			);
		}

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) :
			$total_post = $query->found_posts;
			$total_pages = ceil( $total_post / 5 );
			while ( $query->have_posts() ) :
				$query->the_post();
				get_template_part( 'template-parts/blog/content-blog' );
			endwhile;

			wp_reset_postdata();
		else :
			echo '<h6>' . esc_html__( 'Inga inlägg hittades.', 'boo-energy' ) . '</h6>';
		endif;
		?>
			<span class="d-none" id="filter-cat-max-page" data-max-pages="<?php echo esc_attr( $total_pages ); ?>"></span>
			<?php
	}

	wp_die();
}
add_action( 'wp_ajax_filter_posts_by_category', 'filter_posts_by_category' );
add_action( 'wp_ajax_nopriv_filter_posts_by_category', 'filter_posts_by_category' );
add_action( 'wp_ajax_filter_posts', 'filter_posts_by_category' );
add_action( 'wp_ajax_nopriv_filter_posts', 'filter_posts_by_category' );

/**
 * Load All Posts on Filter
 * Summary of load_all_posts
 * @return void
 */
function load_all_posts() {
	check_ajax_referer( 'blogPosts', 'nonce' );
	$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : 'post';
	$args = array(
		'post_type' => array( $post_type ),
		'posts_per_page' => 5,
		'paged' => isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1,
		'post_status' => 'publish',
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) :
		while ( $query->have_posts() ) :
			$query->the_post();
			get_template_part( 'template-parts/blog/content-blog' ); // Adjust the template part as needed
		endwhile;
	else :
		echo '<h6>Inga inlägg hittades.</h6>';
	endif;

	wp_reset_postdata();
	wp_die();
}

add_action( 'wp_ajax_load_all_posts', 'load_all_posts' );
add_action( 'wp_ajax_nopriv_load_all_posts', 'load_all_posts' );


/**
 * Summary of boo_ajax_live_search
 * @return void
 */
function boo_ajax_live_search() {
	$search_query = isset( $_POST['query'] ) ? sanitize_text_field( $_POST['query'] ) : '';
	$suggestions = [];

	if ( ! empty( $search_query ) ) {
		$args = [ 
			's' => $search_query,
			'post_type' => [ 'post', 'page' ],
			'posts_per_page' => 5,
			'post_status' => 'publish',
		];
		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$suggestions[] = [ 
					'title' => get_the_title(),
					'link' => get_permalink(),
				];
			}
		}
		wp_reset_postdata();
	}

	wp_send_json_success( $suggestions );
	wp_die();
}
add_action( 'wp_ajax_boo_live_search', 'boo_ajax_live_search' );
add_action( 'wp_ajax_nopriv_boo_live_search', 'boo_ajax_live_search' );





/**
 * Filter Notifications via AJAX
 */
function boo_filter_notifications() {
	check_ajax_referer( 'booNotifications', 'nonce' );

	$post_status = sanitize_text_field( $_POST['post_status'] );
	$args = array(
		'post_type' => 'notification',
		'post_status' => $post_status,
		'posts_per_page' => -1,
		'order' => 'ASC',
	);

	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( '/template-parts/notifications/content-notification' );
		}
	} else {
		echo '<p>' . esc_html__( 'Inga driftsdata hittades.', 'boo-energy' ) . '</p>';
	}
	wp_die();
}

add_action( 'wp_ajax_filter_notifications', 'boo_filter_notifications' );
add_action( 'wp_ajax_nopriv_filter_notifications', 'boo_filter_notifications' );



/**
 * Summary of Hide Backend boo_hide_acf_field_from_admin
 * @param mixed $field
 */
add_action( 'admin_head', function () {
	echo '<style>
        .acf-field[data-name="boo_page_menu"],
        .acf-field[data-name="enable_breadcrumb_secendary_color"] {
            display: none !important;
        }
    </style>';
} );

/**
 * Class Add based on Page boo_add_custom_class_to_menu
 * @param mixed $items
 * @param mixed $args
 * @return mixed
 */
function boo_add_custom_class_to_menu( $items, $args ) {
	if ( $args->theme_location === 'menu-3' ) {
		global $post;
		$boo_page_menu = 'private-menu'; // Default to private

		if ( $post ) {
			$main_page = $post;

			// If current page has a parent, get the top-level parent
			if ( $post->post_parent ) {
				$ancestors = get_post_ancestors( $post->ID );
				$main_page = get_post( end( $ancestors ) ); // Get top-level page
			}

			// Force business menu if URL contains "foretag"
			if ( strpos( get_permalink(), 'foretag' ) !== false ) {
				$boo_page_menu = 'business-menu';
			}
			// Otherwise, check ACF selection
			elseif ( class_exists( 'acf' ) ) {
				$acf_value = get_field( 'boo_page_menu', $main_page->ID );
				if ( $acf_value === 'business-menu' ) {
					$boo_page_menu = 'business-menu';
				}
			}
		}

		// Apply class based on the determined menu type
		if ( ! empty( $items ) ) {
			foreach ( $items as $item ) {
				if ( $boo_page_menu === 'private-menu' && $item->title === 'Privat' ) {
					$item->classes[] = 'custom-class-privat';
				}
				if ( $boo_page_menu === 'business-menu' && $item->title === 'Företag' ) {
					$item->classes[] = 'custom-class-foretag';
				}
			}
		}
	}

	return $items;
}
add_filter( 'wp_nav_menu_objects', 'boo_add_custom_class_to_menu', 10, 2 );

// Check Edit By Elementor Or Not
function is_elementor_edit( $post_id ) {
	$meta_value = get_post_meta( $post_id, '_elementor_edit_mode', true );
	return ! empty( $meta_value );
}


/**
 *  move_notifications_to_history
 * @return void
 */
if ( ! wp_next_scheduled( 'move_expired_notifications' ) ) {
	wp_schedule_event( time(), 'hourly', 'move_expired_notifications' );
}
add_action( 'move_expired_notifications', 'move_notifications_to_history' );

function move_notifications_to_history() {
	$current_time = current_time( 'Y-m-d H:i:s' );
	error_log( $current_time );
	$args = array(
		'post_type' => 'notification',
		'post_status' => 'publish',
		'meta_query' => array(
			array(
				'key' => 'avbrott_avslutas',
				'value' => $current_time,
				'compare' => "<=",
				'type' => 'DATETIME',
			),
		),
		'posts_per_page' => -1,
	);
	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$post_id = get_the_ID();

			if ( get_field( 'post_mode_selector', $post_id ) ) {
				error_log( "Skipping post ID For Published to History {$post_id} due to post_mode_selector being true." );
				continue;
			}

			$result = wp_update_post( array(
				'ID' => $post_id,
				'post_status' => 'pending',
			) );
			if ( ! $result ) {
				error_log( "Failed to update post ID {$post_id}" );
			} else {
				error_log( "Post ID {$post_id} updated to status: pending" );
			}
		}
	} else {
		error_log( 'Inga inlägg hittades. to update for publish' );
	}
	wp_reset_postdata();
}


// Move Draft to trash after 48hours
if ( ! wp_next_scheduled( 'trash_history_notifications' ) ) {
	wp_schedule_event( time(), 'hourly', 'trash_history_notifications' );
}
add_action( 'trash_history_notifications', 'trash_expired_notifications' );
function trash_expired_notifications() {
	$args = array(
		'post_type' => 'notification',
		'post_status' => 'pending',
		'date_query' => array(
			array(
				'column' => 'post_modified_gmt',
				'before' => '48 hours ago',
			),
		),
	);
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$post_id = get_the_ID();
			if ( get_field( 'post_mode_selector', $post_id ) ) {
				error_log( "Skipping post ID For History to Trash {$post_id} due to post_mode_selector being true." );
				continue;
			} else {
				error_log( "Not Skipping post ID For History to Trash {$post_id} due to post_mode_selector being true." );
			}
			wp_trash_post( get_the_ID() );
		}
	} else {
		error_log( 'no post found for pending to trash' );
	}
	wp_reset_postdata();
}

// Manuall Control for notification posts
add_action( 'acf/save_post', 'update_post_status_for_notifications', 20 );
function update_post_status_for_notifications( $post_id ) {
	if ( get_post_type( $post_id ) !== 'notification' && ( false === get_field( 'post_mode_selector', $post_id ) ) ) {
		return;
	}
	$new_status = get_field( 'select_post_mode', $post_id );
	$status_mapping = array(
		'ongoing' => 'publish',
		'history' => 'pending',
	);

	if ( isset( $status_mapping[ $new_status ] ) && true === get_field( 'post_mode_selector', $post_id ) ) {
		$updated_post = wp_update_post( [ 
			'ID' => $post_id,
			'post_status' => $status_mapping[ $new_status ],
		] );
		error_log( 'Post Update Result: ' . ( is_wp_error( $updated_post ) ? $updated_post->get_error_message() : 'Success' ) );
	} else {
		error_log( 'Invalid Status: ' . $new_status );
	}
}



// Add custom columns to the 'campaigns' custom post type
function add_campaigns_columns( $columns ) {
	return array_merge( $columns, array(
		'code' => __( 'Kod', 'boo-energy' ),
		'fixed_1' => __( 'Bundet - 1 ār', 'boo-energy' ),
		'fixed_2' => __( 'Bundet - 2 ār', 'boo-energy' ),
		'fixed_3' => __( 'Bundet - 3 ār', 'boo-energy' ),
		'variable' => __( 'Rörligt', 'boo-energy' ),
		'boo_portfolio' => __( 'Boo Portfolio', 'boo-energy' ),
		'actions' => __( 'Åtgärder', 'boo-energy' )
	) );
}
add_filter( 'manage_campaigns_posts_columns', 'add_campaigns_columns' );

// Populate custom columns with data
function custom_campaigns_column( $column, $post_id ) {
	switch ( $column ) {
		case 'code':
			echo get_post_meta( $post_id, 'code', true );
			break;
		case 'fixed_1':
			echo get_post_meta( $post_id, 'fixed_1', true );
			break;
		case 'fixed_2':
			echo get_post_meta( $post_id, 'fixed_2', true );
			break;
		case 'fixed_3':
			echo get_post_meta( $post_id, 'fixed_3', true );
			break;
		case 'variable':
			echo get_post_meta( $post_id, 'variable', true );
			break;
		case 'boo_portfolio':
			echo get_post_meta( $post_id, 'boo_portfolio', true );
			break;
		case 'actions':
			$edit_link = get_edit_post_link( $post_id );
			$delete_link = get_delete_post_link( $post_id );
			echo '<a href="' . $edit_link . '">Edit</a> | <a href="' . $delete_link . '" onclick="return confirm(\'Are you sure you want to delete this item?\');">Delete</a>';
			break;
	}
}
add_action( 'manage_campaigns_posts_custom_column', 'custom_campaigns_column', 10, 2 );



/**
 * Summary of change_rest_api_prefix For Change Rest API Prefix
 * @return string
 */
function change_rest_api_prefix() {
	return 'api';
}
add_filter( 'rest_url_prefix', 'change_rest_api_prefix' );



/**
 * Summary of enqueue_custom_gap_style
 * Enqueue custom gap style for posts that are  built with Elementor
 * @return void
 */
function enqueue_custom_gap_style() {
	if ( is_singular( 'post' ) && \Elementor\Plugin::instance()->db->is_built_with_elementor( get_the_ID() ) ) {
		wp_enqueue_style( 'custom-gap-style', BOO_THEME_CSS_DIR . 'boo-elementor-posts.css' );
	}
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_gap_style' );




/**
 * Assign Capabilities to Editor and Administrator Roles
 */
function boo_assign_capabilities() {
	$roles = [ 'editor', 'administrator' ];

	foreach ( $roles as $role_name ) {
		$role = get_role( $role_name );

		// Custom capabilities for Boo Energi
		$capabilities = [ 
			'edit_boos',
			'read_boos',
			'delete_boos',
			'edit_theme_options', // Grant access to the Customizer
		];

		foreach ( $capabilities as $cap ) {
			$role->add_cap( $cap );
		}
	}
}
add_action( 'init', 'boo_assign_capabilities' );




