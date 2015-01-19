<?php
$nivel_dir = 2;
require_once("../../app/src/core/incluir.php");
$libs = new Incluir($nivel_dir);

$libs->incluir_clase('includes/auth/Login.class.php');
$libs->incluir_clase('app/src/model/User.class.php');
$libs->incluir_clase('app/src/plugins/phpmailer/PHPMailerAutoload.php');

$mail = $_GET['mail'];
if(!empty($mail)){
    if($cadena = crear_url($mail,$libs)){

        $url_recovery = $_SERVER['HTTP_HOST'].'/edu/app/public/pass_recovery.php?code='.$cadena;
        $phpmailer = new PHPMailer;
        $phpmailer->IsHTML(true);
        $phpmailer->CharSet = 'UTF-8';

        $phpmailer->From = 'webmaster@funsepa.net';
        $phpmailer->FromName = 'WebMaster FUNSEPA';
        $phpmailer->addReplyTo = 'webmaster@funsepa.net';
        $phpmailer->addAddress($mail);     // Add a recipient
        $phpmailer->Subject = 'Recuperación de contraseña en FUNSEPA';
        $cuerpo = '¿Solicitaste restablecer la contraseña para tu cuenta en FUNSEPA?<br><br><br>';
        $cuerpo .= 'Si solicitaste restablecer tu contraseña, dirígete a este enlace:<br><br>';
        $cuerpo .= '<a href="http://'.$url_recovery.'">http://'.$url_recovery.'</a><br><br><hr>';
        $cuerpo .= 'Si no solicitaste restablecer tu contraseña puedes ignorar este mensaje.';

        $phpmailer->Body    = $cuerpo;
        if(!$phpmailer->send()) {
            echo json_encode(array('msj'=>'no', 'error'=>$phpmailer->ErrorInfo));
        } else {
            echo json_encode(array('msj'=>'si'));
        }
    }
    else{
        echo json_encode(array('msj'=>'404'));
    }
}

function crear_url($mail, $libs)
{
    $login = new Login($libs);
    return $login->crear_string_recovery($mail);
}

function decodificar($cadena_eviada, $libs)
{
    $login = new Login($libs);
    return $login->validar_string_recovery($cadena_eviada);
}
?>