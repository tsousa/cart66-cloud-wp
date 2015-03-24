<?php

/** 
 * Handle admin tasks for cart66
 */
function cc_task_dispatcher() {
    $task = cc_get( 'cc-task', 'key' );
    // CC_Log::write( "Task dispatcher found: $task" );

    if ( $task ) {
        switch ( $task ) {
            case 'dismiss_notification_theme_support':
                CC_Admin_Notifications::dismiss( 'cart66_theme_support' );
                break;
            case 'dismiss_notification_permalinks':
                CC_Admin_Notifications::dismiss( 'cart66_permalinks' );
                break;
            case 'dismiss_notification_migration':
                CC_Admin_Notifications::dismiss( 'cart66_migration' );
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
            case 'create_slurp_page':
                CC_Page_Slurp::create_slurp_page();
                break;
            case 'migrate_settings':
                $migration = new CC_Migration();
                $migration->run();
                break;
        }
    }

}

/**
 * Handle public actions for cart66
 */
function cc_route_handler() {
    global $wp;

    // If the cc-action is not available forget about doing anything else here
    if ( ! isset( $wp->query_vars[ 'cc-action' ] ) ) {
        return;
    }

    $action = $wp->query_vars[ 'cc-action' ];
    CC_Log::write( "Route handler found action: $action" );


    if ( $action ) {
        unset( $wp->query_vars[ 'cc-action' ] );
        $url = new CC_Cloud_URL();
        switch ( $action ) {
            case 'sign-in':
                wp_redirect( $url->sign_in() );
                exit();
                break;
            case 'sign-out':
                if( class_exists( 'CM_Visitor' ) ) {
                    $visitor = new CM_Visitor();
                    $visitor->sign_out();
                }
                wp_redirect( $url->sign_out() );
                exit();
                break;
            case 'view-cart':
                wp_redirect( $url->view_cart( true ) );
                exit();
                break;
            case 'checkout':
                wp_redirect( $url->checkout( true ) );
                exit();
                break;
            case 'order-history':
                wp_redirect( $url->order_history() );
                exit();
                break;
            case 'profile':
                wp_redirect( $url->profile() );
                exit();
                break;
            case 'receipts':
                $order_id = $wp->query_vars[ 'cc-order-number' ];
                CC_Log::write( "Getting receipt for order number: $order_id" );

                $_GET['cc_page_title'] = 'Receipt';
                $_GET['cc_page_name']  = 'Receipt';
                $_GET['cc_order_id'] = $order_id;

                add_action( 'pre_get_posts', 'CC_Page_Slurp::set_query_to_slurp');
                add_filter( 'wp_title',      'CC_Page_Slurp::set_page_title' );
                add_filter( 'the_title',     'CC_Page_Slurp::set_page_heading' );

                CC_Page_Slurp::check_receipt();

                break;
            case 'product-update':
                if ( 'PUT' == $_SERVER['REQUEST_METHOD'] ) {
                    $sku = $wp->query_vars[ 'cc-sku' ];
                    $product = new CC_Product();
                    $product->update_info( $sku );
                    exit();
                }
                break;
            case 'product-create':
                if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
                    $post_body = file_get_contents('php://input');
                    if ( $product_data = json_decode( $post_body ) ) {
                        $product = new CC_Product();
                        
                        // Check for demo product
                        if ( 'cc-demo-shirt' == $product_data->sku ) {
                            $content = $product->shirt_content();
                            $excerpt = $product->shirt_excerpt();
                            $post_id = $product->create_post( $product_data->sku, $content, $excerpt );
                            $product->attach_shirt_images( $post_id );
                        }
                        else {
                            // Create a normal product pressed from the cloud
                            $product->create_post( $product_data->sku );
                        }

                    }
                    exit();
                }
                break;
            case 'settings-create':
                if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
                    $post_body = file_get_contents('php://input');

                    if ( $settings = json_decode( $post_body ) ) {
                        $main_settings = CC_Admin_Setting::get_options( 'cart66_main_settings' );
                        if ( ( ! isset( $main_settings['secret_key'] ) || empty( $main_settings['secret_key'] ) ) && 
                             ( ! isset( $main_settings['subdomain'] )  || empty( $main_settings['subdomain'] ) ) ) {
                            $main_settings['secret_key'] = $settings->secret_key;
                            $main_settings['subdomain'] = $settings->subdomain;
                            CC_Admin_Setting::update_options( 'cart66_main_settings', $main_settings );                        
                            status_header('201');
                        }
                        else {
                            status_header('412');
                        }
                    }

                    exit();
                }
                break;
        }
    }

}
