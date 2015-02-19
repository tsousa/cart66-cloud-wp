<?php
/**
 * Output necessary content wrappers based on active theme
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$template = get_option( 'template' );
$out = '';

CC_Log::write('template for starting out: ' . $template );

switch( $template ) {

	case 'twentyeleven' :
		$out = '<div id="primary" class="site-content"><div id="content" role="main">';
		break;
	case 'twentytwelve' :
		$out = '<div id="primary" class="site-content"><div id="content" role="main">';
		break;
	case 'twentythirteen' :
		$out = '<div id="primary" class="content-area"><div id="content" role="main" class="site-content entry-content twentythirteen"><article class="hentry">';
		break;
	case 'twentyfourteen' :
        wp_enqueue_style( 'cc_twentyfourteen', CC_URL .'templates/css/twentyfourteen.css' );
		$out = '<div id="primary" class="content-area"><div id="content" role="main" class="site-content twentyfourteen"><div class="cc-twentyfourteen">';
		break;
    case 'twentyfifteen' :
        wp_enqueue_style( 'cc_twentyfifteen', CC_URL .'templates/css/twentyfifteen.css' );
        $out = '<div id="primary" class="content-area"><main id="main" class="site-main"><article class="page hentry">';
        break;
    case 'reddle':
        $out = '<div id="primary"><div id="content" role="main">';
        break;
    case 'patus':
        $out = '<div id="primary" class="content-area"> <main id="main" class="site-main" role="main">';
        break;
	default :
		$out = '<div id="container"><div id="content" role="main">';
		break;

}

echo apply_filters( 'cc_before_main_content_markup', $out );
