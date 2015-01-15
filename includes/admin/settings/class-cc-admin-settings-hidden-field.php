<?php

class CC_Admin_Settings_Hidden_Field extends CC_Admin_Settings_Field {

    public function render( $args ) {
        $field = $this->header;

        $field .= '<input type="hidden" name="' . $this->option_name . '[' . $this->key . ']" id="' . $this->option_name . '_' . $this->key . '" value="' . $this->value . '" />';

        if ( isset( $this->description ) ) {
            $field .= '<p class="description">' . $this->description . '</p>';
        }

        if ( isset( $this->footer ) ) {
            $field .= $this->footer;
        }

        echo $field;
    }

}
