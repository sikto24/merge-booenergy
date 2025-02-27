<?php
function boo_email_template() {
	add_options_page(
		'Email Settings',   // Page Title
		'Boo Email',        // Menu Title
		'manage_options',   // Capability
		'boo_email_settings',   // Menu Slug
		'boo_email_settings_page' // Callback Function
	);
}
add_action( 'admin_menu', 'boo_email_template' );

function boo_register_email_settings() {
	register_setting( 'boo_email_settings_group', 'boo_email_subject' );
	register_setting( 'boo_email_settings_group', 'boo_email_body' );

	add_settings_section(
		'boo_email_main_section',
		'Email Configuration',
		'__return_false',
		'boo_email_settings'
	);

	add_settings_field(
		'boo_email_subject',
		'Email Subject',
		'boo_email_subject_callback',
		'boo_email_settings',
		'boo_email_main_section'
	);

	add_settings_field(
		'boo_email_body',
		'Email Body',
		'boo_email_body_callback',
		'boo_email_settings',
		'boo_email_main_section'
	);
}
add_action( 'admin_init', 'boo_register_email_settings' );

function boo_email_subject_callback() {
	$subject = get_option( 'boo_email_subject', '' );
	echo '<input type="text" name="boo_email_subject" value="' . esc_attr( $subject ) . '" class="regular-text">';
}

function boo_email_body_callback() {
	$content = get_option( 'boo_email_body', '' );
	wp_editor( $content, 'boo_email_body', array( 'textarea_name' => 'boo_email_body', 'media_buttons' => false, 'tinymce' => true, 'quicktags' => false ) );
}

function boo_email_settings_page() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Boo Email Settings', 'boo-energy' ); ?></h1>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'boo_email_settings_group' );
			do_settings_sections( 'boo_email_settings' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}



//send_email_notification for send mail for order conformation.

function send_email_notification() {

	if ( ! isset( $_POST['email'] ) ) {
		error_log( 'No email provided in POST data' );
		wp_send_json_error( array( 'message' => 'No email provided' ) );
		return;
	}


	$email = sanitize_email( $_POST['email'] );
	$subject = get_option( 'boo_email_subject', 'Thank You for Your Submission!' );
	$message_template = get_option( 'boo_email_body', "<p>Dear User {{email}},</p><p>Thank you for submitting your information.</p><p>Best regards,<br>Boo Energi</p>" );
	$message = str_replace( '{{email}}', $email, $message_template );
	$headers = [ 
		'Content-Type: text/html; charset=UTF-8',
		'From: Your Website <noreply@yourwebsite.com>'
	];

	$mail_sent = wp_mail( $email, $subject, $message, $headers );
	error_log( 'Mail sent result: ' . ( $mail_sent ? 'success' : 'failed' ) );

	if ( $mail_sent ) {
		wp_send_json_success( array( 'message' => 'Email sent successfully' ) );
	} else {
		wp_send_json_error( array( 'message' => 'Failed to send email' ) );
	}

	wp_die();
}

add_action( 'wp_ajax_send_email_notification', 'send_email_notification' );
add_action( 'wp_ajax_nopriv_send_email_notification', 'send_email_notification' );