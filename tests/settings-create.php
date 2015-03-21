<?php

$url = 'http://wpdev.blue.hopto.me:8888/cc-api/v1/settings';
$json = '{"secret_key":"s_1234"}';
$data = array( 'secret_key' => 's_1234' );

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode( $data )
    )
);

$context  = stream_context_create( $options );
$result = file_get_contents( $url, false, $context );

var_dump( $result );
