<?php

class CC_Admin {

    public function __construct() {

        // Initialize the main settings for Cart66
        CC_Admin_Main_Settings::init( 'cart66', 'cart66_main_settings' );

        // Add the main cart66 admin pages to the menu
        add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );

        // Add the cart66 product insert media button to the editor
        add_action( 'current_screen', array($this, 'add_media_button_to_editor' ) );
    }

    public function add_menu_pages() {
        $page_title = __( 'Cart66 Cloud Settings', 'cart66' );
        $menu_title = __( 'Cart66 Cloud', 'cart66' );
        $capability = 'manage_options';
        $menu_slug = 'cart66';
        $display_callback = array( $this, 'render_main_settings' );
        $icon_url = 'dashicons-cart';
        add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $display_callback, $icon_url );

        // Admin page for secure console
        $parent_slug = 'cart66';
        $page_title = __( 'Cart66 Cloud Secure Console', 'cart66');
        $menu_title = __( 'Secure Console', 'cart66' );
        $menu_slug = 'cart66_secure_console';
        add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, array($this, 'secure_console') );
    }

    public function render_main_settings() {
        $view = CC_View::get(CC_PATH . 'views/admin/html-main-settings.php');
        echo $view;
    }

    public function secure_console() {
        $view = CC_View::get(CC_PATH . 'views/admin/html-secure-console.php');
        echo $view;
    }

    public function init_settings() {
        $this->register_main_settings();
    }

    public function add_media_button_to_editor() {
        $screen = get_current_screen();

        // Add media button for cart66 shortcodes to post pages
        if ( 'post' == $screen->base ) {
            // CC_Log::write( 'Adding media button. Screen base: ' . $screen->base );
            add_action('media_buttons', array('CC_Admin_Media_Button', 'add_media_button'));
            add_action('admin_footer',  array('CC_Admin_Media_Button', 'add_media_button_popup'));
            add_action('admin_enqueue_scripts', array('CC_Admin_Media_Button', 'enqueue_select2'));
        }
    }

}

return new CC_Admin();
