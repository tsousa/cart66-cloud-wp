<?php

if(!defined('CS_DEBUG')) {
  define('CS_DEBUG', true);
}

class CS_Log {

  public static function write($data) {
    if(defined('CS_DEBUG') && CS_DEBUG) {
      $tz = '- Server time zone ' . date_default_timezone_get();
      $date = date('m/d/Y g:i:s a');
      $header = "[LOG DATE: $date $tz]\n";
      $dir = dirname(dirname(__FILE__));
      $filename = $dir . '/log.txt';
      if(is_writable($dir)) {
        file_put_contents($filename, $header . $data . "\n\n", FILE_APPEND);
      }
    }
  }

}