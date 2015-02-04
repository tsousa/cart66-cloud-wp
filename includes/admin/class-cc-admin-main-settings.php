<?php

class CC_Admin_Main_Settings extends CC_Admin_Setting {

    public static function init() {
        $page = 'cart66_main';
        $option_group = 'cart66_main_settings';
        $setting = new CC_Admin_Main_Settings( $page, $option_group );
        return $setting;
    }

    /**
     * Allow other add-ons to add settings sections to the cart66 main settings page
     */
    public function add_settings_sections() {
        $this->sections = apply_filters( 'cart66_main_settings_sections', $this->sections );
        parent::add_settings_sections();
    }

    /**
     * Register cart66_main_settings
     *
     * Include the following keys:
     *   - subdomain: string
     *   - add_to_cart_redirect_type: cart, checkout, stay
     *   - product_loader: client, server
     *   - slurp_mode: physical, virtual
     *   - debug: on, off
     */
    public function register_settings() {
        
        // Set the name for the options in this section and load any stored values
        $option_values = self::get_options( $this->option_name, array( 
            'shop_name' => 'Shop',  
            'custom_css' => ''
        ) );

        // Create the section for the cart66_main_settings section
        $main_title = __( 'Cart66 Cloud Main Settings', 'cart66' );
        $main_description = __( 'Connect your WordPress site to your secure Cart66 account', 'cart66' );
        $main_section = new CC_Admin_Settings_Section( $main_title, $this->option_name );
        $main_section->description = $main_description;

        // Add secret key field
        $secret_key_title = __( 'Secret Key', 'cart66');
        $secret_key_value = esc_attr( $option_values[ 'secret_key' ] );
        $secret_key = new CC_Admin_Settings_Text_Field( $secret_key_title, 'secret_key', $secret_key_value );
        $secret_key->description = __( 'The secret key from your secure Cart66 management console', 'cart66' );
        $main_section->add_field( $secret_key );

        // Add cart66 subdomain field
        $subdomain = CC_Cloud_Subdomain::load_from_wp();
        $subdomain = isset( $subdomain ) ? $subdomain : 'Not Set';
        $subdomain_field = new CC_Admin_Settings_Hidden_Field( __( 'Cart66 Subdomain', 'cart66' ), 'subdomain', $subdomain );
        $subdomain_field->header = '<p>' . $subdomain . '</p>';

        $cart66_link = '<a href="https://manage.cart66.com">' . __( 'Cart66 Cloud Management Console', 'cart66' ) . '</a>';
        $description = __( 'Set your subdomain on the Settings tab of your ', 'cart66' ) . $cart66_link . '<br />';
        $description .= __( 'Then save these settings with a valid secret key to update this value.', 'cart66' );

        $subdomain_field->description = $description;
        $main_section->add_field( $subdomain_field );

        // Add name of main shop page
        $shop_name_value = $option_values[ 'shop_name' ];
        $shop_name = new CC_Admin_Settings_Text_Field( __( 'Shop name', 'cart66'), 'shop_name', $shop_name_value );
        $shop_name->description = __( 'The title for your main shop page', 'cart66' );
        $main_section->add_field( $shop_name );

        // Add to cart redirect option
        $cart_redirect = new CC_Admin_Settings_Radio_Buttons( __( 'Add To Cart Redirect', 'cart66' ), 'add_to_cart_redirect_type' );
        $cart_redirect->new_option( __( 'Go to view cart page', 'cart66' ), 'view_cart', true );
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

        // Add page slurp mode option
        $slurp_mode = new CC_Admin_Settings_Radio_Buttons( __( 'Page Slurp Mode', 'cart66' ), 'slurp_mode' );
        $slurp_mode->new_option( __( 'Physical Page (recommended)', 'cart66' ), 'physical', true );
        $slurp_mode->new_option( __( 'Virtual Page', 'cart66' ), 'virtual', false );
        $slurp_mode->set_selected( $option_values[ 'slurp_mode' ] );
        $main_section->add_field( $slurp_mode );

        // Add custom css field
        $css = $option_values[ 'custom_css' ];
        $custom_css = new CC_Admin_Settings_Text_Area( __('Custom CSS', 'cart66'), 'custom_css', $css );
        $custom_css->description = __( 'Enter your own CSS to customize the display of Cart66', 'cart66' );
        $main_section->add_field( $custom_css );

        // Add debug mode option
        $debug = new CC_Admin_Settings_Radio_Buttons( __( 'Debugging', 'cart66' ), 'debug' );
        $debug->new_option( __( 'Off', 'cart66' ), 'off', true);
        $debug->new_option( __( 'On', 'cart66' ), 'on', false);
        $debug->set_selected( $option_values[ 'debug' ]);
        $debug->description = __( 'Enable logging of debug and error messages in the log.txt file of the Cart66 Cloud plugin.<br />
                                   Be careful, the log gets big quick. Only use for testing.', 'cart66' );
        $debug_buttons = '<p>';
        $debug_buttons .= '<a href="' . add_query_arg( 'cc_task', 'download_log' ) . '" class="button">' . __( 'Download Log', 'cart66' ) . '</a> ';
        $debug_buttons .= '<a href="' . add_query_arg( 'cc_task', 'reset_log' ) . '" class="button">' . __( 'Reset Log File', 'cart66' ) . '</a> ';
        $debug_buttons .= '<a href="' . add_query_arg( 'cc_task', 'test_remote_calls' ) . '" class="button">' . __( 'Test Remote Calls', 'cart66' ) . '</a> ';
        $debug_buttons .= '</p>';

        if ( $results = CC_Flash_Data::get( 'remote_call_test_results' ) ) {
            $debug_buttons .= $results;
        }

        $debug->footer = $debug_buttons;
        $main_section->add_field( $debug );

        // Add the settings sections for the page and register the settings
        $this->add_section( $main_section );
        $this->register();
    }

    public function render_section() {
        _e( 'Connect your WordPress site to your secure Cart66 Cloud account', 'cart66' );
    }

    public function sanitize( $options ) {
        $clean = true;
        CC_Log::write( '########## SANITZE OPTIONS FOR MAIN SETTINGS :: ' . get_class() . ' ########## ' . print_r( $options, true ) );

        // Attempt to sanitize, validate, and save the options
        if( is_array( $options )) {
            foreach( $options as $key => $value ) {
                if( 'secret_key' == $key ) {
                    if( cc_starts_with($value, 's_' ) ) {
                        // Attempt to get the subdomain from the cloud and save it locally
                        $subdomain = CC_Cloud_Subdomain::load_from_cloud( $value );
                        if( isset($subdomain) ) {
                            $options[ 'subdomain' ] = $subdomain;
                        }
                    }
                    else {
                        $clean = false;
                        $error_message = __( 'The secret key is invalid', 'cart66' );
                        add_settings_error(
                            'cart66_main_settings_group',
                            'invalid-secret-key',
                            $error_message,
                            'error'
                        );
                        CC_Log::write( "Cart66 settings validation error added: $error_message" );
                    }
                }
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
        $options = apply_filters( 'cart66_main_settings_sanitize', $options);

        /*
        if( true == self::$is_valid ) {
            $message = __( 'Cart66 settings saved', 'cart66' );
            add_settings_error(
                'cart66_main_settings_group',
                'settings-valid',
                $message,
                'updated'
            );
        }
        */

        return $options;
    }



}
