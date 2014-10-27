<?php
$query = substr( $_SERVER[ 'REQUEST_URI' ], 5 );
$query = explode( '.', $query );
$slug = $query[0];
$extension = $query[1];
$fp = fopen( 'pastes/' . $slug, 'r' );
if ( !$fp ) {
	header( 'HTTP/1.1 404 Not Found' );
	header( 'Content-Type: text/plain' );
	die( 'Error: No paste found with this identifier.' );
}
$paste = fread( $fp, filesize( 'pastes/' . $slug ) );
fclose( $fp );
if ( !$extension ) {
	header( 'Content-Type: text/plain' );
	if ( filter_var( $paste, FILTER_VALIDATE_URL ) ) {
		header( "Location: $paste" );
	}
	echo $paste;
} else {
	require 'fancyrender.php';
}
