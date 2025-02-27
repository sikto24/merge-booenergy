<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// New functions for popup
/**
 * Summary of enqueue_notification_popup_scripts
 * Enqueue scripts and styles for notification popup
 * @return void
 */
function enqueue_notification_popup_scripts() {
	wp_enqueue_script( 'notification-popup-script', BOO_THEME_URI . '/inc/notification-popup/js/notification-popup.js', array( 'jquery' ), null, true );
	wp_enqueue_style( 'notification-popup-style', BOO_THEME_URI . '/inc/notification-popup/css/notification-popup.css' );

	// Localize script with AJAX URL & Nonce
	wp_localize_script( 'notification-popup-script', 'notificationPopup', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'notification_popup_nonce' ),
		'is_user_logged_in' => is_user_logged_in() ? '1' : '0' // Pass user login status
	) );
}
add_action( 'wp_enqueue_scripts', 'enqueue_notification_popup_scripts' );

/**
 * Summary of fetch_active_notifications
 * Fetch active notifications from database / Custom Post Type
 * @return void
 */
function fetch_active_notifications() {
	check_ajax_referer( 'notification_popup_nonce', 'nonce' );

	$args = array(
		'post_type' => 'notification',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => 'avbrott_startar',
				'compare' => 'EXISTS'
			),
			array(
				'key' => 'avbrott_avslutas',
				'compare' => 'EXISTS'
			),
			array(
				'key' => 'plan_status',
				'value' => 'Planerat',
				'compare' => '!='
			),
		)
	);

	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {
		$notifications = array();
		while ( $query->have_posts() ) {
			$query->the_post();
			$start_time = get_field( 'avbrott_startar' );
			$end_time = get_field( 'avbrott_avslutas' );

			// Format dates
			$formatted_time = date( 'Y-m-d, H:i', strtotime( $start_time ) ) . ' - ' . date( 'Y-m-d, H:i', strtotime( $end_time ) );

			$notifications[] = array(
				'id' => get_the_ID(),
				'title' => 'Aktuell driftstatus',
				'time' => $formatted_time,
				'content' => get_the_content(),
				'permalink' => home_url() . '/aktuell-driftstatus',
			);
		}
		wp_send_json_success( $notifications );
	} else {
		wp_send_json_error( 'No active notifications found.' );
	}

	wp_die();
}
add_action( 'wp_ajax_fetch_active_notifications', 'fetch_active_notifications' );
add_action( 'wp_ajax_nopriv_fetch_active_notifications', 'fetch_active_notifications' );