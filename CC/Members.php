<?php

class CC_Members {

  public static function init() {
    IS_ADMIN ? self::admin_init() : self::public_init();    
  }

}
