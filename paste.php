<?php
if ( !defined( 'PASTEBIN' ) ) {
	die( 'This script is not accessible from the web.' );
}

function keygen() {
	$consonants = 'bcdfghjklmnpqrstvwxyz';
	$vowels = 'aeiou';
	$str = $consonants[ rand( 0, strlen( $consonants ) - 1 ) ];
	$str .= $vowels[ rand( 0, strlen( $vowels ) - 1 ) ];
	$str .= $consonants[ rand( 0, strlen( $consonants ) - 1 ) ];
	return $str;
}

$paste = filter_input( INPUT_POST, 'paste' );
if ( $paste ) {
	if ( strlen( $paste ) == 0 ) {
		header( 'HTTP/1.1 400 Bad Request' );
		die( 'Unable to save an empty paste.' );
	}
	$fp = null;
	$key = null;
	while( !$fp ) {
		$key = keygen();
		if ( not_stored( $key ) ) {
			$fp = fopen( './pastes/' . $key, 'x' );
		}
	}
	if ( $fp ) {
		fwrite( $fp, $paste );
		fclose( $fp );

		header( 'HTTP/1.1 302 Found' );
		header( 'Location: ' . $key );
		echo "Your paste has been created at $key.";

		$fp2 = fopen( './pastes.log', 'a' );
		fwrite( $fp2, "\n" . $key );
		fclose( $fp2 );

		if ( !$_POST[ 'unlisted' ] ) {
			$dir = new DirectoryIterator( "pastes" );
			$x = 0;
			foreach ( $dir as $file ){
				$x += ( $file->isFile() ) ? 1 : 0;
			};
			// filter_var( $paste, FILTER_VALIDATE_URL
			if ( filter_var( $paste, FILTER_VALIDATE_URL ) ) {
				$msg = "New short URL #$x created at ";
				$msg .= $_SERVER[ 'HTTPS' ] ? 'https://' : 'http://';
				$msg .= $_SERVER['SERVER_NAME'] . "/url/$key (";
				$msg .= parse_url( $paste, PHP_URL_HOST ) . ")";
			} else {
				$msg = "Created paste #$x for " . $_SERVER['REMOTE_ADDR'] . " at ";
				$msg .= $_SERVER[ 'HTTPS' ] ? 'https://' : 'http://';
				$msg .= $_SERVER['SERVER_NAME'] . "/$key";
				$tmp = substr( $paste, 0, 150 );
				//$tmp = str_replace( array( "\r\n", "\r", "\n" ), '↵', $tmp );
				$tmp = explode("\r\n",$tmp)[0];
				$msg .= " → $tmp";
				$tmp = null;
				if ( strlen( $paste ) > 150 ) {
					$msg .= "…";
				}
			}
			$socket = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
			if ( $_SERVER['SERVER_NAME'] === 'paste.oaosidl.org' ) {
				$port = 41340;
			} else {
				$port = 41337;
			}
			socket_sendto( $socket, $msg, strlen( $msg ), 0, "127.0.0.1", $port );
			socket_close( $socket );
		}

	} else {
		header( 'HTTP/1.1 500 Internal Server Error' );
		$err = error_get_last();
		die( "Something went wrong when saving your document.\n\n(" . $err['message'] . ")" );
	}
}
?>
