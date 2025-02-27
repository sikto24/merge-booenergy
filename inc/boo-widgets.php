<?php

function boo_energy_widgets_init() {
	register_sidebar(
		array(
			'name' => esc_html__( 'Footer 1', 'boo-energy' ),
			'id' => 'footer-1',
			'description' => esc_html__( 'Add widgets here.', 'boo-energy' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s boo-reset-ul">',
			'after_widget' => '</div>',
			'before_title' => '<h6 class="boo-footer-widget-title pb-16">',
			'after_title' => '</h6>',
		)
	);
	register_sidebar(
		array(
			'name' => esc_html__( 'Footer 2', 'boo-energy' ),
			'id' => 'footer-2',
			'description' => esc_html__( 'Add widgets here.', 'boo-energy' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s boo-reset-ul">',
			'after_widget' => '</div>',
			'before_title' => '<h6 class="boo-footer-widget-title pb-16">',
			'after_title' => '</h6>',
		)
	);
	register_sidebar(
		array(
			'name' => esc_html__( 'Footer 3', 'boo-energy' ),
			'id' => 'footer-3',
			'description' => esc_html__( 'Add widgets here.', 'boo-energy' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s boo-reset-ul">',
			'after_widget' => '</div>',
			'before_title' => '<h6 class="boo-footer-widget-title pb-16">',
			'after_title' => '</h6>',
		)
	);
	register_sidebar(
		array(
			'name' => esc_html__( 'Footer 4', 'boo-energy' ),
			'id' => 'footer-4',
			'description' => esc_html__( 'Add widgets here.', 'boo-energy' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s boo-reset-ul">',
			'after_widget' => '</div>',
			'before_title' => '<h6 class="boo-footer-widget-title d-pb-32">',
			'after_title' => '</h6>',
		)
	);
	register_sidebar(
		array(
			'name' => esc_html__( 'Footer 5', 'boo-energy' ),
			'id' => 'footer-5',
			'description' => esc_html__( 'Add widgets here.', 'boo-energy' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s boo-reset-ul">',
			'after_widget' => '</div>',
			'before_title' => '<h6 class="boo-footer-widget-title d-pb-24">',
			'after_title' => '</h6>',
		)
	);
}
add_action( 'widgets_init', 'boo_energy_widgets_init' );

