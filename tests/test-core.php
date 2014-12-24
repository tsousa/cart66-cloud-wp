<?php

class Cart66CoreTests extends WP_UnitTestCase {

    public $cart66;

    function setUp() {
        $this->cart66 = Cart66_Cloud::instance();
    }

    function test_version_number() {
        $version_number = $this->cart66->version_number();
        $this->assertEquals('1.8', $version_number);
    }

    function test_plugin_path() {
        $path = $this->cart66->plugin_path();
        $this->assertStringEndsWith('/wp-content/plugins/cart66-cloud/', $path);
    }

    function test_plugin_url() {
        $url = $this->cart66->plugin_url();
        $this->assertStringEndsWith('/wp-content/plugins/cart66-cloud/', $url);
    }
}

