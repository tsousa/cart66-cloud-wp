<?php
class CC_Cloud_Subdomain {

    public static $subdomain;

    /**
     * Return the subdomain for the store with the secret key saved in the WordPress options database.
     *
     * @param boolean $force If true, always load from the cloud
     * @return string
     */
    public static function get( $force = false ) {
        if( !isset( self::$subdomain ) ) {
            self::load( $force );
        }

        return self::$subdomain;
    }

    /**
     * Attempt to load subdomain from WordPress database. If not available, load from the cloud.
     */
    public static function load( $force = false ) {
        $settings = CC_Admin_Setting::load_options('cart66_main_settings');

        if ( $force || !isset( $settings['subdomain'] ) ) {
            CC_Log::write('About to load subdomain from the cloud');
            $subdomain = self::load_from_cloud();
        }
        else {
            $subdomain = $settings['subdomain'];
        }

        return $subdomain;
    }

    public static function load_from_cloud() {
        $cloud = CC_Cloud_API_V1::instance();
        $url = $cloud->api . 'subdomain';
        $headers = array('Accept' => 'text/html');

        CC_Log::write("Calling cloud for subdomain URL: $url");
        $response = wp_remote_get($url, $cloud::basic_auth_header($headers));
        CC_Log::write("Response from cloud to get subdomain: $url " . print_r($response, true));

        if($cloud::response_ok($response)) {
            $subdomain = $response['body'];
            self::update_subdomain( $subdomain );
            self::$subdomain = $subdomain;
        }
    }

    /**
     * Update the subdomain in the WordPress options table.
     *
     * @since 2.0
     * @return void
     */
    public static function update_subdomain( $subdomain ) {
        $settings = CC_Admin_Setting::load_options( 'cart66_main_settings' );
        $settings['subdomain'] = $subdomain;
        update_option( 'cart66_main_settings', $settings);
    }

}