<div class="cc-product-box">

    <div class="cc-gallery">
        <div class="cc-gallery-product-image">
            <?php foreach( $images as $key => $image_src ): ?>
                <?php if ( 'image1' == $key ): ?>
                    <img class="cc-gallery-full-image" id="cc-full-<?php echo $key; ?>" src="<?php echo $image_src; ?>" />
                <?php else: ?>
                    <img class="cc-gallery-full-image" id="cc-full-<?php echo $key; ?>" style="display:none;" src="<?php echo $image_src; ?>" />
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <div class="cc-gallery-gutter">
            <?php foreach( $thumbs as $key => $thumb_src ): ?>
                <a href="#" class="cc-gallery-thumb-link" id="cc-gallery-thumb-<?php echo $key; ?>" data-ref="cc-full-<?php echo $key; ?>"><img class="cc-gallery-thumb" src="<?php echo $thumb_src; ?>" /></a>
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
