<?php

/**
 * Return true if the global $post is a WP_Post and the content contains a cc_product shortcode
 *
 * @return boolean
 */
function cc_page_has_products() {
    global $post;
    $has_products = false;
    $post_type = get_post_type();

    // Check if this is the cart66 product post type
    if( 'cc_product' == $post_type ) {
        $has_products = true;
    } 
    // Check if this is a post containing a cart66 product shortcode
    elseif ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'cc_product' )  ) {
        $has_products = true;
    }

    return $has_products;
}

/**
 * Enqueue the javascript file used for client side product loading
 *
 * This script is only enqued if:
 *   - the product loader type is client
 *   - the $post is a WP_Post
 *   - the post's content has the shortcode cc_product
 */
function cc_enqueue_cart66_wordpress_js() {
    $product_loader = CC_Admin_Setting::get_option( 'cart66_main_settings', 'product_loader' );
    $post_type = get_query_var( 'post_type' );

    if( 'cc_product' == $post_type || ( 'client' == $product_loader && cc_page_has_products() ) ) {
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
    if( cc_page_has_products() ) {
        wp_enqueue_script(
            'cc-add-to-cart',
            CC_URL . 'resources/js/add-to-cart.js',
            array( 'jquery' )
        );
        $ajax_url = admin_url('admin-ajax.php');
        wp_localize_script('cc-add-to-cart', 'cc_cart', array('ajax_url' => $ajax_url));
    }
}

/**
 * Enque cart66 styles for basic product layout
 */
function cc_enqueue_cart66_styles() {
    wp_enqueue_style( 'cart66-wp', CC_URL . 'resources/css/cart66-wp.css' );
    wp_enqueue_style( 'cart66-templates', CC_URL . 'templates/css/cart66-templates.css' );
}

/**
 * Write custom css to the head if there is custom css saved in the cart66 main settings
 */
function cc_custom_css() {
    $css = CC_Admin_Setting::get_option( 'cart66_main_settings', 'custom_css' );
    if( ! empty( $css ) ) {
        $styles = '<style type="text/css">' . $css . '</style>';
        echo $styles;
    }
}


function cc_theme_support_notice() {
    if ( CC_Admin_Notifications::show( 'cart66_theme_support' ) ) {
        $dismiss_message = __( 'Dismiss this message', 'cart66' );
        ?>
        <div class="error">
            <p> 
                <?php _e( 'The active theme does not declare support for Cart66', 'cart66' ); ?> 
                <a href="<?php echo add_query_arg( 'cc_task', 'dismiss_notification_theme_support' ); ?>" class="button" style="margin-left: 25px;" ><?php echo $dismiss_message ?></a>
            </p>
        </div>
        <?php
    }
}

function cc_task_dispatcher() {
    $task = cc_get( 'cc_task' );
    CC_Log::write( "Task dispatcher found: $task" );

    if ( $task ) {
        switch ( $task ) {
            case 'dismiss_notification_theme_support':
                CC_Admin_Notifications::dismiss( 'cart66_theme_support' );
                break;
            case 'download_log':
                CC_Log::download();
                break;
            case 'reset_log':
                CC_Log::reset();
                break;
            case 'test_remote_calls':
                $tests = new CC_Cloud_Remote_Check();
                $tests->run();
                break;
        }
    }

}
