<?php

function cc_primary_image_for_product( $post_id, $size = 'cc-gallery-full' ) {
    $primary_src = '';
    $images = cc_get_product_image_sources( $size, $post_id );
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
        $thumbs = cc_get_product_thumb_sources( 'cc-gallery-thumb', $post->ID );
        $images = cc_get_product_image_sources( 'cc-gallery-full', $post->ID );
        $primary = array_shift( $images );
        $primary_src = $primary[0];
        $data = array( 'primary_image_src' => $primary_src, 'thumbs' => $thumbs );
        $single_product_view = CC_View::get( CC_PATH . 'templates/partials/single-product.php', $data );
        $content = $single_product_view . $content;
    } 

    return $content;
}

add_filter( 'the_content', 'cc_filter_product_single' );
