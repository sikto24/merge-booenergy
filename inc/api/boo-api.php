<?php


/**
 * Summary of restrict_rest_api_to_localhost Domain Restriction
 * @return void
 */
// function restrict_rest_api_to_localhost() {
// 	$whitelist = [ '127.0.0.1', "::1" ];

// 	if ( ! in_array( $_SERVER['REMOTE_ADDR'], $whitelist ) ) {
// 		die( 'REST API is disabled.' );
// 	}
// }
// add_action( 'rest_api_init', 'restrict_rest_api_to_localhost', 0 );

/**
 * Summary of get_custom_campaigns For Rest api Init Custom Campaigns
 * 
 * @param WP_REST_Request $request
 * @return WP_Error|WP_REST_Response
 */
add_action( 'rest_api_init', function () {
	register_rest_route( 'boo/v1', '/campaigns', array(
		'methods' => 'GET',
		'callback' => 'get_custom_campaigns',
		'permission_callback' => function () {
			$headers = function_exists( 'getallheaders' ) ? getallheaders() : array();
			if ( isset( $headers['Api-Key'] ) && $headers['Api-Key'] === '$2y$10$/zz1IAiZDYWm0ONhiw.mbetGkJ6S8B7CDtfEzvNa2NFWAZq/u/GQy' ) {
				return true;
			}
			return false;
		}
	) );
} );

/**
 * Get custom campaigns
 * 
 * @param WP_REST_Request $request
 * @return WP_Error|WP_REST_Response
 */
function get_custom_campaigns( $request ) {
	$code = $request->get_param( 'code' );
	if ( empty( $code ) ) {
		return new WP_Error(
			'missing_code_param',
			__( 'The "code" parameter is required.', 'boo-energy' ),
			[ 'status' => 400 ]
		);
	}

	$args = array(
		'post_type' => 'campaigns',
		'post_status' => 'publish',
		'numberposts' => 1,
		'meta_query' => [ 
			[ 
				'key' => 'code',
				'value' => $code,
				'compare' => '==',
			],
		],
	);

	$posts = get_posts( $args );

	if ( empty( $posts ) ) {
		return new WP_Error(
			'no_campaigns_found',
			__( 'No campaigns found for the specified code.', 'boo-energy' ),
			[ 'status' => 404 ]
		);
	}

	$post = $posts[0];
	$meta = get_post_meta( $post->ID );

	$response = [ 
		'id' => $post->ID,
		'code' => isset( $meta['code'][0] ) ? $meta['code'][0] : null,
		'fixed_1' => isset( $meta['fixed_1'][0] ) ? $meta['fixed_1'][0] : null,
		'fixed_2' => isset( $meta['fixed_2'][0] ) ? $meta['fixed_2'][0] : null,
		'fixed_3' => isset( $meta['fixed_3'][0] ) ? $meta['fixed_3'][0] : null,
		'variable' => isset( $meta['variable'][0] ) ? $meta['variable'][0] : null,
		'boo_portfolio' => isset( $meta['boo_portfolio'][0] ) ? $meta['boo_portfolio'][0] : null,
		'title' => get_the_title( $post->ID ),
		'description' => get_the_content( '', false, $post->ID ),
		'created_at' => $post->post_date,
		'updated_at' => $post->post_modified,
	];

	return rest_ensure_response( $response );
}

