<?php

class CC_Admin_Settings_Field {

    /**
     * String used in the 'id' attribute of tags, defaults to the same value as $key
     *
     * @var string
     */
    public $id;

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
     * The type of form element to render
     *
     * @var string
     */
    public $type;


    /*
    public $args;

    public $callback;
    public $page;
    public $section;
    */

    public function __construct( $title, $key='', $value='', $type='text' ) {
        $this->title = $title;
        $this->id = $key;
        $this->key = $key;
        $this->value = $value;
        $this->type = $type;
    }

    /**
     * Override this function to control the display of the settings field.
     *
     * This funciton should echo its output
     */
    public function render( $args ) { }

}