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
            'secret_key' => '',
            'add_to_cart_redirect_type' => '',
            'product_loader' => '',
            'shop_name' => 'Shop',  
            'custom_css' => '',
            'default_css' => 'yes',
            'debug' => ''
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
        /*
        $slurp_mode = new CC_Admin_Settings_Radio_Buttons( __( 'Page Slurp Mode', 'cart66' ), 'slurp_mode' );
        $slurp_mode->new_option( __( 'Physical Page (recommended)', 'cart66' ), 'physical', true );
        $slurp_mode->new_option( __( 'Virtual Page', 'cart66' ), 'virtual', false );
        $slurp_mode->set_selected( $option_values[ 'slurp_mode' ] );
        $main_section->add_field( $slurp_mode );
         */

        // Add custom css field
        $css = $option_values[ 'custom_css' ];
        $custom_css = new CC_Admin_Settings_Text_Area( __('Custom CSS', 'cart66'), 'custom_css', $css );
        $custom_css->description = __( 'Enter your own CSS to customize the display of Cart66', 'cart66' );
        $main_section->add_field( $custom_css );

        // Disable default css
        $default_css = new CC_Admin_Settings_Radio_Buttons( __( 'Include Default CSS', 'cart66' ), 'default_css' );
        $default_css->new_option( __( 'Yes' ), 'yes', true );
        $default_css->new_option( __( 'No', 'cart66' ), 'no', false );
        $default_css->description = __( 'You can choose whether or not to include the default cart66 CSS.', 'cart66' );
        $default_css->set_selected( $option_values[ 'default_css' ] );
        $main_section->add_field( $default_css );

        // Add debug mode option
        $debug = new CC_Admin_Settings_Radio_Buttons( __( 'Debugging', 'cart66' ), 'debug' );
        $debug->new_option( __( 'Off', 'cart66' ), 'off', true);
        $debug->new_option( __( 'On', 'cart66' ), 'on', false);
        $debug->set_selected( $option_values[ 'debug' ]);
        $debug->description = __( 'Enable logging of debug and error messages in the log.txt file of the Cart66 Cloud plugin.<br />
                                   Be careful, the log gets big quick. Only use for testing.', 'cart66' );
        $debug_buttons = '<p>';
        $debug_buttons .= '<a href="' . add_query_arg( 'cc-task', 'download_log' ) . '" class="button">' . __( 'Download Log', 'cart66' ) . '</a> ';
        $debug_buttons .= '<a href="' . add_query_arg( 'cc-task', 'reset_log' ) . '" class="button">' . __( 'Reset Log File', 'cart66' ) . '</a> ';
        $debug_buttons .= '<a href="' . add_query_arg( 'cc-task', 'test_remote_calls' ) . '" class="button">' . __( 'Test Remote Calls', 'cart66' ) . '</a> ';
        $debug_buttons .= '</p>';

        if ( $results = CC_Flash_Data::get( 'remote_call_test_results' ) ) {
            $debug_buttons .= $results;
        }

        $debug->footer = $debug_buttons;
        $main_section->add_field( $debug );

        // Add the settings sections for the page and register the settings
        $this->add_section( $main_section );

        // Load saved label text
        $defaults = array(
            'price' => 'Price:',
            'on_sale' => 'Sale:',
            'view' => 'View Details'
        );
        $option_values = CC_Admin_Setting::get_options( 'cart66_labels', $defaults );

        // Create a section for configuring labels
        $labels_title = __( 'Cart66 Cloud Labels', 'cart66' );
        $labels_description = __( 'Customize the display text for various labels when listing products', 'cart66' );
        $labels_section = new CC_Admin_Settings_Section( $labels_title, 'cart66_labels' );
        $labels_section->description = $labels_description;

        // Add label for price
        $price_value = $option_values[ 'price' ];
        $price = new CC_Admin_Settings_Text_Field( __( 'Price Label', 'cart66'), 'price', $price_value );
        $price->description = __( 'The label displayed next to the product price', 'cart66' );
        $labels_section->add_field( $price );

        // Add label for on sale
        $on_sale_value = $option_values[ 'on_sale' ];
        $on_sale = new CC_Admin_Settings_Text_Field( __( 'On Sale Label', 'cart66'), 'on_sale', $on_sale_value );
        $on_sale->description = __( 'The label displayed next to the product price when the item is on sale', 'cart66' );
        $labels_section->add_field( $on_sale );

        // Add label for view details
        $view_value = $option_values[ 'view' ];
        $view = new CC_Admin_Settings_Text_Field( __( 'View Button', 'cart66'), 'view', $view_value );
        $view->description = __( 'The text in the button to view the details of the product ', 'cart66' );
        $labels_section->add_field( $view );
        
        $this->add_section( $labels_section );
        
        // Load saved product options
        $defaults = array(
            'sort_method' => 'price_desc',
            'max_products' => 4
        );
        $option_values = CC_Admin_Setting::get_options( 'cart66_product_options', $defaults );

        // Create a section for product options
        $products_title = __( 'Product Options', 'cart66' );
        $products_description = __( 'Configure the product display settings', 'cart66' );
        $products_section = new CC_Admin_Settings_Section( $products_title, 'cart66_product_options' );
        $products_section->description = $products_description;

        // Add setting for sorting products
        $sort_value = $option_values[ 'sort_method' ];
        $sort = new CC_Admin_Settings_Select_Box( __( 'Sort Products By', 'cart66'), 'sort_method' );
        $sort->new_option( __( 'Price ascending', 'cart66' ), 'price_asc' );
        $sort->new_option( __( 'Price descending', 'cart66' ), 'price_desc' );
        $sort->new_option( __( 'Name ascending', 'cart66' ), 'name_asc' );
        $sort->new_option( __( 'Name descending', 'cart66' ), 'name_desc' );
        $sort->new_option( __( 'Menu order', 'cart66'), 'menu_order' );
        $plugin = '<a href="https://wordpress.org/plugins/intuitive-custom-post-order/">Intuitive Custom Post Order</a>';
        $sort->description = __( 'When sorting by menu order you may enjoy a plugin such as ' ); 
        $sort->description .= $plugin . '<br/>'; 
        $sort->description .= __( 'This plugin lets you drag-and-drop the order of your products and product categories', 'cart66' );
        $sort->set_selected( $option_values[ 'sort_method' ] );
        $products_section->add_field( $sort );

        // Add setting for number of products on a page
        $max_products_value = $option_values[ 'max_products' ];
        $max_products = new CC_Admin_Settings_Select_Box( __( 'Products Per Page', 'cart66' ), 'max_products' );
        for ( $i = 2; $i <= 50; $i++ ) {
            $max_products->new_option( $i, $i );
        }
        $max_products->set_selected( $max_products_value );
        $products_section->add_field( $max_products );

        $this->add_section( $products_section );

        // Create section for theme content wrappers
        $defaults = array(
            'start_markup' => '',
            'end_markup' => ''
        );
        $option_values = CC_Admin_Setting::get_options( 'cart66_content_wrapper', $defaults );

        $wrapper_section = new CC_Admin_Settings_Section( __( 'Content Wrapper', 'cart66' ), 'cart66_content_wrapper' );
        $wrapper_section->description = __( 'If using the Cart66 Product post type upsets your theme layout, the problem is most likely caused by the markup wrapping the page content. This is usually fixed by copying the markup from your theme\'s page.php file into these settings.', 'cart66' );
        $wrapper_section->description .= ' <a href="http://cart66.com/tutorial/content-wrapper">';
        $wrapper_section->description .= __( 'Learn more about fixing layout problems.', 'cart66' );
        $wrapper_section->description .= '</a>';

        $start_markup_value = $option_values[ 'start_markup' ];
        $start_markup = new CC_Admin_Settings_Text_Area( __('Start Markup', 'cart66'), 'start_markup', $start_markup_value );
        $wrapper_section->add_field( $start_markup );

        $end_markup_value = $option_values[ 'end_markup' ];
        $end_markup = new CC_Admin_Settings_Text_Area( __('End Markup', 'cart66'), 'end_markup', $end_markup_value );
        $wrapper_section->add_field( $end_markup );

        $this->add_section( $wrapper_section );

        // Register all of the settings
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
