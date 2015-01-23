<?php
/**
 * The template used for displaying product content
 *
 * @package Reality66
 * @since 2.0
 */
?>

<header class="entry-header">
    <h1 class="entry-title"><?php the_title(); ?></h1>
</header>

<div class="cc_product_box">
    <div class="cc_product_image">
        <?php the_post_thumbnail(); ?>
    </div>

    <div class="cc_product_form">
        <?php
            $product_id = get_post_meta( get_the_ID(), 'cc_product_id', true );
            echo do_shortcode( '[cc_product sku="' . $product_id[0] . '" quantity="true" price="true"]' );                    
        ?>
    </div>
</div>

<div style="clear:both;"></div>

<div class="entry-content">
    <?php the_content(); ?>
</div>
