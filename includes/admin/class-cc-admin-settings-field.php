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


    public $args;

    public $callback;
    public $page;
    public $section;

    public function __construct( $title, $key, $value, $type='text' ) {
        $this->title = $title;
        $this->id = $key;
        $this->key = $key;
        $this->value = $value;
        $this->type = $type;
    }

    public function render( $args ) {
        CC_Log::write('Calling render on settings field: ' . $this->type);
        switch ( $this->type ) {
            case 'text':
                $this->render_text_field( $args );
                break;

            default:
                throw new CC_Exception_Settings_Field_Unknown('Unable to render settings field for unknown type of setting');
                break;
        }
    }

    public function render_text_field( $args ) {
        CC_Log::write( 'Render text field args: ' . print_r( $args, true ) );

        $field = '<input type="text" name="%1$s[%2$s] id="%3$s" value="%4$s" class="regular-text" />';

        if ( isset( $args['description'] ) ) {
            $field .= '<p class="description">' . $args['description'].'</p>';
        }

        printf($field,
            $args['option_name'],
            $args['key'],
            $args['label_for'],
            $args['value']
        );
    }
}