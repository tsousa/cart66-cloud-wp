<?php
function send_post( $username, $url, $data = array() ) {
    $process = curl_init($url);
    curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($process, CURLOPT_HEADER, 1);

    if ( $username ) {
        curl_setopt($process, CURLOPT_USERPWD, $username . ':');
    }

    curl_setopt($process, CURLOPT_TIMEOUT, 30);
    curl_setopt($process, CURLOPT_POST, 1);
    curl_setopt($process, CURLOPT_POSTFIELDS, json_encode( $data ) );
    curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
    $status_code = curl_getinfo($process, CURLINFO_HTTP_CODE);   //get status code
    $result = curl_exec($process);
    curl_close($process);

    echo $status_code . "\n\n" . $result; 
}

function send_put( $username, $url, $data = array() ) {
    $process = curl_init($url);
    curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($process, CURLOPT_HEADER, 1);
    curl_setopt($process, CURLOPT_USERPWD, $username . ':');
    curl_setopt($process, CURLOPT_TIMEOUT, 30);
    curl_setopt($process, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($process, CURLOPT_POSTFIELDS, json_encode( $data ) );
    curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
    $status_code = curl_getinfo($process, CURLINFO_HTTP_CODE);   //get status code
    $result = curl_exec($process);
    curl_close($process);

    echo $status_code . "\n\n" . $result; 
}

function send_get( $username, $url, $data = array() ) {
    $process = curl_init($url);
    curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($process, CURLOPT_HEADER, 1);
    curl_setopt($process, CURLOPT_USERPWD, $username . ':');
    curl_setopt($process, CURLOPT_TIMEOUT, 30);
    curl_setopt($process, CURLOPT_HTTPGET, 1);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
    $status_code = curl_getinfo($process, CURLINFO_HTTP_CODE);   //get status code
    $result = curl_exec($process);
    curl_close($process);

    echo $status_code . "\n\n" . $result; 
}

/**
 * Begin Tests
 */

function test_product_update() {
    $username = 's_8109aff2b2954e287f4b39da';
    $url = 'http://wpdev.blue.hopto.me:8888/cc-api/v1/products/tinker';
    send_put( $username, $url );
}

// test_product_update();

function test_product_create_demo() {
    $username = 's_8109aff2b2954e287f4b39da';
    $url = 'http://wpdev.blue.hopto.me:8888/cc-api/v1/products';
    send_post( $username, $url, array( 'sku' => 'cc-cellerciser' ) );
}

// test_product_create_demo();

function test_subdomain_create() {
    $username = 's_8109aff2b2954e287f4b39da';
    $url = 'http://wpdev.blue.hopto.me:8888/cc-api/v1/settings';
    send_post( $username, $url, array( 'subdomain' => 'something-new' ) );
}

// test_subdomain_create();

function test_secret_key_create() {
    $username = false;
    $key = 's_8109aff2b2954e287f4b39da';
    $key = 'leehblue';
    $url = 'http://wpdev.blue.hopto.me:8888/cc-api/v1/init';
    send_post($username, $url, array( 'secret_key' => $key ) );
}

test_secret_key_create();
