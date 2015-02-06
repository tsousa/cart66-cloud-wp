<?php

class CC_Shortcode_Manager {

    public static function init() {
        self::register_shortcodes();
    }

    public static function register_shortcodes() {
        add_shortcode('cc_product',              array('CC_Shortcode_Manager', 'cc_product'));
        add_shortcode('cc_product_link',         array('CC_Shortcode_Manager', 'cc_product_link'));
        add_shortcode('cc_product_price',        array('CC_Shortcode_Manager', 'cc_product_price'));
        add_shortcode('cc_cart_item_count',      array('CC_Shortcode_Manager', 'cc_cart_item_count'));
        add_shortcode('cc_cart_subtotal',        array('CC_Shortcode_Manager', 'cc_cart_subtotal'));
    }

    public static function cc_product( $args, $content ) {


        $product_loader = CC_Admin_Setting::get_option( 'cart66_main_settings', 'product_loader' );
        $subdomain      = CC_Cloud_Subdomain::load_from_wp();
        $id             = cc_rand_string(12, 'lower');
        $product_form   = '';
        $client_loading = 'true';

        $product_id       = isset($args['id']) ? $args['id'] : false;
        $product_sku      = isset($args['sku']) ? $args['sku'] : false;
        $display_quantity = isset($args['quantity']) ? $args['quantity'] : 'true';
        $display_price    = isset($args['price']) ? $args['price'] : 'true';
        $display_mode     = isset($args['display']) ? $args['display'] : '';

        CC_Log::write( "cc_product shortcode: subdomain: $subdomain :: product loader: $product_loader" );

        if($product_loader == 'server' || preg_match( '/(?i)msie [2-9]/', $_SERVER['HTTP_USER_AGENT'] ) ) {
            // if IE<=9 do not use the ajax product form method
            $product_form   =  self::cc_product_via_api($args, $content);
            $client_loading = 'false';
        }

        $out = "<div class=\"cc_product_wrapper\"><div id='" . $id . "' class='cc_product' data-subdomain='$subdomain' data-sku='$product_sku' data-quantity='$display_quantity' data-price='$display_price' data-display='$display_mode' data-client='$client_loading'>$product_form</div></div>";

        return $out;
    }

    public static function cc_product_via_api( $args, $content ) {
        $form = '';
        if($error_message = CC_Flash_Data::get( 'api_error' )) {
            $form .= "<p class=\"cc_error\">$error_message</p>";
        }

        $product_id       = isset( $args['id'] ) ? $args['id'] : false;
        $product_sku      = isset( $args['sku'] ) ? $args['sku'] : false;
        $display_quantity = isset( $args['quantity'] ) ? $args['quantity'] : 'true';
        $display_price    = isset( $args['price'] ) ? $args['price'] : 'true';
        $display_mode     = isset( $args['display'] ) ? $args['display'] : null;

        if ( $form_with_errors = CC_Flash_Data::get( $product_sku ) ) {
            $form .= $form_with_errors;
        } else {
            $product = new CC_Product();

            if ( $product_sku ) {
                $product->sku = $product_sku;
            } elseif ( $product_id ) {
                $product->id = $product_id;
            } else {
                throw new CC_Exception_Product( 'Unable to add product to cart without know the product sku or id' );
            }

            try {
                $form .= $product->get_order_form( $display_quantity, $display_price, $display_mode );
            } catch ( CC_Exception_Product $e ) {
                $form = "Product order form unavailable";
            }
        }

        return $form;
    }

    public static function cc_product_price( $args, $content ) {
        $product_sku = isset( $args['sku'] ) ? $args['sku'] : false;
        return CC::product_price( $product_sku );
    }
}
