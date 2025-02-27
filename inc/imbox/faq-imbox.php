<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$api_key = defined( 'IMBOX_API_KEY' ) ? IMBOX_API_KEY : '';
$user_id = defined( 'IMBOX_USER_ID' ) ? IMBOX_USER_ID : '';
$help_center_id = defined( 'IMBOX_HELP_CENTER_ID' ) ? IMBOX_HELP_CENTER_ID : '';

// **Function to fetch all FAQ categories**
function fetch_all_faq_categories() {
	global $api_key, $user_id;

	$api_url = "https://apiv2.imbox.io/api/{$api_key}/{$user_id}/faq/categories?lang=sv";
	$response = wp_remote_get( $api_url, array( 'timeout' => 30 ) );

	if ( is_wp_error( $response ) ) {
		error_log( 'API Request Failed: ' . $response->get_error_message() );
		return [];
	}

	$body = wp_remote_retrieve_body( $response );
	return json_decode( $body, true ) ?: [];
}

// **Function to fetch FAQs by category**
function fetch_faqs_by_category( $faq_category_id ) {
	global $api_key, $user_id, $help_center_id;
	$api_url = "https://apiv2.imbox.io/api/{$api_key}/{$user_id}/faq/categories/{$faq_category_id}?lang=sv&helpCenterId={$help_center_id}";

	$response = wp_remote_get( $api_url, array( 'timeout' => 30 ) );

	if ( is_wp_error( $response ) ) {
		error_log( "Failed to fetch FAQs for Category ID $faq_category_id: " . $response->get_error_message() );
		return [];
	}

	$body = wp_remote_retrieve_body( $response );
	$faqs_data = json_decode( $body, true );
	return isset( $faqs_data['allArticles'] ) ? $faqs_data['allArticles'] : [];
}

// **Function to fetch, sync and delete FAQs/categories**
function fetch_and_sync_faqs() {
	$categories = fetch_all_faq_categories();
	$existing_terms = get_terms( [ 'taxonomy' => 'faq-cat', 'hide_empty' => false ] );
	$existing_term_ids = wp_list_pluck( $existing_terms, 'term_id', 'slug' );

	if ( empty( $categories ) ) {
		error_log( 'No categories found for syncing FAQs.' );
		return;
	}

	$sync_report = [];
	$synced_categories = [];
	$synced_posts = [];

	foreach ( $categories as $category ) {
		if ( ! isset( $category['faqCategoryId'], $category['title'], $category['description'] ) ) {
			continue;
		}

		$faq_category_id = intval( $category['faqCategoryId'] );
		$category_slug = (string) $faq_category_id;
		$category_name = sanitize_text_field( $category['title'] );
		$category_description = sanitize_text_field( $category['description'] );

		// Check if the term exists
		if ( isset( $existing_term_ids[ $category_slug ] ) ) {
			$term_id = $existing_term_ids[ $category_slug ];
			if ( get_term( $term_id, 'faq-cat' )->name !== $category_name ) {
				wp_update_term( $term_id, 'faq-cat', [ 
					'name' => $category_name,
					'description' => $category_description,
				] );
			}
		} else {
			$term_data = wp_insert_term( $category_name, 'faq-cat', [ 
				'slug' => $category_slug,
				'description' => $category_description,
			] );
			if ( ! is_wp_error( $term_data ) ) {
				$term_id = $term_data['term_id'];
			} else {
				continue;
			}
		}

		$synced_categories[] = $category_slug;
		$posts = fetch_faqs_by_category( $faq_category_id );

		foreach ( $posts as $faq ) {
			$faq_id = intval( $faq['id'] );
			$faqArticleId = intval( $faq['faqArticleId'] );
			$post_title = sanitize_text_field( $faq['title'] );
			$post_content = wp_kses_post( $faq['content'] );

			$existing_post = get_posts( [ 
				'post_type' => 'faq',
				'meta_query' => [ [ 
					'key' => '_external_faq_id',
					'value' => $faq_id,
				] ],
			] );

			if ( $existing_post ) {
				$post_id = $existing_post[0]->ID;
				wp_update_post( [ 
					'ID' => $post_id,
					'post_title' => $post_title,
					'post_content' => $post_content,
				] );
				update_post_meta( $post_id, 'faqArticleId', $faqArticleId );
			} else {
				$post_id = wp_insert_post( [ 
					'post_type' => 'faq',
					'post_title' => $post_title,
					'post_content' => $post_content,
					'post_status' => 'publish',
					'meta_input' => [ 
						'_external_faq_id' => $faq_id,
						'faqArticleId' => $faqArticleId,
					],
				] );
			}

			if ( ! is_wp_error( $post_id ) ) {
				wp_set_post_terms( $post_id, [ $term_id ], 'faq-cat' );
				$synced_posts[] = $post_id;
			}
		}
	}

	// Delete missing categories and posts
	foreach ( $existing_term_ids as $slug => $term_id ) {
		if ( ! in_array( $slug, $synced_categories ) ) {
			wp_delete_term( $term_id, 'faq-cat' );
		}
	}

	$existing_posts = get_posts( [ 'post_type' => 'faq', 'posts_per_page' => -1 ] );
	foreach ( $existing_posts as $post ) {
		if ( ! in_array( $post->ID, $synced_posts ) ) {
			wp_delete_post( $post->ID, true );
		}
	}
}

// **Admin Sync Button**
function faq_sync_admin_menu() {
	add_submenu_page( 'edit.php?post_type=faq', 'Sync FAQs', 'Sync FAQs', 'manage_options', 'sync-faqs', 'faq_sync_callback' );
}
add_action( 'admin_menu', 'faq_sync_admin_menu' );

function faq_sync_callback() {
	if ( isset( $_POST['sync_faqs'] ) ) {
		fetch_and_sync_faqs();
		echo '<div class="updated"><p>FAQ Sync Completed!</p></div>';
	}
	echo '<div class="wrap"><h2>Manual FAQ Sync</h2><form method="post"><input type="submit" name="sync_faqs" class="button button-primary" value="Sync Now"></form></div>';
}

// **Automate Sync via WP-Cron**
function schedule_faq_sync() {
	if ( ! wp_next_scheduled( 'faq_sync_cron' ) ) {
		wp_schedule_event( time(), 'daily', 'faq_sync_cron' );
	}
}
add_action( 'wp', 'schedule_faq_sync' );
add_action( 'faq_sync_cron', 'fetch_and_sync_faqs' );

// **Clear WP-Cron on Plugin Deactivation**
function clear_faq_sync_cron() {
	wp_clear_scheduled_hook( 'faq_sync_cron' );
}
register_deactivation_hook( __FILE__, 'clear_faq_sync_cron' );







//*****  Yes vote no vote function
// Start the session on WordPress
function start_session() {
	if ( session_status() === PHP_SESSION_NONE ) {
		session_start();
	}
}
add_action( 'init', 'start_session', 1 );

// Register FAQ Post Meta (faqArticleId, Likes/Dislikes)
function register_faq_post_meta() {
	// Register the FAQ Article ID as post meta
	register_post_meta( 'faq', 'faqArticleId', [ 
		'type' => 'string',
		'description' => 'FAQ Article ID',
		'single' => true,
		'show_in_rest' => true, // Make it accessible via the REST API
	] );

	// Register Likes and Dislikes as post meta
	register_post_meta( 'faq', '_faq_likes', [ 
		'type' => 'integer',
		'single' => true,
		'default' => 0,
		'show_in_rest' => true,
	] );

	register_post_meta( 'faq', '_faq_dislikes', [ 
		'type' => 'integer',
		'single' => true,
		'default' => 0,
		'show_in_rest' => true,
	] );
}
add_action( 'init', 'register_faq_post_meta' );

// Add Like/Dislike Columns to Admin Dashboard
function add_faq_columns( $columns ) {
	$columns['likes'] = 'Ja (Yes)';
	$columns['dislikes'] = 'Nej (No)';
	return $columns;
}
add_filter( 'manage_faq_posts_columns', 'add_faq_columns' );

function faq_column_content( $column, $post_id ) {
	if ( $column == 'likes' ) {
		echo get_post_meta( $post_id, '_faq_likes', true ) ?: 0;
	}
	if ( $column == 'dislikes' ) {
		echo get_post_meta( $post_id, '_faq_dislikes', true ) ?: 0;
	}
}
add_action( 'manage_faq_posts_custom_column', 'faq_column_content', 10, 2 );

// Make Columns Sortable
function make_faq_columns_sortable( $columns ) {
	$columns['likes'] = '_faq_likes';
	$columns['dislikes'] = '_faq_dislikes';
	return $columns;
}
add_filter( 'manage_edit-faq_sortable_columns', 'make_faq_columns_sortable' );

// Shortcode for Yes/No Voting
function faq_vote_buttons_shortcode() {
	if ( ! is_singular( 'faq' ) ) {
		return ''; // Only display on single FAQ posts
	}

	$post_id = get_the_ID();

	$faqArticleId = get_post_meta( $post_id, 'faqArticleId', true );

	if ( empty( $faqArticleId ) ) {
		error_log( 'Missing FAQ Article ID for post ID ' . $post_id );
	}

	ob_start();
	?>
	<div class="post-feedback">
		<p>Var den här artikeln användbar?</p>
		<div class="faq-yesno-btns">
			<button id="yes-btn" data-post-id="<?php echo esc_attr( $post_id ); ?>"
				data-article-id="<?php echo esc_attr( $faqArticleId ); ?>">Ja</button>
			<button id="no-btn" data-post-id="<?php echo esc_attr( $post_id ); ?>"
				data-article-id="<?php echo esc_attr( $faqArticleId ); ?>">Nej</button>
		</div>
	</div>

	<style>
		.post-feedback {
			display: flex;
			gap: 15px;
			align-items: center;
		}

		.post-feedback p {
			margin-bottom: 0 !important;
		}

		.faq-yesno-btns button#yes-btn {
			margin-right: 5px;
		}

		.post-feedback button {
			cursor: pointer;
			border-radius: 8px 8px 8px 8px;
			padding: 8px 24px 8px 24px;
			line-height: 19.6px;
			font-weight: 700;
			font-size: 14px;
			color: #fff;
			background-color: #18332F;
			font-family: "Boo Energi Sans";
			transition: all 0.3s;
		}

		.post-feedback button:hover,
		.post-feedback button:focus {
			background-color: #24544D;
		}

		@media(max-width:480px) {
			.post-feedback {
				flex-direction: column;
				align-items: flex-start;
			}
		}
	</style>

	<script>
		document.addEventListener("DOMContentLoaded", function () {
			let yesBtn = document.getElementById("yes-btn");
			let noBtn = document.getElementById("no-btn");

			if (!yesBtn || !noBtn) return;

			// Event listener for "Yes" vote
			yesBtn.addEventListener("click", function () {
				let postId = this.dataset.postId;
				let articleId = this.dataset.articleId;
				sendFeedback(postId, 'like', articleId);
			});

			// Event listener for "No" vote
			noBtn.addEventListener("click", function () {
				let postId = this.dataset.postId;
				let articleId = this.dataset.articleId;
				sendFeedback(postId, 'dislike', articleId);
			});

			// AJAX function to send feedback
			function sendFeedback(postId, feedback, faqArticleId) {
				let xhr = new XMLHttpRequest();
				xhr.open("POST", "<?php echo admin_url( 'admin-ajax.php' ); ?>", true);
				xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

				xhr.onreadystatechange = function () {
					if (xhr.readyState === 4 && xhr.status === 200) {
						try {
							let response = JSON.parse(xhr.responseText);
							if (response.success) {
								document.querySelector(".post-feedback").innerHTML = "<p>Tack för din feedback!</p>";
							} else {
								// Display the error message (e.g., already voted)
								let errorMessage = response.data.message || "Något gick fel. Försök igen.";
								document.querySelector(".post-feedback").innerHTML = "<p>" + errorMessage + "</p>";
							}
						} catch (e) {
							console.error("Error parsing response:", e);
						}
					}
				};

				xhr.send("action=update_faq_feedback&post_id=" + postId + "&feedback=" + feedback + "&faqArticleId=" + faqArticleId);
			}
		});

	</script>
	<?php
	return ob_get_clean();
}
add_shortcode( 'faq_vote', 'faq_vote_buttons_shortcode' );

// Send Vote Data to IMBOX API
function send_vote_to_imbox_api( $post_id, $feedback, $faqArticleId ) {


	if ( empty( $faqArticleId ) ) {
		error_log( "Article ID is missing for post ID $post_id." );
		return false;  // Exit if article ID is not set
	}
	$api_key = defined( 'IMBOX_API_KEY' ) ? IMBOX_API_KEY : '';
	$user_id = defined( 'IMBOX_USER_ID' ) ? IMBOX_USER_ID : '';
	$help_center_id = defined( 'IMBOX_HELP_CENTER_ID' ) ? IMBOX_HELP_CENTER_ID : '';

	$page_url = get_permalink( $post_id );
	global $api_key, $user_id;

	// Define URL for vote (Yes or No)
	$vote_action = ( $feedback === 'like' ) ? 'voteyes' : 'voteno';
	$url = "https://apiv2.imbox.io/api/{$api_key}/{$user_id}/faq/analytics/{$vote_action}";

	$body = json_encode( [ 
		'lang' => 'sv',
		'articleId' => $faqArticleId,
		'device' => wp_is_mobile() ? 'mobile' : 'desktop',
		'helpCenterId' => $help_center_id,
		'pageUrl' => $page_url
	] );

	$response = wp_remote_post( $url, [ 
		'body' => $body,
		'headers' => [ 
			'Content-Type' => 'application/json',
		],
		'method' => 'POST',
	] );

	if ( is_wp_error( $response ) ) {
		error_log( "IMBOX API Error: " . $response->get_error_message() );
		return false;
	}

	// error_log("IMBOX API Response: " . print_r($response, true)); // Log the response for debugging

	return true;
}

// Handle AJAX Requests for Voting
function update_faq_feedback() {
	if ( session_status() === PHP_SESSION_NONE ) {
		session_start(); // Ensure the session is started
	}

	global $_SESSION; // Explicitly declare global variable

	if ( ! isset( $_POST['post_id'] ) || ! isset( $_POST['feedback'] ) || ! isset( $_POST['faqArticleId'] ) ) {
		wp_send_json_error( [ 'message' => 'Invalid request' ] );
	}

	$post_id = intval( $_POST['post_id'] );
	$feedback = sanitize_text_field( $_POST['feedback'] );
	$faqArticleId = sanitize_text_field( $_POST['faqArticleId'] );

	// Initialize session array if not set
	if ( ! isset( $_SESSION['faq_vote'] ) || ! is_array( $_SESSION['faq_vote'] ) ) {
		$_SESSION['faq_vote'] = [];
	}

	if ( ! isset( $_SESSION['faq_vote'][ $post_id ] ) ) {
		$_SESSION['faq_vote'][ $post_id ] = ''; // Initialize with empty value
	}

	// Prevent duplicate voting
	if ( $_SESSION['faq_vote'][ $post_id ] === $feedback ) {
		// Returning error message for frontend
		wp_send_json_error( [ 'message' => 'Du har redan röstat.' ] );
	}

	// If switching votes, remove previous vote
	if ( $_SESSION['faq_vote'][ $post_id ] !== '' && $_SESSION['faq_vote'][ $post_id ] !== $feedback ) {
		$previous_vote = $_SESSION['faq_vote'][ $post_id ];
		$previous_meta_key = ( $previous_vote === 'like' ) ? '_faq_likes' : '_faq_dislikes';
		$previous_count = get_post_meta( $post_id, $previous_meta_key, true );
		update_post_meta( $post_id, $previous_meta_key, max( 0, $previous_count - 1 ) );
	}

	// Update the new vote
	$meta_key = ( $feedback === 'like' ) ? '_faq_likes' : '_faq_dislikes';
	$count = get_post_meta( $post_id, $meta_key, true );
	update_post_meta( $post_id, $meta_key, ( $count ? (int) $count + 1 : 1 ) );

	$_SESSION['faq_vote'][ $post_id ] = $feedback; // Store vote per post

	send_vote_to_imbox_api( $post_id, $feedback, $faqArticleId );

	wp_send_json_success( [ 
		'likes' => get_post_meta( $post_id, '_faq_likes', true ),
		'dislikes' => get_post_meta( $post_id, '_faq_dislikes', true ),
	] );
}

add_action( 'wp_ajax_update_faq_feedback', 'update_faq_feedback' );
add_action( 'wp_ajax_nopriv_update_faq_feedback', 'update_faq_feedback' );


function faq_taxonomy_with_post_count_shortcode() {
	// Get all terms for the 'faq-cat' taxonomy
	$terms = get_terms( array(
		'taxonomy' => 'faq-cat',  // Replace with your taxonomy slug
		'orderby' => 'name',
		'order' => 'ASC',
		'hide_empty' => true,  // Only show terms with posts
	) );

	// Check if there are any terms or if get_terms() failed
	if ( $terms && ! is_wp_error( $terms ) ) {
		$output = '
        <style>
        .faq-terms-lists {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .faq-term-item {
            flex: 1 1 30%;
            background: #ffffff;
            border-radius: 8px;
            padding: 24px;
            border: 1px solid #E2DAD6;
            display: flex;
            flex-direction: column;
        }

        .faq-term h3 {
            font-family: "Boo Energi Sans", sans-serif;
            font-size: 20px;
            font-weight: 800;
            line-height: 26px;
            color: #000;
            margin-bottom: 15px;
        }

        .term-image {
            margin-bottom: 15px;
        }
        .faq-term {
            margin-bottom: 15px;
        }
        .term-image img {
            max-width: 64px;
        }

        .faq-term-btn {
            margin-top: auto;
        }

        a.faq-cat-button {
            font-weight: 700;
            color: #ff5500;
            display: flex;
            align-items: center;
        }

        a.faq-cat-button i {
            font-size: 30px;
            font-weight: 700;
            transition: 0.3s ease-in-out;
        }

        a.faq-cat-button:hover i {
            margin-left: 5px;
        }
        @media(min-width:581px) and (max-width: 980px){
            .faq-term-item {
                flex: 1 1 calc(50% - 30px);
            }
        }    

        @media(max-width:580px){
            .faq-term-item {
                flex: 1 1 100%;
            }
        }
        </style>
        ';
		$output .= '<div class="faq-terms-lists">';

		foreach ( $terms as $term ) {
			// Get post count for each term
			$post_count = $term->count;
			$term_link = get_term_link( $term );

			// Get the image URL for the taxonomy term (using ACF)

			$term_image = get_field( 'category_image', 'faq-cat_' . $term->term_id );
			$term_image_url = is_numeric( $term_image ) ? wp_get_attachment_image_url( $term_image, 'full' ) : $term_image;

			$output .= '<div class="faq-term-item">';
			// Output the term image (if available)
			if ( ! empty( $term_image_url ) ) {
				$output .= '<div class="term-image"><img src="' . esc_url( $term_image_url ) . '" alt="' . esc_attr( $term->name ) . '"></div>';
			}

			// Output the term details
			$output .= '<div class="faq-term">';
			$output .= '<h3>' . esc_html( $term->name ) . '</h3>';
			$output .= '<p>' . esc_html( $term->description ) . '</p>';
			$output .= '</div>';
			$output .= '<div class="faq-term-btn"><a href="' . esc_url( $term_link ) . '" class="faq-cat-button">Visa alla ' . $post_count . ' artiklar <i aria-hidden="true" class="boo boo-arrow-right"></i></a></div>';
			$output .= '</div>';
		}

		$output .= '</div>';
		return $output;
	} else {
		return '<p>No terms found or an error occurred.</p>';
	}
}
add_shortcode( 'faq_taxonomy_with_post_count', 'faq_taxonomy_with_post_count_shortcode' );


// Popular article shortcode
function fetch_imbox_popular_articles( $atts ) {

	$api_key = defined( 'IMBOX_API_KEY' ) ? IMBOX_API_KEY : '';
	$user_id = defined( 'IMBOX_USER_ID' ) ? IMBOX_USER_ID : '';
	$help_center_id = defined( 'IMBOX_HELP_CENTER_ID' ) ? IMBOX_HELP_CENTER_ID : '';
	// Set default values for the attributes
	$atts = shortcode_atts(
		array(
			'api_key' => $api_key,
			'user_id' => $user_id,
			'lang' => 'sv',
			'limit' => 5, // Number of posts to fetch (can be adjusted via the shortcode)
		),
		$atts,
		'imbox_popular_articles'
	);

	// API URL with placeholders for dynamic data
	$url = "https://apiv2.imbox.io/api/{$atts['api_key']}/{$atts['user_id']}/faq/popular-articles?lang=sv&helpCenterId=55";

	// Fetch data from Imbox API
	$response = wp_remote_get( $url );

	if ( is_wp_error( $response ) ) {
		return 'Unable to fetch popular articles at the moment.';
	}

	$data = wp_remote_retrieve_body( $response );
	$articles = json_decode( $data, true );

	if ( empty( $articles ) ) {
		return 'No popular articles found.';
	}

	// Start output buffer to generate the HTML
	ob_start();
	echo '<style>
    .imbox-popular-articles h4 {
        margin-bottom: 20px;
    }

    .imbox-popular-articles .imbox-article p a {
        color: #ff5602;
    }

    .imbox-popular-articles {
        background: #ffffff;
        border-radius: 8px;
        padding: 24px;
        border: 1px solid #E2DAD6;
    }

    .imbox-popular-articles .imbox-article p a:hover {
        color: #000;
    }

    .imbox-article {
        margin-bottom: 10px;
    }
    </style>';
	echo '<div class="imbox-popular-articles">';
	echo '<h4>Populära artiklar</h4>';

	// Limit the number of articles to display
	$articles = array_slice( $articles, 0, $atts['limit'] );

	foreach ( $articles as $article ) {
		// Check if 'faqArticleId' is available and is a valid ID (as integer)
		$faq_article_id = isset( $article['faqArticleId'] ) ? (int) $article['faqArticleId'] : 0;

		if ( $faq_article_id > 0 ) {
			// Query WordPress to find the post with this faqArticleId stored in meta
			$faq_post = get_posts( array(
				'post_type' => 'faq', // Change to your actual post type slug
				'meta_key' => 'faqArticleId', // Change to the actual meta key storing faqArticleId
				'meta_value' => $faq_article_id,
				'numberposts' => 1,
			) );

			if ( ! empty( $faq_post ) ) {
				$post_id = $faq_post[0]->ID;
				$permalink = get_permalink( $post_id );

				// Debugging: Log to check if permalink is retrieved
				error_log( "Post ID: " . $post_id . " | Permalink: " . $permalink );

				echo '<div class="imbox-article">';
				echo '<p><a href="' . esc_url( $permalink ) . '">' . esc_html( $article['title'] ) . '</a></p>';
				echo '</div>';
			} else {
				error_log( "No matching FAQ post found for faqArticleId: " . $faq_article_id );
			}
		}
	}


	echo '</div>';

	// Return the buffered HTML
	return ob_get_clean();
}

// Register the shortcode
add_shortcode( 'imbox_popular_articles', 'fetch_imbox_popular_articles' );


function get_imbox_article_author_info_dynamic( $atts ) {
	global $post;
	$api_key = defined( 'IMBOX_API_KEY' ) ? IMBOX_API_KEY : '';
	$user_id = defined( 'IMBOX_USER_ID' ) ? IMBOX_USER_ID : '';
	$help_center_id = defined( 'IMBOX_HELP_CENTER_ID' ) ? IMBOX_HELP_CENTER_ID : '';
	// Define default shortcode attributes
	$atts = shortcode_atts(
		array(
			'api_key' => $api_key,
			'user_id' => $user_id,
			'help_center_id' => '55',
			'lang' => 'sv',
			'meta_key' => 'faqArticleId', // Meta key where faqArticleId is stored
		),
		$atts,
		'imbox_author_info'
	);

	// Retrieve the faqArticleId from post meta dynamically
	$faq_article_id = get_post_meta( $post->ID, $atts['meta_key'], true );

	// If no valid faqArticleId, return an error message
	if ( empty( $faq_article_id ) ) {
		return 'No FAQ Article ID found in post meta.';
	}

	// Check cache first
	$cache_key = 'imbox_author_' . $faq_article_id;
	$cached_data = get_transient( $cache_key );

	if ( $cached_data !== false ) {
		return $cached_data; // Return cached data if available
	}

	// Build API request URL
	$api_url = "https://apiv2.imbox.io/api/{$atts['api_key']}/{$atts['user_id']}/faq/articles/{$faq_article_id}?lang={$atts['lang']}&helpCenterId={$atts['help_center_id']}";

	// Fetch API data
	$response = wp_remote_get( $api_url );

	if ( is_wp_error( $response ) ) {
		return 'Unable to fetch author info.';
	}

	$data = wp_remote_retrieve_body( $response );
	$article = json_decode( $data, true );

	if ( empty( $article ) || ! isset( $article['authorName'] ) ) {
		return 'No author info found.';
	}

	// Extract author details
	$author_name = esc_html( $article['authorName'] );
	$author_picture = ! empty( $article['authorPicture'] ) ? esc_url( $article['authorPicture'] ) : '';

	// Output author information
	ob_start();
	?>
	<style>
		.imbox-author-info {
			display: flex;
			gap: 15px;
			align-items: center;
		}

		.imbox-author-meta p {
			margin: 0px !important;
		}

		.imbox-author-meta p.imbox-author-name {
			font-weight: 700;
			font-size: 18px;
		}
	</style>
	<div class="imbox-author-info">

		<?php if ( ! empty( $author_picture ) ) : ?>
			<div class="imbox-author-img">
				<img src="<?php echo $author_picture; ?>" alt="<?php echo $author_name; ?>" class="imbox-author-picture"
					style="width: 50px; height: 50px; border-radius: 50%;">
			</div>
		<?php endif; ?>
		<div class="imbox-author-meta">
			<p>Skrivet av</p>
			<p class="imbox-author-name"><?php echo $author_name; ?></p>
		</div>
	</div>
	<?php
	$output = ob_get_clean();

	// Cache for 1 hour
	set_transient( $cache_key, $output, HOUR_IN_SECONDS );

	return $output;
}
add_shortcode( 'imbox_author_info', 'get_imbox_article_author_info_dynamic' );