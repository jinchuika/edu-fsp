<?php
$nivel_dir = 3;
include '../core/incluir.php';
$libs = new Incluir($nivel_dir);
$fn_nombre = Controlador::get_var('fn_nombre');
if(!empty($fn_nombre) && isset($fn_nombre)){
	switch ($fn_nombre) {
		case 'enviar_ayuda':
			$libs->incluir_clase('app/src/plugins/phpmailer/PHPMailerAutoload.php');
			echo json_encode(enviar_ayuda(Controlador::get_var('nombre'), Controlador::get_var('mail'), nl2br(Controlador::get_var('mensaje'))));
			break;
		
		default:
			# code...
			break;
	}
}

function enviar_ayuda($nombre, $mail, $mensaje)
{
	$phpmailer = new PHPMailer;
    $phpmailer->IsHTML(true);
    $phpmailer->CharSet = 'UTF-8';

    $phpmailer->From = $mail;
    $phpmailer->FromName = $nombre;
    $phpmailer->addReplyTo = 'lcontreras@funsepa.org';
    $phpmailer->addAddress('lcontreras@funsepa.org');
    //$phpmailer->addAddress('dsalazar@funsepa.org');
    $phpmailer->Subject = 'Ayuda en portal EDU';
    $phpmailer->Body = $mensaje;
    if(!$phpmailer->send()) {
    	return array('msj'=>'no', 'error'=>$phpmailer->ErrorInfo);
    } else {
        return array('msj'=>'si');
    }
}
?>