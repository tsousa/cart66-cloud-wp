<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Cart66 Exceptions
 *
 * Exception classes used with Cart66 Cloud Toolkit
 */

/**
 * Base exception class for Cart66
 */
class C66_Exception extends Exception {
  public function get_message() {
    return parent::getMessage();
  }
}


/**
 * Exceptions used for API calls
 */
class C66_Exception_API extends C66_Exception {}
class C66_Exception_API_InvalidPublicKey extends C66_Exception_API {}
class C66_Exception_API_InvalidSecretKey extends C66_Exception_API {}
class C66_Exception_API_CartNotFound extends C66_Exception_API {}

/**
 * Exceptions used for the Cart66 Store
 */
class C66_Exception_Store extends C66_Exception {}
class C66_Exception_Store_ReceiptNotFound extends C66_Exception_Store {}

/**
 * Exceptions used for Cart66 Products
 */
class C66_Exception_Product extends C66_Exception {}

