<?php
/**
 * Include opening HTML markup to render before the product content
 */
function cc_before_main_content() {
    $template = CC_PATH . 'templates/context/content-start.php';
    $template = apply_filters( 'cc_before_main_content_template', $template );   
    include_once $template;
}

/**
 * Include opening HTML markup to render before the product content
 */
function cc_after_main_content() {
    $template = CC_PATH . 'templates/context/content-end.php';
    $template = apply_filters( 'cc_after_main_content_template', $template );   
    include_once $template;
}

add_action( 'cc_before_main_content', 'cc_before_main_content' );
add_action( 'cc_after_main_content',  'cc_after_main_content' );
