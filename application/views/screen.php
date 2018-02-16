<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<!-- en desarrollo la url base apunta al servidor de webpack (con live-reloading, etc) -->
	<base href="http://localhost:8080/">
	<!-- sin el servidor de webpack Codeigniter deberia pasar la url base por parametro -->
	<!-- <base href="http://pasante.sis.cooperativaobrera.coop/Signage/"> -->
	<title> Signage - Screen </title>
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
