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
                array( $section, 'render'),      // Function to echo output for this section
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
            array( $this, 'sanitize')     // Validation callback
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

    public static function load_options( $option_name, $defaults = array() ) {
        static $option_values = array();

        if( !isset( $option_values[$option_name] ) ) {
            $values = get_option($option_name);
            $values = $values ? $values : array();
            $option_values[$option_name] = array_merge($defaults, $values);
        }
        else {
            CC_Log::write("Reusing option values for $option_name");
        }

        return $option_values[$option_name];
    }

}