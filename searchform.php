<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
	<input required id="search-input" type="search" class="search-field"
		placeholder="<?php echo esc_html__( 'Skriv ett sökord', 'boo_energy' ) ?>"
		value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_html__( 'Search for:', 'label' ) ?>" />
	<input type="submit" class="search-submit" value="<?php echo esc_html__( 'Sök', 'boo-energy' ) ?>" />
	<?php if ( ! wp_is_mobile() ) : ?>
		<div id="search-suggestions" class="search-suggestions"></div>
	<?php endif; ?>
</form>