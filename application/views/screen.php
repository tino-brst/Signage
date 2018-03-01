<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<!-- en desarrollo la url base apunta al servidor de webpack (con live-reloading, etc) -->
	<!-- En caso de estar usando el emulador de Android, usar "http://10.0.2.2:8089/" -->
	<base href="http://10.0.2.2:8089/">
	<title> Signage - Screen </title>
	<link href="https://fonts.googleapis.com/css?family=Work+Sans" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="dist/screen.css">
</head>
<body>
	<div id="app"></div>
	<script>
			var udid = "<?=$screen_udid?>";
	</script>
	<script type="text/javascript" src="dist/vendor.js"></script>
	<script type="text/javascript" src="dist/screen.js"></script>
</body>
</html>
