<?php
$query = $_SERVER[ 'REQUEST_URI' ];
$query = explode( '.', $query );
$slug = $query[0];
if ( count( $query ) > 1 ) {
	$extension = $query[1];
} else {
	$extension = null;
}
if ( file_exists( "pastes/$slug" ) ) {
	$fp = fopen( 'pastes/' . $slug, 'r' );
	if ( !$fp ) {
		error( 403 );
	}
	$paste = fread( $fp, filesize( 'pastes/' . $slug ) );
	fclose( $fp );
	if ( !$extension ) {
		header( 'Content-Type: text/plain' );
		echo $paste;
	} else {
		require 'fancyrender.php';
	}
} else {
	if ( not_stored( substr( $slug, 1 ) ) ) {
		if ( strlen( $slug ) !== 4 ) {
			error( 400, 'slug wrong length' );
		} else {
			if ( strpos( 'bcdefghjklmnpqrstvwxyz', substr( $slug, 1, 1 ) ) === FALSE ) {
				error( 400, substr( $slug, 1, 1 ) );
			} else if ( strpos( 'bcdefghjklmnpqrstvwxyz', substr( $slug, 3, 1 ) ) === FALSE ) {
				error( 400 );
			} else if ( strpos( 'aeiou', substr( $slug, 2, 1 ) ) === FALSE ) {
				error( 400 );
			} else {
				error( 404 );
			}
		}
	} else {
		error( 410 );
	}
}

function error( $code, $debug = null ) {
	if ( function_exists( 'http_response_code' ) ) {
		http_response_code( $code );
	} else {
		header( "HTTP/1.1 $code" );
	}
	header( 'Content-Type: text/plain' );
	$output = file_get_contents( "errors/$code.txt" );
	echo $output;
	if ( $debug ) {
		$err = "Debug info: $debug";
		$err = "\n\n\n" . str_repeat( '-', strlen( $err ) ) . "\n$err";
		echo $err;
	}
}
?>
