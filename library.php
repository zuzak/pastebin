<?php
function not_stored( $str ) {
	$str = escapeshellarg( $str );
	$contents = file_get_contents( 'pastes.log' );
	$pattern = preg_quote( $str, '/' );
	$pattern = "/^.*$pattern.*\$/m";
	if ( preg_match_all( $pattern, $contents, $matches ) ) {
		return false;
	} else {
		return true;
	}
}
