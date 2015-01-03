<?php

class CC_Admin_Settings_Radio_Buttons extends CC_Admin_Settings_Input_Group {

    public function render( $args ) {
        $labels = array();
        foreach( $this->options as $option ) {
            $checked = $option->is_selected ? ' checked="checked"' : '';
            $label = '<label title="' . $option->value . '">';
            $label .= '<input type="radio" name="' . $args['option_name'] . '[' . $args['key'] . ']" value="' . $option->value . '"' . $checked . '>';
            $label .= '<span>' . $option->display . '</span>';
            $label .= '</label>';
            $labels[] = $label;
        }

        $labels = implode( '<br>', $labels );

        $field = '<fieldset><legend class="screen-reader-text"><span>' . $this->title . '</span></legend>';
        $field .= $labels;

        if( !empty( $args['description'] ) ) {
            $field .= '<p class="description">' . $args['description'].'</p>';
        }

        $field .= '</fieldset>';

        echo $field;
    }

}