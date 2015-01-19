<?php
$fn_nombre = !empty($_POST['fn_nombre']) ? $_POST['fn_nombre'] : $_GET['fn_nombre'];
$args = !empty($_POST['args']) ? json_decode($_POST['args'], true) : json_decode($_GET['args'], true);

if(!empty($fn_nombre)){
    include '../model/User.class.php';
    

    if($fn_nombre=='crear_usuario'){
        echo json_encode(f_crear_usuario($args));
    }

    if($fn_nombre=='validar_nombre'){
        echo json_encode(f_validar_nombre($_GET['username']));
    }

    if($fn_nombre=='validar_mail'){
        echo json_encode(f_validar_mail($_GET['mail']));
    }

    if($fn_nombre=='editar_usuario'){
        echo json_encode(f_editar_usuario($_POST['pk'], $_POST['name'], $_POST['value'], 'user'));
    }

    if($fn_nombre=='editar_persona'){
        echo json_encode(f_editar_usuario($_POST['pk'], $_POST['name'], $_POST['value'], 'usr_persona'));
    }

    if($fn_nombre=='cambiar_password'){
        echo json_encode(f_cambiar_password($_GET['id_user'], $_GET['old_pass'], $_GET['new_pass']));
    }

    if($fn_nombre=='nuevo_password'){
        echo json_encode(f_nuevo_password($_POST['username'], $_POST['password'], $_POST['salt']));
    }
}

function f_crear_usuario($args)
{
    $usuario = new User();
    $args['extra'] = array();
    foreach ($args as $key => $value) {
        if(in_array($key, array('direccion', 'tel_casa', 'tel_movil', 'fecha_nacimiento'))){
            $args['extra'][$key] = $value;
        }
    }
    return $usuario->crear_usuario($args);
}

function f_validar_nombre($username)
{
    $usuario = new User();
    return $usuario->validar_nombre(array('campo'=>'username', 'valor'=>$username));
}

function f_validar_mail($mail)
{
    $usuario = new User();
    return $usuario->validar_nombre(array('campo'=>'mail', 'valor'=>$mail), 'usr_persona');
}

function f_editar_usuario($id_user, $campo, $valor, $tabla)
{
    $usuario = new User();
    return $usuario->editar_usuario($id_user, $campo, $valor, $tabla);
}
function f_cambiar_password($id_user, $old_pass, $new_pass)
{
    $usuario = new User();
    return $usuario->cambiar_password($id_user, $old_pass, $new_pass);
}

function f_nuevo_password($username, $new_pass, $salt)
{
    $usuario = new User();
    return $usuario->nuevo_password($username, $new_pass, $salt);
}
?>