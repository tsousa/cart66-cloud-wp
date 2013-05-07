<?php

if(!defined('CS_DEBUG')) {
  $logging = get_site_option('cs_logging');
  $logging = $logging == 1 ? true : false;
  define('CS_DEBUG', $logging);
}

class CS_Log {

  public static function write($data) {
    if(defined('CS_DEBUG') && CS_DEBUG) {
      $backtrace = debug_backtrace();
      $file = $backtrace[0]['file'];
      $line = $backtrace[0]['line'];
      $date = date('m/d/Y g:i:s a');
      $tz = '- Server time zone ' . date_default_timezone_get();
      $out = "CS ========== $date $tz ==========\nFile: $file" . ' :: Line: ' . $line . "\n$data";
      $dir = dirname(dirname(__FILE__));
      $filename = $dir . '/log.txt';
      if(is_writable($dir)) {
        file_put_contents($filename, $out . "\n\n", FILE_APPEND);
      }
    }
  }

}
