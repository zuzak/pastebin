<?php
if ( !defined( 'PASTEBIN' ) ) {
	header('HTTP/1.1 403 Forbidden');
	die();
}
if ( !isset( $_SESSION ) ) {
	session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Pastebin</title>
	<style>
		html{height:100%}body,html{margin:0;padding:0}body,form{height:100%}
		textarea{background:#202020;color:#fff;padding-left:2em;padding-top:
		2em;width:100%;height:100%;outline:0;border:0;font-size:120%;
		font-family:monospace}button{padding:1em 2em;font-size:140%;
		border-bottom-left-radius:10px;border:1px solid #ccc;
		background-color:#363636;color:#fff}button:focus,button:hover{
		box-shadow:0 1px rgba(0,0,0,.1),inset 0 -3px rgba(0,0,0,.2)}.controls
		{position:fixed;top:0;right:0;font-family:sans-serif;}.controls div{background-color:#444;
		margin-left:1em;border-left:1px solid #ccc;color:#ccc;}
		.controls div:last-child{border-bottom-left-radius:3px;border-bottom:1px solid #ccc}
	</style>
</head>
<body>
	<form method="POST">
		<div class="controls">
			<button value="save" title="Click here to save the paste.">Save</button>
			<div>
				<input type="checkbox" id="unlisted" name="unlisted" />
				<label for="unlisted" title="Check this box to suppress the paste creation from being announced in any feeds.">Suppress</label>
			</div>
		</div>
		<textarea name="paste" placeholder="Start typing here!"></textarea>
	</form>
	<script>
		// http://stackoverflow.com/a/18303822
		document.querySelector("textarea").addEventListener("keydown",
		function(e){if(e.keyCode===9){var t=this.selectionStart;
		var n=this.selectionEnd;var r=e.target;var i=r.value;
		r.value=i.substring(0,t)+"	"+i.substring(n);
		this.selectionStart=this.selectionEnd=t+1;e.preventDefault()}},
		false)
	</script>
</body>
</html>
