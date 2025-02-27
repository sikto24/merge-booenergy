<div class="d-flex justify-content-center purchase-flow-main-wrapper-success">
	<div class="m-lg-5 m-md-3 col-lg-6 col-md-10 ">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-center gap-3">
					<img src="<?php echo get_template_directory_uri() ?>/flow/assets/check_circle.svg" alt="success"
						width="40">
					<h3 style="margin-bottom: 0;">Din beställning är genomförd</h3>
				</div>
				<p class="bread pt-4 fw-bold">Tack för att du har valt Boo Energi som din energipartner!</p>
				<p class="bread pt-3">Ditt nya elavtal har registrerats, och allt är klart för att vi ska börja leverera
					el
					till
					dig.
				</p>
				<p class="bread fw-bold pt-3">Detta händer nu:</p>
				<ul class="pt-2">
					<li>En bekräftelse skickas till <span id="thank-you-email-address"></span>
						inom kort.
						Där hittar du information om ditt nya avtal.
					</li>
					<li>I bekräftelsemailet hittar du information om hur du loggar in i <a href="#">Mina sidor</a> där
						du kan
						hålla koll på din
						elförbrukning, dina fakturor och dina avtalsvillkor.</li>
				</ul>
			</div>
		</div>
		<div class="mt-4">
			<img src="<?php echo get_template_directory_uri() ?>/flow/assets/arrow-left.svg" alt="boo_logo" width="18">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="text-decoration: none;"
				class="bread fw-bold"><?php echo esc_html__( 'Tillbaka till Boo Energi', 'boo-energy' ); ?></a>
		</div>
	</div>
</div>