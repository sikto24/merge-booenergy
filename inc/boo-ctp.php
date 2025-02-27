<?php
/**
 * Add Boo Energy Main Menu
 */
function boo_menu_top_level() {
	add_menu_page(
		__( "Boo Energi", "boo-energy" ),
		__( "Boo Energi", "boo-energy" ),
		"edit_boos", // Custom capability for Editors and Administrators.
		"boo-main-menu",
		"",
		"/app/uploads/2024/11/boo.svg",
		4
	);
}
add_action( "admin_menu", "boo_menu_top_level" );

/**
 * Register Custom Post Types
 */
function boo_register_custom_post_types() {
	$post_types = [ 
		'notification' => [ 
			'name' => 'Driftstatus',
			'menu_name' => 'Driftstatus',
			'supports' => [ 'title', 'editor', 'revisions' ],
		],
		'studion' => [ 
			'name' => 'Boo Studion',
			'menu_name' => 'Boo Studion',
			'supports' => [ 'title', 'editor', 'thumbnail', 'revisions' ],
		],
		'skolan' => [ 
			'name' => 'Boo-Skolan',
			'menu_name' => 'Boo-Skolan',
			'supports' => [ 'title', 'editor', 'thumbnail', 'revisions' ],
			'taxonomy' => [ 
				'name' => 'skolan_category',
				'hierarchical' => true,
			],
			'custom_skolan_tag' => 'skolan-tag', // Custom tag for Skolan
		],
		'team_members' => [ 
			'name' => 'Team Members',
			'menu_name' => 'Team Members',
			'supports' => [ 'title', 'thumbnail', 'revisions' ],
		],
		'campaigns' => [ 
			'name' => 'Coupon Codes',
			'menu_name' => 'Coupon Codes',
			'supports' => [ 'title', 'editor', 'revisions' ],
			'public' => false,
			'show_in_rest' => false,
		],
		'faq' => [ 
			'name' => 'FAQs',
			'menu_name' => 'FAQs',
			'supports' => [ 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ],
		],
		'new_connection' => [ 
			'name' => 'New Connection',
			'menu_name' => 'New Connections',
			'supports' => [ 'title', 'revisions' ],
		],
		'electricity_grid' => [ 
			'name' => 'Electricity Grid',
			'menu_name' => 'Electricity Grids',
			'supports' => [ 'title', 'revisions' ],
		],
	];

	foreach ( $post_types as $key => $details ) {
		$labels = [ 
			'name' => esc_html__( $details['name'], 'boo-energy' ),
			'singular_name' => esc_html__( $details['name'], 'boo-energy' ),
			'menu_name' => esc_html__( $details['menu_name'], 'boo-energy' ),
		];
		$args = [ 
			'label' => esc_html__( $details['name'], 'boo-energy' ),
			'labels' => $labels,
			'description' => "Add {$details['name']} Here",
			'public' => $details['public'] ?? true,
			'publicly_queryable' => $details['publicly_queryable'] ?? true,
			'show_ui' => true,
			'show_in_menu' => in_array( $key, [ 'skolan', 'faq' ] ) ? true : 'boo-main-menu',
			'has_archive' => in_array( $key, [ 'skolan', 'faq' ] ) ? true : false,
			'hierarchical' => $details['hierarchical'] ?? false,
			'can_export' => false,
			'supports' => $details['supports'],
			'capability_type' => 'post',
			'capabilities' => [ 
				'edit_post' => 'edit_boos',
				'edit_posts' => 'edit_boos',
				'edit_others_posts' => 'edit_boos',
				'publish_posts' => 'edit_boos',
				'read_post' => 'read_boos',
				'read_private_posts' => 'read_boos',
				'delete_post' => 'delete_boos',
			],
		];

		register_post_type( $key, $args );


		if ( isset( $details['custom_skolan_tag'] ) ) {
			$custom_tag = $details['custom_skolan_tag'];
			register_taxonomy(
				$custom_tag,
				$key,
				[ 
					'hierarchical' => true, // Behaves like tags
					'labels' => [ 
						'name' => esc_html__( ucfirst( $custom_tag ), 'boo-energy' ),
						'singular_name' => esc_html__( ucfirst( $custom_tag ), 'boo-energy' ),
						'search_items' => esc_html__( 'Search ' . ucfirst( $custom_tag ), 'boo-energy' ),
						'all_items' => esc_html__( 'All ' . ucfirst( $custom_tag ), 'boo-energy' ),
						'edit_item' => esc_html__( 'Edit ' . ucfirst( $custom_tag ), 'boo-energy' ),
						'update_item' => esc_html__( 'Update ' . ucfirst( $custom_tag ), 'boo-energy' ),
						'add_new_item' => esc_html__( 'Add New ' . ucfirst( $custom_tag ), 'boo-energy' ),
						'new_item_name' => esc_html__( 'New ' . ucfirst( $custom_tag ) . ' Name', 'boo-energy' ),
						'menu_name' => esc_html__( ucfirst( $custom_tag ), 'boo-energy' ),
					],
					'show_ui' => true,
					'show_admin_column' => true,
					'show_in_rest' => false,
				]
			);
		}
	}
}
add_action( 'init', 'boo_register_custom_post_types' );
add_action( 'init', 'register_skolan_category_taxonomy' );

/**
 * Register Skolan Category Taxonomy
 */
function register_skolan_category_taxonomy() {
	register_taxonomy(
		'skolan_category',
		'skolan',
		[ 
			'hierarchical' => true,
			'labels' => [ 
				'name' => esc_html__( 'Skolan Categories', 'boo-energy' ),
				'singular_name' => esc_html__( 'Skolan Category', 'boo-energy' ),
				'search_items' => esc_html__( 'Search Skolan Categories', 'boo-energy' ),
				'all_items' => esc_html__( 'All Skolan Categories', 'boo-energy' ),
				'parent_item' => esc_html__( 'Parent Skolan Category', 'boo-energy' ),
				'parent_item_colon' => esc_html__( 'Parent Skolan Category:', 'boo-energy' ),
				'edit_item' => esc_html__( 'Edit Skolan Category', 'boo-energy' ),
				'update_item' => esc_html__( 'Update Skolan Category', 'boo-energy' ),
				'add_new_item' => esc_html__( 'Add New Skolan Category', 'boo-energy' ),
				'new_item_name' => esc_html__( 'New Skolan Category Name', 'boo-energy' ),
				'menu_name' => esc_html__( 'Skolan Categories', 'boo-energy' ),
			],
			'show_ui' => true,
			'show_admin_column' => true,
			'show_in_rest' => true,
		]
	);
}




// Unregister Post taxonomy and Register taxonomy type with hierarchical true
function make_post_tags_hierarchical() {
	// Unregister the default 'post_tag' taxonomy
	unregister_taxonomy( 'post_tag' );

	// Register the new hierarchical 'post_tag' taxonomy
	register_taxonomy(
		'post_tag',
		'post',
		array(
			'hierarchical' => true,
			'labels' => array(
				'name' => _x( 'Tags', 'taxonomy general name', 'text-domain' ),
				'singular_name' => _x( 'Tag', 'taxonomy singular name', 'text-domain' ),
				'search_items' => __( 'Search Tags', 'text-domain' ),
				'all_items' => __( 'All Tags', 'text-domain' ),
				'parent_item' => __( 'Parent Tag', 'text-domain' ),
				'parent_item_colon' => __( 'Parent Tag:', 'text-domain' ),
				'edit_item' => __( 'Edit Tag', 'text-domain' ),
				'update_item' => __( 'Update Tag', 'text-domain' ),
				'add_new_item' => __( 'Add New Tag', 'text-domain' ),
				'new_item_name' => __( 'New Tag Name', 'text-domain' ),
				'menu_name' => __( 'Tags', 'text-domain' ),
			),
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'tag' ),
		)
	);
}
add_action( 'init', 'make_post_tags_hierarchical', 0 );




/**
 * Summary of cptui_register_my_taxes
 * add custom taxonomy for faq post type
 * @return void
 */
function cptui_register_my_taxes() {

	/**
	 * Taxonomy: Faq Categories.
	 */

	$labels = [ 
		"name" => esc_html__( "Faq Categories", "boo-energy" ),
		"singular_name" => esc_html__( "Faq Categorie", "boo-energy" ),
	];


	$args = [ 
		"label" => esc_html__( "Faq Categories", "boo-energy" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'faq-cat', 'with_front' => true,],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "faq-cat",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];
	register_taxonomy( "faq-cat", [ "faq" ], $args );
}
add_action( 'init', 'cptui_register_my_taxes' );