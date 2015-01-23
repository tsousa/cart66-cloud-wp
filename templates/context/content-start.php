<?php
/**
 * Output necessary content wrappers based on active theme
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$template = get_option( 'template' );
$out = '';

switch( $template ) {

	case 'twentyeleven' :
		$out = '<div id="primary"><div id="content" role="main">';
		break;
	case 'twentytwelve' :
		$out = '<div id="primary" class="site-content"><div id="content" role="main">';
		break;
	case 'twentythirteen' :
		$out = '<div id="primary" class="content-area"><div id="content" role="main" class="site-content entry-content twentythirteen"><article class="hentry">';
		break;
	case 'twentyfourteen' :
		$out = '<div id="primary" class="content-area"><div id="content" role="main" class="site-content twentyfourteen"><div class="cc_twentyfourteen">';
		break;
	default :
		$out = '<div id="container"><div id="content" role="main">';
		break;

}

CC_Log::write( 'Content start for template: ' . $template . "\n" . $out );

echo $out;
