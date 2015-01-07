<?php
class CC_Cloud_API_V1 {

    public $protocol;
    public $app_domain;
    public $api;
    public $secure;

    public static function instance() {
        static $instance = null;

        if ( !isset( $instance ) ) {
            $class = get_called_class();
            $instance = new $class;
        }

        return $instance;
    }

    protected function __construct() {
        $this->protocol   = 'https://';
        $this->app_domain = 'cart66.com';
        $this->api        = $this->protocol . 'api.' . $this->app_domain . '/1/';
        $this->secure     = $this->protocol . 'secure.' . $this->app_domain . '/';
    }

    public static function basic_auth_header($extra_headers=array()) {
        $settings = CC_Admin_Setting::load_options('cart66_main_settings');
        $username = $settings['secret_key'];
        $password = ''; // not in use
        $headers = array(
            'sslverify' => false,
            'timeout' => 30,
            'headers' => array( 'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password ) )
        );

        if ( is_array( $extra_headers ) ) {
            foreach ( $extra_headers as $key => $value ) {
                $headers['headers'][$key] = $value;
            }
        }

        //CC_Log::write('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] Built headers :: " . print_r($headers, true));
        return $headers;
    }

    public static function response_ok($response) {
        $ok = true;
        if(is_wp_error($response) || $response['response']['code'] != 200) {
            $ok = false;
        }
        return $ok;
    }

    public function response_created($response) {
        $ok = true;
        if(is_wp_error($response) || $response['response']['code'] != 201) {
            $ok = false;
        }
        return $ok;
    }

}