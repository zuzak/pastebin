<?php require 'extensions.php';
if ( getExtension( $extension ) == 'markdown' ) {
	require 'markdownrender.php';
	die;
} ?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo "$slug.$extension · " . date( 'Y-m-d H:i:s', filemtime( "pastes/$slug" ) ); ?></title>
	<script src="//cdn.jsdelivr.net/ace/1.1.3/min/ace.js"></script>
	<script src="//cdn.jsdelivr.net/ace/1.1.3/min/mode-<?php echo getExtension($extension); ?>.js"></script>
	<style>
		#editor {
			position: absolute;
			top:0;
			right:0;
			bottom:0;
			left:0;
			font-size:120%;
		}
	</style>
</head>
<body>
	<div id="editor"><?php echo htmlspecialchars($paste); ?></div>
	<script>
	var editor = ace.edit( 'editor' );
	editor.getSession().setMode( 'ace/mode/<?php echo getExtension( $extension ); ?>' );
	editor.setTheme( 'ace/theme/ambiance' ); // sic
	// editor.setReadOnly( true ); // TODO: paste from here
	</script>
</body>
</head>
