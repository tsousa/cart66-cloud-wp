<?php

/** 
 * Handle admin tasks for cart66
 */
function cc_task_dispatcher() {
    $task = cc_get( 'cc-task' );
    // CC_Log::write( "Task dispatcher found: $task" );

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
        $url = new CC_Cloud_URL();
        switch ( $action ) {
            case 'sign-in':
                wp_redirect( $url->sign_in() );
                exit();
            case 'sign-out':
                wp_redirect( $url->sign_out() );
                exit();
            case 'view-cart':
                wp_redirect( $url->view_cart() );
                exit();
            case 'checkout':
                wp_redirect( $url->checkout() );
                exit();
            case 'order-history':
                wp_redirect( $url->order_history() );
                exit();
            case 'profile':
                wp_redirect( $url->profile() );
                exit();
        }
    }

}
