<?php

class CC_Admin_Settings_Field {

    /**
     * String used in the 'id' attribute of tags, defaults to the same value as $key
     *
     * @var string
     */
    public $option_name;

    /**
     * The key in the serialized option values array
     *
     * @var string
     */
    public $key;

    /**
     * The value in the serialized options vaules array associated with the key for this settings field
     *
     * @var mixed
     */
    public $value;

    /**
     * Display title of the settings field
     *
     * @var string
     */
    public $title;

    /**
     * Content describing the use and purpose of this setting.
     *
     * @var string
     */
    public $description;

    /**
     * Content that appears before the input field
     *
     * @var string
     */
    public $header;

    /**
     * Additional content that is displayed below the description.
     *
     * @var string
     */
    public $footer;

    /*
    public $args;

    public $callback;
    public $page;
    public $section;
    */

    public function __construct( $title, $option_name, $key, $value='' ) {
        $this->title = $title;
        $this->option_name = $option_name;
        $this->key = $key;
        $this->value = $value;
        $this->description = null;
        $this->header = null;
        $this->footer = null;
    }

    /**
     * Override this function to control the display of the settings field.
     *
     * This funciton should echo its output
     */
    public function render( $args ) { }

}
