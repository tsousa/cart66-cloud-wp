<?php
/**
 * Close the content with the appropriate tags based on active theme.
 *
 * This file is used in conjunction with content-start.php to attempt to get 
 * the page content styled according to the active theme.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$template = get_option( 'template' );
$out = '';

switch( $template ) {
	case 'twentyeleven' :
		$out = '</div></div>';
		break;
	case 'twentytwelve' :
		$out = '</div></div>';
		break;
	case 'twentythirteen' :
		$out = '</article></div></div>';
		break;
	case 'twentyfourteen' :
		$out = '</div></div></div>';
		get_sidebar( 'content' );
		break;
	default :
		$out = '</div></div>';
		break;
}

CC_Log::write( "Content end for template: $template\n$out" );

echo $out;
