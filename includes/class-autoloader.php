<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Cart66 class autoloader
 *
 * Load classes for Cart66 as they are referenced
 *
 * @class    C66_Loader
 * @version  1.8
 * @package  Cart66
 * @category Class
 * @author   Reality66
 */
class CC_Loader {
  
    public static function starts_with($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    public static function class_loader($class) {
        if(self::starts_with($class, 'CC_')) {
            $class = strtolower($class);
            $file = 'class-' . str_replace( '_', '-', $class ) . '.php';
            $root = CC_PATH;

            if(self::starts_with($class_name, 'CC_Exception')) {
                include $root . 'includes/exception-library.php';
            } else {
                include $root . 'includes/' . $file;
            }
        } elseif($class_name == 'CC') {
            include CC_PATH . 'includes/class-cc.php';
        }
    }
  
}

// Register autoloader
spl_autoload_register(array('Cart66_Loader', 'class_loader'));
