<?php
function not_stored( $str ) {
	$str = escapeshellarg( $str );
	$ret = -1;
	exec( "grep $str pastes.log", $foo, $ret );
	return $ret;
}
