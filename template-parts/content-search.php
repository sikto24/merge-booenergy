<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Boo_Energy
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="search-result-header">
		<?php the_title( sprintf( '<h6 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h6>' ); ?>
	</header>
	<div class="search-result-header-content">
		<?php the_excerpt(); ?>
	</div>
</article>