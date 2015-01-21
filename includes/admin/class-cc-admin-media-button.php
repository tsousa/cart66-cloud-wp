<?php

class CC_Admin_Media_Button {

    public static function add_media_button( $context ) {
        // CC_Log::write( 'Called add_media_button. Context: ' . print_r( $context, true ) );

        $style = <<<EOL
<style type="text/css">
    #cart66-menu-button-icon {
        padding: 4px 0px;
        font-size: 1.3em;
        color: #888;
    }
</style>

EOL;

        $title = __( 'Insert Product Into Content', 'cart66' );

        $button = '<a id="cc_product_shortcodes" href="#TB_inline?width=480&height=600&inlineId=cc_editor_pop_up" class="button thickbox" title="' . $title . '">';
        $button .= '<span id="cart66-menu-button-icon" class="dashicons dashicons-cart">  </span>';
        $button .= 'Cart66 Product';
        $button .= '</a>';

        $out = $style . $button;
        echo $out;
    }

    public static function add_media_button_popup() {
        $product = new CC_Cloud_Product();
        $product_data = array();

        try {
            // $product_data = $product->get_products(); // TODO: This call slows things down alot
        } catch( CC_Exception_API $e ) {
            $product_data = $product->unavailable();
            CC_Log::write( "Unable to retreive products for media button pop up: " . $e->get_message() );
        }

        $data = array('product_data' => $product_data);
        $view = CC_View::get(CC_PATH . 'views/editor-pop-up.php', $data);
        echo $view;
    }

    public static function enqueue_chosen() {
        wp_enqueue_style( 'chosen', CC_URL .'resources/css/chosen.css' );
        wp_enqueue_script( 'cc_add_to_cart', CC_URL . 'resources/js/chosen.jquery.min.js', array('jquery') );
    }

}
