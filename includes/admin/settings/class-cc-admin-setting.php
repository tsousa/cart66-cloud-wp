<?php
/**
 * Wrapper class for the register_setting WordPress function.
 *
 * The sanitize callback funciton name is sanitize()
 *
 * @author reality66
 * @since 1.8
 * @package CC\Admin\Settings
 */
class CC_Admin_Setting {

    /**
     * The settings group name, also the name use in the function settings_field( $group_name )
     *
     * @var string
     */
    public $option_group;

    /**
     * The option name key in WordPress database.
     *
     * The option name is usually the key for a serialized array of option key/value pairs.
     * To avoid the "Error: options page not found" problem an easy solution is to make
     * $option_group match $option_name.
     *
     * @var string
     */
    public $option_name;

    /**
     * An array of option values from the WordPress database.
     *
     * The key is the option name and the value is the option value.
     *
     * @var array
     */
    public static $option_values;

    /**
     * The page should match the menu_slug used for adding the options page.
     *
     * @var string
     */
    public $page_slug;

    /**
     * An array of CC_Admin_Settings_Section objects
     *
     * @var array
     */
    protected $sections;


    public static function instance($page, $section) {
        static $instance = array();

        $class = get_called_class();

        if( !isset($instance[$class] ) ) {
            $instance = new $class( $page, $section );
        }
        else {
            CC_Log::write("Reusing static instance of $class");
        }

        return $instance;
    }


    /**
     * Construct the WordPress setting.
     *
     * Set the page_slug where the settings sections should be located and the option name.
     * The option name is set to the same value as the option group if the optional third
     * parameter is omitted.
     *
     * @param string $page_slug
     * @param string $option_group
     * @param string $option_name
     * @return void
     */
    public function __construct( $page_slug, $option_group, $option_name = null ) {
        $this->page_slug = $page_slug;
        $this->option_group = $option_group;
        $this->option_name = is_null($option_name) ? $option_group : $option_name;
        $this->sections = array();

        if( !isset( self::$option_values ) ) {
            self::$option_values = array();
        }

        add_action( 'admin_init', array( $this, 'register_settings') );
    }

    public function add_section( CC_Admin_Settings_Section $section ) {
        $this->sections[] = $section;
    }

    /**
     * Iterate over the CC_Admin_Settings_Section objects and add them to the page
     *
     * @return void
     */
    public function add_settings_sections() {
        foreach( $this->sections as $section ) {
            $section->add_settings_fields( $this->page_slug );

            add_settings_section(
                $section->id,                    // String used in 'id' attribute of tags
                $section->title,                 // Title for section
                array( $section, 'render' ),     // Function to echo output for this section
                $this->page_slug                 // Menu slug for the page holding this section
            );
        }
    }

    /**
     * Call this function to register the setting after adding sections and fields.
     *
     * @return void
     */
    public function register() {
        $this->add_settings_sections();
        register_setting(
            $this->option_group,          // Group name, also the name use in settings_field( $group_name )
            $this->option_name,           // Option name key in WordPress database
            array( $this, 'sanitize' )    // Validation callback
        );
    }

    /**
     * The sanitize callback function set by register_settings()
     *
     * Override this function when extending this class to provide custom
     * sanitization and validation for your options.
     *
     * @param array $options
     * @return array The sanitized options
     */
    public function sanitize( $options ) {
        return $options;
    }

    public static function get_options( $option_name, $defaults = array() ) {

        if( !isset( self::$option_values[$option_name] ) ) {
            $values = get_option($option_name);
            // CC_Log::write('Loaded values from option name: ' . print_r( $values, true) );
            $values = $values ? $values : array();
            self::$option_values[$option_name] = array_merge($defaults, $values);
            // CC_Log::write( "Loading option values for $option_name: " . print_r( self::$option_values[ $option_name ], true ) );
        }
        else {
            // CC_Log::write( "Reusing option values for $option_name: " . print_r( self::$option_values[ $option_name ], true ) );
        }

        return self::$option_values[ $option_name ];
    }

    public static function reload_options( $option_name, $defaults = array() ) {
        unset( self::$option_values[ $option_name ]);
        $options = self::get_options( $option_name, $defaults );
        // CC_Log::write( "Reloaded the options values for $option_name: " . print_r( $options, true) );
        return $options;
    }

    public static function update_options( $option_name, $values ) {
        if ( isset( self::$option_values[ $option_name ] ) ) {
            // CC_Log::write( "Updating option values by merging in new values for $option_name: " . print_r( $values, true ) );
            self::$option_values[ $option_name ] = array_merge( self::$option_values[ $option_name ], $values );
        }
        else {
            // CC_Log::write( "Updating option values by adding new values for $option_name: " .print_r( $values, true ) );
            self::$option_values[ $option_name ] = $values;
        }

        update_option( $option_name, self::$option_values[ $option_name ] );
        // CC_Log::write( "About to reload options after updating $option_name with " . print_r( self::$option_values[ $option_name ], true ) );

        self::reload_options( $option_name );
    }

}