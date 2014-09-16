<?php
function getExtension( $ext ) {
	$extensions = array(
		"apache" => "apache_conf",
		"batchfile" => "sh",
		"c" => "c_cpp",
		"cpp" => "c_cpp",
		"tex" => "latex",
		"md" => "markdown",
		"pl" => "perl",
		"txt" => "plain_text",
		"py" => "python",
		"rb" => "ruby",
		"js" => "javascript"
	);
	if ( $extensions[$ext] ) {
		return $extensions[$ext];
	} else {
		return $ext;
	}
}
