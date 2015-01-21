<?php

//add_filter( 'template_include', 'cc_template_include' );

function cc_template_include( $template ) {
    $post_type = get_query_var( 'post_type' );

    if ( 'product' == $post_type ) {
        $template = CC_PATH . 'templates/single-product.php';
    }

    CC_Log::write( "Considering which template to include:\nTemplate: " . $template . "\nPost type: " . $post_type );

    return $template;
}
