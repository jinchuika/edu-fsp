<?php
$nivel_dir = 3;
include '../core/incluir.php';
$libs = new Incluir($nivel_dir);

$libs->incluir_clase('app/src/model/GnEscuela.class.php');

$fn_nombre = !empty($_POST['fn_nombre']) ? $_POST['fn_nombre'] : $_GET['fn_nombre'];
!empty($_POST['args']) ? $args = json_decode($_POST['args'], true) : json_decode($_GET['args'], true);

if($fn_nombre=='listar_escuela'){
    echo json_encode(f_listar_escuela());
}

function f_listar_escuela()
{
	$gn_escuela = new GnEscuela();
	return $gn_escuela->listar_escuela(null, '_id as value, nombre as text');
}
?>