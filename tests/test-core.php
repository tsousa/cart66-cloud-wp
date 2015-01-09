<?php

class Cart66_Core_Tests extends LB_Test {

    public $cart66;

    public $version_number = '1.8';

    public function before_tests() {
        $this->cart66 = Cart66_Cloud::instance();
    }

    /**
     * Clear the cart66 settings from the WordPress options table before each test.
     */
    public function _before_each_test() {
        // CC_Admin_Setting::update_options( 'cart66_main_settings', array() );
    }

    public function test_version_number_function_should_return_correct_version_number() {
        $version_number = $this->cart66->version_number();
        $this->check( $this->version_number == $version_number, "Expecting $this->version_number but got $version_number" );
    }

    public function test_version_number_constant_should_return_correct_version_number() {
        $this->check( $this->version_number == CC_VERSION_NUMBER, "Expecting $this->version_number to equal " . CC_VERSION_NUMBER );
    }

    public function test_plugin_path_should_end_with_plugin_root_directory() {
        $result = LB_Should::end_with( $this->cart66->plugin_path(), '/wp-content/plugins/cart66-cloud/' );
        $this->check( $result, "Path was: $path" );
    }

    public function test_plugin_url_should_end_with_plugin_root_directory() {
        $result = LB_Should::end_with( $this->cart66->plugin_url(), '/wp-content/plugins/cart66-cloud/' );
        $this->check( $result, "URL was: $path" );
    }

    public function test_getting_subdomain_from_cloud() {

        // Put the secret key in the WordPress options table
        $settings = array( 'secret_key' => 's_2d53b0386171cd0f47ad040d' );
        CC_Admin_Setting::update_options('cart66_main_settings', $settings);

        // Look for subdomain
        $subdomain = CC_Cloud_Subdomain::load_from_cloud();
        $this->check( $subdomain == 'demo-store', "Expecting demo-store but found $subdomain" );
    }

    public function test_getting_subdomain_from_wordpress_database() {

        // Putting example-subdomain in the cart66_main_settings
        $settings = array( 'subdomain' => 'example-subdomain' );
        CC_Admin_Setting::update_options( 'cart66_main_settings', $settings );

        // Attempt to pull the subdomain back out of the WordPress options table
        $subdomain = CC_Cloud_Subdomain::load_from_wp();
        $this->check( $subdomain == 'example-subdomain', "Expecting example-subdomain but found $subdomain" );
    }

}

Cart66_Core_Tests::run_tests();