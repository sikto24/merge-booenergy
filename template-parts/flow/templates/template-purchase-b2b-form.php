<?php

// Step title
$boo_purchase_flow_field_frist_step_title_business = get_theme_mod( 'boo_purchase_flow_field_frist_step_title_business', 'Fyll i dina uppgifter' );
$boo_purchase_flow_field_second_step_title_business = get_theme_mod( 'boo_purchase_flow_field_second_step_title_business', 'Vart ska elen?' );
$boo_purchase_flow_field_three_step_title_business = get_theme_mod( 'boo_purchase_flow_field_three_step_title_business', 'Anläggningsuppgifter' );
$boo_purchase_flow_field_four_step_title_business = get_theme_mod( 'boo_purchase_flow_field_four_step_title_business', 'Signering' );
?>
<div class="purchase-form container purchase-flow purchase-flow-main-wrapper-form">
	<div class="row">
		<div class="col-lg-12">
			<div class="tillbaka-btn">
				<img src="<?php echo get_template_directory_uri() ?>/flow/assets/arrow-left.svg" alt="boo_logo" width="18">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="text-decoration: none;"
					class="bread fw-bold"><?php echo esc_html__( 'Tillbaka', 'boo-energy' ); ?></a>
			</div>
		</div>
	</div>
	<div class="row gy-4">
		<div class="col-lg-8 my-5 order-1 order-lg-0">
			<div id="multistep-form">
				<!-- Step 1 -->
				<div class="step-container active-step-container">
					<div data-step="1" class="d-flex gap-2 align-items-center justify-content-between step-head">
						<div class="d-flex gap-2 align-items-center">
							<div class="step-number-container">
								1
							</div>
							<h6><?php echo esc_html__( 'Fyll i dina uppgifter', 'boo-energy' ); ?></h6>
						</div>
						<img src="<?php echo get_template_directory_uri() ?>/flow/assets/pencil_icon.svg"
							alt="Edit icon" class="step-edit-icon">
						<img src="<?php echo get_template_directory_uri() ?>/flow/assets/check_circle.svg" width="34"
							height="34" alt="Check icon" class="step-check-icon">
					</div>
					<div class="pt-3 step-content">
						<div class="form-group">
							<label for="person-number"
								class="form-label"><?php echo esc_html__( $boo_purchase_flow_field_frist_step_title_business, 'boo-energy' ); ?>
								<span class="asterisk">*</span></label>
							<div class="input-container">
								<input type="text" class="form-control" id="person-number" name="person-number"
									placeholder="ÅÅÅÅMMDD-XXXX" required>
								<span class="checkmark">
									<i class="fa-solid fa-check" style="color: #009A44;"></i>
								</span>
							</div>
							<p id="person-number-error" class="error-validation bread-small pt-1 hidden">

								<?php echo esc_html__( 'Vänligen ange ett giltigt organisationsnummer', 'boo-energy' ); ?>
							</p>
							<div id="downloaded-data" class="current-address-container hidden">
								<!-- <p>Namn Namnsson</p>
				  <p>Lu***at*n 1* A / *00*</p>
				  <p>1*0 0* St*ckh*lm</p> -->
							</div>
							<div id="download-data-button-container">
								<button id="download-data-button" class="primary-button">
									<span class="hidden" style="padding-right: 8px;">
										<i class="fa-solid fa-circle-notch spin"></i>
									</span>
									<?php echo esc_html__( 'Hämta mina uppgifter', 'boo-energy' ); ?>
								</button>
								<p id="personnumber-data-hint" class="bread-small pt-1">
									<?php echo esc_html__( 'Vi hämtar dina uppgifter från', 'boo-energy' ); ?>
									allmänna
									register.
								</p>
							</div>
						</div>
						<div class="pt-4">
							<div class="form-group">
								<label for="email" class="form-label">E-postadress <span
										class="asterisk">*</span></label>
								<div class="input-container">
									<input type="email" class="form-control" id="email" name="email" maxlength="255"
										placeholder="Ange din e-postadress" required>
									<span class="checkmark">
										<i class="fa-solid fa-check" style="color: #009A44;"></i>
									</span>
									<p class="error-validation bread-small pt-1 hidden">Vänligen ange en giltig
										e-postadress</p>
								</div>
							</div>
							<div class="form-group pt-3">
								<label for="phone" class="form-label">Telefonnummer <span
										class="asterisk">*</span></label>
								<div class="input-container">
									<input type="tel" class="form-control" id="phone" name="phone"
										placeholder="Ange ditt telefonnummer"
										oninput="this.value = this.value.replace(/[^0-9+\-]/g, '')"
										pattern="^\+?[0-9]{8,15}$" maxlength="15" required>
									<span class="checkmark">
										<i class="fa-solid fa-check" style="color: #009A44;"></i>
									</span>
									<p class="error-validation bread-small pt-1 hidden">Vänligen ange ett giltigt
										telefonnummer</p>
								</div>
							</div>
						</div>

						<div class="mt-4">
							<button id="first-step-save" class="primary-button" disabled>Spara</button>
						</div>
					</div>
				</div>

				<!-- Step 2 -->
				<div class="step-container active-step-container">
					<div data-step="2" class="d-flex gap-2 align-items-center justify-content-between step-head">
						<div class="d-flex gap-2 align-items-center">
							<div class="step-number-container">
								2
							</div>
							<h6><?php echo esc_html__( $boo_purchase_flow_field_second_step_title_business, 'boo-energy' ); ?>
							</h6>
						</div>
						<img src="<?php echo get_template_directory_uri() ?>/flow/assets/pencil_icon.svg"
							alt="Edit icon" class="step-edit-icon">
						<img src="<?php echo get_template_directory_uri() ?>/flow/assets/check_circle.svg" width="34"
							height="34" alt="Check icon" class="step-check-icon">
					</div>

					<div class="pt-3 step-content">
						<!-- Current Address container retrieved from person number -->
						<div id="current-address-root" class="address-container-selected">
							<div class="d-flex align-items-center gap-2">
								<input type="radio" id="current-address" name="address-option" value="current-address"
									style="flex-shrink: 0;" checked>
								<label for="current-address">Nuvarande adress</label>
							</div>
							<!-- Current address from person number -->
							<div id="current-address-view">
								<div id="current-address-container" class="current-address-container">
									<!-- <p>Namn Namnsson</p>
					<p>Lu***at*n 1* A / *00*</p>
					<p>1*0 0* St*ckh*lm</p> -->
								</div>

								<!-- Defualt checked as current address and billing address to same -->
								<div class="d-flex align-items-center gap-2 mt-3">
									<input type="checkbox" id="use-billing-as-current" name="use-billing-as-current"
										style="flex-shrink: 0;" value="use-billing-as-current" checked>
									<label class="form-check-label" for="use-billing-as-current">Använd som
										faktureringsadress</label>
								</div>
								<div id="custom-billing-address-container">
									<h6 class="mt-4 mb-3">Ange Faktureringsadress</h6>
									<div class="form-group">
										<label for="billing-postal-address" class="form-label">Postadress <span
												class="asterisk">*</span></label>
										<input type="text" class="form-control" id="billing-postal-address"
											name="billing-postal-address" placeholder="Ange din postadress"
											maxlength="100" required>
										<span class="checkmark">
											<i class="fa-solid fa-check" style="color: #009A44;"></i>
										</span>
										<p class="error-validation bread-small pt-1 hidden">Vänligen ange en giltig
											postadress</p>
									</div>
									<div class="form-group pt-3">
										<label for="billing-zipcode" class="form-label">Postnummer <span
												class="asterisk">*</span></label>
										<input type="number" class="form-control" id="billing-zipcode"
											name="billing-zipcode" placeholder="Ange ditt postnummer" required>
										<span class="checkmark">
											<i class="fa-solid fa-check" style="color: #009A44;"></i>
										</span>
										<p id="postcode-error" class="error-validation bread-small pt-1 hidden">Vänligen
											ange ett giltigt
											postnummer</p>
									</div>
									<div class="form-group pt-3">
										<label for="billing-postal-code" class="form-label">Postort <span
												class="asterisk">*</span></label>
										<input type="text" class="form-control" id="billing-postal-code"
											name="billing-postal-code" placeholder="Ange din postort" maxlength="50"
											required>
										<span class="checkmark">
											<i class="fa-solid fa-check" style="color: #009A44;"></i>
										</span>
										<p class="error-validation bread-small pt-1 hidden">Vänligen ange en giltig
											postort</p>
									</div>
								</div>
							</div>
						</div>

						<div id="new-address-root" class="mt-3">
							<div class="d-flex align-items-center gap-2 ">
								<input class="" type="radio" style="flex-shrink: 0;" name="address-option"
									id="new-address" value="new-address">
								<label for="new-address">
									Jag ska flytta till en ny adress
								</label>
							</div>

							<div id="new-address-view">
								<h6 class="mt-4 mb-3">Ange ny adress</h6>
								<div class="form-group">
									<label for="new-address-postal-address" class="form-label">Postadress <span
											class="asterisk">*</span></label>
									<input type="text" class="form-control" id="new-address-postal-address"
										name="new-address-postal-address" placeholder="Ange din postadress"
										maxlength="100" required>
									<span class="checkmark">
										<i class="fa-solid fa-check" style="color: #009A44;"></i>
									</span>
									<p class="error-validation bread-small pt-1 hidden">Vänligen ange en giltig
										postadress</p>
								</div>
								<div class="form-group pt-3">
									<label for="new-address-zipcode" class="form-label">Postnummer <span
											class="asterisk">*</span></label>
									<input type="number" class="form-control" id="new-address-zipcode"
										name="new-address-zipcode" placeholder="Ange ditt postnummer" required>
									<span class="checkmark">
										<i class="fa-solid fa-check" style="color: #009A44;"></i>
									</span>
									<p class="error-validation bread-small pt-1 hidden">Vänligen ange ett giltigt
										postnummer</p>
								</div>
								<div class="form-group pt-3">
									<label for="new-address-postal-code" class="form-label">Postort <span
											class="asterisk">*</span></label>
									<input type="text" class="form-control" id="new-address-postal-code"
										name="new-address-postal-code" placeholder="Ange din postort" maxlength="50"
										required>
									<span class="checkmark">
										<i class="fa-solid fa-check" style="color: #009A44;"></i>
									</span>
									<p class="error-validation bread-small pt-1 hidden">Vänligen ange en giltig postort
									</p>
								</div>

								<!-- Defualt checked as current address and billing address to same -->
								<div class="d-flex align-items-center gap-2 mt-3">
									<input type="checkbox" id="use-billing-as-new" name="use-billing-as-new"
										style="flex-shrink: 0;" value="use-billing-as-new" checked>
									<label class="form-check-label" for="use-billing-as-new">Använd som
										faktureringsadress</label>
								</div>
								<div id="new-custom-billing-address-container">
									<h6 class="mt-4 mb-3">Ange faktureringsadress</h6>
									<div class="form-group">
										<label for="new-address-billing-postal-address" class="form-label">Postadress
											<span class="asterisk">*</span></label>
										<input type="text" class="form-control" id="new-address-billing-postal-address"
											name="new-address-billing-postal-address" placeholder="Ange din postadress"
											maxlength="100" required>
										<span class="checkmark">
											<i class="fa-solid fa-check" style="color: #009A44;"></i>
										</span>
										<p class="error-validation bread-small pt-1 hidden">Vänligen ange en giltig
											postadress</p>
									</div>
									<div class="form-group pt-3">
										<label for="new-address-billing-zipcode" class="form-label">Postnummer <span
												class="asterisk">*</span></label>
										<input type="number" class="form-control" id="new-address-billing-zipcode"
											name="new-address-billing-zipcode" placeholder="Ange ditt postnummer"
											required>
										<span class="checkmark">
											<i class="fa-solid fa-check" style="color: #009A44;"></i>
										</span>
										<p class="error-validation bread-small pt-1 hidden">Vänligen ange ett giltigt
											postnummer</p>
									</div>
									<div class="form-group pt-3">
										<label for="new-address-billing-postal-code" class="form-label">Postort <span
												class="asterisk">*</span></label>
										<input type="text" class="form-control" id="new-address-billing-postal-code"
											name="new-address-billing-postal-code" placeholder="Ange din postort"
											maxlength="50" required>
										<span class="checkmark">
											<i class="fa-solid fa-check" style="color: #009A44;"></i>
										</span>
										<p class="error-validation bread-small pt-1 hidden">Vänligen ange en giltig
											postort</p>
									</div>
								</div>
							</div>
						</div>

						<button id="second-step-save" class="primary-button mt-3">Spara</button>
					</div>
				</div>

				<!-- Step 3 -->
				<div class="step-container active-step-container">
					<div data-step="3" class="d-flex gap-2 align-items-center justify-content-between step-head">
						<div class="d-flex gap-2 align-items-center">
							<div class="step-number-container">
								3
							</div>
							<h6><?php echo esc_html__( $boo_purchase_flow_field_three_step_title_business, 'boo-energy' ); ?>
							</h6>
						</div>
						<img src="<?php echo get_template_directory_uri() ?>/flow/assets/pencil_icon.svg"
							alt="Edit icon" class="step-edit-icon">
						<img src="<?php echo get_template_directory_uri() ?>/flow/assets/check_circle.svg" width="34"
							height="34" alt="Check icon" class="step-check-icon">
					</div>

					<div class="pt-3 step-content">
						<div id="prefilled-facility-root" class="address-container-selected">
							<div class="d-flex align-items-center gap-2">
								<input type="radio" id="facility-boo" name="facility-option" value="facility-boo"
									style="flex-shrink: 0;" checked>
								<label for="facility-boo">Jag låter Boo Energi hämta anläggningsuppgifter.</label>
							</div>
							<!-- Current address from person number -->
							<div id="prefilled-facility-view">
								<div class="current-address-container d-flex gap-2 align-items-center"
									style="font-weight: 400;">
									<img src="<?php echo get_template_directory_uri() ?>/flow/assets/info.svg"
										alt="info icon" width="14" height="14">
									<!-- <p>Jag ger Boo Energi fullmakt att kontakta min nätägare och nuvarande... <span
											id="read-more-info"
											style="text-decoration: underline; cursor: pointer;"><strong>Läs
												mer</strong></span></p> -->
									<p class="add-read-more show-less-content">Jag ger Boo Energi fullmakt att kontakta min nätägare och nuvarande elleverantör för att komplettera uppgifter om anläggnings-ID och områdes-ID samt säga upp mitt befintliga elavtal till det datum då det löper ut.</p>
								</div> 
							</div>
						</div>

						<div id="own-filled-facility" class="mt-3">
							<div class="d-flex align-items-center gap-2 ">
								<input class="" type="radio" name="facility-option" id="own-filled-facility"
									style="flex-shrink: 0;" value="own-filled-facility">
								<label for="own-filled-facility">
									Jag fyller själv i anläggningsuppgifter
								</label>
							</div>

							<div id="own-filled-facility-view">
								<div class="current-address-container d-flex" style="font-weight: 400;">
									Områdes- och anläggnings ID hittar du på din elmätare eller din faktura.
								</div>
								<div class="form-group mt-3">
									<label for="facility-id" class="form-label">Anläggnings-ID <span
											class="asterisk">*</span></label>
									<input type="text" minlength="18" maxlength="18" class="form-control"
										id="facility-id" name="facility-id" required>
									<span class="checkmark">
										<i class="fa-solid fa-check" style="color: #009A44;"></i>
									</span>
									<p class="error-validation bread-small pt-1 hidden">Vänligen ange ett giltigt
										anläggnings-ID</p>
								</div>
								<div class="form-group mt-3">
									<label for="area-id" class="form-label">Områdes-ID <span
											class="asterisk">*</span></label>
									<input type="text" class="form-control" id="area-id" name="area-id" required>
									<span class="checkmark">
										<i class="fa-solid fa-check" style="color: #009A44;"></i>
									</span>
									<p class="error-validation bread-small pt-1 hidden">Vänligen ange ett giltigt
										områdes-ID</p>
								</div>
							</div>
						</div>

						<hr>
						<div class="my-3">
							<div class="form-group" style="position: relative;">
								<label for="start-date" class="form-label">När ska avtalet börja gälla? <span
										class="asterisk">*</span></label>
								<input type="text" class="form-control" id="start-date" placeholder="Välj datum"
									name="start-date" required>
								<p class="error-validation bread-small pt-1 hidden">Välj ett datum</p>
							</div>

						</div>

						<button id="third-step-save" class="primary-button">Spara</button>
					</div>
				</div>

				<!-- Step 4 -->
				<div class="step-container active-step-container">
					<div data-step="4" class="d-flex gap-2 align-items-center justify-content-between step-head">
						<div class="d-flex gap-2 align-items-center">
							<div class="step-number-container">
								4
							</div>
							<h6><?php echo esc_html__( $boo_purchase_flow_field_four_step_title_business, 'boo-energy' ); ?>
							</h6>
						</div>
						<img src="<?php echo get_template_directory_uri() ?>/flow/assets/pencil_icon.svg"
							alt="Edit icon" class="step-edit-icon">
						<img src="<?php echo get_template_directory_uri() ?>/flow/assets/check_circle.svg" width="34"
							height="34" alt="Check icon" class="step-check-icon">
					</div>

					<div class="pt-3 step-content">
						<p id="signin-description">Dags för signering. Du har två avtal som behöver signeras. Köpet
							genomförs när du har
							signerat båda
							avtalen.</p>

						<div class="d-flex gap-2 align-items-center my-3">
							<i class="fa fa-check" style="color: orange;"></i>
							Du har alltid 14 dagar ångerrätt när du handlar hos oss.
						</div>

						<p style="font-size: 12px;">Läs mer om Boo Energis <strong>allmänna villkor</strong>.</p>

						<div class="accept-term-container d-flex align-items-center gap-2 mt-3">
							<input type="checkbox" id="accept-term" name="accept-term" style="flex-shrink: 0;"
								value="accepted">
							<label id="show-term-button" class="form-check-label" style="cursor: pointer;"><u>Jag har
									läst och godkänner samtliga villkor och förstår hur ett Timprisavtal
									fungerar</u></label>
						</div>

						<div id="authorize-contract-view" class="current-address-container">
							<h6>1. Fullmakt</h6>
							<p class="py-2 fw-normal">Signera fullmakt som tillåter Boo Energi hämta och hantera
								dina anläggningsuppgifter åt
								dig. Så att du slipper!</p>
							<div id="proxy-signed-content" class="hidden discount-content-container">
								<i class="fa fa-check"></i>
								<p id="proxy-signed-text"></p>
							</div>
							<button id="sign-power-of-attorney" type="button" class="primary-button mt-2">Signera
								fullmakt</button>

							<p class="fw-normal mt-1" style="font-size: 12px;">Länk öppnas i nytt fönster och signering
								sker säkert
								och i
								samarbete med
								Scrive.
							</p>
						</div>

						<div id="electricity-contract-view" class="current-address-container">
							<h6>2. Elavtal</h6>
							<button id="sign-electricity-contract" class="primary-button mt-3">Signera
								elavtalet</button>
							<p class="fw-normal mt-1" style="font-size: 12px;">Länk öppnas i nytt fönster och signering
								sker säkert
								och i
								samarbete med
								Scrive.
							</p>
						</div>

					</div>
				</div>
			</div>
		</div>
		<!-- Order Summary -->
		<div class="col-lg-4 my-5">
			<div class="card p-3">
				<p>Din beställning</p>
				<h3 id="order-summary-title">Boo-portföljen</h3>
				<hr>
				<div class="row gap-2">
					<div class="d-flex justify-content-between align-items-center">
						<p class="fw-bold fs-6">Elpris</p>
						<p id="order-summary-electricity-price"></p>
					</div>
					<div class="d-flex align-items-center gap-2">
						<img src="<?php echo get_template_directory_uri() ?>/flow/assets/info.svg" alt="info icon"
							width="14" height="14">
						<p id="show-price-modal" class="text-decoration-underline cursor-pointer">Såhär har vi räknat
						</p>
					</div>
					<div class="d-flex justify-content-between align-items-center mt-3">
						<p class="fw-bold fs-6">Månadsavgift</p>
						<p id="order-summary-fee-with-vat"></p>
					</div>
				</div>
				<hr>
				<div class="row justify-content-end">
					<p id="order-summary-total" class="text-end offer-price"></p>
				</div>

				<p class="price-summary-info-text">Din slutliga månadskostnad kommer att beräknas på din faktiska
					elanvändning
					och det gällande elpriset under
					perioden. Kostnader kring elnät ingår inte utan kommer från din elnätsägare.</p>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div id="signing-modal" class="modal fade" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog custom-modal-container" role="document">
			<div class="gap-2 modal-content d-flex flex-column align-items-center justify-content-center h-100">
				<div class="text-center">
					<p>(Avtalet öppnas i ett annat fönster)</p>
					<h1 id="sign-title" class="py-3" style="color: #101010;">Signera med Scrive</h1>
					<div id="after-sign-view" class="d-flex gap-3 align-items-center hidden">
						<i class="fa fa-check fw-bold" style="color: #009A44; font-size: xx-large;"></i>
						<h1 id="sign-title" class="py-3" style="color: #101010;">Signera med Scrive</h1>
					</div>
					<button id="sign-button" class="primary-button"><span class="hidden" style="padding-right: 8px;">
							<i class="fa-solid fa-circle-notch spin"></i>
						</span> Signera fullmakt</button>
					<a id="scrive-link-attorny" href="" class="primary-button" target="_blank"
						rel="noopener noreferrer">Signera fullmakt</a>
				</div>
				<div class="current-address-container signing-error hidden">
					<div class="text-center">
						<p class="error-message">
							Något gick fel vid signering av avtalet.
						</p>
						<p class="error-message">Vänligen försök igen.</p>
					</div>
					<button id="back-from-sign" class="primary-button mt-3">Tillbaka</button>
				</div>
			</div>
		</div>
	</div>

	<div id="contract-modal" class="modal fade" data-backdrop="static" role="dialog"
		aria-labelledby="exampleModalLabel">
		<div class="modal-dialog custom-modal-container" role="document">
			<div class="gap-2 modal-content d-flex flex-column align-items-center justify-content-center h-100">
				<div class="text-center">
					<p>(Avtalet öppnas i ett annat fönster)</p>
					<h1 id="contract-sign-title" class="py-3" style="color: #101010;">Signera med Scrive</h1>
					<div id="contract-after-sign-view" class="d-flex gap-3 align-items-center hidden">
						<i class="fa fa-check fw-bold" style="color: #009A44; font-size: xx-large;"></i>
						<h1 id="contract-sign-title" class="py-3" style="color: #101010;">Signera med Scrive</h1>
					</div>
					<button id="sign-button-contract" class="primary-button"><span class="hidden"
							style="padding-right: 8px;">
							<i class="fa-solid fa-circle-notch spin"></i>
						</span>Signera elavtalet</button>
					<a id="scrive-link-contract" href="" class="primary-button" target="_blank"
						rel="noopener noreferrer">Signera elavtalet</a>
				</div>
				<div class="current-address-container signing-error hidden">
					<div class="text-center">
						<p class="error-message">
							Något gick fel vid signering av avtalet.
						</p>
						<p class="error-message">Vänligen försök igen.</p>
					</div>
					<button id="back-from-contract" class="primary-button mt-3">Tillbaka</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Information Modal -->
	<div id="info-modal" class="modal fade" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="gap-2 modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Information</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body d-flex gap-2">
					<img src="<?php echo get_template_directory_uri() ?>/flow/assets/info.svg" alt="info icon"
						class="pt-1" width="21" height="21">
					<p>Jag ger Boo Energi fullmakt att kontakta min nätägare och nuvarande elleverantör för att
						komplettera
						uppgifter om anläggnings-ID och områdes-ID samt säga upp mitt befintliga elavtal till det datum
						då det
						löper
						ut.</p>
				</div>
			</div>
		</div>
	</div>

	<!-- Price Details Modal -->
	<div id="price-details-modal" class="modal fade" data-backdrop="static" role="dialog">
		<div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 61rem;">
			<div class="gap-2 modal-content">
				<div class="custom-price-modal">
					<p class="bread-large">Prisspecifikation</p>
					<p id="custom-price-modal-description" class="bread pt-4">Elpriset som visas här baseras alltid på
						förra månadens elpris, din angivna
						förbrukning och vart du bor.</p>

					<div class="d-flex flex-column gap-3 pt-4">
						<div class="accordion" id="accordion-category">
							<div class="accordion-item">
								<h2 class="accordion-header">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
										data-bs-target="#collapse-category" aria-expanded="false">
										<div class="d-flex justify-content-between w-100">
											<div>
												<h6>Uppskattad månadskostnad</h6>
												<h6 class="offer-price d-md-none d-block pt-2"
													style="color: var(--brand-orange); padding-right: 1rem;">
												</h6>
											</div>
											<div class="d-flex align-items-center gap-1">
												<h6 class="offer-price d-none d-md-block"
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
								<div id="collapse-category" class="accordion-collapse collapse"
									data-bs-parent="#accordion-category">
									<div class="accordion-body pt-0">
										<div class="price-details">
											<!-- Dynamic content will be injected here -->
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="accordion" id="accordion-comparison">
							<div class="accordion-item">
								<h2 class="accordion-header">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
										data-bs-target="#collapse-comparison" aria-expanded="false"
										aria-controls="collapseTwo">
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
								<div id="collapse-comparison" class="accordion-collapse collapse"
									data-bs-parent="#accordion-comparison">
									<div class="accordion-body pt-0">
										<hr class="my-2" style="background-color: #E2DAD6;" />
										<div class="modal-accordion-info">
											<img src="<?php echo get_template_directory_uri() ?>/flow/assets/info.svg"
												alt="info" width="21" height="21">
											<p>Här är en beskrivande text om vad jämförelsepris innebär.</p>
										</div>
										<div class="price-comparison">
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

	<!-- Terms Modal -->
	<div id="terms-modal" class="modal fade" data-bs-backdrop="static" role="dialog">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="gap-2 modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Avtalsvillkor</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p>Inget elpaket har valts. Du kommer att omdirigeras till startsidan.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn" data-bs-dismiss="modal">Avbryt</button>
					<div><button id="accept-term-button" type="button" class="primary-button">Bekräfta
							<i class="fa-solid fa-check "></i>
						</button></div>
				</div>
			</div>
		</div>
	</div>
</div>