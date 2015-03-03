<div class="cc-product-box">
    <div class="cc-product-image">
        <img src="<?php echo $primary_image_src; ?>" />
    </div>

    <div class="cc-product-form">
        <?php
            $product_sku = get_post_meta( get_the_ID(), '_cc_product_sku', true );
            echo do_shortcode( '[cc_product sku="' . $product_sku . '" quantity="true" price="true" display="vertical" ]' );                    
        ?>
    </div>
</div>

<div style="clear:both;"></div>
