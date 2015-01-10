<?php

/*
if( !defined( 'CC_DEBUG' ) ) {
    $logging = get_site_option( 'cc_logging' );
    $logging = $logging == 1 ? true : false;
    define( 'CC_DEBUG', $logging );
}
*/
define( 'CC_DEBUG', true );

class CC_Log {

    public static function write( $data ) {
        if ( defined( 'CC_DEBUG' ) && CC_DEBUG) {
            $backtrace = debug_backtrace();
            $file = $backtrace[0]['file'];
            $line = $backtrace[0]['line'];
            $date = current_time('m/d/Y g:i:s A') . ' ' . get_option('timezone_string');
            $out = "========== $date ==========\nFile: $file" . ' :: Line: ' . $line . "\n$data";

            if( is_writable( CC_PATH ) ) {
                $filename = CC_PATH . 'log.txt';
                file_put_contents( $filename, $out . "\n\n", FILE_APPEND );
            }
        }
    }

}
