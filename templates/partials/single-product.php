<div class="cc-product-box">

    <div class="cc-gallery">
        <div class="cc-gallery-product-image">
            <img src="<?php echo $primary_image_src; ?>" />
        </div>

        <div class="cc-gallery-gutter">
            <?php foreach( $thumbs as $thumb_src ): ?>
                <a href=""><img class="cc-gallery-thumb" src="<?php echo $thumb_src; ?>" /></a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="cc-product-form">
        <?php
            $product_sku = get_post_meta( get_the_ID(), '_cc_product_sku', true );
            echo do_shortcode( '[cc_product sku="' . $product_sku . '" quantity="true" price="true" display="vertical" ]' );                    
        ?>
    </div>
</div>

<div style="clear:both;"></div>
