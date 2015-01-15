<?php
$nivel_dir = 2;
require_once("../../app/src/core/incluir.php");
$libs = new Incluir($nivel_dir);

$libs->incluir_clase('includes/auth/Login.class.php');
$libs->incluir_clase('app/src/model/User.class.php');

$mail = $_GET['mail'];
if(!empty($mail)){
	$user = new User($libs);
	$datos_user = $user->datos_password($mail);
	echo crear_url();
	echo Login::recuperar_password($datos_user['username'], $datos_user['salt']);

}

function crear_url($value='')
{
	//$uri = dirname('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']).'?code=';
	$uri = basename(__DIR__);
	return $uri;
}
?>