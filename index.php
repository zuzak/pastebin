<?php
define( 'PASTEBIN', 1 );
require 'vendor/autoload.php';
require 'library.php';
if ( filter_input( INPUT_POST, 'paste' ) !== null ) {
	require 'paste.php';
} else if ( $_SERVER[ 'REQUEST_URI' ] !== '/' ) {
	if ( substr( $_SERVER[ 'REQUEST_URI' ], 0, 4 ) == '/url' ) {
		require 'url.php';
	} else {
		require 'render.php';
	}
} else {
	require 'prompt.php';
}
?>
