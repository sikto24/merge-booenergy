<?php
/**
 * Custom Breadcrumbs for the theme.
 *
 * @package     Boo_Energy
 * @since       Boo_Energy 1.0.0
 */

function boo_energy_breadcrumb_markup() {
	global $post;

	if ( is_home() ) {
		$blog_page_id = get_option( 'page_for_posts' );
	} else {
		$blog_page_id = get_the_ID();
	}


	if ( is_single() && get_post_type() == 'post' ) {
		$single_blog_class = 'single-blog-style';
	} elseif ( is_single() && get_post_type() == 'skolan' ) {
		$single_blog_class = 'single-skolan-blog-style';
	} else {
		$single_blog_class = '';
	}


	if ( is_single() ) {
		$title = get_the_title();
	} elseif ( is_home() ) {
		$title = esc_html__( 'Aktuellt', 'boo-energy' );

	} elseif ( is_search() ) {
		$title = esc_html__( 'Search Results for : ', 'boo-energy' ) . get_search_query();
	} elseif ( is_404() ) {
		$title = esc_html__( 'Page not Found', 'boo-energy' );
	} else {
		$title = get_the_title();
	}

	// Get custom ACF fields for the blog page if on the blog page, otherwise get for the current page
	$enable_description = ( get_field( "enable_description", $blog_page_id ) === true ) ? true : false;
	$page_description = get_field( "page_description", $blog_page_id ) ?: "";
	$enable_breadcrumb_secendary_color = ( get_field( "enable_breadcrumb_secendary_color", $blog_page_id ) === true ) ? true : false;
	$boo_enable_cs_button = ( get_field( "enable_cs_button", $blog_page_id ) === true ) ? true : false;
	$boo_button_cs_text = get_field( 'button_cs_text', $blog_page_id ) ? get_field( 'button_cs_text', $blog_page_id ) : '';
	$boo_button_cs_url = get_field( 'button_cs_url', $blog_page_id ) ? get_field( 'button_cs_url', $blog_page_id ) : '';

	$boo_custom_page_title_cs = get_field( 'enable_custom_page_title', $blog_page_id );
	$boo_page_title_cs = get_field( 'page_title', $blog_page_id );

	$boo_custom_page_title = ( ! empty( $boo_page_title_cs ) && $boo_custom_page_title_cs ) ? $boo_page_title_cs : $title;

	// Set color and background image based on page type and ACF fields
	$breadcrumb_bg_color = ( true === $enable_breadcrumb_secendary_color ) ? 'var(--e-global-color-secondary)' : 'var(--e-global-color-primary)';
	$breadcrumb_class_btn = ( true === $enable_breadcrumb_secendary_color ) ? 'boo-btn boo-btn-secondary' : 'boo-btn';
	$pageTitleColor = ( true === $enable_breadcrumb_secendary_color ) ? 'var(--e-global-color-3de5e34)' : 'var(--e-global-color-3de5e34)';
	$breadcrumb_bg_image = ( has_post_thumbnail() && ! is_home() ) ? get_the_post_thumbnail_url() : BOO_THEME_IMG_DIR . 'breadcrumb-b2b.svg';
	$breadcrumb_class = ( has_post_thumbnail() && ! is_home() ) ? 'boo-single-post-breadcrumb' : 'boo-default-breadcrumb';

	// Start breadcrumb markup
	?>

	<section style="background-color:<?php echo $breadcrumb_bg_color; ?>;"
		class="breadcrumb-area-wrapper d-flex <?php echo esc_attr( $single_blog_class . ' ' . $breadcrumb_class ) ?>">
		<div class="breadcrumb-area section_px_left">
			<!-- Start Boo Tag -->
			<?php
			if ( function_exists( 'boo_tags' ) && is_single( $blog_page_id ) ) {
				$post_type = get_post_type();
				boo_tags( $post_type );
			} ?>
			<!-- End Boo Tag -->
			<h1 style="color:<?php echo $pageTitleColor; ?>"><?php echo esc_html( $boo_custom_page_title ); ?></h1>
			<?php
			if ( $enable_description ) {
				echo wpautop( $page_description );
			}
			if ( true === $boo_enable_cs_button && ! empty( $boo_button_cs_url ) && ! empty( $boo_button_cs_text ) ) : ?>
				<a href="<?php echo esc_url( $boo_button_cs_url ) ?>" class="<?php echo esc_attr( $breadcrumb_class_btn ); ?>">
					<?php echo esc_html__( $boo_button_cs_text, 'boo-energy' ); ?>
				</a>
				<?php
			endif;
			?>
		</div>
		<div class="breadcrumb-area-img d-flex justify-content-end">
			<img src="<?php echo esc_url( $breadcrumb_bg_image ); ?>"
				alt="<?php echo esc_attr( $boo_custom_page_title ); ?>">
		</div>
	</section>
	<?php
}

function add_breadcrumb_for_non_front_page() {
	if ( ! is_front_page() && ! is_page( 'foretag' ) && 'elementor_header_footer' !== get_page_template_slug() && ! is_404() && ! is_search() ) {
		add_action( 'boo_before_main_content', 'boo_energy_breadcrumb_markup' );
	}
}
add_action( 'wp', 'add_breadcrumb_for_non_front_page' );

function boo_search_mockup() {
	?>


	<div class="boo-search-bar-area-wrapper">
		<div class="row boo-search-bar-top">
			<div class="col-md-8 col-12">
				<div class="search-bar-top-left">
					<h6><?php echo esc_html__( 'Vad letar du efter?', 'boo-energy' ); ?></h6>
				</div>
			</div>
			<div class="col-md-4 col-12">
				<div class="search-bar-top-right justify-content-end d-flex">
					<span class="search-close-btn"><?php echo esc_html__( 'Stäng', 'boo-enegery' ); ?> <img
							src="<?php echo BOO_THEME_IMG_DIR . 'closed.svg'; ?>" alt=""></span>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="boo-search-bar">
					<?php get_search_form(); ?>
				</div>
			</div>
		</div>
	</div>


	<?php
}
add_action( 'boo_before_main_content', 'boo_search_mockup' );