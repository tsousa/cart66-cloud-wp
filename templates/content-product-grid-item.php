<li class="cc_product_grid_item">
    <p class="cc_product_grid_title"><?php the_title(); ?></p>

    <div class="cc_product_grid_image">
        <?php the_post_thumbnail(  ); ?>
    </div>

    <a class="cc_button_primary" href="<?php echo get_permalink(); ?>"><?php _e( 'View Details', 'cart66' ); ?></a>
</li>
