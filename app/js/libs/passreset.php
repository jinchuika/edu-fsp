<?php
include '../../src/core/incluir.php';
$libs = new Incluir(3);

$bd = $libs->incluir('db');
$libs->incluir_clase('includes/auth/Login.class.php');

$query = "select _id, password from user where _id>3";
$stmt = $bd->ejecutar($query, true);
while ($user = $bd->obtener_fila($stmt, 0)) {
	$new_pass = Login::encriptar($user['password']);
	
	$query_pass = "UPDATE user SET password='".$new_pass['string']."', salt='".$new_pass['key']."' WHERE _id='".$user['_id']."'";
	//echo $query_pass;
	if($stmt2 = $bd->ejecutar($query_pass, true)){
		echo $user['password']."-".$new_pass['key']."-".$new_pass['string']."<br>";
	}
}
?>