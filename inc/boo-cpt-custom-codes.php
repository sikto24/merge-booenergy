<?php


function create_campaign_codes_table() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'campaign_codes';
	if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) !== $table_name ) {
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE {$table_name} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            code VARCHAR(255) NOT NULL,
            fixed_1_price_group_id VARCHAR(255) DEFAULT NULL,
            fixed_2_price_group_id VARCHAR(255) DEFAULT NULL,
            fixed_3_price_group_id VARCHAR(255) DEFAULT NULL,
            variable_price_group_id VARCHAR(255) DEFAULT NULL,
            boo_portfolio_price_group_id VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP NULL DEFAULT NULL,
            updated_at TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (id)
        ) {$charset_collate};";

		// Include the WordPress dbDelta function
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
}
add_action( 'after_setup_theme', 'create_campaign_codes_table' );

function add_campaign_columns( $columns ) {
	$columns['campaign_code'] = esc_html__( 'Kod', 'boo-energy' );
	$columns['fixed_1_price_group_id'] = esc_html__( 'Bundet - 1 ar', 'boo-energy' );
	$columns['fixed_2_price_group_id'] = esc_html__( 'Bundet - 2 ar', 'boo-energy' );
	$columns['fixed_3_price_group_id'] = esc_html__( 'Bundet - 3 ár', 'boo-energy' );
	$columns['variable_price_group_id'] = esc_html__( 'Rörligt', 'boo-energy' );
	$columns['boo_portfolio_price_group_id'] = esc_html__( 'Boo Portfolio', 'boo-energy' );
	$columns['actions'] = esc_html__( 'Actions', 'boo-energy' );
	return $columns;
}
add_filter( 'manage_campaigns_posts_columns', 'add_campaign_columns' );

function custom_campaign_column( $column, $post_id ) {
	switch ( $column ) {
		case 'campaign_code':
			echo esc_html( get_post_meta( $post_id, 'campaign_code', true ) );
			break;
		case 'fixed_1_price_group_id':
			echo esc_html( get_post_meta( $post_id, 'fixed_1_price_group_id', true ) );
			break;
		case 'fixed_2_price_group_id':
			echo esc_html( get_post_meta( $post_id, 'fixed_2_price_group_id', true ) );
			break;
		case 'fixed_3_price_group_id':
			echo esc_html( get_post_meta( $post_id, 'fixed_3_price_group_id', true ) );
			break;
		case 'variable_price_group_id':
			echo esc_html( get_post_meta( $post_id, 'variable_price_group_id', true ) );
			break;
		case 'boo_portfolio_price_group_id':
			echo esc_html( get_post_meta( $post_id, 'boo_portfolio_price_group_id', true ) );
			break;
		case 'actions':
			$edit_url = get_edit_post_link( $post_id );
			$delete_url = get_delete_post_link( $post_id );
			echo '<a href="' . esc_url( $edit_url ) . '">' . __( 'Edit', 'boo-energy' ) . '</a> | ';
			echo '<a href="' . esc_url( $delete_url ) . '" onclick="return confirm(\'' . __( 'Are you sure you want to delete this item?', 'boo-energy' ) . '\');">' . __( 'Delete', 'boo-energy' ) . '</a>';
			break;
	}
}
add_action( 'manage_campaigns_posts_custom_column', 'custom_campaign_column', 10, 2 );

function add_campaign_meta_boxes() {
	add_meta_box(
		'campaign_meta_box', // $id
		__( 'Campaign Details', 'boo-energy' ), // $title
		'render_campaign_meta_box', // $callback
		'campaigns', // $screen
		'normal', // $context
		'high' // $priority
	);
}
add_action( 'add_meta_boxes', 'add_campaign_meta_boxes' );

function render_campaign_meta_box( $post ) {
	$meta = get_post_meta( $post->ID );
	?>
	<p>
		<label for="campaign_code"><?php _e( 'Code', 'boo-energy' ); ?></label>
		<input type="text" name="campaign_code" id="campaign_code"
			value="<?php echo esc_attr( $meta['campaign_code'][0] ); ?>" />
	</p>
	<p>
		<label for="fixed_1_price_group_id"><?php _e( 'Fixed 1 Price Group ID', 'boo-energy' ); ?></label>
		<input type="text" name="fixed_1_price_group_id" id="fixed_1_price_group_id"
			value="<?php echo esc_attr( $meta['fixed_1_price_group_id'][0] ); ?>" />
	</p>
	<p>
		<label for="fixed_2_price_group_id"><?php _e( 'Fixed 2 Price Group ID', 'boo-energy' ); ?></label>
		<input type="text" name="fixed_2_price_group_id" id="fixed_2_price_group_id"
			value="<?php echo esc_attr( $meta['fixed_2_price_group_id'][0] ); ?>" />
	</p>
	<p>
		<label for="fixed_3_price_group_id"><?php _e( 'Fixed 3 Price Group ID', 'boo-energy' ); ?></label>
		<input type="text" name="fixed_3_price_group_id" id="fixed_3_price_group_id"
			value="<?php echo esc_attr( $meta['fixed_3_price_group_id'][0] ); ?>" />
	</p>
	<p>
		<label for="variable_price_group_id"><?php _e( 'Variable Price Group ID', 'boo-energy' ); ?></label>
		<input type="text" name="variable_price_group_id" id="variable_price_group_id"
			value="<?php echo esc_attr( text: $meta['variable_price_group_id'][0] ); ?>" />
	</p>
	<p>
		<label for="boo_portfolio_price_group_id"><?php _e( 'Boo Portfolio Price Group ID', 'boo-energy' ); ?></label>
		<input type="text" name="boo_portfolio_price_group_id" id="boo_portfolio_price_group_id"
			value="<?php echo esc_attr( $meta['boo_portfolio_price_group_id'][0] ); ?>" />
	</p>
	<?php
}

function save_campaign_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! isset( $_POST['campaign_code'] ) ) {
		return;
	}

	update_post_meta( $post_id, 'campaign_code', sanitize_text_field( $_POST['campaign_code'] ) );
	update_post_meta( $post_id, 'fixed_1_price_group_id', sanitize_text_field( $_POST['fixed_1_price_group_id'] ) );
	update_post_meta( $post_id, 'fixed_2_price_group_id', sanitize_text_field( $_POST['fixed_2_price_group_id'] ) );
	update_post_meta( $post_id, 'fixed_3_price_group_id', sanitize_text_field( $_POST['fixed_3_price_group_id'] ) );
	update_post_meta( $post_id, 'variable_price_group_id', sanitize_text_field( $_POST['variable_price_group_id'] ) );
	update_post_meta( $post_id, 'boo_portfolio_price_group_id', sanitize_text_field( $_POST['boo_portfolio_price_group_id'] ) );
}
add_action( 'save_post', 'save_campaign_meta' );

function save_campaign_to_db( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! isset( $_POST['campaign_code'] ) ) {
		return;
	}

	global $wpdb;
	$table_name = $wpdb->prefix . 'campaign_codes';

	$data = array(
		'code' => sanitize_text_field( $_POST['campaign_code'] ),
		'fixed_1_price_group_id' => sanitize_text_field( $_POST['fixed_1_price_group_id'] ),
		'fixed_2_price_group_id' => sanitize_text_field( $_POST['fixed_2_price_group_id'] ),
		'fixed_3_price_group_id' => sanitize_text_field( $_POST['fixed_3_price_group_id'] ),
		'variable_price_group_id' => sanitize_text_field( $_POST['variable_price_group_id'] ),
		'boo_portfolio_price_group_id' => sanitize_text_field( $_POST['boo_portfolio_price_group_id'] ),
		'updated_at' => current_time( 'mysql' ),
	);

	$existing_entry = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $post_id ) );

	if ( $existing_entry ) {
		$wpdb->update( $table_name, $data, array( 'id' => $post_id ) );
	} else {
		$data['created_at'] = current_time( 'mysql' );
		$data['id'] = $post_id;
		$wpdb->insert( $table_name, $data );
	}
}
add_action( 'save_post', 'save_campaign_to_db' );

function delete_campaign_from_db( $post_id ) {
	if ( get_post_type( $post_id ) !== 'campaigns' ) {
		return;
	}

	global $wpdb;
	$table_name = $wpdb->prefix . 'campaign_codes';

	$wpdb->delete( $table_name, array( 'id' => $post_id ) );
}

add_action( 'before_delete_post', 'delete_campaign_from_db' );