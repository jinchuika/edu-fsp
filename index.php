<?php
$nivel_dir = 0;
require_once("app/src/core/incluir.php");
$libs = new Incluir($nivel_dir);
$sesion = $libs->incluir('sesion');
if($sesion->get('id_user')){
    header( 'Location: app');
}
$menu = $libs->incluir('menu');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php
    $libs->incluir('html_template');
    ?>
    <meta charset="UTF-8">
    <title>Educación</title>
</head>
<body>
    <?php echo $menu->imprimir(); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <form class="form-horizontal well" id="form_login">
                    <fieldset>
                        <legend>Inicia sesión</legend>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="username">Nombre de usuario</label>  
                            <div class="col-md-5">
                                <input id="username" name="username" type="text" placeholder="" class="form-control input-md" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="password">Contraseña</label>
                            <div class="col-md-5">
                                <input id="password" name="password" type="password" placeholder="" class="form-control input-md" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="login"></label>
                            <div class="col-md-6">
                                <div class="btn-group">
                                    <button type="submit" id="login" name="login" class="btn btn-success">Iniciar sesión</button>
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="#" id="btn-recovery">Recuperar contraseña</a></li>
                                    </ul>
                                </div>
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
            </div>
        </div>
    </div>
</body>
<?php
$libs->imprimir('js', 'app/js/auth/Auth.js');
$libs->imprimir('js', 'app/js/auth/login_page.js');
?>
</html>