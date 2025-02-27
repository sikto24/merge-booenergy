<?php
/**
 * Summary of render_purchase_flow_section
 * for Purchase Flow Section
 * @return bool|string
 */
function render_purchase_flow_section( $atts = [] ) {
	if ( is_admin() ) {
		return;
	}
	$atts = shortcode_atts( [ 
		'package' => 'boo_portfolio,variable,fixed_1',
		'portfolio_package_values' => '',
		'movable_package_values' => '',
		'fixed_package_value' => '',
		'title' => 'Se ditt elpris',
		'subtitle' => 'Fyll i dina boendeuppgifter i kalkylatorn för att se dina priser.',
		'show_title_content' => 'yes',
		'show_b2b' => 'false',
	], $atts, 'purchase_flow' );
	$packages = explode( ',', $atts['package'] );
	$packages_layout = count( $packages );
	$portfolio_package_values = explode( ',', $atts['portfolio_package_values'] );
	$movable_package_values = explode( ',', $atts['movable_package_values'] );
	$fixed_package_value = explode( ',', $atts['fixed_package_value'] );
	$show_title_content = $atts['show_title_content'];
	$boo_b2b = $atts['show_b2b'];


	// Assign Customizer Data For Private
	// Title
	$private_portfolio_title = get_theme_mod( 'boo_purchase_flow_field_portfolio_title', 'Boo–portföljen' );
	$private_variable_title = get_theme_mod( 'boo_purchase_flow_field_variable_title', 'Rörligt elpris' );
	$private_fixed_title = get_theme_mod( 'boo_purchase_flow_field_fixed_title', 'Bundet elpris' );

	// Sub Title
	$private_portfolio_subtitle = get_theme_mod( 'boo_purchase_flow_field_portfolio_sub_title', 'Boo Energi optimerar priserna' );
	$private_variable_subtitle = get_theme_mod( 'boo_purchase_flow_field_variable_sub_title', 'Boo Energi optimerar priserna' );
	$private_fixed_subtitle = get_theme_mod( 'boo_purchase_flow_field_fixed_sub_title', 'Boo Energi optimerar priserna' );


	// Description
	$private_portfolio_desc = get_theme_mod( 'boo_purchase_flow_field_portfolio_desc', 'Elpriset som visas här baseras alltid på förra månadens elpris, din angivna förbrukning och vart du bor.' );
	$private_variable_desc = get_theme_mod( 'boo_purchase_flow_field_variable_desc', 'Elpriset som visas här baseras alltid på förra månadens elpris, din angivna förbrukning och vart du bor.' );
	$private_fixed_desc = get_theme_mod( 'boo_purchase_flow_field_fixed_desc', 'Elpriset som visas här baseras alltid på förra månadens elpris, din angivna förbrukning och vart du bor. Ett bundet elavtal får du samma elpris oavsett priset på marknaden, fakturan ändras beroende på hur mycket el du använder.' );

	// Bollet Data Lists

	// Private Lists
	$private_portfolio_desc_list = get_theme_mod( 'boo_purchase_flow_field_portfolio_desc_list', 'Stabilt elpris över tid
     Styra din förbrukning i vår app' );
	$private_variable_desc_list = get_theme_mod( 'boo_purchase_flow_field_variable_desc_list', 'Styra din förbrukning i vår app 
    3 veckors uppsägningstid' );
	$private_fixed_desc_list = get_theme_mod( 'boo_purchase_flow_field_fixed_desc_list', 'Välj 1, 2 eller 3 år
    Låg risk' );


	// Business Lists
	$private_portfolio_desc_list_business = get_theme_mod( 'boo_purchase_flow_field_portfolio_desc_list_business', 'Stabilt elpris över tid
	 Styra din förbrukning i vår app' );
	$private_variable_desc_list_business = get_theme_mod( 'boo_purchase_flow_field_variable_desc_list_business', 'Styra din förbrukning i vår app 
	3 veckors uppsägningstid' );
	$private_fixed_desc_list_business = get_theme_mod( 'boo_purchase_flow_field_fixed_desc_list_business', 'Välj 1, 2 eller 3 år
	Låg risk' );

	$private_portfolio_desc_list_data = explode( "\n", $private_portfolio_desc_list );
	$private_variable_desc_list_data = explode( "\n", $private_variable_desc_list );
	$private_fixed_desc_list_data = explode( "\n", $private_fixed_desc_list );

	$private_portfolio_desc_list_business_data = explode( "\n", $private_portfolio_desc_list_business );
	$private_variable_desc_list_business_data = explode( "\n", $private_variable_desc_list_business );
	$private_fixed_desc_list_business_data = explode( "\n", $private_fixed_desc_list_business );


	// Assign Customizer Data For Private

	// Lists Data
	$private_lists_data = ( 'true' === $boo_b2b ) ? $private_portfolio_desc_list_data : $private_portfolio_desc_list_business_data;
	$variable_lists_data = ( 'true' === $boo_b2b ) ? $private_variable_desc_list_data : $private_variable_desc_list_business_data;
	$fixed_lists_data = ( 'true' === $boo_b2b ) ? $private_fixed_desc_list_data : $private_fixed_desc_list_business_data;
	// Title
	$business_portfolio_title = get_theme_mod( 'boo_purchase_flow_field_portfolio_title_business', 'Boo–portföljen' );
	$business_variable_title = get_theme_mod( 'boo_purchase_flow_field_variable_title_business', 'Rörligt elpris' );
	$business_fixed_title = get_theme_mod( 'boo_purchase_flow_field_fixed_title_business', 'Bundet elpris' );

	// Sub Title

	$business_portfolio_subtitle = get_theme_mod( 'boo_purchase_flow_field_portfolio_sub_title_business', 'Boo Energi optimerar priserna' );
	$business_variable_subtitle = get_theme_mod( 'boo_purchase_flow_field_variable_sub_title_business', 'Boo Energi optimerar priserna' );
	$business_fixed_subtitle = get_theme_mod( 'boo_purchase_flow_field_fixed_sub_title_business', 'Boo Energi optimerar priserna' );


	// Description
	$business_portfolio_desc = get_theme_mod( 'boo_purchase_flow_field_portfolio_desc_business', 'Elpriset som visas här baseras alltid på förra månadens elpris, din angivna förbrukning och vart du bor.' );
	$business_variable_desc = get_theme_mod( 'boo_purchase_flow_field_variable_desc_business', 'Elpriset som visas här baseras alltid på förra månadens elpris, din angivna förbrukning och vart du bor.' );
	$business_fixed_desc = get_theme_mod( 'boo_purchase_flow_field_fixed_desc_business', 'Elpriset som visas här baseras alltid på förra månadens elpris, din angivna förbrukning och vart du bor. Ett bundet elavtal får du samma elpris oavsett priset på marknaden, fakturan ändras beroende på hur mycket el du använder.' );






	// Title
	$boo_purchase_flow_field_portfolio_title = ( 'true' === $boo_b2b ) ? $private_portfolio_title : $business_portfolio_title;
	$boo_purchase_flow_field_variable_title = ( 'true' === $boo_b2b ) ? $private_variable_title : $business_variable_title;
	$boo_purchase_flow_field_fixed_title = ( 'true' === $boo_b2b ) ? $private_fixed_title : $business_fixed_title;


	// Sub Title
	$boo_purchase_flow_field_portfolio_sub_title = ( 'true' === $boo_b2b ) ? $private_portfolio_subtitle : $business_portfolio_subtitle;
	$boo_purchase_flow_field_variable_sub_title = ( 'true' === $boo_b2b ) ? $private_variable_subtitle : $business_variable_subtitle;
	$boo_purchase_flow_field_fixed_sub_title = ( 'true' === $boo_b2b ) ? $private_fixed_subtitle : $business_fixed_subtitle;

	// Description

	$boo_purchase_flow_field_portfolio_desc = ( 'true' === $boo_b2b ) ? $private_portfolio_desc : $business_portfolio_desc;
	$boo_purchase_flow_field_variable_desc = ( 'true' === $boo_b2b ) ? $private_variable_desc : $business_variable_desc;
	$boo_purchase_flow_field_fixed_desc = ( 'true' === $boo_b2b ) ? $private_fixed_desc : $business_fixed_desc;


	if ( $packages_layout === 3 ) {
		$layout_left = 'col-lg-4';
		$layout_right = 'col-lg-8';
		$layout_right_inner = 'col-lg-4';
	}
	if ( $packages_layout === 2 ) {
		$layout_left = 'col-lg-4';
		$layout_right = 'col-lg-8';
		$layout_right_inner = 'col-lg-6';
	}
	if ( $packages_layout === 1 ) {
		$layout_left = 'col-lg-6';
		$layout_right = 'col-lg-6';
		$layout_right_inner = 'col-lg-12';
	}


	wp_enqueue_script( 'purchase-flow-lime-form', 'https://booenergi.lime-forms.se/js/ce/latest', array( 'jquery' ), null, true );
	wp_localize_script( 'flow-main', 'booPurchaseFlowData', array(
		'portfolio_package_values' => $portfolio_package_values,
		'movable_package_values' => $movable_package_values,
		'fixed_package_value' => $fixed_package_value,
	) );


	// Send Data to flow-form

	$customizer_data = array(
		// Title
		'portfolio_title' => esc_html__( $boo_purchase_flow_field_portfolio_title, 'boo-energy' ),
		'variable_title' => esc_html__( $boo_purchase_flow_field_variable_title, 'boo-energy' ),
		'fixed_title' => esc_html__( $boo_purchase_flow_field_fixed_title, 'boo-energy' ),


		// Description
		'portfolio_title_desc' => esc_html__( $boo_purchase_flow_field_portfolio_desc, 'boo-energy' ),
		'variable_title_desc' => esc_html__( $boo_purchase_flow_field_variable_desc, 'boo-energy' ),
		'fixed_title_desc' => esc_html__( $boo_purchase_flow_field_fixed_desc, 'boo-energy' ),

	);
	wp_localize_script( 'flow-form', 'booCustomizerPackageName', $customizer_data );

	ob_start();
	?>

	<section class="container purchase-flow">
		<?php if ( 'yes' === $show_title_content ) : ?>
			<div class="d-flex align-items-center gap-2 mb-3">
				<h2><?php echo esc_html( $atts['title'] ); ?></h2>
				<img src="<?php echo get_template_directory_uri() ?>/flow/assets/arrow_down.svg" alt="Arrow" width="30"
					height="30">
			</div>

			<p class="text-muted mb-4"><?php echo esc_html( $atts['subtitle'] ); ?></p>
		<?php endif; ?>
		<div class="row gy-4">
			<!-- Left Form -->
			<div class="<?php echo $layout_left; ?>">
				<div class="card card-variant p-4 shadow-sm">
					<div class="mb-3">
						<label for="postcode" class="card-item-label form-label">Vart ska elen levereras? <span
								class="asterisk">*</span></label>
						<div class="input-container">
							<input type="text" id="postcode" pattern="\d{5}" maxlength="5" class="form-control"
								placeholder="Ange postnummer"
								oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5)">
							<span class="checkmark">
								<i class="fa-solid fa-check" style="color: #009A44;"></i>
							</span>
							<span class="checkmark">
								<i class="fa-solid fa-circle-notch spin"></i>
							</span>
						</div>
						<p id="postcode-error" class="error-validation bread-small pt-1 hidden">Ange ett giltigt postnummer
						</p>
					</div>

					<?php if ( 'true' === $boo_b2b ) : ?>
						<div class="mb-3">
							<label class="form-label card-item-label">Välj typ av bostad</label>
							<div class="gap-3 d-flex" role="group" aria-label="Housing type">
								<input type="radio" class="btn-check" name="housingType" id="apartment" autocomplete="off"
									checked>
								<label class="btn label-button" for="apartment">Villa</label>

								<input type="radio" class="btn-check" name="housingType" id="house" autocomplete="off">
								<label class="btn label-button" for="house">Lägenhet</label>

								<input type="radio" class="btn-check" name="housingType" id="other" autocomplete="off">
								<label class="btn label-button" for="other">Radhus</label>
							</div>
						</div>

						<div class="mb-3">
							<label for="size" class="form-label card-item-label">Hur stor är bostaden?</label>
							<div class="d-flex flex-column gap-3 pt-2">
								<input type="range" id="size" class="custom-range-slider" min="0" max="50" value="5">
								<div class="d-flex align-items-center justify-content-end gap-2">
									<input type="number" id="sizeValue" class="form-control w-25" min="0" max="50" value="5"
										oninput="this.value = Math.min(Math.max(this.value, 0), 50)">
									<span>kvm</span>
								</div>
							</div>
						</div>

						<div class="mb-3">
							<label for="usage" class="form-label card-item-label">Ange årsforbrukning</label>
							<div class="d-flex flex-column gap-3 pt-2">
								<input type="range" id="usage" class="custom-range-slider" min="1" max="30000" value="2000">
								<div class="d-flex align-items-center justify-content-end gap-2">
									<input type="number" id="usageValue" class="form-control w-25" min="0" max="30000"
										value="2000" oninput="this.value = Math.min(Math.max(this.value, 0), 30000)">
									<span>kWh/år</span>
								</div>
							</div>
						</div>
					<?php else : ?>
						<!-- B2B Slider -->
						<div class="b2b-slider-section">
							<div id="b2b-slider-section">
								<div class="mb-2">
									<label for="annual-consumption-small"
										class="d-flex align-items-center gap-2 mb-4 d-relative">
										<input type="radio" value="small" id="annual-consumption-small"
											name="annual-consumption-option" checked>
										0 - 80000 kWh/år</label>
								</div>
								<div class="position-relative w-100">
									<input type="range" id="annual-consumtion" class="custom-range-slider" min="0" max="80000"
										step="1" value="2000">
									<!-- <div class=" d-flex justify-content-between w-100">
										<div class="slider-label" data-value="0">
											|
											<p>Liten</p>
										</div>
										<div class="slider-label" data-value="1">
											|
											<p>Medelstor</p>
										</div>
										<div class="slider-label" data-value="2">
											|
											<p>Stor</p>
										</div>
									</div> -->
								</div>
								<div class="d-flex align-items-end my-4 justify-content-end gap-2">
									<input type="number" max="80000" min="0" id="annual-consumtion-value" class="form-control"
										style="width: 30%;" value="2000"
										oninput="this.value = Math.min(Math.max(this.value, 0), 80000)">
									<span class="fw-bold">kWh/år</span>
								</div>
							</div>

							<div class="mb-2">
								<label for="annual-consumption-medium" class="d-flex align-items-center gap-2 mb-4">
									<input type="radio" value="medium" id="annual-consumption-medium"
										name="annual-consumption-option">
									80001 - 399999 kWh/år</label>
							</div>
							<div class="mb-2">
								<label for="annual-consumption-large" class="d-flex align-items-center gap-2 mb-4">
									<input type="radio" value="large" id="annual-consumption-large"
										name="annual-consumption-option">
									Mer än 400000 kWh/år</label>
							</div>
						</div>
					<?php endif; ?>

					<!-- Discount -->
					<div class="mb-3">
						<div id="discount-accordion"
							class="d-flex justify-content-between align-items-center cursor-pointer">
							<p class="bread">Har du en rabattkod?</p>
							<i id="discount-accordion-chevron" class="fa-solid fa-chevron-down"></i>
						</div>
						<div id="discount-accordion-content" class="hidden d-flex gap-2 mt-2">
							<div class="input-container">
								<input type="text" id="discount-input" class="form-control" placeholder="Ange rabattkod">
								<span class="checkmark">
									<i class="fa-solid fa-circle-notch spin"></i>
								</span>
							</div>
							<button id="discount-button" disabled class="btn btn-link disabled-text"
								style="box-shadow: none;">Tillämpa</button>
						</div>
						<p id="discount-error" class="bread-small pt-1 hidden">Ogiltlig rabattkod</p>
						<div id="discount-content" class="hidden discount-content-container">
							<p id="discount-text"></p>
							<i id="discount-remove" class="fa-solid fa-x cursor-pointer"></i>
							<span class="hidden">
								<i class="fa-solid fa-circle-notch spin"></i>
							</span>
						</div>
					</div>

					<button id="see-price-button" type="submit" class="w-100">
						<span class="hidden" style="padding-right: 8px;">
							<i class="fa-solid fa-circle-notch spin"></i>
						</span>
						Se mitt pris</button>
				</div>
			</div>



			<!-- Package Options -->
			<div id="package-section" class="<?php echo $layout_right; ?>">
				<div class="row gy-4" style="min-height: 25rem;">
					<?php
					foreach ( $packages as $package ) {

						$package = trim( $package );
						if ( $package === 'boo_portfolio' ) :
							?>

							<div id="first-package" class="<?php echo $layout_right_inner; ?>">
								<div class="card p-3 shadow-sm"
									style="display: flex; height: 100%; flex-direction: column; justify-content: space-between;">
									<div class="recommendation-label">
										<p>Vi rekommenderar</p>
									</div>
									<div>
										<h4 class="">
											<?php echo esc_html__( $boo_purchase_flow_field_portfolio_title, 'boo-energy' ); ?>
										</h4>

										<div class="higlighted-label">
											<p class="">
												<?php echo esc_html__( $boo_purchase_flow_field_portfolio_sub_title, 'boo-energy' ); ?>
											</p>
										</div>
										<ul>
											<?php if ( ! empty( $private_lists_data ) ) :
												foreach ( $private_lists_data as $list ) :
													?>
													<li><?php echo esc_html( $list ); ?></li>
												<?php endforeach; endif; ?>
										</ul>
										<div class="d-flex align-items-center gap-2 pb-3">
											<a href="#">Läs mer om
												<?php echo esc_html__( $boo_purchase_flow_field_portfolio_title, 'boo-energy' ); ?>
											</a>
											<img src="<?php echo get_template_directory_uri() ?>/flow/assets/arrow_right.svg"
												alt="Arrow" width="18" height="15">
										</div>

										<div class="package-discount-container hidden">
											<hr>
											<div id="discount-content" class="discount-content-container">
												<p id="discount-text" class="discount-text"></p>
												<i id="discount-remove" class="fa-solid fa-x cursor-pointer discount-remove"></i>
												<span class="hidden">
													<i class="fa-solid fa-circle-notch spin"></i>
												</span>
											</div>
											<p class="calculated-discount"></p>
											<p class="discount-description">Fri månadsavgift i 12 månader. Därefter 49 kr/mån</p>
										</div>

									</div>

									<!-- Card Footer -->
									<div class="card-footer-custom">
										<div class="card-footer-info">
											<img src="<?php echo get_template_directory_uri() ?>/flow/assets/info.svg" alt="Price"
												class="img-fluid">
											<p>Fyll i kalkylatorn till vänster för att se ditt pris</p>
										</div>

										<!-- Price info -->
										<div class="card-footer-price">
											<div class="d-flex gap-2 align-items-center mb-3">
												<img src="<?php echo get_template_directory_uri() ?>/flow/assets/info.svg"
													alt="info" width="14" height="14">
												<p id="show-portfolio-modal" data-package="boo_portfolio"
													class="text-decoration-underline cursor-pointer">Så har
													vi räknat</p>
											</div>
											<div class="d-flex flex-column">
												<div class="d-flex flex-sm-row flex-lg-column flex-xxl-row w-100 gap-1">
													<p class="offer-price">100 kr/mån</p>
													<p class="original-price"></p>
												</div>
												<p class="total-category-price"></p>
											</div>
											<button id="select-portfolio-package-button" class="button-outline">Välj
												<?php echo esc_html__( $boo_purchase_flow_field_portfolio_title, 'boo-energy' ); ?></button>
										</div>
									</div>
								</div>
							</div>
						<?php endif;
						if ( $package === 'variable' ) :
							?>
							<div id="second-package" class="<?php echo $layout_right_inner; ?>">
								<div class="card ac-header">
									<h4><?php echo esc_html__( $boo_purchase_flow_field_variable_title, 'boo-energy' ); ?></h4>
									<img src="<?php echo get_template_directory_uri() ?>/flow/assets/plus.svg" alt="Plus" width="20"
										height="20">
								</div>
								<div id="variable-electricity-price" class="card p-3 shadow-sm sm-hidden"
									style="display: flex; height: 100%; flex-direction: column; justify-content: space-between; position: relative;">
									<div>
										<h4 class="">
											<?php echo esc_html__( $boo_purchase_flow_field_variable_title, 'boo-energy' ); ?>
										</h4>
										<div class="higlighted-label">
											<p><?php echo esc_html__( $boo_purchase_flow_field_variable_sub_title, 'boo-energy' ); ?>
											</p>
										</div>
										<ul>
											<?php if ( ! empty( $variable_lists_data ) ) :
												foreach ( $variable_lists_data as $list ) :
													?>
													<li><?php echo esc_html( $list ); ?></li>
												<?php endforeach; endif; ?>
										</ul>
										<div class="d-flex align-items-center gap-2 pb-3">
											<a href="#">Läs mer om
												<?php echo esc_html__( $boo_purchase_flow_field_variable_title, 'boo-energy' ); ?></a>
											<img src="<?php echo get_template_directory_uri() ?>/flow/assets/arrow_right.svg"
												alt="Arrow" width="18" height="15">
										</div>

										<div class="package-discount-container hidden">
											<hr>
											<div id="discount-content" class="discount-content-container">
												<p id="discount-text" class="discount-text"></p>
												<i id="discount-remove" class="fa-solid fa-x cursor-pointer discount-remove"></i>
												<span class="hidden">
													<i class="fa-solid fa-circle-notch spin"></i>
												</span>
											</div>
											<p class="calculated-discount"></p>
											<p class="discount-description">Fri månadsavgift i 12 månader. Därefter 49 kr/mån</p>
										</div>
									</div>

									<!-- Card Footer -->
									<div class="card-footer-custom">
										<div class="card-footer-info">
											<img src="<?php echo get_template_directory_uri() ?>/flow/assets/info.svg" alt="Price"
												class="img-fluid">
											<p>Fyll i kalkylatorn till vänster för att se ditt pris</p>
										</div>

										<!-- Price info -->
										<div class="card-footer-price">
											<div class="d-flex gap-2 align-items-center mb-3">
												<img src="<?php echo get_template_directory_uri() ?>/flow/assets/info.svg"
													alt="info" width="14" height="14">
												<p id="show-variable-modal" data-package="variable"
													class="text-decoration-underline cursor-pointer">Så har vi räknat</p>
											</div>
											<div class="d-flex flex-column">
												<div class="d-flex flex-sm-row flex-lg-column flex-xxl-row w-100 gap-1">
													<p class="offer-price">100 kr/mån</p>
													<p class="original-price"></p>
												</div>
												<p class="total-category-price"></p>
											</div>
											<button id="select-variable-package-button" class="button-outline">Välj
												<?php echo esc_html__( $boo_purchase_flow_field_variable_title, 'boo-energy' ); ?></button>
										</div>

									</div>
								</div>
							</div>
						<?php endif;
						if ( $package === 'fixed_1' ) :
							?>

							<div id="third-package" class="<?php echo $layout_right_inner; ?>">
								<div class="card ac-header">
									<h4><?php echo esc_html__( $boo_purchase_flow_field_fixed_title, 'boo-energy' ); ?></h4>
									<img src="<?php echo get_template_directory_uri() ?>/flow/assets/plus.svg" alt="Plus" width="20"
										height="20">
								</div>
								<div class="card p-3 shadow-sm sm-hidden"
									style="display: flex; height: 100%; flex-direction: column; justify-content: space-between;">
									<div>
										<h4 class=""><?php echo esc_html__( $boo_purchase_flow_field_fixed_title, 'boo-energy' ); ?>
										</h4>
										<div class="higlighted-label">
											<p><?php echo esc_html__( $boo_purchase_flow_field_fixed_sub_title, 'boo-energy' ); ?>
											</p>
										</div>
										<ul>
											<?php if ( ! empty( $fixed_lists_data ) ) :
												foreach ( $fixed_lists_data as $list ) :
													?>
													<li><?php echo esc_html( $list ); ?></li>
												<?php endforeach; endif; ?>
										</ul>
										<div class="d-flex align-items-center gap-2 pb-3">
											<a href="#">Läs mer om
												<?php echo esc_html__( $boo_purchase_flow_field_fixed_title, 'boo-energy' ); ?></a>
											<img src="<?php echo get_template_directory_uri() ?>/flow/assets/arrow_right.svg"
												alt="Arrow" width="18" height="15">
										</div>

										<div class="mb-3">
											<label style="font-size: 12px; color: #818181;" class="form-label card-item-label">Välj
												bindningstid</label>
											<div class="gap-2 d-flex" role="group" aria-label="Binding time">
												<input type="radio" value="1" class="btn-check" name="bindingTime" id="time-one"
													autocomplete="off">
												<label class="btn label-button-small" for="time-one">1 år</label>

												<input type="radio" value="2" class="btn-check" name="bindingTime" id="time-two"
													autocomplete="off">
												<label class="btn label-button-small" for="time-two">2 år</label>

												<input type="radio" value="3" class="btn-check" name="bindingTime" id="time-three"
													autocomplete="off">
												<label class="btn label-button-small" for="time-three">3 år</label>
											</div>
										</div>

										<div class="package-discount-container hidden">
											<hr>
											<div id="discount-content" class="discount-content-container">
												<p id="discount-text" class="discount-text"></p>
												<i id="discount-remove" class="fa-solid fa-x cursor-pointer discount-remove"></i>
												<span class="hidden">
													<i class="fa-solid fa-circle-notch spin"></i>
												</span>
											</div>
											<p class="calculated-discount"></p>
											<p class="discount-description">Fri månadsavgift i 12 månader. Därefter 49 kr/mån</p>
										</div>

									</div>
									<div class="card-footer-custom">
										<div class="card-footer-info">
											<img src="<?php echo get_template_directory_uri() ?>/flow/assets/info.svg" width="28"
												height="28" alt="Price" class="img-fluid">
											<p>Fyll i kalkylatorn till vänster för att se ditt pris</p>
										</div>

										<!-- Price info -->
										<div class="card-footer-price">
											<div class="d-flex gap-2 align-items-center mb-3">
												<img src="<?php echo get_template_directory_uri() ?>/flow/assets/info.svg"
													alt="info" width="14" height="14">
												<p id="show-fixed-modal" class="text-decoration-underline cursor-pointer">Så har vi
													räknat</p>
											</div>
											<div class="d-flex flex-column">
												<div class="d-flex flex-sm-row flex-lg-column flex-xxl-row w-100 gap-1">
													<p class="offer-price">100 kr/mån</p>
													<p class="original-price"></p>
												</div>
												<p class="total-category-price"></p>
											</div>
											<button id="select-fixed-package-button" class="button-outline">Välj
												<?php echo esc_html__( $boo_purchase_flow_field_fixed_title, 'boo-energy' ); ?></button>
										</div>

									</div>
								</div>
							</div>
						<?php endif;
					}
					?>
				</div>
			</div>

			<div class="contact-section col-lg-8 hidden">
				<h2 class="h2-large">Kontakta oss för personlig rådgivning</h2>
				<p class="bread-large fw-normal" style="line-height: 33px;">Förbrukar ni mer är 80.000 kW per år? Vi hjälper
					er att hitta det bästa elavtalet för er. Kontakta oss för personlig rådgivning.</p>
				<button class="contact-button fw-bold" style="width: fit-content;">Kontakta oss</button>
			</div>
			<div class="consumption-medium-section col-lg-8 hidden">
				<lime-form form-id="BuIFNdzgnLga4arbT9aT"></lime-form>
			</div>
			<div class="consumption-large-section col-lg-8 hidden">
				<lime-form form-id="8lZDpoPqp5LQ1hc8yLVv"></lime-form>
			</div>
		</div>


		<!-- Package Modal Template -->
		<?php foreach ( [ 'portfolio', 'variable', 'fixed' ] as $package ) :
			$titles = [ 
				'portfolio' => esc_html__( $boo_purchase_flow_field_portfolio_title, 'boo-energy' ),
				'variable' => esc_html__( $boo_purchase_flow_field_variable_title, 'boo-energy' ),
				'fixed' => esc_html__( $boo_purchase_flow_field_fixed_title, 'boo-energy' ),
			];
			$descriptions = [ 
				'portfolio' => esc_html__( $boo_purchase_flow_field_portfolio_desc, 'boo-energy' ),
				'variable' => esc_html__( $boo_purchase_flow_field_variable_desc, 'boo-energy' ),
				'fixed' => esc_html__( $boo_purchase_flow_field_fixed_desc, 'boo-energy' ),
			];
			?>
			<div id="price-modal-<?php echo $package; ?>" class="modal fade" data-backdrop="static" role="dialog">
				<div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 61rem;">
					<div class="gap-2 modal-content">
						<div class="custom-price-modal">
							<p class="bread-large">Prisspecifikation <?php echo $titles[ $package ]; ?></p>
							<p class="bread pt-4"><?php echo $descriptions[ $package ] ?></p>

							<?php if ( $package === 'fixed' ) : ?>
								<div class="pt-4">
									<div class="d-flex align-items-center gap-2 pb-1">
										<img src="<?php echo get_template_directory_uri() ?>/flow/assets/info.svg" alt="info"
											width="15" height="15">
										<p class="bread fw-bold">Preliminärt pris</p>
									</div>
									<p>Priset avser avtal med startdatum 2024-10-21. När du väljer att teckna ett bundet avtal
										kommer du kunna välja startdatum för ditt nya avtal innan du bekräftar köpet.</p>
								</div>
							<?php endif; ?>

							<div class="d-flex flex-column gap-3 pt-4">
								<div class="accordion" id="accordion-<?php echo $package; ?>">
									<div class="accordion-item">
										<h2 class="accordion-header">
											<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
												data-bs-target="#collapse-<?php echo $package; ?>" aria-expanded="false">
												<div class="d-flex justify-content-between gap-1 w-100">
													<div>
														<h6>Uppskattad månadskostnad</h6>
														<h6 class="offer-price-<?php echo $package; ?> d-md-none d-block pt-2"
															style="color: var(--brand-orange); padding-right: 1rem;">
														</h6>
													</div>
													<div class="d-flex align-items-center gap-1">
														<h6 class="offer-price-<?php echo $package; ?> d-none d-md-block"
															style="color: var(--brand-orange); padding-right: 1rem;">
														</h6>
														<img class="accordion-state-icon"
															src="<?php echo get_template_directory_uri() ?>/flow/assets/plus.svg"
															data-plus-icon="<?php echo get_template_directory_uri() ?>/flow/assets/plus.svg"
															data-minus-icon="<?php echo get_template_directory_uri() ?>/flow/assets/minus.svg"
															alt="info" width="21" height="21">
													</div>
												</div>
											</button>
										</h2>
										<div id="collapse-<?php echo $package; ?>" class="accordion-collapse collapse"
											data-bs-parent="#accordion-<?php echo $package; ?>">
											<div class="accordion-body pt-0">
												<div class="price-details-<?php echo $package; ?>">
													<!-- Dynamic content will be injected here -->
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="accordion" id="accordion-<?php echo $package; ?>-comparison">
									<div class="accordion-item">
										<h2 class="accordion-header">
											<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
												data-bs-target="#collapse-<?php echo $package ?>-comparison"
												aria-expanded="false" aria-controls="collapseTwo">
												<div class="d-flex justify-content-between w-100">
													<h6>Jämförelsepris</h6>
													<img class="accordion-state-icon"
														src="<?php echo get_template_directory_uri() ?>/flow/assets/plus.svg"
														data-plus-icon="<?php echo get_template_directory_uri() ?>/flow/assets/plus.svg"
														data-minus-icon="<?php echo get_template_directory_uri() ?>/flow/assets/minus.svg"
														alt="info" width="21" height="21">
												</div>
											</button>
										</h2>
										<div id="collapse-<?php echo $package ?>-comparison" class="accordion-collapse collapse"
											data-bs-parent="#accordion-<?php echo $package; ?>-comparison">
											<div class="accordion-body pt-0">
												<hr class="my-2" style="background-color: #E2DAD6;" />
												<div class="modal-accordion-info">
													<img src="<?php echo get_template_directory_uri() ?>/flow/assets/info.svg"
														alt="info" width="21" height="21">
													<p>Här är en beskrivande text om vad jämförelsepris innebär.</p>
												</div>
												<div class="price-comparison-<?php echo $package ?> ">
													<!-- Dynamic content will be injected here -->
												</div>

											</div>
										</div>
									</div>
								</div>


								<p class="bread-small"> Din slutliga månadskostnad kommer att beräknas på din faktiska
									elanvändning och
									det
									gällande elpriset
									under
									perioden. Kostnader kring elnät ingår inte utan kommer från din elnätsägare.</p>

								<button type="button" class="button-outline modal-close-button" data-bs-dismiss="modal">Okej,
									jag förstår</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>

	</section>

	<?php
	return ob_get_clean();
}
add_shortcode( 'purchase_flow', 'render_purchase_flow_section' );