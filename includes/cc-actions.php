<?php

/**
 * Enqueue the javascript file used for client side product loading
 *
 * This script is only enqued if:
 *   - the product loader type is client
 *   - the $post is a WP_Post
 *   - the post's content has the shortcode cc_product
 */
function cc_enqueue_cart66_wordpress_js() {
    global $post;
    $product_loader = CC_Admin_Setting::get_option( 'cart66_main_settings', 'product_loader' );
    if( 'client' == $product_loader && is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'cc_product' ) ) {
        $cloud = new CC_Cloud_API_V1();
        $source = $cloud->protocol . 'manage.' . $cloud->app_domain . '/assets/cart66.wordpress.js';
        wp_enqueue_script('cart66-wordpress', $source, 'jquery', '1.0', true);
    }
}

/**
 * Enque javascript to implement ajax add to cart
 *
 * The script is only enqued if :
 *   - the $post is a WP_Post
 *   - the post's content has the shortcode cc_product
 */
function cc_enqueue_ajax_add_to_cart() {
    global $post;
    if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'cc_product' ) ) {
        wp_enqueue_script(
            'cc-add-to-cart',
            CC_URL . 'resources/js/add-to-cart.js',
            array( 'jquery' )
        );
        $ajax_url = admin_url('admin-ajax.php');
        wp_localize_script('cc-add-to-cart', 'cc_cart', array('ajax_url' => $ajax_url));
    }
}