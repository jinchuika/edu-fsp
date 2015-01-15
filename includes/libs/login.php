<?php
$nivel_dir = 2;
require_once("../../app/src/core/incluir.php");
require_once("../auth/Login.class.php");
$libs = new Incluir($nivel_dir);

$db = $libs->incluir('db');

$login = new Login($_GET['rand_key']);
$usuario = $login->log_in($_POST['user'], $_POST['pass']);
if($usuario['valid'] ==true){
	$login->crear_sesion($usuario['id_user']);
	echo json_encode(array('msj'=>'si'));
}
else{
	echo json_encode(array('msj'=>'no'));
}
?>