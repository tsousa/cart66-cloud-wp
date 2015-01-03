<?php

class CC_Admin_Main_Settings extends CC_Admin_Setting {



    public function register_settings() {
        $defaults = array(
            'secret_key' => '',
        );

        // Set the name for the options in this section and load any stored values
        $option_name = 'cart66_main_settings';
        $option_values = $this->load_options( $option_name, $defaults );

        // Add text field for cart66_main_settings[secret_key]
        $secret_key_title = __( 'Secret Key', 'cart66');
        $secret_key_value = esc_attr( $option_values[ 'secret_key' ] );
        $secret_key = new CC_Admin_Settings_Text_Field( $secret_key_title, 'secret_key', $secret_key_value, 'text' );
        $secret_key->description = __( 'The secret key from your secure Cart66 management console', 'cart66' );

        // Create the section for the cart66_main_settings section
        $main_title = __( 'Cart66 Cloud Main Settings', 'cart66' );
        $main_description = __( 'Connect your WordPress site to your secure Cart66 account', 'cart66' );
        $main_section = new CC_Admin_Settings_Section( $option_name, $main_title );
        $main_section->description = $main_description;
        $main_section->add_field( $secret_key );

        // Add to cart redirect option
        $cart_redirect = new CC_Admin_Settings_Radio_Buttons( __( 'Add To Cart Redirect', 'cart66' ), 'add_to_cart_redirect_type' );
        $cart_redirect->new_option( __( 'Go to view cart page', 'cart66' ), 'cart', true );
        $cart_redirect->new_option( __( 'Go to checkout page', 'cart66' ), 'checkout', false );
        $cart_redirect->new_option( __( 'Stay on same page', 'cart66' ), 'stay', false );
        $cart_redirect->description = __( 'Where to direct the buyer after adding a product to the shopping cart', 'cart66' );
        $cart_redirect->set_selected( $option_values[ 'add_to_cart_redirect_type' ] );
        $main_section->add_field( $cart_redirect );

        // Add product loader option
        $product_loader = new CC_Admin_Settings_Radio_Buttons( __( 'Product Loader', 'cart66' ), 'product_loader' );
        $product_loader->new_option( __( 'Client side loading (recommended)', 'cart66' ), 'client', true);
        $product_loader->new_option( __( 'Server side loading', 'cart66' ), 'server', false);
        $product_loader->description = __('Client side is faster, but requires Javascript', 'cart66');
        $product_loader->set_selected( $option_values[ 'product_loader' ] );
        $main_section->add_field( $product_loader );

        // Add the settings sections for the page and register the settings
        $this->add_section($main_section);
        $this->register();
    }

    public function render_section() {
        _e( 'Connect your WordPress site to your secure Cart66 Cloud account', 'cart66' );
    }

    public function sanitize( $options ) {
        CC_Log::write( 'sanitze options for main settings: ' . print_r( $options, true ) );

        // Attempt to sanitize, validate, and save the options
        if( is_array( $options )) {
            foreach( $options as $key => $value ) {
                if( 'secret_key' == $key ) {
                    if( !cc_starts_with($value, 's_') ) {
                        $error_message = __( 'The secret key is invalid', 'cart66' );
                        add_settings_error(
                            'cart66_main_settings_group',
                            'invalid-secret-key',
                            $error_message,
                            'error'
                        );
                        CC_Log::write( "Cart66 settings validation error added: $error_message" );
                        $options = false;
                    }
                }
            }

            if( false !== $options ) {
                $message = __( 'Cart66 settings saved', 'cart66' );
                add_settings_error(
                    'cart66_main_settings_group',
                    'settings-valid',
                    $message,
                    'updated'
                );
            }
        }
        else {
            $message = __( 'Cart66 settings were not saved', 'cart66' );
            add_settings_error(
                'cart66_main_settings_group',
                'settings-valid',
                $message,
                'error'
            );
        }

        // Sanitize options registered by add-on plugins
        do_action( 'cart66_main_settings_sanitize', $options);

        return $options;
    }



}