<?php

function cc_starts_with( $haystack, $needle ) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

/**
 * Return a random string that contains only numbers or uppercase letters or
 * for added entropy, lowercase letters and symbols.
 *
 * The default length of the string is 14 characters.
 *
 * @param int (Optional) $length The number of characters in the string. Default: 14
 * @param string (Optional) $entropy 'lower' includes lower case letters, 'symbols' includes lower case letters and symbols
 * @return string
*/
function cc_rand_string($length = 14, $entropy='none') {
    $string = '';
    $chrs = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    if ( $entropy == 'lower' ) {
        $chrs .= 'abcdefghijklmnopqrstuvwxyz';
    }
    elseif ( $entropy == 'symbols' ) {
       $chrs .= 'abcdefghijklmnopqrstuvwxyz!@#%^&*()+~:';
    }

    for ( $i=0; $i<$length; $i++ ) {
        $loc = mt_rand(0, strlen($chrs)-1);
        $string .= $chrs[$loc];
    }

    return $string;
}

/**
 * Return true if the provided slug is part of the page request
 */
function cc_match_page_request($slug) {
    global $wp;
    global $wp_query;

    $match = false;
    if ( strtolower( $wp->request ) == strtolower( $slug ) ||
       ( isset( $wp->query_vars['page_id'] ) && $wp->query_vars['page_id'] == $slug )
    ) { $match = true; }

    return $match;
}

/**
 * Return true if the provided array is an associative array.
 *
 * @param array $array The array to inspect
 * @return boolean True if array is assoc
 */
function cc_is_assoc($array) {
    return is_array( $array ) && !is_numeric( implode( '', array_keys( $array ) ) );
}

function cc_unavailable_product_data() {
    $product_data = array(
        array( 'id' => 0, 'sku' => '', 'price' => '', 'name' => 'Products Unavailable' )
    );

    return $product_data;
}

function cc_strip_slashes( $value ) {
    if(get_magic_quotes_gpc() || function_exists('wp_magic_quotes')) {
      $value = cc_strip_slashes_deep($value);
    }

    return $value;
}

function cc_strip_slashes_deep($value) {
    $value = is_array( $value ) ?  array_map( 'cc_strip_slashes_deep', $value ) : stripslashes( $value );
    return $value;
}