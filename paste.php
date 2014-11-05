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
	$count = 0;
	while( !$fp ) {
		if ( $count > 2000 ) {
			header( 'HTTP/1.1 507 Insufficient Storage' );
			die( 'Unable to find suitable key.' );
		}
		$count++;
		$key = keygen();
		if ( not_stored( $key ) ) {
			$fn = "./pastes/$key";
			if ( !file_exists( $fn ) ) {
				$fp = fopen( './pastes/' . $key, 'x' );
			}
		}
	}
	if ( $fp ) {
		fwrite( $fp, $paste );
		fclose( $fp );


		$fp2 = fopen( 'pastes.log', 'a' );
		if ( !$fp2 ) {
			die('qq');
		}
		fwrite( $fp2, "\n" . $key );
		fclose( $fp2 );

		$dir = new DirectoryIterator( "pastes" );
		$x = 0;
		foreach ( $dir as $file ){
			$x += ( $file->isFile() ) ? 1 : 0;
		};
		// filter_var( $paste, FILTER_VALIDATE_URL
		if ( filter_var( $paste, FILTER_VALIDATE_URL ) ) {
			$msg = "New short URL #$x created at ";
			$msg .= filter_input( INPUT_SERVER, 'HTTPS' ) ? 'https://' : 'http://';
			$msg .= filter_input( INPUT_SERVER, 'SERVER_NAME' ) . "/url/$key (";
			$msg .= parse_url( $paste, PHP_URL_HOST ) . ")";
		} else {
			$msg = "Created paste #$x for " . filter_input( INPUT_SERVER, 'REMOTE_ADDR' ) . " at ";
			$msg .= filter_input( INPUT_SERVER, 'HTTPS' ) ? 'https://' : 'http://';
			$msg .= filter_input( INPUT_SERVER, 'SERVER_NAME' ) . "/$key";
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
		if ( filter_input( INPUT_SERVER, 'SERVER_NAME' ) === 'paste.oaosidl.org' ) {
			$port = 41340;
		} else {
			$port = 41337;
		}
		socket_sendto( $socket, $msg, strlen( $msg ), 0, "127.0.0.1", $port );
		socket_close( $socket );

		header( 'HTTP/1.1 302 Found' );
		header( 'Location: ' . $key );
		echo "Your paste has been created at $key.";

	} else {
		header( 'HTTP/1.1 500 Internal Server Error' );
		$err = error_get_last();
		die( "Something went wrong when saving your document.\n\n(" . $err['message'] . ")" );
	}
}
