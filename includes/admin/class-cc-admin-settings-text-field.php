<?php

class CC_Admin_Settings_Text_Field extends CC_Admin_Settings_Field {

    public function render( $args ) {
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