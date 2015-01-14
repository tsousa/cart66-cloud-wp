<?php
/**
 * Wrapper class for WordPress add_settings_section
 *
 * The callback for rendering the display markup is render()
 *
 * @author reality66
 * @since 2.0
 * @package CC\Admin\Settings
 */
class CC_Admin_Settings_Section {

    /**
     * The option name in the WordPress options table
     *
     * @var string
     */
    public $id;

    /**
     * The displayed title of the section
     *
     * @var string
     */
    public $title;

    /**
     * The description of the settings section
     *
     * @var string
     */
    public $description;

    /**
     * An array of CC_Admin_Settings_Field objects representing the fields attached to this section
     *
     * @var array
     */
    protected $fields;

    public function __construct( $id, $title ) {
        $this->id = $id;
        $this->title = $title;
        $this->fields = array();
    }

    /**
     *
     * The callback function referenced in add_settings_section()
     *
     * This function receives a single optional argument, which is an array with three elements.
     *   - $id
     *   - $title
     *   - $callback
     *
     * Override this function to provide a description for this section of settings.
     *
     * This function should echo its output.
     *
     * @param array $args
     * @return void
     */
    public function render( $args ) {
        echo $this->description;
    }

    /**
     * Attach a CC_Admin_Settings_Field to this section.
     *
     * @param CC_Admin_Settings_Field $field
     * @return void
     */
    public function add_field( CC_Admin_Settings_Field $field ) {
        $this->fields[] = $field;
    }

    public function clear_fields() {
        $this->fields = array();
    }

    public function add_settings_fields( $page_slug ) {
        foreach( $this->fields as $field ) {
            // CC_Log::write('field id: ' . $field->id);
            add_settings_field(
                $field->id,                            // String used in the id attribute of HTML tags
                $field->title,                         // Title of the field
                array($field, 'render'),               // Callback function to render field
                $page_slug,                            // Menu slug: 4th parameter from add_menu_page()
                $this->id,                             // The section of the settings page: Section ID from add_settings_section()
                array(                                 // Additional arguments passed to the callback function
                  'option_name' => $this->id,
                )
            );
        }
    }
}