<?php

class CC_Admin_Main_Settings {

    protected $option_name = 'cart66_main_settings';

    public static function instance() {
        static $instance = null;

        if( null == $instance ) {
            $instance = new CC_Admin_Main_Settings();
        }

        return $instance;
    }

    private function __construct() {
        add_action( 'admin_init', array( $this, 'register_settings') );
        add_action( 'admin_menu', array( $this, 'add_menu_page') );
    }

    public function load_options( $option_name, $defaults = array() ) {
        $option_values = get_option($option_name);
        $option_values = $option_values ? $option_values : array();
        CC_Log::write("Loaded options for $option_name :: " . print_r( $option_values, true ) );
        return array_merge($defaults, $option_values);
    }

    public function add_menu_page() {
        add_menu_page(
            __( 'Cart66 Cloud', 'cart66' ),        // Page title
            __( 'Cart66 Cloud', 'cart66' ),        // Menu title
            'manage_options',                      // Capability
            'cart66',                              // Menu slug
            array( $this, 'render_settings' ),     // Display function
            CC_URL . 'resources/images/icon.png'   // Icon
        );
    }

    public function register_settings() {
        $defaults = array(
            'secret_key' => '',
        );
        $option_name = 'cart66_main_settings';
        $option_values = $this->load_options( 'cart66_main_settings', $defaults );


        register_setting(
            'cart66_main_settings_group',          // Group name, also the name use in settings_field( $group_name )
            $option_name,                          // Option name key in WordPress database
            array( $this, 'validate')              // Validation callback
        );

        add_settings_section(
            'cart66_main_settings_section',        // String used in 'id' attribute of tags
            'Main Settings',                       // Title for section
            array( $this, 'render_section'),       // Function to echo output for this section
            'cart66'                               // Menu slug for the page holding this section
        );

        add_settings_field(
            'cart66_secret_key',                   // String used in the id attribute of HTML tags
            'Cart66 Secret Key',                   // Title of the field
            array($this, 'render_text_field'),     // Callback function to render field
            'cart66',                              // Menu slug: 4th parameter from add_menu_page()
            'cart66_main_settings_section',        // The section of the settings page: Section ID from add_settings_section()
            array(                                 // Additional arguments passed to the callback function
              'label_for'   => 'cart66_secret_key',// Makes the field name clickable,
              'key'         => 'secret_key',       // Value for 'name' attribute
              'value'       => esc_attr($option_values['secret_key']),
              'option_name' => $option_name,
              'description' => 'The secret key from your secure Cart66 management console'
            )
        );
    }

    public function render_section() {
        _e( 'Connect your WordPress site to your secure Cart66 Cloud account', 'cart66' );
    }

    public function render_settings() {
        $view = CC_PATH . '/views/admin/html-main-settings.php';
        echo CC_View::get($view);
    }

    public function validate( $options ) {
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
            $message = __( 'Cart66 settings saved.', 'cart66' );
            add_settings_error(
                'cart66_main_settings_group',
                'settings-valid',
                $message,
                'updated'
            );
        }

        return $options;
    }

    public function render_text_field($args) {
        $field = '<input type="text" name="%1$s[%2$s] id="%3$s" value="%4$s" class="regular-text" />';
        if (isset($args['description'])) {
            $field .= '<p class="description">'.$args['description'].'</p>';
        }

        printf($field,
            $args['option_name'],
            $args['key'],
            $args['label_for'],
            $args['value']
        );
    }

}