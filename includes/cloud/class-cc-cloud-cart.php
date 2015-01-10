<?php

class CC_Cloud_Cart {

    public $cloud;
    public static $cart_key;

    public function __construct() {
        $this->cloud = new CC_Cloud_API_V1();
    }

    public function create( $slurp_url = '' ) {
        $url = $this->cloud->api . 'carts';

        // Build the headers to create the cart
        $headers = array( 'Accept' => 'application/json' );
        $args = $this->cloud->basic_auth_header($headers);

        $data = array( 'ip_address' => $_SERVER['REMOTE_ADDR'] );
        $data = json_encode( $data );
        $args['body'] = $data;

        // Post to create cart
        CC_Log::write("Create cart via library call to Cart66 Cloud: $url " . print_r( $args, true ) );
        $response = wp_remote_post( $url, $args );

        if( !$this->cloud->response_created( $response ) ) {
            CC_Log::write('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] Failed to create new cart in Cart66 Cloud: $url :: " . print_r( $response, true ) );
            throw new CC_Exception_API( 'Failed to create new cart in Cart66 Cloud' );
        }

        $cart_data = json_decode( $response['body'] );
        return $cart_data->key;
    }

    /**
     * Return the cart id from self, cookie, or create a new one
     *
     * If force is false, a new cart will not be created and false will be returned
     * if a cart_key is not in the cookie
     *
     * @return mixed string or false
     */
    public function get_cart_key( $create_if_empty = true ) {
        $cart_key = false;

        if ( isset( self::$cart_key ) ) {
            $cart_key = self::$cart_key;
        } elseif ( isset( $_COOKIE['cc_cart_key'] ) ) {
            $cart_key = $_COOKIE['cc_cart_key'];
        }

        if ( $cart_key == false && $create_if_empty !== false ) {
            $cart_key = $this->create();
        }

        return $cart_key;
    }

    /**
     * Returns summary information for the given shopping cart
     *
     * subtotal: the sum of the totals of all the items (not including shipping, taxes, or discounts)
     * item_count: the number of items in the cart
     *
     * @return stdClass object
     */
    public function summary( $cart_key ) {
        $headers = array( 'Accept' => 'application/json' );
        $url = $this->cloud->api . "carts/$cart_key/summary";
        $response = wp_remote_get( $url, $this->cloud->basic_auth_header( $headers ) );
        if ( !self::_response_ok( $response ) ) {
            if ( is_object( $response ) ) {
                $error_code = $response->get_error_code();

                if( $error_code == '500' ) {
                    CC_Log::write( "Cart summary response from library: $url :: 500 Server Error" );
                }
                else {
                    CC_Log::write( "Cart summary response from library: $url :: " . print_r($response, true) );
                }

                throw new CC_Exception_API( "Unable to retrieve cart summary information for cart id: $cart_key" );
            }
            elseif ( is_array( $response ) ) {

                if ( isset( $response['response']['code'] ) && $response['response']['code'] == '404') {
                    CC_Log::write( "Cart key not found. Drop the cart: $cart_key" );
                    throw new CC_Exception_API_CartNotFound( "Cart key not found: $cart_key" );
                }
            }
        }

        $summary = json_decode( $response['body'] );
        return $summary;
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
    public static function view_cart_url( $force_create_cart = false ) {
        $url = null;

        // Do not create a cart if the id is not available in the cookie unless it is forced
        $cart_key = self::get_cart_key( $force_create_cart );

        if ( $cart_key ) {
            $subdomain_url = $this->cloud->subdomain_url();
            if ( $subdomain_url ) {
                $url =  $subdomain_url . '/carts/' . $cart_key;
            }
        }

        return $url;
    }

    /**
     * Return the URL to the checkout page on the cloud
     *
     * @return string
     */
    public function checkout_url($cart_key) {
        $url = null;
        $subdomain_url = $this->cloud->subdomain_url();

        if ( $subdomain_url ) {
            $url = $subdomain_url . '/checkout/' . $cart_key;
        }

        return $url;
    }

    /**
     * Returns the HTML markup for the add to cart form for the given product id
     *
     * @return string
     */
    public function get_order_form( $product_id, $redirect_url, $display_quantity=null, $display_price=null, $display_mode=null ) {

        // Prepare the query string
        $params = array(
            'redirect_url' => urlencode( $redirect_url )
        );

        if ( isset( $display_mode ) ) {
            $params[] = 'display=' . $display_mode;
        }

        if ( isset( $display_quantity ) ) {
            $params[] = 'quantity=' . $display_quantity;
        }

        if ( isset( $display_price ) ) {
            $params[] = 'price=' . $display_price;
        }

        $query_string = '?' . implode( '&', $params );

        // Prepare the url
        $headers       = array( 'Accept' => 'text/html' );
        $subdomain_url = $this->cloud->subdomain_url();
        $url           = $subdomain_url . '/products/' . $product_id . '/forms/add_to_cart' . $query_string;
        CC_Log::write("Getting order form get_order_form URL: $url");
        $response = wp_remote_get( $url, $this->cloud->basic_auth_header( $headers ) );

        if( is_wp_error( $response ) ) {
            CC_Log::write( "CC_Library::get_add_to_cart_form had an error: " . print_r( $response, true ) );
            throw new CC_Exception_API( 'Failed to retrieve product add to cart form from Cart66 Cloud' );
        } elseif ( $response['response']['code'] != 200 ) {
            CC_Log::write('CC_Library::get_add_to_cart_form invalid response code: ' . print_r( $response, true ) );
            throw new CC_Exception_API( 'Failed to retrieve product add to cart form from Cart66 Cloud :: Response code error :: ' . $response['response']['code'] );
        }

        $form_html = $response['body'];
        return $form_html;
    }

    public function add_to_cart( $cart_key, $post_data ) {
        $subdomain_url = $this->cloud->subdomain_url();
        $url = $subdomain_url . "/carts/$cart_key/items";
        $headers = $this->cloud->basic_auth_header();
        $headers = array(
            'sslverify' => false,
            'method'    => 'POST',
            'body'      => $post_data,
            'headers'   => $headers['headers']
        );
        $response = wp_remote_post( $url, $headers );

        return $response;
    }

}