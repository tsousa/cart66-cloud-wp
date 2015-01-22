<?php
/**
 * The Template for displaying all single products
 *
 * @package Cart66/Templates
 * @since 2.0
 */

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php cc_get_template_part( 'content', 'product' ); ?>

				<?php //comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
