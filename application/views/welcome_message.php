<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<!-- en desarrollo la url base apunta al servidor de webpack (con live-reloading, etc) -->
	<base href="http://localhost:8080/">
	<!-- en produccion Codeigniter pasa la url base por parametro -->
	<!-- <base href="...base_url()..." -->
	<title> Codeigniter + Webpack </title>
	<link rel="stylesheet" type="text/css" href="dist/admin.css">
</head>
<body>
	<div id="app"></div>
	
	<script type="text/javascript" src="dist/vendor.js"></script>
	<script type="text/javascript" src="dist/admin.js"></script>
</body>
</html>