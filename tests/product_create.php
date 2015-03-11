<?php
$url = 'http://lessons.blue.hopto.me:8888/cc-api/v1/products';
$data = array('sku' => 'fiskars');

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => json_encode( $data ),
    )
);

$context  = stream_context_create( $options );
$result = file_get_contents( $url, false, $context );

var_dump( $result );
