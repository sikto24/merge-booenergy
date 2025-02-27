<?php
/**
 * Summary of price_repeater_meta_box
 * @return void
 */
function price_repeater_meta_box() {
	add_meta_box(
		'price_repeater_box',
		'Price Repeater',
		'price_repeater_callback',
		'new_connection',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'price_repeater_meta_box' );

/**
 * Summary of price_repeater_callback
 * @param mixed $post
 * @return void
 */


function price_repeater_callback( $post ) {
	// Get and sanitize the stored data
	$new_connection_repeater = get_post_meta( $post->ID, 'new_connection_repeater', true );
	$new_connection_repeater = is_array( $new_connection_repeater ) ? $new_connection_repeater : array();

	// Security nonce
	wp_nonce_field( 'new_connection_repeater_nonce_action', 'new_connection_repeater_nonce' );
	?>

	<div id="custom-repeater">
		<div id="repeater-fields">
			<?php
			if ( ! empty( $new_connection_repeater ) ) {
				foreach ( $new_connection_repeater as $index => $field ) {
					render_repeater_row( $index, $field );
				}
			}
			?>
		</div>
		<button type="button" id="add-repeater-row" class="button button-primary">Add Row</button>
	</div>

	<style>
		.repeater-item {
			background: #f9f9f9;
			padding: 15px;
			margin-bottom: 15px;
			border: 1px solid #ddd;
			border-radius: 4px;
		}

		.repeater-item input {
			margin-bottom: 10px;
			width: 100%;
		}

		.nested-repeater {
			margin: 10px 0;
			padding: 10px;
			background: #fff;
			border: 1px solid #eee;
		}

		.nested-item {
			display: grid;
			grid-template-columns: 1fr 1fr auto;
			gap: 10px;
			padding: 10px;
			margin-bottom: 5px;
			background: #f0f0f0;
			border-radius: 3px;
		}

		.remove-row,
		.remove-nested {
			color: #dc3232;
			cursor: pointer;
		}

		.button {
			margin: 5px;
		}

		.single-repeater-item {
			display: flex;
			gap: 30px;
		}

		.single-repeater-item input[type="checkbox"] {
			width: 10px;
			margin: 0px;
		}
	</style>

	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const repeaterContainer = document.getElementById('repeater-fields');
			const addButton = document.getElementById('add-repeater-row');
			let rowIndex = <?php echo ! empty( $new_connection_repeater ) ? max( array_keys( $new_connection_repeater ) ) + 1 : 0; ?>;

			// Function to create a new main row
			function createRepeaterRow(index) {
				const row = document.createElement('div');
				row.classList.add('repeater-item');
				row.dataset.index = index;

				row.innerHTML = `
																																																							<div class="single-repeater-item-wrapper">
																																								<div class="single-repeater-item">
																																									<input type="text" name="new_connection_repeater[${index}][title]" value="" placeholder="Title" />
																																								</div>
																																								<div class="single-repeater-item">
																												<input type="checkbox" name="new_connection_repeater[${index}][1_checked_label]" />
																												<input type="text" name="new_connection_repeater[${index}][1Label]" value="" placeholder="Label" />
																												<input type="checkbox" name="new_connection_repeater[${index}][1_checked_value]" />
																												<input type="text" name="new_connection_repeater[${index}][1Value]" value="" placeholder="Value" />
																											</div>
																											<div class="single-repeater-item">
																												<input type="checkbox" name="new_connection_repeater[${index}][2_checked_label]" />
																												<input type="text" name="new_connection_repeater[${index}][2Label]" value="" placeholder="Label" />
																												<input type="checkbox" name="new_connection_repeater[${index}][2_checked_value]" />
																												<input type="text" name="new_connection_repeater[${index}][2Value]" value="" placeholder="Value" />
																											</div>
																											<div class="single-repeater-item">
																												<input type="checkbox" name="new_connection_repeater[${index}][3_checked_label]" />
																												<input type="text" name="new_connection_repeater[${index}][3Label]" value="" placeholder="Label" />
																												<input type="checkbox" name="new_connection_repeater[${index}][3_checked_value]" />
																												<input type="text" name="new_connection_repeater[${index}][3Value]" value="" placeholder="Value" />
																											</div>
																											<div class="single-repeater-item">
																												<input type="checkbox" name="new_connection_repeater[${index}][4_checked_label]" />
																												<input type="text" name="new_connection_repeater[${index}][4Label]" value="" placeholder="Label" />
																												<input type="checkbox" name="new_connection_repeater[${index}][4_checked_value]" />
																												<input type="text" name="new_connection_repeater[${index}][4Value]" value="" placeholder="Value" />
																											</div>
																											<div class="single-repeater-item">
																												<input type="checkbox" name="new_connection_repeater[${index}][5_checked_label]" />
																												<input type="text" name="new_connection_repeater[${index}][5Label]" value="" placeholder="Label" />
																												<input type="checkbox" name="new_connection_repeater[${index}][5_checked_value]" />
																												<input type="text" name="new_connection_repeater[${index}][5Value]" value="" placeholder="Value" />
																											</div>
																											<div class="single-repeater-item">
																												<input type="checkbox" name="new_connection_repeater[${index}][6_checked_label]" />
																												<input type="text" name="new_connection_repeater[${index}][6Label]" value="" placeholder="Label" />
																												<input type="checkbox" name="new_connection_repeater[${index}][6_checked_value]" />
																												<input type="text" name="new_connection_repeater[${index}][6Value]" value="" placeholder="Value" />
																											</div>
																											<div class="single-repeater-item">
																												<input type="checkbox" name="new_connection_repeater[${index}][7_checked_label]" />
																												<input type="text" name="new_connection_repeater[${index}][7Label]" value="" placeholder="Label" />
																												<input type="checkbox" name="new_connection_repeater[${index}][7_checked_value]" />
																												<input type="text" name="new_connection_repeater[${index}][7Value]" value="" placeholder="Value" />
																											</div>
																											<div class="single-repeater-item">
																												<input type="checkbox" name="new_connection_repeater[${index}][8_checked_label]" />
																												<input type="text" name="new_connection_repeater[${index}][8Label]" value="" placeholder="Label" />
																												<input type="checkbox" name="new_connection_repeater[${index}][8_checked_value]" />
																												<input type="text" name="new_connection_repeater[${index}][8Value]" value="" placeholder="Value" />
																											</div>
																											<div class="single-repeater-item">
																												<input type="checkbox" name="new_connection_repeater[${index}][9_checked_label]" />
																												<input type="text" name="new_connection_repeater[${index}][9Label]" value="" placeholder="Label" />
																												<input type="checkbox" name="new_connection_repeater[${index}][9_checked_value]" />
																												<input type="text" name="new_connection_repeater[${index}][9Value]" value="" placeholder="Value" />
																											</div>
																											<div class="single-repeater-item">
													
																												<input type="text" name="new_connection_repeater[${index}][totalLabel]" value="" placeholder="Total Label" />
																												<input type="text" name="new_connection_repeater[${index}][totalValue]" value="" placeholder="Total Value" />
																											</div>

																																							</div>


						
	
																																																																																<div class="nested-repeater">
																																																																																	<div class="nested-fields"></div>
																																																																																	<button type="button" class="button add-nested">Add Nested Row</button>
																																																																																</div>
	
																																																																																<button type="button" class="button remove-row">Remove Row</button>
																																																																															`;

				return row;
			}

			// Function to create a new nested row
			function createNestedRow(parentIndex, nestedIndex) {
				const nestedRow = document.createElement('div');
				nestedRow.classList.add('nested-item');

				nestedRow.innerHTML = `
																																																																																<input type="text" 
																																																																																	name="new_connection_repeater[${parentIndex}][nested][${nestedIndex}][fuse]" 
																																																																																	placeholder="Säkring" />
																																																																																<input type="text" 
																																																																																	name="new_connection_repeater[${parentIndex}][nested][${nestedIndex}][vat]" 
																																																																																	placeholder="exkl. moms" />
																																																																																<button type="button" class="button remove-nested">Remove</button>
																																																																															`;

				return nestedRow;
			}

			// Add main row
			addButton.addEventListener('click', function () {
				const newRow = createRepeaterRow(rowIndex++);
				repeaterContainer.appendChild(newRow);
			});

			// Event delegation for all dynamic buttons
			repeaterContainer.addEventListener('click', function (event) {
				const target = event.target;

				// Remove main row
				if (target.classList.contains('remove-row')) {
					if (confirm('Are you sure you want to remove this row?')) {
						target.closest('.repeater-item').remove();
					}
				}

				// Add nested row
				if (target.classList.contains('add-nested')) {
					const parentItem = target.closest('.repeater-item');
					const nestedContainer = parentItem.querySelector('.nested-fields');
					const parentIndex = parentItem.dataset.index;
					const nestedIndex = nestedContainer.children.length;

					const nestedRow = createNestedRow(parentIndex, nestedIndex);
					nestedContainer.appendChild(nestedRow);
				}

				// Remove nested row
				if (target.classList.contains('remove-nested')) {
					if (confirm('Are you sure you want to remove this nested row?')) {
						target.closest('.nested-item').remove();
					}
				}
			});
		});
	</script>

	<?php
}

// Helper function to render a repeater row
function render_repeater_row( $index, $field ) {
	?>
	<div class="repeater-item" data-index="<?php echo esc_attr( $index ); ?>">
		<div class="single-repeater-item">
			<input type="text" name="new_connection_repeater[<?php echo esc_attr( $index ); ?>][title]"
				value="<?php echo esc_attr( $field['title'] ?? '' ); ?>" placeholder="Title" />
		</div>
		<?php for ( $i = 1; $i <= 8; $i++ ) : ?>
			<div class="single-repeater-item">
				<input type="checkbox"
					name="new_connection_repeater[<?php echo esc_attr( $index ); ?>][<?php echo esc_attr( $i ); ?>_checked_label]"
					<?php checked( ! empty( $field[ $i . '_checked_label' ] ) ); ?> />
				<input type="text"
					name="new_connection_repeater[<?php echo esc_attr( $index ); ?>][<?php echo esc_attr( $i ); ?>Label]"
					value="<?php echo esc_attr( $field[ $i . 'Label' ] ?? '' ); ?>" placeholder="Label" />
				<input type="checkbox"
					name="new_connection_repeater[<?php echo esc_attr( $index ); ?>][<?php echo esc_attr( $i ); ?>_checked_value]"
					<?php checked( ! empty( $field[ $i . '_checked_value' ] ) ); ?> />
				<input type="text"
					name="new_connection_repeater[<?php echo esc_attr( $index ); ?>][<?php echo esc_attr( $i ); ?>Value]"
					value="<?php echo esc_attr( $field[ $i . 'Value' ] ?? '' ); ?>" placeholder="Value" />
			</div>
		<?php endfor; ?>
		<div class="single-repeater-item">
			<input type="text" name="new_connection_repeater[<?php echo esc_attr( $index ); ?>][totalLabel]"
				value="<?php echo esc_attr( $field['totalLabel'] ?? '' ); ?>" placeholder="Total Label" />
			<input type="text" name="new_connection_repeater[<?php echo esc_attr( $index ); ?>][totalValue]"
				value="<?php echo esc_attr( $field['totalValue'] ?? '' ); ?>" placeholder="Total Value" />
		</div>

		<div class="nested-repeater">
			<div class="nested-fields">
				<?php
				if ( ! empty( $field['nested'] ) ) {
					foreach ( $field['nested'] as $nested_index => $nested_field ) {
						?>
						<div class="nested-item">
							<input type="text"
								name="new_connection_repeater[<?php echo esc_attr( $index ); ?>][nested][<?php echo esc_attr( $nested_index ); ?>][fuse]"
								value="<?php echo esc_attr( $nested_field['fuse'] ?? '' ); ?>" placeholder="Säkring" />
							<input type="text"
								name="new_connection_repeater[<?php echo esc_attr( $index ); ?>][nested][<?php echo esc_attr( $nested_index ); ?>][vat]"
								value="<?php echo esc_attr( $nested_field['vat'] ?? '' ); ?>" placeholder="exkl. moms" />
							<button type="button" class="button remove-nested">Remove</button>
						</div>
						<?php
					}
				}
				?>
			</div>
			<button type="button" class="button add-nested">Add Nested Row</button>
		</div>
		<button type="button" class="button remove-row">Remove Row</button>
	</div>
	<?php
}


// Save the repeater data
// function save_price_repeater( $post_id ) {
// 	// Verify nonce
// 	if ( ! isset( $_POST['new_connection_repeater_nonce'] ) ||
// 		! wp_verify_nonce( $_POST['new_connection_repeater_nonce'], 'new_connection_repeater_nonce_action' ) ) {
// 		return;
// 	}

// 	// Check autosave
// 	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
// 		return;
// 	}

// 	// Check permissions
// 	if ( ! current_user_can( 'edit_post', $post_id ) ) {
// 		return;
// 	}

// 	// Save the data
// 	if ( isset( $_POST['new_connection_repeater'] ) && is_array( $_POST['new_connection_repeater'] ) ) {
// 		$data = wp_unslash( $_POST['new_connection_repeater'] );

// 		foreach ( $data as &$field ) {
// 			// Loop through checkboxes (1 to 9) and set missing checkboxes to 0
// 			for ( $i = 1; $i <= 9; $i++ ) {
// 				$field[ $i . '_checked_label' ] = isset( $field[ $i . '_checked_label' ] ) ? 1 : 0;
// 				$field[ $i . '_checked_value' ] = isset( $field[ $i . '_checked_value' ] ) ? 1 : 0;
// 			}

// 			// Sanitize all text fields
// 			array_walk_recursive( $field, function (&$value) {
// 				$value = sanitize_text_field( $value );
// 			} );

// 			// Ensure nested repeater fields are stored properly
// 			if ( isset( $field['nested'] ) && is_array( $field['nested'] ) ) {
// 				$field['nested'] = array_values( array_filter( $field['nested'], function ($nested_field) {
// 					return isset( $nested_field['fuse'], $nested_field['vat'] ) &&
// 						! empty( $nested_field['fuse'] ) &&
// 						! empty( $nested_field['vat'] );
// 				} ) );
// 			}
// 		}

// 		update_post_meta( $post_id, 'new_connection_repeater', $data );
// 	} else {
// 		delete_post_meta( $post_id, 'new_connection_repeater' );
// 	}
// }


function save_price_repeater_meta( $post_id ) {
	// Verify nonce
	if ( ! isset( $_POST['new_connection_repeater_nonce'] ) ||
		! wp_verify_nonce( $_POST['new_connection_repeater_nonce'], 'new_connection_repeater_nonce_action' ) ) {
		return;
	}

	// Check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check permissions
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Initialize empty array to store sanitized data
	$sanitized_data = array();

	if ( isset( $_POST['new_connection_repeater'] ) && is_array( $_POST['new_connection_repeater'] ) ) {
		// Get the raw data and unslash it
		$data = wp_unslash( $_POST['new_connection_repeater'] );

		// Preserve array keys during sanitization
		foreach ( $data as $index => $field ) {
			$sanitized_data[ $index ] = array();

			// Sanitize title
			$sanitized_data[ $index ]['title'] = isset( $field['title'] ) ?
				sanitize_text_field( $field['title'] ) : '';


			$sanitized_data[ $index ]['totalLabel'] = isset( $field['totalLabel'] ) ?
				sanitize_text_field( $field['totalLabel'] ) : '';
			$sanitized_data[ $index ]['totalValue'] = isset( $field['totalValue'] ) ?
				sanitize_text_field( $field['totalValue'] ) : '';



			// Handle the 9 repeater fields
			for ( $i = 1; $i <= 9; $i++ ) {
				// Checkboxes - explicitly set to 0 or 1
				$sanitized_data[ $index ][ $i . '_checked_label' ] =
					isset( $field[ $i . '_checked_label' ] ) ? 1 : 0;
				$sanitized_data[ $index ][ $i . '_checked_value' ] =
					isset( $field[ $i . '_checked_value' ] ) ? 1 : 0;

				// Text fields
				$sanitized_data[ $index ][ $i . 'Label' ] = isset( $field[ $i . 'Label' ] ) ?
					sanitize_text_field( $field[ $i . 'Label' ] ) : '';
				$sanitized_data[ $index ][ $i . 'Value' ] = isset( $field[ $i . 'Value' ] ) ?
					sanitize_text_field( $field[ $i . 'Value' ] ) : '';



			}

			// Handle nested fields
			if ( isset( $field['nested'] ) && is_array( $field['nested'] ) ) {
				$sanitized_data[ $index ]['nested'] = array();
				foreach ( $field['nested'] as $nested_index => $nested_field ) {
					if ( ! empty( $nested_field['fuse'] ) || ! empty( $nested_field['vat'] ) ) {
						$sanitized_data[ $index ]['nested'][ $nested_index ] = array(
							'fuse' => sanitize_text_field( $nested_field['fuse'] ),
							'vat' => sanitize_text_field( $nested_field['vat'] )
						);
					}
				}
				// Reindex nested array to ensure sequential keys
				$sanitized_data[ $index ]['nested'] = array_values( $sanitized_data[ $index ]['nested'] );
			}
		}

		// Save the sanitized data
		update_post_meta( $post_id, 'new_connection_repeater', $sanitized_data );
	} else {
		delete_post_meta( $post_id, 'new_connection_repeater' );
	}
}

// Remove the duplicate save function and keep only this one
remove_action( 'save_post', 'save_price_repeater' );
add_action( 'save_post', 'save_price_repeater_meta' );

// add_action( 'save_post', 'save_price_repeater_meta' );


// Add this at the end of the save function to debug
add_action( 'admin_notices', function () {
	if ( isset( $_POST['new_connection_repeater'] ) ) {
		echo '<div class="notice notice-info is-dismissible">';
		echo '<p>Data received: ' . print_r( $_POST['new_connection_repeater'], true ) . '</p>';
		echo '<p>Data saved: ' . print_r( get_post_meta( get_the_ID(), 'new_connection_repeater', true ), true ) . '</p>';
		echo '</div>';
	}
} );


function new_connection_repeater_shortcode( $atts ) {
	$args = [ 
		'post_type' => 'new_connection',
		'meta_key' => 'new_connection_repeater',
		'posts_per_page' => -1,
		'orderby' => 'DESC',
	];
	$repeater_data = new WP_Query( $args );


	ob_start();

	?>
	<div class="new-connection-repeater d-flex flex-column">

		<?php
		if ( $repeater_data->have_posts() ) :

			?>
			<?php while ( $repeater_data->have_posts() ) :
				$repeater_data->the_post();
				$fields = get_post_meta( get_the_ID(), 'new_connection_repeater', true );
				$price = get_field( 'price' );
				$recommend = get_field( 'recommend' ) ? 'true' : 'false';
				$recommend_text = get_field( 'text' );


				?>


				<?php $accordion_id = 'accordion' . get_the_ID(); ?>
				<div class="accordion" id="<?php echo esc_attr( $accordion_id ); ?>">
					<div class="accordion-item">
						<h2 class="accordion-header" id="heading-<?php echo esc_attr( get_the_ID() ); ?>">
							<button class="accordion-button" type="button" data-bs-toggle="collapse"
								data-bs-target="#collapse-<?php echo esc_attr( get_the_ID() ); ?>" aria-expanded="false"
								aria-controls="collapse-<?php echo esc_attr( get_the_ID() ); ?>">
								<h6>
									<span class="our-price-header-title">
										<?php the_title(); ?>
										<?php if ( 'true' === $recommend ) : ?>
											<span class="recommend-text">
												<?php echo esc_html__( $recommend_text, 'boo-energy' ); ?>
											</span>
										<?php endif; ?>

										<span class="mobile-price price d-none">
											<?php echo esc_html__( $price ); ?>
										</span>
									</span>
									<span class="price">
										<span class="desktop-price">
											<?php echo esc_html__( $price ); ?>
										</span>
										<img class="plus-icon-main" src="<?php echo BOO_THEME_IMG_DIR . 'plus.svg' ?>" alt="Plus">
										<img class="minus-icon-main" src="<?php echo BOO_THEME_IMG_DIR . 'minus.svg' ?>"
											alt="Minus">
									</span>
								</h6>
							</button>
						</h2>
						<div id="collapse-<?php echo esc_attr( get_the_ID() ); ?>" class="accordion-collapse collapse"
							aria-labelledby="heading-<?php echo esc_attr( get_the_ID() ); ?>"
							data-bs-parent="#<?php echo esc_attr( $accordion_id ); ?>">
							<div class="accordion-body">
								<?php if ( ! empty( $fields ) ) : ?>
									<?php foreach ( $fields as $field ) :
										?>
										<div class="new-connection-repeater-item">
											<?php if ( ! empty( $field['title'] ) ) : ?>
												<p class="new-connection-repeater-title typography-bread-large">
													<?php echo esc_html( $field['title'] ); ?>
												</p>
											<?php endif; ?>

											<?php for ( $i = 1; $i <= 8; $i++ ) : ?>
												<?php if ( ! empty( $field[ $i . 'Label' ] ) || ! empty( $field[ $i . 'Value' ] ) ) : ?>
													<p class="new-connection-repeater-label">
														<?php if ( ! empty( $field[ $i . '_checked_label' ] ) ) : ?>
															<span class="bold">
																<?php echo esc_html( $field[ $i . 'Label' ] ); ?>
															</span>
														<?php else : ?>
															<span>
																<?php echo esc_html( $field[ $i . 'Label' ] ); ?>
															</span>
														<?php endif; ?>

														<?php if ( ! empty( $field[ $i . '_checked_value' ] ) ) : ?>
															<span class="bold">
																<?php echo esc_html( $field[ $i . 'Value' ] ); ?>
															</span>
														<?php else : ?>
															<?php echo esc_html( $field[ $i . 'Value' ] ); ?>
														<?php endif; ?>
													</p>
													<?php
												endif;
											endfor; ?>



											<?php if ( ! empty( $field['totalLabel'] ) || ! empty( $field['totalValue'] ) ) : ?>
												<p class="total-new-connection">
													<span class="bold">
														<?php echo esc_html( $field['totalLabel'] ); ?>
													</span>
													<span class="bold">
														<?php
														echo esc_html( $field['totalValue'] );
														?>

													</span>

												</p>
											<?php endif; ?>

											<?php if ( ! empty( $field['nested'] ) ) : ?>
												<div class="bottom-price-breakdown-area">
													<div class="bottom-price-breakdown-start">
														<div class="bottom-price-breakdown-area-left">
															<p class="bold"><?php echo esc_html__( 'Säkring', 'boo-energy' ); ?></p>
															<p><?php echo esc_html__( 'exkl. moms', 'boo-energy' ); ?></p>
														</div>
														<div class="bottom-price-breakdown-area-right">
															<div class="bottom-price-breakdown-area-right-top">
																<?php foreach ( $field['nested'] as $nested_item ) : ?>

																	<p class="bold"><?php echo $nested_item['fuse']; ?></p>
																<?php endforeach; ?>
															</div>
															<div class="bottom-price-breakdown-area-right-bottom">
																<?php foreach ( $field['nested'] as $nested_item ) : ?>

																	<p><?php echo $nested_item['vat']; ?></p>
																<?php endforeach; ?>
															</div>
														</div>
													</div>
													<div class="bottom-price-breakdown-area-end">
														<p>kr/mån</p>
													</div>
												</div>
											<?php endif; ?>

											<div class="mobile-price-breakdown">
												<div class="accordion" id="accordionExample">
													<?php foreach ( $field['nested'] as $index => $nested_item ) : ?>
														<div class="accordion-item">
															<h2 class="accordion-header" id="heading-<?php echo $index; ?>">
																<button class="accordion-button" type="button" data-bs-toggle="collapse"
																	data-bs-target="#collapse-<?php echo $index; ?>" aria-expanded="false"
																	aria-controls="collapse-<?php echo $index; ?>">
																	<p class="bold"><?php echo $nested_item['fuse']; ?></p>
																	<img class="plus-icon-main"
																		src="<?php echo BOO_THEME_IMG_DIR . 'plus.svg'; ?>" alt="Plus">
																	<img class="minus-icon-main"
																		src="<?php echo BOO_THEME_IMG_DIR . 'minus.svg'; ?>" alt="Minus">
																</button>
															</h2>
															<div id="collapse-<?php echo $index; ?>" class="accordion-collapse collapse"
																aria-labelledby="heading-<?php echo $index; ?>"
																data-bs-parent="#accordionExample">
																<div class="accordion-body">
																	<p class="bold"><?php echo $nested_item['vat']; ?></p>
																</div>
															</div>
														</div>
													<?php endforeach; ?>
												</div>
											</div>


										</div>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>

			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		<?php else : ?>
			<p>No posts found</p>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'new_connection_repeater', 'new_connection_repeater_shortcode' );