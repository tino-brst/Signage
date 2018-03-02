<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<!-- En desarrollo, la url base apunta al puerto donde corre el servidor Webpack (con live-reloading, etc) -->
	<!-- <base href="http://10.0.2.2:8089/"> -->
	<base href="http://localhost:8089/">
	<title> Signage - Screen </title>
	<link href="https://fonts.googleapis.com/css?family=Work+Sans" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="dist/screen.css">
</head>
<body>
	<div id="app"></div>
	<script>
			var udid = "<?=$screen_udid?>";
			// var socketUrl = "http://10.0.2.2:4000";
			var socketUrl = "http://localhost:4000";
	</script>
	<script type="text/javascript" src="dist/screen.js"></script>
</body>
</html>
