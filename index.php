<?php
$nivel_dir = 0;
require_once("app/src/core/incluir.php");
$libs = new Incluir($nivel_dir);
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
    <div class="container">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
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
                            <div class="col-md-4">
                                <button type="submit" id="login" name="login" class="btn btn-success">Iniciar sesión</button>
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
<script>
function encriptar_string (enc_string) {
    var resultado = '';
    for (var i = 0; i < enc_string.length; i++) {
        var enc_key = Math.floor((Math.random() * 50) +1);
        resultado += '-'+(enc_string.charCodeAt(i) * enc_key)+'.'+(enc_key-1);
    };
    return resultado;
}
$(document).ready(function () {
    $('#form_login').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: 'includes/libs/login.php',
            type: 'post',
            data: {
                user: $('#username').val(),
                pass: encriptar_string($('#password').val())
            },
            success: function (respuesta) {
                var respuesta = $.parseJSON(respuesta);
                if(respuesta.msj=='si'){
                    window.location = (respuesta.url ? respuesta.url : 'app');
                }
            }
        });
    });
});
</script>
</html>