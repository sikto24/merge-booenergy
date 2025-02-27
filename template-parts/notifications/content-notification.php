<?php
$boo_omrade = get_field( 'omrade' );
$avbrott_startar = get_field( 'avbrott_startar' );
$avbrott_avslutas = get_field( 'avbrott_avslutas' );
$post_mode_selector = get_field( 'post_mode_selector' );
$link_contact_person = get_field( 'link_contact_person' ) ? get_field( 'link_contact_person' ) : false;
$boo_plan_status = get_field( 'plan_status' );
$contact_person_name = get_field( 'select_team_member', get_the_ID() );
$contact_phone_number = get_field( 'phone_number', $contact_person_name->ID );
$contact_email_address = get_field( 'email_address', $contact_person_name->ID );

$startTime = new DateTime( $avbrott_startar );
$start_time_only = $startTime->format( 'H:i' );

$endTime = new DateTime( $avbrott_avslutas );
$end_time_only = $endTime->format( 'H:i' );
?>

<div class="single-notification-result">
	<div class="single-notification-result-top">
		<div class="single-notification-result-date">
			<img src="/app/uploads/2024/12/calendar.svg">
			<p><?php echo get_the_date(); ?></p>
		</div>
		<?php if ( ! empty( $boo_plan_status ) ) : ?>
			<div class="single-notification-result-type">
				<p><?php echo $boo_plan_status; ?></p>
			</div>
		<?php endif; ?>
	</div>
	<div class="single-notification-result-middle">
		<div class="single-notification-result-title">
			<h4>
				<?php the_title(); ?>
			</h4>
		</div>
		<div class="single-notification-result-informartion">
			<p><?php echo esc_html__( 'Område: ', 'boo-energy' ) . '<span>' . $boo_omrade . '</span>'; ?>
			</p>
			<p>
				<?php echo esc_html__( 'Avbrott startar: ', 'boo-energy' ) . '<span>' . $start_time_only . '</span>'; ?>
			</p>
			<p>
				<?php echo esc_html__( 'Avbrott avslutas: ', 'boo-energy' ) . '<span>' . $end_time_only . '</span>'; ?>
			</p>
		</div>
		<div class="single-notification-result-desc">
			<p><?php the_content(); ?></p>
		</div>
		<?php if ( $link_contact_person ) : ?>
			<div class="single-notification-result-btn">
				<div class="single-notification-contact-area d-flex flex-row justify-content-between">
					<p><a href="#"><?php echo esc_html__( 'Kontakt', 'boo-energy' ); ?></a></p>
					<img src="<?php echo BOO_THEME_IMG_DIR . 'arrow-down.svg'; ?>">
				</div>
				<div style="display:none" class="single-notification-result-main">
					<p><?php echo esc_html__( $contact_person_name->post_title, 'boo-energy' ); ?></p>
					<p><a target="_blank" href="tel:<?php echo $contact_phone_number ?>">
							<?php echo esc_html__( 'Telefon: ' . $contact_phone_number, 'boo-energy' ); ?></a></p>
					<p><a target="_blank"
							href="mailto:<?php echo esc_attr( $contact_email_address ); ?>"><?php echo esc_html__( 'Mailadress: ' . $contact_email_address, 'boo-energy' ); ?></a>
					</p>
					<p></p>
				</div>
			</div>
		<?php endif; ?>

	</div>
</div>