<?php
/**
 * Notification bar functions
 */

//  Add Notification Bar Settings in WordPress Admin

function notification_bar_add_menu_page() {
	add_options_page(
		'Notification Bar Settings',   // Page Title
		'Notification Bar',            // Menu Title
		'edit_posts',                 // Capability
		'notification-bar-settings',   // Menu Slug
		'notification_bar_settings_page' // Callback Function
	);
}
add_action( 'admin_menu', 'notification_bar_add_menu_page' );

function notification_bar_settings_page() {
	?>
	<div class="wrap">
		<h1><?php echo esc_html__( 'Notification Bar Settings', 'boo-energy' ); ?></h1>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'notification_bar_settings_group' );
			do_settings_sections( 'notification-bar-settings' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}


function notification_bar_register_settings() {
	register_setting( 'notification_bar_settings_group', 'notification_bar_content' );
	register_setting( 'notification_bar_settings_group', 'notification_bar_active' );
	register_setting( 'notification_bar_settings_group', 'notification_bar_content_action' );
	register_setting( 'notification_bar_settings_group', 'notification_bar_content_action_url' );

	add_settings_section(
		'notification_bar_main_section',
		'Notification Bar Configuration',
		'',
		'notification-bar-settings'
	);

	add_settings_field(
		'notification_bar_active',
		'Enable Notification Bar',
		'notification_bar_toggle_field',
		'notification-bar-settings',
		'notification_bar_main_section'
	);

	add_settings_field(
		'notification_bar_content',
		'Notification Bar Content',
		'notification_bar_wysiwyg_field',
		'notification-bar-settings',
		'notification_bar_main_section'
	);

	add_settings_field(
		'notification_bar_content_action',
		'Notification Bar Button Text',
		'notification_bar_wysiwyg_field_action',
		'notification-bar-settings',
		'notification_bar_main_section'
	);

	add_settings_field(
		'notification_bar_content_action_url',
		'Notification Bar Button URL',
		'notification_bar_wysiwyg_field_action_url',
		'notification-bar-settings',
		'notification_bar_main_section'
	);


}
add_action( 'admin_init', 'notification_bar_register_settings' );

function notification_bar_toggle_field() {
	$active = get_option( 'notification_bar_active', '0' );
	echo '<input type="checkbox" name="notification_bar_active" value="1" ' . checked( 1, $active, false ) . '>';
}

function notification_bar_wysiwyg_field() {
	$content = get_option( 'notification_bar_content', '' );
	echo '<textarea name="notification_bar_content" rows="5" class="large-text" style="resize:none;">' . esc_textarea( $content ) . '</textarea>';
}

function notification_bar_wysiwyg_field_action() {
	$content = get_option( 'notification_bar_content_action', '' );
	echo '<textarea name="notification_bar_content_action" rows="5" class="large-text" style="resize:none;">' . esc_textarea( $content ) . '</textarea>';
}

function notification_bar_wysiwyg_field_action_url() {
	$content = get_option( 'notification_bar_content_action_url', '' );
	$pages = get_pages();
	echo '<select name="notification_bar_content_action_url" class="large-text">';
	echo '<option value="">' . esc_html__( 'Select a page', 'boo-energy' ) . '</option>';
	foreach ( $pages as $page ) {
		$selected = ( $content == get_permalink( $page->ID ) ) ? 'selected="selected"' : '';
		echo '<option value="' . esc_url( get_permalink( $page->ID ) ) . '" ' . $selected . '>' . esc_html( $page->post_title ) . '</option>';
	}
	echo '</select>';
}



// Display Notification Bar in the Frontend

function display_notification_bar() {
	$is_active = get_option( 'notification_bar_active', '0' );
	$content = get_option( 'notification_bar_content', '' );
	$content_action = get_option( 'notification_bar_content_action', '' );
	$content_action_url = get_option( 'notification_bar_content_action_url', '' );

	// Check if the user has closed the notification bar in this session
	if ( $is_active && ! empty( $content ) && ! empty( $content_action_url ) && ! empty( $content_action ) && ( ! isset( $_COOKIE['notificationBarClosed'] ) || $_COOKIE['notificationBarClosed'] !== 'true' ) ) {
		?>
		<div id="notification-bar">
			<div class="notification-content">
				<span class="warning-icon"><img
						src="<?php echo get_template_directory_uri() ?>/inc/notification-bar/warning-white.svg"></span><span><?php echo $content; ?></span><span>,
					<strong>
						<a href="<?php echo esc_attr( $content_action_url ) ?>">
							<?php echo esc_html__( $content_action, 'boo-energy' ); ?>
						</a>

					</strong>
				</span>
			</div>
			<button id="notification-close"><img
					src="<?php echo get_template_directory_uri() ?>/inc/notification-bar/close-white.svg"></button>
		</div>
		<?php
	}
}
add_action( 'boo_before_main_content', 'display_notification_bar' );



function enqueue_notification_bar_style_script() {
	wp_enqueue_script( 'notification-bar', get_template_directory_uri() . '/inc/notification-bar/js/notification-bar.js', array( 'jquery' ), null, true );
	wp_enqueue_style( 'notification-bar', get_template_directory_uri() . '/inc/notification-bar/css/notification-bar.css' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_notification_bar_style_script' );


