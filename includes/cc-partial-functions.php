<?php

function cc_primary_image_for_product( $post_id, $size = 'medium' ) {
    $primary_src = '';
    $images = get_images_src( $size, false, $post_id );
    if ( is_array( $images ) ) {
        $primary = array_shift( $images );
        if ( is_array( $primary ) && isset( $primary[0] ) ) {
            $primary_src = $primary[0];
        }
    }

    return $primary_src;
}

function cc_filter_product_single( $content ) {
    global $post;
    $post_type = get_post_type();

    if ( is_single() && 'cc_product' == $post_type ) {
        $images = get_images_src( 'medium', false, $post->ID );
        $primary = array_shift( $images );
        $primary_src = $primary[0];

        CC_Log::write( 'Attached images for post id: ' . $post->ID . "\n:Primary image src: $primary_src\n" . print_r( $images, true ) );

        $single_product_view = CC_View::get( CC_PATH . 'templates/partials/single-product.php', array('primary_image_src' => $primary_src ) );
        $content = $single_product_view . $content;
    } 

    return $content;
}

add_filter( 'the_content', 'cc_filter_product_single' );


/**
 * Include multipel file uploads for product pages
 */
function cc_image_cpt() {
    $cpts = array( 'cc_product' );
    return $cpts;
}

add_filter( 'images_cpt', 'cc_image_cpt' );

/**
 * Set the number of product images that can be uploaded
 */
function cc_set_image_count() {
    $picts = array(
        'product_image_1' => '_product_image_1',
        'product_image_2' => '_product_image_2',
        'product_image_3' => '_product_image_3',
        'product_image_4' => '_product_image_4',
        'product_image_5' => '_product_image_5',
    );

    return $picts;
}

add_filter( 'list_images', 'cc_set_image_count' );
