<?php
require_once("../src/core/incluir.php");
$nivel_dir = 2;
$libs = new Incluir($nivel_dir);

$sesion = $libs->incluir('sesion');
$bd = $libs->incluir('db');
$menu = $libs->incluir('menu', array('nivel_dir'=>$nivel_dir, 'sesion'=>$sesion));

$sesion->validar_acceso();

$libs->incluir_clase('app/src/model/User.class.php');

$user_object = new User($bd);
$user = $user_object->abrir_usuario(array('user._id'=>$_GET['id']));
if(empty($user)){
    $user = $user_object->abrir_usuario(array('user._id'=>$sesion->get('id_user')));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $user['nombre'].' '.$user['apellido']; ?></title>
    <?php
    $libs->incluir('html_template');
    ?>
</head>
<body>
    <?php echo $menu->imprimir(); ?>
    <form class="form-horizontal well" id="form_login">
        <fieldset>
            <legend><?php echo $user['nombre'].' '.$user['apellido']; ?></legend>
            <div class="form-group">
                <label class="col-md-4 control-label" for="username">Nombre de usuario</label>  
                <div class="col-md-5">
                    <div class="lead"><?php echo $user['username']; ?></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="mail">Correo electrrónico</label>  
                <div class="col-md-5">
                    <div class="lead"><?php echo $user['mail']; ?></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="telefono">Teléfono</label>  
                <div class="col-md-5">
                    <div class="lead"><?php echo $user['telefono']; ?></div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-md-4 control-label" for="signup"></label>
                <div class="col-md-4">
                    <a href="signup.php" id="signup" name="signup" class="btn btn-info">Registrarse</a>
                </div>
            </div>
        </fieldset>
    </form>
</body>
</html>