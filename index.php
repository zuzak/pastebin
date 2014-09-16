<?php
define( 'PASTEBIN', 1 );
require 'library.php';
if ( isset( $_POST[ 'paste' ] ) ) {
	require 'paste.php';
} else if ( $_SERVER[ 'REQUEST_URI' ] !== '/' ) {
	if ( $_SERVER[ 'REQUEST_URI' ] == '/beta' ) {
		require 'beta.php';
	} else if ( substr( $_SERVER[ 'REQUEST_URI' ], 0, 4 ) == '/url' ) {
		require 'url.php';
	} else {
		require 'render.php';
	}
} else {
	require 'prompt.php';
}
?>
