<li class="cc-product-grid-item">

    <div class="cc-product-grid-image-container">
        <?php the_post_thumbnail( 200, array( 'class' => 'cc-grid-item-image'  ) ); ?>
    </div>

    <p class="cc-product-grid-title"><?php the_title(); ?></p>

    <a class="cc-button-primary" href="<?php echo get_permalink(); ?>"><?php _e( 'View Details', 'cart66' ); ?></a>

</li>
