<?php
/**
 * The template used for displaying product content
 *
 * @package Reality66
 * @since 2.0
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<header class="entry-header">
            
			<h1 class="entry-title"><?php the_title(); ?></h1>

		</header>

		<div class="entry-content">

            <div style="float: left; width: 40%; height: 300px;">
                <?php the_post_thumbnail(); ?>
            </div>

            <div style="float: right; width: 40%;">
                <?php
                    $product_id = get_post_meta( get_the_ID(), 'cc_product_id', true );
                    echo do_shortcode( '[cc_product sku="' . $product_id[0] . '" quantity="true" price="true"]' );                    
                ?>
            </div>

            <div style="clear:both"></div>

            <?php the_content(); ?>

			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'twentytwelve' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->

		<footer class="entry-meta">
			<?php edit_post_link( __( 'Edit', 'twentytwelve' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->

	</article><!-- #post -->

