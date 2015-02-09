<li class="cc-product-grid-item">

    <div class="cc-product-grid-image-container">
        <a href="<?php echo get_permalink(); ?>" title="<?php the_title() ?>"><?php the_post_thumbnail( 200, array( 'class' => 'cc-grid-item-image'  ) ); ?></a>
    </div>

    <p class="cc-product-grid-title"><?php the_title(); ?></p>

    <?php 
        $post = get_post();
        if ( ! empty( $post->post_excerpt ) ) :
    ?>
    <p class="cc-product-grid-excerpt"><?= $post->post_excerpt ?></p>
    <?php endif; ?>

    <?php if ( 1 == get_post_meta( $post->ID, '_cc_product_on_sale', true ) ): ?>
        <p class="cc-product-price cc-product-price-sale">
            <span class="cc-product-sale-price-label"><?php _e('On Sale', 'cart66') ?>:</span>
            <span class="cc-product-price-amount"><?php echo get_post_meta( $post->ID, '_cc_product_formatted_price', true ); ?></span>
            <span class="cc-product-price-sale-amount"><?php echo get_post_meta( $post->ID, '_cc_product_formatted_sale_price', true ); ?></span>
        </p>
    <?php else: ?>
        <p class="cc-product-price">
            <span class="cc-product-price-label">Price:</span> 
            <span class="cc-product-price-amount"><?php echo get_post_meta( $post->ID, '_cc_product_formatted_price', true ); ?></span>
        </p>
    <?php endif; ?>

    <a class="cc-button-primary" href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>"><?php _e( 'View Details', 'cart66' ); ?></a>

</li>
