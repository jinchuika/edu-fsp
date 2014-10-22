<?php
$fn_nombre = $_GET['fn_nombre'];
if(!empty($fn_nombre)){
	include '../model/User.class.php';
	$usuario = new User();
    if($fn_nombre=='crear_usuario'){
        $args = json_decode($_GET['args'], true);
        $args['extra'] = array();
        foreach ($args as $key => $value) {
            if(in_array($key, array('direccion', 'tel_casa', 'tel_movil', 'fecha_nacimiento'))){
                $args['extra'][$key] = $value;
            }
        }
        echo json_encode($usuario->crear_usuario($args));
    }
    elseif ($fn_nombre=='validar_nombre') {
        echo json_encode($usuario->$fn_nombre(array('campo' => 'username', 'valor' =>$_GET['username'])));
    }
    elseif ($fn_nombre=='validar_mail') {
        echo json_encode($usuario->validar_nombre(array('campo' => 'mail', 'valor' =>$_GET['mail']), 'usr_persona'));
    }
    else{
        echo json_encode($usuario->$fn_nombre($args));
    }
}
?>