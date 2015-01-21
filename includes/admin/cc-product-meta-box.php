<?php

add_action( 'load-post.php',     'cc_product_meta_box_setup' );
add_action( 'load-post-new.php', 'cc_product_meta_box_setup' );
add_action( 'wp_ajax_cc_ajax_product_search', 'cc_ajax_product_search' );

function cc_ajax_product_search( ) {
    $products = CC_Cloud_Product::search( $_REQUEST['search'] );
    $options = array(); 

    foreach ( $products as $p ) {
        $options[] = array( 'id' => $p['sku'] . '~~' . $p['name'], 'text' => $p['name'] );
    }

    echo json_encode( $options );
    die();
}

function cc_product_meta_box_setup() {
    add_action( 'add_meta_boxes', 'cc_add_product_meta_box' );
    add_action( 'save_post', 'cc_save_product_meta_box', 10, 2 );

    wp_enqueue_style( 'select2', CC_URL .'resources/js/select2/select2.css' );
    wp_enqueue_script( 'select2', CC_URL . 'resources/js/select2/select2.min.js' );

}

function cc_add_product_meta_box() {
    add_meta_box(
        'cart66-product-box',             // unique id assigned to the meta box
        __( 'Cart66 Product', 'cart66' ), // title for metabox
        'cc_product_meta_box_render',     // callback to display the output for the meta box
        'cc_product',                        // the name of the post type on which to display the meta box
        'side',                           // where on the page to display the meta box (normal, side, advanced)
        'default'                         // priority (default, core, high, low)
    );
}

/**
 * Render the output for the cart66 product meta box on the product post type
 *
 * This function should echo the content
 */
function cc_product_meta_box_render( $post, $box ) {

    $value = get_post_meta( $post->ID, 'cc_product_id', true );

    if ( empty( $value ) ) {
        $value = 'Select Product';
    } else {
        $value = $value[1];
    }

    $data = array( 
        'post' => $post, 
        'box' => $box,
        'value' => $value
    );

    $template = CC_PATH . 'views/admin/html-product-meta-box.php';
    $view = CC_View::get( $template, $data );
    echo $view;
}

/**
 * Save the product id associated with this product post
 */
function cc_save_product_meta_box( $post_id, $post ) {

    /* Verify the nonce before proceeding. */
    if ( !isset( $_POST['cc_product_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['cc_product_meta_box_nonce'], 'cc_product_meta_box' ) ) {
        return $post_id;
    }

    /* Get the post type object. */
    $post_type = get_post_type_object( $post->post_type );

    /* Check if the current user has permission to edit the post. */
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
        return $post_id;

    /* Store the meta key value in the wp_postmeta table */
    cc_store_meta_box_value( $post_id, 'cc_product_id' );
}

/**
 * Function to add, update, or delete post meta
 *
 * Note that the given $meta_key is both the HTML form field name and the 
 * meta_key in the wp_postmeta table
 * 
 * If a new product is selected, the submitted value will be in the format:
 * cloud_id~~product_name
 *
 * If the stored value is being displayed, the submitted value is empty
 *
 * @param int $post_id
 * @param string $meta_key
 */
function cc_store_meta_box_value( $post_id, $meta_key ) {
    // Get the posted data and sanitize it for use as an HTML class.
    $new_meta_value = ( isset( $_POST[ $meta_key ] ) ? sanitize_text_field( $_POST[ $meta_key ] ) : '' );

    CC_Log::write( 'New meta value: ' . $new_meta_value );

    // The value should contain cloud_id~~product_name. If it doesn't then don't do anything
    if ( false !== strpos( $new_meta_value, '~~' ) ) {
        $new_meta_value = explode( '~~', $new_meta_value );

        // Get the meta value of the custom field key.
        $meta_value = get_post_meta( $post_id, $meta_key, true );

        // If a new meta value was added and there was no previous value, add it.
        if ( $new_meta_value && '' == $meta_value )
            add_post_meta( $post_id, $meta_key, $new_meta_value, true );

        // If the new meta value does not match the old value, update it.
        elseif ( $new_meta_value && $new_meta_value != $meta_value )
            update_post_meta( $post_id, $meta_key, $new_meta_value );

        // If there is no new meta value but an old value exists, delete it.
        elseif ( '' == $new_meta_value && $meta_value )
            delete_post_meta( $post_id, $meta_key, $meta_value );
    }

}
