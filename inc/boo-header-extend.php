<?php
if ( class_exists( "ACF" ) ) {
	function boo_wp_nav_menu_objects( $items, $args ) {
		foreach ( $items as &$item ) {
			// Get custom fields using ACF
			$recommend = get_field( 'recommend', $item );
			$menu_recommend_text = get_field( 'menu_recommend_text', $item );
			$sub_menu_br = get_field( 'sub_menu_layout_br', $item );
			$keep_on_left_side = get_field( 'keep_on_left_side', $item );
			$boo_custom_add_icon = get_field( 'add_icon', $item );
			$boo_custom_menu_icon = get_field( 'menu_icon', $item );

			// If 'recommend' field is set, append the recommendation text and add class to <li>
			if ( $recommend ) {
				$item->title .= '<span class="boo-recommend">' . $menu_recommend_text . '</span>';

			}

			if ( $sub_menu_br ) {
				$item->classes[] = 'boo-sub-menu-broken-wrapper';
			}

			if ( $keep_on_left_side ) {
				$item->classes[] = 'boo-sub-keep-left';
			}

			if ( true === $boo_custom_add_icon ) {
				$item->title = '<img src="' . $boo_custom_menu_icon . '" class="boo-custom-menu-icon" alt="' . $item->title . '">' . $item->title;
			}

		}
		return $items;
	}

	// Hook into 'wp_nav_menu_objects' to modify menu items
	add_filter( 'wp_nav_menu_objects', 'boo_wp_nav_menu_objects', 10, 2 );

}
/**
 * Summary of WP_Bootstrap_Navwalker_Custom
 */
class WP_Bootstrap_Navwalker_Custom extends Walker_Nav_Menu {

	// Start Level
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );

		// Assign classes and IDs based on depth level
		$submenu_class = $depth === 0 ? 'boo-mega-sub-menu' : 'boo-mega-sub-menu-second';
		$submenu_id = $depth === 0 ? 'id="main-mega-menu"' : 'id="sub-mega-menu"';

		$output .= "\n$indent<ul class=\"$submenu_class\" $submenu_id>\n";
	}

	// End Level
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "$indent</ul>\n";
	}

	// Start Element
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$args = (object) $args; // Ensure $args is treated as an object
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		// Check for classes and apply custom classes
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		if ( in_array( 'menu-item-has-children', $classes ) ) {
			$classes[] = 'menu-item-has-children';
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= $indent . '<li' . $class_names . '>';

		// Set attributes for the link
		$attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';

		// Build the link markup
		$item_output = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';

		// Add description if available
		if ( ! empty( $item->description ) ) {
			$item_output .= '<p>' . esc_html( $item->description ) . '</p>';
		}

		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	// End Element
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}
}