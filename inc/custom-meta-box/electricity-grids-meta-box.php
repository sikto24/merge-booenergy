<?php
/**
 * Summary of electricity_repeater_meta_box
 * @return void
 */
function electricity_repeater_meta_box() {
	add_meta_box(
		'electricity_repeater_box',
		'Electricity Grids Repeater',
		'electricity_repeater_callback',
		'electricity_grid',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'electricity_repeater_meta_box' );

function electricity_repeater_callback( $post ) {
	// Get and sanitize the stored data
	$electricity_grids_repeater = get_post_meta( $post->ID, 'electricity_grids_repeater', true );
	$electricity_grids_repeater = is_array( $electricity_grids_repeater ) ? $electricity_grids_repeater : array();

	// Security nonce
	wp_nonce_field( 'electricity_grids_repeater_nonce_action', 'electricity_grids_repeater_nonce' );
	?>

	<div id="custom-repeater">
		<div id="repeater-fields">
			<?php
			if ( ! empty( $electricity_grids_repeater ) ) {
				foreach ( $electricity_grids_repeater as $index => $field ) {
					render_repeater_rows( $index, $field );
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
			display: flex;
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
	</style>

	<script>
		jQuery(document).ready(function ($) {
			const repeaterContainer = $('#repeater-fields');
			const addButton = $('#add-repeater-row');
			let rowIndex = <?php echo ! empty( $electricity_grids_repeater ) ? max( array_keys( $electricity_grids_repeater ) ) + 1 : 0; ?>;

			// Function to create a new main row
			function createRepeaterRow(index) {
				const fields = [
					{ name: 'title', type: 'text', placeholder: 'Title' },
					{ name: 'sub_title', type: 'text', placeholder: 'Sub Title' },
					{ name: 'fuse_size', type: 'text', placeholder: 'Säkringsstorlek' },
					{ name: 'max', type: 'text', placeholder: 'Max 35A' },
					{ name: 'price_vat', type: 'text', placeholder: 'Pris inkl. moms' },
					{ name: 'price_show_text', type: 'text', placeholder: 'Priserna visar inkl. moms.' },
					{ name: 'under_price_show_text', type: 'text', placeholder: 'Text Under Priserna visar inkl. moms.' },
				];

				const fieldsHtml = fields.map(field => `
																																																																				<input type="${field.type}" 
																																																																					name="electricity_grids_repeater[${index}][${field.name}]"
																																																																					placeholder="${field.placeholder}"
																																																																					${field.step ? `step="${field.step}"` : ''}
																																																																				/>
																																																																			`).join('');

				const row = $(`
																																																																				<div class="repeater-item" data-index="${index}">
																																																																					${fieldsHtml}
																																																																					<div class="nested-repeater">
																																																																						<div class="nested-fields"></div>
																																																																						<button type="button" class="button add-nested">Add Nested Row</button>
																																																																					</div>
																																																																					<button type="button" class="button remove-row">Remove Row</button>
																																																																				</div>
																																																																			`);

				return row;
			}

			// Function to create a new nested row
			function createNestedRow(parentIndex, nestedIndex) {
				return $(`
																																																																				<div class="nested-item">
																																																																					<input type="text" 
																																																																						name="electricity_grids_repeater[${parentIndex}][nested][${nestedIndex}][fuse]" 
																																																																						placeholder="Säkring" />
																																																																					<input type="text" 
																																																																						name="electricity_grids_repeater[${parentIndex}][nested][${nestedIndex}][electricity_grid_fee]" 
																																																																						placeholder="Fast elnätsavgift" />
																																																																					<input type="text" 
																																																																						name="electricity_grids_repeater[${parentIndex}][nested][${nestedIndex}][electricity_grid_charge]" 
																																																																						placeholder="Rörlig elnätsavgift" />
																																																																					<button type="button" class="button remove-nested">Remove</button>
																																																																				</div>
																																																																			`);
			}

			// Add main row
			addButton.on('click', function () {
				const newRow = createRepeaterRow(rowIndex++);
				repeaterContainer.append(newRow);
			});

			// Event delegation for dynamic buttons
			repeaterContainer.on('click', '.remove-row', function () {
				if (confirm('Are you sure you want to remove this row?')) {
					$(this).closest('.repeater-item').remove();
				}
			});

			repeaterContainer.on('click', '.add-nested', function () {
				const parentItem = $(this).closest('.repeater-item');
				const nestedContainer = parentItem.find('.nested-fields');
				const parentIndex = parentItem.data('index');
				const nestedIndex = nestedContainer.children().length;

				const nestedRow = createNestedRow(parentIndex, nestedIndex);
				nestedContainer.append(nestedRow);
			});

			repeaterContainer.on('click', '.remove-nested', function () {
				if (confirm('Are you sure you want to remove this nested row?')) {
					$(this).closest('.nested-item').remove();
				}
			});
		});
	</script>
	<?php
}



function save_electricity_repeater_meta( $post_id ) {
	// Verify nonce
	if ( ! isset( $_POST['electricity_grids_repeater_nonce'] ) ||
		! wp_verify_nonce( $_POST['electricity_grids_repeater_nonce'], 'electricity_grids_repeater_nonce_action' ) ) {
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

	// If no data, delete the meta and return
	if ( ! isset( $_POST['electricity_grids_repeater'] ) || ! is_array( $_POST['electricity_grids_repeater'] ) ) {
		delete_post_meta( $post_id, 'electricity_grids_repeater' );
		return;
	}

	// Get the posted data and unslash it
	$raw_data = wp_unslash( $_POST['electricity_grids_repeater'] );
	$clean_data = array();

	// Loop through each main row
	foreach ( $raw_data as $index => $row ) {
		$clean_row = array();

		// Sanitize main fields
		$clean_row['title'] = isset( $row['title'] ) ? sanitize_text_field( $row['title'] ) : '';
		$clean_row['sub_title'] = isset( $row['sub_title'] ) ? sanitize_text_field( $row['sub_title'] ) : '';
		$clean_row['fuse_size'] = isset( $row['fuse_size'] ) ? sanitize_text_field( $row['fuse_size'] ) : '';
		$clean_row['max'] = isset( $row['max'] ) ? sanitize_text_field( $row['max'] ) : '';
		$clean_row['price_vat'] = isset( $row['price_vat'] ) ? sanitize_text_field( $row['price_vat'] ) : '';
		$clean_row['price_show_text'] = isset( $row['price_show_text'] ) ? sanitize_text_field( $row['price_show_text'] ) : '';
		$clean_row['under_price_show_text'] = isset( $row['under_price_show_text'] ) ? sanitize_text_field( $row['under_price_show_text'] ) : '';

		// Handle nested repeater
		if ( isset( $row['nested'] ) && is_array( $row['nested'] ) ) {
			$clean_row['nested'] = array();
			foreach ( $row['nested'] as $nested_index => $nested_field ) {
				if ( ! empty( $nested_field['fuse'] ) ||
					! empty( $nested_field['electricity_grid_fee'] ) ||
					! empty( $nested_field['electricity_grid_charge'] ) ) {

					$clean_row['nested'][] = array(
						'fuse' => sanitize_text_field( $nested_field['fuse'] ),
						'electricity_grid_fee' => sanitize_text_field( $nested_field['electricity_grid_fee'] ),
						'electricity_grid_charge' => sanitize_text_field( $nested_field['electricity_grid_charge'] )
					);
				}
			}
		}

		// Only add the row if it has any non-empty main fields
		if ( ! empty( $clean_row['title'] ) ||
			! empty( $clean_row['sub_title'] ) ||
			! empty( $clean_row['fuse_size'] ) ||
			! empty( $clean_row['max'] ) ||
			! empty( $clean_row['price_vat'] ) ||
			! empty( $clean_row['price_show_text'] ) ||
			! empty( $clean_row['under_price_show_text'] ) ||
			! empty( $clean_row['nested'] ) ) {

			$clean_data[] = $clean_row;
		}
	}

	// Update or delete based on clean data
	if ( ! empty( $clean_data ) ) {
		update_post_meta( $post_id, 'electricity_grids_repeater', array_values( $clean_data ) );
	} else {
		delete_post_meta( $post_id, 'electricity_grids_repeater' );
	}
}
add_action( 'save_post', 'save_electricity_repeater_meta' );

function render_repeater_rows( $index, $field ) {
	?>
	<div class="repeater-item" data-index="<?php echo esc_attr( $index ); ?>">
		<input type="text" name="electricity_grids_repeater[<?php echo esc_attr( $index ); ?>][title]"
			value="<?php echo esc_attr( $field['title'] ?? '' ); ?>" placeholder="Title" />

		<input type="text" name="electricity_grids_repeater[<?php echo esc_attr( $index ); ?>][sub_title]"
			value="<?php echo esc_attr( $field['sub_title'] ?? '' ); ?>" placeholder="Sub Title" />

		<input type="text" name="electricity_grids_repeater[<?php echo esc_attr( $index ); ?>][fuse_size]"
			value="<?php echo esc_attr( $field['fuse_size'] ?? '' ); ?>" placeholder="Säkringsstorlek" />

		<input type="text" name="electricity_grids_repeater[<?php echo esc_attr( $index ); ?>][max]"
			value="<?php echo esc_attr( $field['max'] ?? '' ); ?>" placeholder="Max 35A" />

		<input type="text" name="electricity_grids_repeater[<?php echo esc_attr( $index ); ?>][price_vat]"
			value="<?php echo esc_attr( $field['price_vat'] ?? '' ); ?>" placeholder="Pris inkl. moms" />

		<input type="text" name="electricity_grids_repeater[<?php echo esc_attr( $index ); ?>][price_show_text]"
			value="<?php echo esc_attr( $field['price_show_text'] ?? 'Priserna visar inkl. moms.' ); ?>"
			placeholder="Priserna visar inkl. moms." />

		<input type="text" name="electricity_grids_repeater[<?php echo esc_attr( $index ); ?>][under_price_show_text]"
			value="<?php echo esc_attr( $field['under_price_show_text'] ?? '' ); ?>"
			placeholder="Text Under Priserna visar inkl. moms." />

		<div class="nested-repeater">
			<div class="nested-fields">
				<?php
				if ( ! empty( $field['nested'] ) ) {
					foreach ( $field['nested'] as $nested_index => $nested_field ) {
						?>
						<div class="nested-item">
							<input type="text"
								name="electricity_grids_repeater[<?php echo esc_attr( $index ); ?>][nested][<?php echo esc_attr( $nested_index ); ?>][fuse]"
								value="<?php echo esc_attr( $nested_field['fuse'] ?? '' ); ?>" placeholder="Säkring" />
							<input type="text"
								name="electricity_grids_repeater[<?php echo esc_attr( $index ); ?>][nested][<?php echo esc_attr( $nested_index ); ?>][electricity_grid_fee]"
								value="<?php echo esc_attr( $nested_field['electricity_grid_fee'] ?? '' ); ?>"
								placeholder="Fast elnätsavgift" />
							<input type="text"
								name="electricity_grids_repeater[<?php echo esc_attr( $index ); ?>][nested][<?php echo esc_attr( $nested_index ); ?>][electricity_grid_charge]"
								value="<?php echo esc_attr( $nested_field['electricity_grid_charge'] ?? '' ); ?>"
								placeholder="Rörlig elnätsavgift" />
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

function electricity_grids_repeater_shortcode( $atts ) {
	$args = [ 
		'post_type' => 'electricity_grid',
		'meta_key' => 'electricity_grids_repeater',
		'posts_per_page' => -1,
		'orderby' => 'DESC',
	];
	$repeater_data = new WP_Query( $args );


	ob_start();

	?>
	<div class="new-connection-repeater d-flex flex-column electricity_grid_charge_area_main">
        <script>
            // Slice Text and add span
            jQuery(document).ready(function($) {
                $('.electricity_grid_charge_area_main .new-connection-repeater-item p').each(function() {
                    var text = $(this).html().trim();
                    var parts = text.split(':'); 
                    
                    if (parts.length > 1) {
                        $(this).html(parts[0] + ': <strong>' + parts.slice(1).join(':').trim() + '</strong>');
                    }
                });
            });
        </script>
		<?php
		if ( $repeater_data->have_posts() ) :

			?>
			<?php while ( $repeater_data->have_posts() ) :
				$repeater_data->the_post();
				$fields = get_post_meta( get_the_ID(), 'electricity_grids_repeater', true );
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


									</span>
									<span class="price">
										<img class="plus-icon-main" src="<?php echo BOO_THEME_IMG_DIR . 'Plus.svg' ?>" alt="Plus">
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

											<?php if ( ! empty( $field['sub_title'] ) ) : ?>
												<p>
													<?php echo esc_html( $field['sub_title'] ); ?>
												</p>
											<?php endif; ?>

											<?php if ( ! empty( $field['fuse_size'] ) ) : ?>
												<p>

													<?php echo esc_html( $field['fuse_size'] ); ?>
												</p>
											<?php endif; ?>

											<?php if ( ! empty( $field['max'] ) ) : ?>
												<p>
													<?php echo esc_html( $field['max'] ) . " öre/kWh"; ?>
												</p>
											<?php endif; ?>

											<?php if ( ! empty( $field['price_vat'] ) ) : ?>
												<p>
													<?php echo esc_html( $field['price_vat'] ); ?>
												</p>
											<?php endif; ?>

                                        

                                            <?php if ( ! empty( $field['nested'] ) ) : ?>
												<div class="bottom-price-breakdown-area electricity_grid_charge_area">
													<div class="bottom-price-breakdown-label">
                                                        <span class="bold">Säkring</span>
                                                        <span class="bold">Fast elnätsavgift</span>
                                                        <span class="bold">Rörlig elnätsavgift</span>
													</div>
                                                    <div class="bottom-price-breakdown-value">
                                                       
                                                            <?php foreach ( $field['nested'] as $nested_item ) : ?>
                                                                 <div class="single-grid-price-list">
                                                                    <span><?php echo $nested_item['fuse']; ?></span>
                                                                    <span><?php echo $nested_item['electricity_grid_fee']; ?></span>
                                                                    <span><?php echo $nested_item['electricity_grid_charge']; ?></span>
                                                                 </div>
															<?php endforeach; ?>
                                                       
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
																	<img class="plus-icon-main" src="<?php echo BOO_THEME_IMG_DIR . 'Plus.svg'; ?>" alt="Plus">
																	<img class="minus-icon-main" src="<?php echo BOO_THEME_IMG_DIR . 'minus.svg'; ?>" alt="Minus">
																</button>
															</h2>
															<div id="collapse-<?php echo $index; ?>" class="accordion-collapse collapse"
																aria-labelledby="heading-<?php echo $index; ?>" data-bs-parent="#accordionExample">
																<div class="accordion-body">
                                                                    <p>
                                                                        <span>Fast elnätsavgift:</span><?php echo $nested_item['electricity_grid_fee']; ?>
                                                                    </p>
                                                                    <p>
                                                                        <span>Rörlig elnätsavgift:</span><?php echo $nested_item['electricity_grid_charge']; ?>
                                                                    </p>
																</div>
															</div>
														</div>
													<?php endforeach; ?>
                                                </div>
                                            </div>

											<?php if ( ! empty( $field['price_show_text'] ) ) : ?>
												<p class="total-new-connection price-show">

													<span class="small-text">
														<?php
														echo $field['price_show_text'];
														?>

													</span>

												</p>
											<?php endif; ?>
											<?php if ( ! empty( $field['under_price_show_text'] ) ) : ?>
												<p class="total-new-connection">

													<span class="small-text">
														<?php
														echo $field['under_price_show_text'];
														?>

													</span>

												</p>
											<?php endif; ?>



											


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
add_shortcode( 'electricity_grid', 'electricity_grids_repeater_shortcode' );