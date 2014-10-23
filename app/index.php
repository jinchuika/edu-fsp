<?php
header( 'Location: plan');
$nivel_dir = 1;
require_once("src/core/incluir.php");
$libs = new Incluir($nivel_dir);
$libs->incluir('menu');
$sesion = $libs->incluir('sesion');
$sesion->validar_acceso();
$menu = new Menu;
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Educación FUNSEPA</title>
    <?php
    $libs->incluir('html_template');
    ?>
</head>
<body>
	
</body>
</html>