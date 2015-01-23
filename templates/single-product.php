<?php
/**
 * The Template for displaying all single products
 *
 * @package Cart66/Templates
 * @since 2.0
 */

get_header(); ?>

<?php do_action( 'cc_before_main_content' ); ?>

<?php while ( have_posts() ) : the_post(); ?>

    <?php cc_get_template_part( 'content', 'product' ); ?>

<?php endwhile; // end of the loop. ?>

<?php do_action( 'cc_after_main_content' ); ?>

<?php //do_action( 'cc_sidebar' ); ?>

<?php 
get_sidebar(); 
get_footer(); 
