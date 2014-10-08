<?php
$nivel_dir = 2;
require_once("../src/core/incluir.php");

$libs = new Incluir($nivel_dir);
$menu = $libs->incluir('menu', array('nivel_dir'=>$nivel_dir));
$sesion = $libs->incluir('sesion');
$sesion->validar_acceso();
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
    <?php echo $menu->imprimir(); ?>
</body>
</html>