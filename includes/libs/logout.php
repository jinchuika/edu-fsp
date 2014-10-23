<?php
require_once("../auth/Sesion.class.php");
$sesion = Sesion::getInstance(null, '../../');
$usuario = $sesion->get("id_user");
if(!empty($usuario))
{
    if($sesion->termina_sesion()){
        $sesion->validar_acceso();
    }
}
?>