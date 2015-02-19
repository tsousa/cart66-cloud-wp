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

<div class="cc-product-box">
    <div class="cc-product-image">
        <?php the_post_thumbnail(); ?>
    </div>

    <div class="cc-product-form">
        <?php
            $product_sku = get_post_meta( get_the_ID(), '_cc_product_sku', true );
            echo do_shortcode( '[cc_product sku="' . $product_sku . '" quantity="true" price="true" display="vertical" ]' );                    
        ?>
    </div>
</div>

<div style="clear:both;"></div>

<div class="entry-content">
    <?php the_content(); ?>
</div>
