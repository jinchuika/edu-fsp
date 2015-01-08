<?php
require_once("../src/core/incluir.php");
$nivel_dir = 2;
$libs = new Incluir($nivel_dir);
//Seguridad
$sesion = $libs->incluir('sesion');
$sesion->validar_acceso();

//core
$bd = $libs->incluir('db');
$menu = $libs->incluir('menu', array('nivel_dir'=>$nivel_dir, 'sesion'=>$sesion));

//clases
$libs->incluir_clase('app/src/model/User.class.php');
$libs->incluir_clase('app/src/model/GnEscuela.class.php');

//globals
$user_object = new User($libs);
$user = $user_object->abrir_usuario(array('user._id'=>$_GET['id']));
if(empty($user)){
    $user = $user_object->abrir_usuario(array('user._id'=>$sesion->get('id_user')));
}
$gn_escuela = new GnEscuela($bd);
$user['escuela'] = $gn_escuela->abrir_escuela(array('_id' => $user['id_escuela']));
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
    <div class="row-fluid">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <input type="hidden" value="<?php echo $user['_id']; ?>" id="id_user"></input>
            <form class="form-horizontal well" id="form_login">
                <fieldset>
                    <legend><?php echo $user['nombre'].' '.$user['apellido']; ?></legend>
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="username">Nombre de usuario</label>  
                        <div class="col-md-5">
                            <a href="#" id="username" data-pk="<?php echo $user['_id']; ?>" data-name="username" class="lead campo"><?php echo $user['username']; ?></a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="mail">Correo electrrónico</label>  
                        <div class="col-md-5">
                            <a href="#" id="mail" data-pk="<?php echo $user['_id']; ?>" data-name="mail" class="lead campo"><?php echo $user['mail']; ?></a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="tel_movil">Teléfono</label>  
                        <div class="col-md-5">
                            <a href="#" id="tel_movil" data-pk="<?php echo $user['_id']; ?>" data-name="tel_movil" class="lead campo"><?php echo $user['tel_movil']; ?></a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="direccion">Dirección</label>  
                        <div class="col-md-5">
                            <a href="#" id="direccion" data-pk="<?php echo $user['_id']; ?>" data-name="direccion" class="lead campo"><?php echo $user['direccion']; ?></a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="escuela">Escuela</label>  
                        <div class="col-md-5">
                            <a href="#" id="id_escuela" data-pk="<?php echo $user['_id']; ?>" data-name="id_escuela" class="lead campo"><?php echo $user['escuela']['nombre']; ?></a>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="btn-edit"></label>
                        <div class="col-md-4">
                            <a href="#" id="btn-edit" data-accion="true" name="btn-edit" class="btn btn-info btn-mini">Editar perfil</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="btn-password"></label>
                        <div class="col-md-4">
                            <a href="#" id="btn-password" data-accion="true" name="btn-password" class="btn btn-primary btn-mini">Cambiar contraseña</a>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="col-md-2"></div>
    </div>
    
</body>
<?php $libs->imprimir('js', 'app/js/usr/index.js'); ?>
<?php $libs->imprimir('js', 'app/js/gn/GnEscuela.class.js'); ?>
</html>