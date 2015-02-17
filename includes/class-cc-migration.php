<?php

class CC_Migration {

    protected $main_settings;
    protected $labels;
    protected $product_options;

    public function __construct() {
        $this->main_settings = array(
            'secret_key' => '',
            'add_to_cart_redirect_type' => 'view_cart',
            'product_loader' => 'client',
            'shop_name' => 'Shop',  
            'custom_css' => '',
            'debug' => 'off'
        );

        $this->labels = array(
            'price' => 'Price:',
            'on_sale' => 'Sale:',
            'view' => 'View Details'
        );

        $this->product_options = array(
            'sort_method' => 'price_desc',
            'max_products' => 4
        );
    }

    public function run() {
        $this->migrate_secret_key();
        $this->migrate_redirect_type();
        $this->migrate_product_loader();
        $this->migrate_subdomain();
        $this->update_settings();
    }

    public function migrate_secret_key() {
        $secret_key = get_option( 'cc_secret_key' );
        $this->main_settings['secret_key'] = $secret_key;
    }

    public function migrate_redirect_type() {
        $type = get_option( 'cc_redirect_type' );
        $this->main_settings['add_to_cart_redirect_type'] = $type;
    }

    public function migrate_product_loader() {
        $loader = get_option( 'cc_product_loader' );
        $this->main_settings['product_loader'] = $loader;
    }

    public function migrate_subdomain() {
        $subdomain = get_option( 'cc_subdomain' );
        $this->main_settings['subdomain'] = $subdomain;
    }

    public function update_settings() {
        CC_Admin_Setting::update_options( 'cart66_main_settings', $this->main_settings );
        CC_Admin_Setting::update_options( 'cart66_labels', $this->labels );
        CC_Admin_Setting::update_options( 'cart66_product_options', $this->product_options );
        CC_Admin_Notifications::dismiss( 'cart66_migration' );
    }

}
