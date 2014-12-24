<?php
/*
Plugin Name: Cart66 Cloud
Plugin URI: http://cart66.com
Description: Securely Hosted Ecommerce For WordPress
Version: 1.8
Author: Reality66
Author URI: http://www.reality66.com

-------------------------------------------------------------------------
Cart66 Cloud
Copyright 2015  Reality66

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists('Cart66_Cloud') ) {

    $plugin_file = __FILE__;
    if(isset($plugin)) { $plugin_file = $plugin; }
    elseif (isset($mu_plugin)) { $plugin_file = $mu_plugin; }
    elseif (isset($network_plugin)) { $plugin_file = $network_plugin; }

    define('CC_PLUGIN_FILE', $plugin_file);
    define('CC_PATH', WP_PLUGIN_DIR . '/' . basename(dirname($plugin_file)) . '/');
    define('CC_URL',  WP_PLUGIN_URL . '/' . basename(dirname($plugin_file)) . '/');

    /**
     * Cart66 main class
     *
     * The main Cart66 class should not be extended
     */
    final class Cart66_Cloud {

        protected static $instance;

        /**
         * Cart66 should only be loaded one time
         *
         * @since 1.8
         * @static
         * @return Cart66 instance
         */
        public static function instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function __construct() {
            // Define constants
            define( 'CC_VERSION_NUMBER', $this->version_number() );

            // Register autoloader
            spl_autoload_register( array( $this, 'class_loader' ) );
        }
  
        public static function starts_with($haystack, $needle) {
            $length = strlen($needle);
            return (substr($haystack, 0, $length) === $needle);
        }

        public static function class_loader($class) {
            if(self::starts_with($class, 'CC_')) {
                $class = strtolower($class);
                $file = 'class-' . str_replace( '_', '-', $class ) . '.php';
                $root = CC_PATH;

                if(self::starts_with($class, 'CC_Exception')) {
                    include $root . 'includes/exception-library.php';
                } else {
                    include $root . 'includes/' . $file;
                }
            } elseif($class == 'CC') {
                include CC_PATH . 'includes/class-cc.php';
            }
        }

        /** Helper functions ******************************************************/

        /**
         * Get the plugin url
         *
         * @return string
         */
        public function plugin_url() {
            return CC_URL;
        }

        /**
         * Get the plugin path
         *
         * @return string
         */
        public function plugin_path() {
            return CC_PATH;
        }

        /**
         * Get the template path
         *
         * @return string
         */
        public function template_path() {
            return apply_filters( 'cart66_template_path', 'cart66/' );
        }

        /**
         * Get the plugin version number from the header comments
         *
         * @return string
         */
        public function version_number() {
            if(!function_exists('get_plugin_data')) {
              require_once(ABSPATH . 'wp-admin/includes/plugin.php');
            }

            $plugin_data = get_plugin_data(CC_PLUGIN_FILE);
            return $plugin_data['Version'];
        }
        
    }

}

Cart66_Cloud::instance();

