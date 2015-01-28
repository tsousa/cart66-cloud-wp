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

    <a class="cc-button-primary" href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>"><?php _e( 'View Details', 'cart66' ); ?></a>

</li>
