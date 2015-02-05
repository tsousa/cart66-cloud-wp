<?php

class CC_Cloud_URL {

    public static $cloud;

    public function __construct() {
        if ( ! isset( self::$cloud ) ) {
            self::$cloud = new CC_Cloud_API_V1(); 
        }
    }

    public function sign_in() {
        $redirect_url = '';
        $page_id = CC_Admin_Setting::get_option( 'cart66_members_notifications', 'member_home' );

        if ( $page_id > 0 ) {
            $redirect_url = get_permalink( $page_id );
        }

        $encoded_redirect_url = empty( $redirect_url ) ? '' : '?redirect_url=' . urlencode( $redirect_url );
        $url = self::$cloud->subdomain_url() . 'sign_in' . $encoded_redirect_url;
        return $url;
    }

    public function sign_out() {
        $redirect_url = urlencode( home_url() );
        $url = self::$cloud->subdomain_url() . 'sign_out?redirect_url=' . $redirect_url;
        return $url;
    }

    public function order_history() {
        return self::$cloud->subdomain_url();
    }

    public function profile() {
        return self::$cloud->subdomain_url() . 'profile';
    }

    /**
     * Get the URL to view the secure cart in the cloud
     *
     * The cart must exist and have a cart key in order to view the cart. If no
     * cart key exists, the view cart URL is null
     *
     * @param boolean $force_create_cart When true, create a cart if no cart key exists
     * @return string
     */
    public function view_cart_url( $force_create_cart = false ) {
        $url = null;

        // Do not create a cart if the id is not available in the cookie unless it is forced
        $cart_key = self::get_cart_key( $force_create_cart );

        if ( $cart_key ) {
            $subdomain_url = self::$cloud->subdomain_url();
            if ( $subdomain_url ) {
                $url =  $subdomain_url . 'carts/' . $cart_key;
            }
        }

        CC_Log::write( "Cart Key: $cart_key :: view cart URL: $url" );

        return $url;
    }

    /**
     * Return the URL to the checkout page on the cloud
     *
     * @return string
     */
    public function checkout_url() {
        $url = null;
        $cart_key = self::get_cart_key( false );
        $subdomain_url = self::$cloud->subdomain_url();

        if ( $cart_key && $subdomain_url ) {
            $url = $subdomain_url . 'checkout/' . $cart_key;
        }

        return $url;
    }
}
