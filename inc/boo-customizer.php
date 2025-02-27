<?php

/**
 * Summary of boo_sanitize_list
 * @param mixed $input
 * @return string
 */
function boo_sanitize_list_for_lists( $input ) {
	$allowed_tags = array(
		'ul' => array(),
		'li' => array(),
	);

	return wp_kses( $input, $allowed_tags );
}


/**
 * Summary of boo_custom_customizer
 * @param mixed $wp_customize
 * @return void
 */
function boo_custom_customizer( $wp_customize ) {
	// Main Panel
	$wp_customize->add_panel(
		'boo_theme_panel',
		array(
			'title' => esc_html__( 'Boo Theme Panel', 'boo-energy' ),
			'description' => esc_html__( 'Custom Panel for Boo Energy', 'boo-energy' ),
			'priority' => 10,
		)
	);

	// Call sub-functions to add sections and settings
	boo_general_options( $wp_customize );
	boo_social_options( $wp_customize );
	boo_footer_options( $wp_customize );
	boo_footer_store( $wp_customize );
	boo_single_blog_layout_controls( $wp_customize );
	boo_login_store( $wp_customize );
	boo_placeholder_img( $wp_customize );
	boo_iphone_laptop_image( $wp_customize );
	boo_purchase_flow_fields( $wp_customize );
	boo_purchase_flow_fields_business( $wp_customize );
	boo_b2b_purchase_flow( $wp_customize );
	boo_b2c_purchase_flow( $wp_customize );

}
add_action( 'customize_register', 'boo_custom_customizer' );

// General Options
function boo_general_options( $wp_customize ) {
	$wp_customize->add_section(
		'boo_general_section',
		array(
			'title' => esc_html__( 'General Options', 'boo-energy' ),
			'description' => esc_html__( 'General settings for Boo Energy', 'boo-energy' ),
			'panel' => 'boo_theme_panel',
		)
	);

	$wp_customize->add_setting(
		'boo_preloader_switcher',
		array(
			'default' => false,
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_preloader_switch_control',
			array(
				'label' => esc_html__( 'Enable Preloader', 'boo-energy' ),
				'description' => esc_html__( 'Enable or disable the preloader for the theme', 'boo-energy' ),
				'section' => 'boo_general_section',
				'settings' => 'boo_preloader_switcher',
				'type' => 'checkbox',
			)
		)
	);

}

// Social Options
function boo_social_options( $wp_customize ) {
	$wp_customize->add_section(
		'boo_social_section',
		array(
			'title' => esc_html__( 'Social Links', 'boo-energy' ),
			'description' => esc_html__( 'Add and reorder social links for Boo Energy', 'boo-energy' ),
			'panel' => 'boo_theme_panel',
		)
	);

	$social_links = array( 'instagram', 'facebook' );

	foreach ( $social_links as $social ) {
		$setting_id = 'boo_social_' . $social . '_link';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default' => '',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			$setting_id,
			array(
				'label' => esc_html__( ucfirst( $social ) . ' URL', 'boo-energy' ),
				'section' => 'boo_social_section',
				'type' => 'url',
			)
		);
	}
}

// Footer Options
function boo_footer_options( $wp_customize ) {
	// Footer Section
	$wp_customize->add_section(
		'boo_footer_section',
		array(
			'title' => esc_html__( 'Footer', 'boo-energy' ),
			'description' => esc_html__( 'Options for the footer area of Boo Energy', 'boo-energy' ),
			'panel' => 'boo_theme_panel',
		)
	);

	// Footer CTA Heading
	$wp_customize->add_setting(
		'boo_footer_cta_heading',
		array(
			'default' => 'Vi är stolta över att vara små',
			'transport' => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'boo_footer_cta_heading',
		array(
			'label' => esc_html__( 'Heading', 'boo-energy' ),
			'description' => esc_html__( 'Add a custom footer call-to-action heading.', 'boo-energy' ),
			'section' => 'boo_footer_section',
			'type' => 'text',
		)
	);

	// Footer CTA Description
	$wp_customize->add_setting(
		'boo_footer_cta_desc',
		array(
			'default' => 'Vi är lokalt förankrade i Saltsjöö-Boo men tillgängliga för hela Sverige',
			'transport' => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'boo_footer_cta_desc',
		array(
			'label' => esc_html__( 'Details', 'boo-energy' ),
			'description' => esc_html__( 'Add a custom footer call-to-action details.', 'boo-energy' ),
			'section' => 'boo_footer_section',
			'type' => 'textarea',
		)
	);

	// Footer CTA Image
	$wp_customize->add_setting(
		'boo_footer_cta_img',
		array(
			'default' => BOO_THEME_IMG_DIR . 'boo-energy-flag.svg',
			'transport' => 'postMessage',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'boo_footer_cta_img',
			array(
				'label' => esc_html__( 'Image', 'boo-energy' ),
				'description' => esc_html__( 'Add a custom footer call-to-action image.', 'boo-energy' ),
				'section' => 'boo_footer_section',
				'settings' => 'boo_footer_cta_img',
			)
		)
	);

	// Footer CTA BG Image
	$wp_customize->add_setting(
		'boo_footer_cta_bg_img',
		array(
			'default' => BOO_THEME_IMG_DIR . 'Boo-Energi-Karta.svg',
			'transport' => 'postMessage',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'boo_footer_cta_bg_img',
			array(
				'label' => esc_html__( 'Background Image', 'boo-energy' ),
				'description' => esc_html__( 'Add a custom footer call-to-action Bg image.', 'boo-energy' ),
				'section' => 'boo_footer_section',
				'settings' => 'boo_footer_cta_bg_img',
			)
		)
	);

	// Footer CTA Button Text
	$wp_customize->add_setting(
		'boo_footer_cta_button_text',
		array(
			'default' => '',
			'transport' => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'boo_footer_cta_button_text',
		array(
			'label' => esc_html__( 'Button', 'boo-energy' ),
			'description' => esc_html__( 'Add a custom footer call-to-action button text.', 'boo-energy' ),
			'section' => 'boo_footer_section',
			'type' => 'text',
		)
	);

	// Footer CTA Button URL
	$wp_customize->add_setting(
		'boo_footer_cta_button_url',
		array(
			'default' => '',
			'transport' => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'boo_footer_cta_button_url',
		array(
			'label' => esc_html__( 'Button URL', 'boo-energy' ),
			'description' => esc_html__( 'Add a custom footer call-to-action button URL.', 'boo-energy' ),
			'section' => 'boo_footer_section',
			'type' => 'url',
		)
	);
}

// Footer Options
function boo_footer_store( $wp_customize ) {
	// Footer Section
	$wp_customize->add_section(
		'boo_footer_store',
		array(
			'title' => esc_html__( 'Store URL', 'boo-energy' ),
			'description' => esc_html__( 'Options for the footer area of Boo Energy', 'boo-energy' ),
			'panel' => 'boo_theme_panel',
		)
	);


	// Footer App Store Button Text
	$wp_customize->add_setting(
		'boo_footer_app_store_link_text',
		array(
			'default' => '',
			'transport' => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'boo_footer_app_store_link_text',
		array(
			'label' => esc_html__( 'App Store Text', 'boo-energy' ),
			'description' => esc_html__( 'Type Here App Store Text', 'boo-energy' ),
			'section' => 'boo_footer_store',
			'type' => 'text',
		)
	);

	// Footer App Store Button URL
	$wp_customize->add_setting(
		'boo_footer_app_store_url',
		array(
			'default' => '',
			'transport' => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'boo_footer_app_store_url',
		array(
			'label' => esc_html__( 'App Store URL', 'boo-energy' ),
			'description' => esc_html__( 'Add App Store URL', 'boo-energy' ),
			'section' => 'boo_footer_store',
			'type' => 'url',
		)
	);

	// Footer Google Store Button Text
	$wp_customize->add_setting(
		'boo_footer_google_store_link_text',
		array(
			'default' => '',
			'transport' => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'boo_footer_google_store_link_text',
		array(
			'label' => esc_html__( 'Google Store Text', 'boo-energy' ),
			'description' => esc_html__( 'Type Here Google Store Text', 'boo-energy' ),
			'section' => 'boo_footer_store',
			'type' => 'text',
		)
	);

	// Footer App Store Button URL
	$wp_customize->add_setting(
		'boo_footer_google_store_url',
		array(
			'default' => '',
			'transport' => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'boo_footer_google_store_url',
		array(
			'label' => esc_html__( 'Google Store URL', 'boo-energy' ),
			'description' => esc_html__( 'Add Google Store URL', 'boo-energy' ),
			'section' => 'boo_footer_store',
			'type' => 'url',
		)
	);
}

// Single Layout 
function boo_single_blog_layout_controls( $wp_customize ) {

	$wp_customize->add_section(
		'boo_single_blog_layout',
		array(
			'title' => esc_html__( 'Single Blog Layout', 'boo-energy' ),
			'description' => esc_html__( 'Adjust Layout Using Draging', 'boo-energy' ),
			'panel' => 'boo_theme_panel',
		)
	);

	// Add Setting
	$wp_customize->add_setting( 'single_blog_layout_orders', [ 
		'default' => json_encode( [ "publish_date", "content", "skolan_attached_video", "linked_post", "linked_post_skolan", "extra_content" ] ),
		'sanitize_callback' => 'wp_kses_post',
	] );

	// Add Control
	$wp_customize->add_control( new WP_Customize_Control(
		$wp_customize,
		'single_blog_layout_orders',
		[ 
			'section' => 'boo_single_blog_layout',
			'label' => __( 'Arrange Layout', 'boo-energy' ),
			'settings' => 'single_blog_layout_orders',
			'type' => 'hidden', // Managed via JavaScript
		]
	) );
}

// Login URL 
function boo_login_store( $wp_customize ) {
	// Footer Section
	$wp_customize->add_section(
		'boo_login_store',
		array(
			'title' => esc_html__( 'Login URL', 'boo-energy' ),
			'description' => esc_html__( 'Boo Energy login URL', 'boo-energy' ),
			'panel' => 'boo_theme_panel',
		)
	);


	$wp_customize->add_setting(
		'boo_main_menu_top_login',
		array(
			'default' => '',
			'transport' => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'boo_main_menu_top_login',
		array(
			'label' => esc_html__( 'Login Text', 'boo-energy' ),
			'description' => esc_html__( 'Type Here Login Text', 'boo-energy' ),
			'section' => 'boo_login_store',
			'type' => 'text',
			'default' => 'Logga in',
		)
	);

	// Footer App Store Button URL
	$wp_customize->add_setting(
		'boo_main_menu_top_login_url',
		array(
			'default' => '',
			'transport' => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'boo_main_menu_top_login_url',
		array(
			'label' => esc_html__( 'Login URL', 'boo-energy' ),
			'description' => esc_html__( 'Add login URL', 'boo-energy' ),
			'section' => 'boo_login_store',
			'type' => 'url',
			'default' => 'https://minasidor.booenergi.se/Login',
		)
	);
}


// PLaceHolder Image  
function boo_placeholder_img( $wp_customize ) {
	// Footer Section
	$wp_customize->add_section(
		'boo_placeholder_img',
		array(
			'title' => esc_html__( 'Placeholder Image', 'boo-energy' ),
			'panel' => 'boo_theme_panel',
		)
	);

	// Add Setting
	$wp_customize->add_setting(
		'boo_placeholder_img_src',
		array(
			'default' => BOO_THEME_IMG_DIR . 'default-placeholder.jpg',
			'transport' => 'postMessage',
		)
	);

	// Add Control
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'boo_placeholder_img_src',
			array(
				'label' => esc_html__( 'Placeholder Image', 'boo-energy' ),
				'description' => esc_html__( 'Add here your placeholder image.', 'boo-energy' ),
				'section' => 'boo_placeholder_img',
			)
		)
	);
}


// iPhone Image & Laptop Image
function boo_iphone_laptop_image( $wp_customize ) {
	// Footer Section
	$wp_customize->add_section(
		'boo_iphone_laptop_image',
		array(
			'title' => esc_html__( 'iPhone Image & Laptop Image', 'boo-energy' ),
			'panel' => 'boo_theme_panel',
		)
	);

	// Add iPhone image Setting
	$wp_customize->add_setting(
		'boo_iphone_image_src',
		array(
			'default' => BOO_THEME_IMG_DIR . 'mobile-mockup.png',
			'transport' => 'postMessage',
		)
	);

	// Add iPhone image control
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'boo_iphone_image_src',
			array(
				'label' => esc_html__( 'iPhone Image', 'boo-energy' ),
				'description' => esc_html__( 'Add here your iPhone image.', 'boo-energy' ),
				'section' => 'boo_iphone_laptop_image',
			)
		)
	);

	// Add Laptop image Setting
	$wp_customize->add_setting(
		'boo_laptop_image_src',
		array(
			'default' => BOO_THEME_IMG_DIR . 'macbook.png',
			'transport' => 'postMessage',
		)
	);

	// Add iPhone image control
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'boo_laptop_image_src',
			array(
				'label' => esc_html__( 'Laptop Image', 'boo-energy' ),
				'description' => esc_html__( 'Add here your laptop image.', 'boo-energy' ),
				'section' => 'boo_iphone_laptop_image',
			)
		)
	);
}


// B2B Purchase Flow
function boo_b2b_purchase_flow( $wp_customize ) {
	// Footer Section
	$wp_customize->add_section(
		'boo_b2b_purchase_flow',
		array(
			'title' => esc_html__( 'Business Purchase Flow', 'boo-energy' ),
			'panel' => 'boo_theme_panel',
		)
	);

	// Add Title Settings
	$wp_customize->add_setting(
		'boo_b2b_purchase_flow_title',
		array(
			'default' => 'Teckna företagsavtal',
			'transport' => 'postMessage',
		)
	);

	// Add Title Control

	$wp_customize->add_control(
		'boo_b2b_purchase_flow_title',
		array(
			'label' => esc_html__( 'Title', 'boo-energy' ),
			'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
			'section' => 'boo_b2b_purchase_flow',
			'type' => 'textarea',
		)
	);

	// Add Sub title Settings

	$wp_customize->add_setting(
		'boo_b2b_purchase_flow_content',
		array(
			'default' => 'Fyll i dina boendeuppgifter i kalkylatorn för att se dina priser.',
			'transport' => 'postMessage',
		)
	);

	// Add Content Control
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'boo_b2b_purchase_flow_content',
			array(
				'label' => esc_html__( 'Content', 'boo-energy' ),
				'description' => esc_html__( 'Add here your Content here.', 'boo-energy' ),
				'section' => 'boo_b2b_purchase_flow',
				'type' => 'textarea',
			)
		)
	);

	// Add Price Settings
	$wp_customize->add_setting(
		'boo_b2b_purchase_flow_price_values_portfolio',
		array(
			'default' => '',
			'transport' => 'postMessage',
		)
	);

	// Add Price Controls
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'boo_b2b_purchase_flow_price_values_portfolio',
			array(
				'label' => esc_html__( 'Product Id (Portfolio)', 'boo-energy' ),
				'description' => esc_html__( 'Add here your Portfolio Product ID here.', 'boo-energy' ),
				'section' => 'boo_b2b_purchase_flow',
				'type' => 'textarea',
			)
		)
	);


	// Add variable Price Settings
	$wp_customize->add_setting(
		'boo_b2b_purchase_flow_price_values_variable',
		array(
			'default' => '',
			'transport' => 'postMessage',
		)
	);

	// Add Price Controls
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'boo_b2b_purchase_flow_price_values_variable',
			array(
				'label' => esc_html__( 'Product Id (Variable)', 'boo-energy' ),
				'description' => esc_html__( 'Add here your variable Product Id here.', 'boo-energy' ),
				'section' => 'boo_b2b_purchase_flow',
				'type' => 'textarea',
			)
		)
	);

	// Add Fixed Price Settings
	$wp_customize->add_setting(
		'boo_b2b_purchase_flow_price_values_fixed',
		array(
			'default' => '',
			'transport' => 'postMessage',
		)
	);

	// Add Price Controls
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'boo_b2b_purchase_flow_price_values_fixed',
			array(
				'label' => esc_html__( 'Product Id (Fixed)', 'boo-energy' ),
				'description' => esc_html__( 'Add here your Fixed Product Id here.', 'boo-energy' ),
				'section' => 'boo_b2b_purchase_flow',
				'type' => 'textarea',
			)
		)
	);
}


// B2C Purchase Flow
function boo_b2c_purchase_flow( $wp_customize ) {
	// Footer Section
	$wp_customize->add_section(
		'boo_b2c_purchase_flow',
		array(
			'title' => esc_html__( 'Private Purchase Flow', 'boo-energy' ),
			'panel' => 'boo_theme_panel',
		)
	);

	// Add Title Settings
	$wp_customize->add_setting(
		'boo_b2c_purchase_flow_title',
		array(
			'default' => 'Vilket elavtal passar dig?',
			'transport' => 'postMessage',
		)
	);

	// Add Title Control

	$wp_customize->add_control(
		'boo_b2c_purchase_flow_title',
		array(
			'label' => esc_html__( 'Title', 'boo-energy' ),
			'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
			'section' => 'boo_b2c_purchase_flow',
			'type' => 'textarea',
		)
	);

	// Add Sub title Settings

	$wp_customize->add_setting(
		'boo_b2c_purchase_flow_content',
		array(
			'default' => 'Fyll i dina uppgifter och jämför avtal.',
			'transport' => 'postMessage',
		)
	);

	// Add Content Control
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'boo_b2c_purchase_flow_content',
			array(
				'label' => esc_html__( 'Content', 'boo-energy' ),
				'description' => esc_html__( 'Add here your Content here.', 'boo-energy' ),
				'section' => 'boo_b2c_purchase_flow',
				'type' => 'textarea',
			)
		)
	);

	// Add Price Settings
	$wp_customize->add_setting(
		'boo_b2c_purchase_flow_price_values_portfolio',
		array(
			'default' => '',
			'transport' => 'postMessage',
		)
	);

	// Add Price Controls
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'boo_b2c_purchase_flow_price_values_portfolio',
			array(
				'label' => esc_html__( 'Product Id (Portfolio)', 'boo-energy' ),
				'description' => esc_html__( 'Add here your Portfolio Product ID here.', 'boo-energy' ),
				'section' => 'boo_b2c_purchase_flow',
				'type' => 'textarea',
			)
		)
	);


	// Add variable Price Settings
	$wp_customize->add_setting(
		'boo_b2c_purchase_flow_price_values_variable',
		array(
			'default' => '',
			'transport' => 'postMessage',
		)
	);

	// Add Price Controls
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'boo_b2c_purchase_flow_price_values_variable',
			array(
				'label' => esc_html__( 'Product Id (Variable)', 'boo-energy' ),
				'description' => esc_html__( 'Add here your variable Product Id here.', 'boo-energy' ),
				'section' => 'boo_b2c_purchase_flow',
				'type' => 'textarea',
			)
		)
	);

	// Add Fixed Price Settings
	$wp_customize->add_setting(
		'boo_b2c_purchase_flow_price_values_fixed',
		array(
			'default' => '',
			'transport' => 'postMessage',
		)
	);

	// Add Price Controls
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'boo_b2c_purchase_flow_price_values_fixed',
			array(
				'label' => esc_html__( 'Product Id (Fixed)', 'boo-energy' ),
				'description' => esc_html__( 'Add here your Fixed Product Id here.', 'boo-energy' ),
				'section' => 'boo_b2c_purchase_flow',
				'type' => 'textarea',
			)
		)
	);
}

// B2C Purchase Flow Fields
function boo_purchase_flow_fields( $wp_customize ) {
	$wp_customize->add_section(
		'boo_purchase_flow_field',
		array(
			'title' => esc_html__( 'Purchase Flow Fields (Privat)', 'boo-energy' ),
			'panel' => 'boo_theme_panel',
		)
	);

	// Add Title
	$wp_customize->add_setting(
		'boo_purchase_flow_field_portfolio_title',
		array(
			'default' => 'Boo–portföljen',
			'transport' => 'postMessage',
		)
	);

	// Add Title Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_portfolio_title',
			array(
				'label' => esc_html__( 'Boo Portfolio Title', 'boo-energy' ),
				'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field',
				'settings' => 'boo_purchase_flow_field_portfolio_title',
				'type' => 'text',

			)
		)
	);

	// Add Sub Title
	$wp_customize->add_setting(
		'boo_purchase_flow_field_portfolio_sub_title',
		array(
			'default' => 'Boo Energi optimerar priserna',
			'transport' => 'postMessage',
		)
	);

	// Add Sub Title Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_portfolio_sub_title',
			array(
				'label' => esc_html__( 'Boo Portfolio Sub Title', 'boo-energy' ),
				'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field',
				'settings' => 'boo_purchase_flow_field_portfolio_sub_title',
				'type' => 'text',
			)
		)
	);

	// Add Description
	$wp_customize->add_setting(
		'boo_purchase_flow_field_portfolio_desc',
		array(
			'default' => 'Elpriset som visas här baseras alltid på förra månadens elpris, din angivna förbrukning och vart du bor.',
			'transport' => 'postMessage',
		)
	);

	// Add Description Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_portfolio_desc',
			array(
				'label' => esc_html__( 'Boo Portfolio Details', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field',
				'settings' => 'boo_purchase_flow_field_portfolio_desc',
				'type' => 'textarea',
			)
		)
	);


	// Add Lists
	$wp_customize->add_setting(
		'boo_purchase_flow_field_portfolio_desc_list',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => 'boo_sanitize_list_for_lists',
		)
	);

	// Add Lists Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_portfolio_desc_list',
			array(
				'label' => esc_html__( 'Boo Portfolio List Data', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field',
				'settings' => 'boo_purchase_flow_field_portfolio_desc_list',
				'type' => 'textarea',
			)
		)
	);

	// Add Title
	$wp_customize->add_setting(
		'boo_purchase_flow_field_variable_title',
		array(
			'default' => 'Rörligt elpris',
			'transport' => 'postMessage',
		)
	);

	// Add Title Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_variable_title',
			array(
				'label' => esc_html__( 'Boo Variable Title', 'boo-energy' ),
				'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field',
				'settings' => 'boo_purchase_flow_field_variable_title',
				'type' => 'text',

			)
		)
	);

	// Add Sub Title
	$wp_customize->add_setting(
		'boo_purchase_flow_field_variable_sub_title',
		array(
			'default' => 'Priset ändras varje månad',
			'transport' => 'postMessage',
		)
	);

	// Add Sub Title Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_variable_sub_title',
			array(
				'label' => esc_html__( 'Boo Variable Sub Title', 'boo-energy' ),
				'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field',
				'settings' => 'boo_purchase_flow_field_variable_sub_title',
				'type' => 'text',
			)
		)
	);


	// Add Description
	$wp_customize->add_setting(
		'boo_purchase_flow_field_variable_desc',
		array(
			'default' => 'Elpriset som visas här baseras alltid på förra månadens elpris, din angivna förbrukning och vart du bor.',
			'transport' => 'postMessage',
		)
	);

	// Add Description Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_variable_desc',
			array(
				'label' => esc_html__( 'Boo Variable Details', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field',
				'settings' => 'boo_purchase_flow_field_variable_desc',
				'type' => 'textarea',
			)
		)
	);


	// Add Lists
	$wp_customize->add_setting(
		'boo_purchase_flow_field_variable_desc_list',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => 'boo_sanitize_list_for_lists',
		)
	);

	// Add Lists Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_variable_desc_list',
			array(
				'label' => esc_html__( 'Boo Variable List Data', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field',
				'settings' => 'boo_purchase_flow_field_variable_desc_list',
				'type' => 'textarea',
			)
		)
	);

	// Add Title
	$wp_customize->add_setting(
		'boo_purchase_flow_field_fixed_title',
		array(
			'default' => 'Bundet elpris',
			'transport' => 'postMessage',
		)
	);

	// Add Title Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_fixed_title',
			array(
				'label' => esc_html__( 'Boo Fixed Title', 'boo-energy' ),
				'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field',
				'settings' => 'boo_purchase_flow_field_fixed_title',
				'type' => 'text',

			)
		)
	);

	// Add Sub Title
	$wp_customize->add_setting(
		'boo_purchase_flow_field_fixed_sub_title',
		array(
			'default' => 'Priset är samma varje månad',
			'transport' => 'postMessage',
		)
	);

	// Add Sub Title Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_fixed_sub_title',
			array(
				'label' => esc_html__( 'Boo Fixed Sub Title', 'boo-energy' ),
				'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field',
				'settings' => 'boo_purchase_flow_field_fixed_sub_title',
				'type' => 'text',
			)
		)
	);

	// Add Description
	$wp_customize->add_setting(
		'boo_purchase_flow_field_fixed_desc',
		array(
			'default' => 'Elpriset som visas här baseras alltid på förra månadens elpris, din angivna förbrukning och vart du bor. Ett bundet elavtal får du samma elpris oavsett priset på marknaden, fakturan ändras beroende på hur mycket el du använder.',
			'transport' => 'postMessage',
		)
	);

	// Add Description Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_fixed_desc',
			array(
				'label' => esc_html__( 'Boo Fixed Details', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field',
				'settings' => 'boo_purchase_flow_field_fixed_desc',
				'type' => 'textarea',
			)
		)
	);



	// Add Lists
	$wp_customize->add_setting(
		'boo_purchase_flow_field_fixed_desc_list',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => 'boo_sanitize_list_for_lists',
		)
	);

	// Add Lists Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_fixed_desc_list',
			array(
				'label' => esc_html__( 'Boo Fixed List Data', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field',
				'settings' => 'boo_purchase_flow_field_fixed_desc_list',
				'type' => 'textarea',
			)
		)
	);


	// Step Text
	$wp_customize->add_setting(
		'boo_purchase_flow_field_frist_step_title',
		array(
			'default' => 'Fyll i dina uppgifter',
			'transport' => 'postMessage',
		)
	);

	// Add Step Text Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_frist_step_title',
			array(
				'label' => esc_html__( 'Step 1: Title', 'boo-energy' ),
				'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field',
				'settings' => 'boo_purchase_flow_field_frist_step_title',
				'type' => 'text',
			)
		)
	);


	// Step Text
	$wp_customize->add_setting(
		'boo_purchase_flow_field_second_step_title',
		array(
			'default' => 'Vart ska elen?',
			'transport' => 'postMessage',
		)
	);

	// Add Step Text Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_second_step_title',
			array(
				'label' => esc_html__( 'Step 2: Title', 'boo-energy' ),
				'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field',
				'settings' => 'boo_purchase_flow_field_second_step_title',
				'type' => 'text',
			)
		)
	);

	// Step Text
	$wp_customize->add_setting(
		'boo_purchase_flow_field_three_step_title',
		array(
			'default' => 'Anläggningsuppgifter',
			'transport' => 'postMessage',
		)
	);

	// Add Step Text Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_three_step_title',
			array(
				'label' => esc_html__( 'Step 3: Title', 'boo-energy' ),
				'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field',
				'settings' => 'boo_purchase_flow_field_three_step_title',
				'type' => 'text',
			)
		)
	);



	// Step Text
	$wp_customize->add_setting(
		'boo_purchase_flow_field_four_step_title',
		array(
			'default' => 'Anläggningsuppgifter',
			'transport' => 'postMessage',
		)
	);

	// Add Step Text Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_four_step_title',
			array(
				'label' => esc_html__( 'Step 4: Title', 'boo-energy' ),
				'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field',
				'settings' => 'boo_purchase_flow_field_four_step_title',
				'type' => 'text',
			)
		)
	);

}


// B2B Purchase Flow Fields
function boo_purchase_flow_fields_business( $wp_customize ) {
	$wp_customize->add_section(
		'boo_purchase_flow_field_business',
		array(
			'title' => esc_html__( 'Purchase Flow Fields (Företag)', 'boo-energy' ),
			'panel' => 'boo_theme_panel',
		)
	);

	// Add Title
	$wp_customize->add_setting(
		'boo_purchase_flow_field_portfolio_title_business',
		array(
			'default' => 'Boo–portföljen',
			'transport' => 'postMessage',
		)
	);

	// Add Title Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_portfolio_title_business',
			array(
				'label' => esc_html__( 'Boo Portfolio Title', 'boo-energy' ),
				'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field_business',
				'settings' => 'boo_purchase_flow_field_portfolio_title_business',
				'type' => 'text',

			)
		)
	);

	// Add Sub Title
	$wp_customize->add_setting(
		'boo_purchase_flow_field_portfolio_sub_title_business',
		array(
			'default' => 'Boo Energi optimerar priserna',
			'transport' => 'postMessage',
		)
	);

	// Add Sub Title Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_portfolio_sub_title_business',
			array(
				'label' => esc_html__( 'Boo Portfolio Sub Title', 'boo-energy' ),
				'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field_business',
				'settings' => 'boo_purchase_flow_field_portfolio_sub_title_business',
				'type' => 'text',
			)
		)
	);

	// Add Description
	$wp_customize->add_setting(
		'boo_purchase_flow_field_portfolio_desc_business',
		array(
			'default' => 'Elpriset som visas här baseras alltid på förra månadens elpris, din angivna förbrukning och vart du bor.',
			'transport' => 'postMessage',
		)
	);

	// Add Description Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_portfolio_desc_business',
			array(
				'label' => esc_html__( 'Boo Portfolio Details', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field_business',
				'settings' => 'boo_purchase_flow_field_portfolio_desc_business',
				'type' => 'textarea',
			)
		)
	);


	// Add Lists
	$wp_customize->add_setting(
		'boo_purchase_flow_field_portfolio_desc_list_business',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => 'boo_sanitize_list_for_lists',
		)
	);

	// Add Lists Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_portfolio_desc_list_business',
			array(
				'label' => esc_html__( 'Boo Fixed List Data', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field_business',
				'settings' => 'boo_purchase_flow_field_portfolio_desc_list_business',
				'type' => 'textarea',
			)
		)
	);

	// Add Title
	$wp_customize->add_setting(
		'boo_purchase_flow_field_variable_title_business',
		array(
			'default' => 'Rörligt elpris',
			'transport' => 'postMessage',
		)
	);

	// Add Title Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_variable_title_business',
			array(
				'label' => esc_html__( 'Boo Variable Title', 'boo-energy' ),
				'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field_business',
				'settings' => 'boo_purchase_flow_field_variable_title_business',
				'type' => 'text',

			)
		)
	);

	// Add Sub Title
	$wp_customize->add_setting(
		'boo_purchase_flow_field_variable_sub_title_business',
		array(
			'default' => 'Priset ändras varje månad',
			'transport' => 'postMessage',
		)
	);

	// Add Sub Title Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_variable_sub_title_business',
			array(
				'label' => esc_html__( 'Boo Variable Sub Title', 'boo-energy' ),
				'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field_business',
				'settings' => 'boo_purchase_flow_field_variable_sub_title_business',
				'type' => 'text',
			)
		)
	);


	// Add Description
	$wp_customize->add_setting(
		'boo_purchase_flow_field_variable_desc_business',
		array(
			'default' => 'Elpriset som visas här baseras alltid på förra månadens elpris, din angivna förbrukning och vart du bor.',
			'transport' => 'postMessage',
		)
	);

	// Add Description Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_variable_desc_business',
			array(
				'label' => esc_html__( 'Boo Variable Details', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field_business',
				'settings' => 'boo_purchase_flow_field_variable_desc_business',
				'type' => 'textarea',
			)
		)
	);


	// Add Lists
	$wp_customize->add_setting(
		'boo_purchase_flow_field_variable_desc_list_business',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => 'boo_sanitize_list_for_lists',
		)
	);

	// Add Lists Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_variable_desc_list_business',
			array(
				'label' => esc_html__( 'Boo Variable List Data', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field_business',
				'settings' => 'boo_purchase_flow_field_variable_desc_list_business',
				'type' => 'textarea',
			)
		)
	);

	// Add Title
	$wp_customize->add_setting(
		'boo_purchase_flow_field_fixed_title_business',
		array(
			'default' => 'Bundet elpris',
			'transport' => 'postMessage',
		)
	);

	// Add Title Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_fixed_title_business',
			array(
				'label' => esc_html__( 'Boo Fixed Title', 'boo-energy' ),
				'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field_business',
				'settings' => 'boo_purchase_flow_field_fixed_title_business',
				'type' => 'text',

			)
		)
	);

	// Add Sub Title
	$wp_customize->add_setting(
		'boo_purchase_flow_field_fixed_sub_title_business',
		array(
			'default' => 'Priset är samma varje månad',
			'transport' => 'postMessage',
		)
	);

	// Add Sub Title Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_fixed_sub_title_business',
			array(
				'label' => esc_html__( 'Boo Fixed Sub Title', 'boo-energy' ),
				'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field_business',
				'settings' => 'boo_purchase_flow_field_fixed_sub_title_business',
				'type' => 'text',
			)
		)
	);

	// Add Description
	$wp_customize->add_setting(
		'boo_purchase_flow_field_fixed_desc_business',
		array(
			'default' => 'Elpriset som visas här baseras alltid på förra månadens elpris, din angivna förbrukning och vart du bor. Ett bundet elavtal får du samma elpris oavsett priset på marknaden, fakturan ändras beroende på hur mycket el du använder.',
			'transport' => 'postMessage',
		)
	);

	// Add Description Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_fixed_desc_business',
			array(
				'label' => esc_html__( 'Boo Fixed Details', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field_business',
				'settings' => 'boo_purchase_flow_field_fixed_desc_business',
				'type' => 'textarea',
			)
		)
	);

	// Add Lists
	$wp_customize->add_setting(
		'boo_purchase_flow_field_fixed_desc_list_business',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => 'boo_sanitize_list_for_lists',
		)
	);

	// Add Lists Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_fixed_desc_list_business',
			array(
				'label' => esc_html__( 'Boo Fixed List Data', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field_business',
				'settings' => 'boo_purchase_flow_field_fixed_desc_list_business',
				'type' => 'textarea',
			)
		)
	);


	// Step Text
	$wp_customize->add_setting(
		'boo_purchase_flow_field_frist_step_title_business',
		array(
			'default' => 'Fyll i dina uppgifter',
			'transport' => 'postMessage',
		)
	);

	// Add Step Text Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_frist_step_title_business',
			array(
				'label' => esc_html__( 'Step 1: Title', 'boo-energy' ),
				'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field_business',
				'settings' => 'boo_purchase_flow_field_frist_step_title_business',
				'type' => 'text',
			)
		)
	);


	// Step Text
	$wp_customize->add_setting(
		'boo_purchase_flow_field_second_step_title_business',
		array(
			'default' => 'Vart ska elen?',
			'transport' => 'postMessage',
		)
	);

	// Add Step Text Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_second_step_title_business',
			array(
				'label' => esc_html__( 'Step 2: Title', 'boo-energy' ),
				'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field_business',
				'settings' => 'boo_purchase_flow_field_second_step_title_business',
				'type' => 'text',
			)
		)
	);

	// Step Text
	$wp_customize->add_setting(
		'boo_purchase_flow_field_three_step_title_business',
		array(
			'default' => 'Anläggningsuppgifter',
			'transport' => 'postMessage',
		)
	);

	// Add Step Text Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_three_step_title_business',
			array(
				'label' => esc_html__( 'Step 3: Title', 'boo-energy' ),
				'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field_business',
				'settings' => 'boo_purchase_flow_field_three_step_title_business',
				'type' => 'text',
			)
		)
	);



	// Step Text
	$wp_customize->add_setting(
		'boo_purchase_flow_field_four_step_title_business',
		array(
			'default' => 'Anläggningsuppgifter',
			'transport' => 'postMessage',
		)
	);

	// Add Step Text Controls
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'boo_purchase_flow_field_four_step_title_business',
			array(
				'label' => esc_html__( 'Step 4: Title', 'boo-energy' ),
				'description' => esc_html__( 'Add here your title here.', 'boo-energy' ),
				'section' => 'boo_purchase_flow_field_business',
				'settings' => 'boo_purchase_flow_field_four_step_title_business',
				'type' => 'text',
			)
		)
	);

}