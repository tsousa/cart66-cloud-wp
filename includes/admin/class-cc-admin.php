<?php

class CC_Admin {

    public function __construct() {
        CC_Admin_Main_Settings::instance();

        //add_action( 'admin_init', array( $this, 'init_settings' ) );
        //add_action( 'admin_menu', array( $this, 'add_menu_pages') );
    }

    public function add_menu_pages() {

        add_submenu_page(
            'cart66',                                       // Parent slug
            __( 'Cart66 Cloud Member Settings', 'cart66' ), // Page title
            __( 'Member Settings', 'cart66' ),              // Menu title
            'manage_options',                               // Capability
            'cart66_member_settings',                       // Menu slug
            array($this, 'member_settings')                 // Display function
        );

        add_submenu_page(
            'cart66',
            __( 'Secure Console', 'cart66' ),
            __( 'Secure Console', 'cart66' ),
            'manage_options',
            'cart66_secure_console',
            array($this, 'secure_console')
        );
    }

    public function member_settings() {
        echo "<h1>Member Settings</h1>";
    }

    public function secure_console() {
        echo "<h1>Secure Console</h1>";
    }

    public function init_settings() {
        $this->register_main_settings();
        $this->register_member_settings();
    }

    public function validate_main_settings() {
        return true;
    }

    public function register_member_settings() {

    }
}

return new CC_Admin();