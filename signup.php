<?php
$nivel_dir = 0;

require_once("app/src/core/incluir.php");

$libs = new Incluir($nivel_dir);
$libs->incluir('menu');
$libs->incluir_clase('app/src/model/GnEscuela.class.php');
$bd = $libs->incluir('db');
$menu = $libs->incluir('menu', array('nivel_dir'=>$nivel_dir));

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php
    $libs->incluir('html_template');
    ?>
    <meta charset="UTF-8">
    <title>Nuevo usuario</title>
</head>
<body>
    <?php echo $menu->imprimir(); ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <form id="form_nuevo" action="javascript:return;" class="form-horizontal well">
                    <fieldset>
                        <legend>Registra un nuevo usuario</legend>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="nombre">Nombre</label>  
                            <div class="col-md-5">
                                <input id="nombre" name="nombre" type="text" placeholder="" class="form-control input-md" required="">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="apellido">Apellido</label>  
                            <div class="col-md-5">
                                <input id="apellido" name="apellido" type="text" placeholder="" class="form-control input-md" required="">

                            </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="id_genero">Género</label>
                          <div class="col-md-4">
                            <select id="id_genero" name="id_genero" class="form-control">
                              <option value="1">Hombre</option>
                              <option value="2">Mujer</option>
                          </select>
                      </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-4 control-label" for="id_escuela">Escuela</label>  
                    <div class="col-md-5">
                        <select name="id_escuela" id="id_escuela" class="form-control input-md" >
                            <?php
                            $escuela = new GnEscuela($bd);
                            foreach ($escuela->listar_escuela() as $key => $value) {
                                echo '<option value="'.$value['_id'].'">'.$value['nombre'].'</option>
                                ';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="username">Nombre de usuario</label>  
                    <div class="col-md-5">
                        <input id="username" name="username" type="text" placeholder="" class="form-control input-md" required="required">

                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="password">Contraseña</label>
                    <div class="col-md-5">
                        <input id="password" name="password" type="password" placeholder="" class="form-control input-md" required="required">

                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="password2">Repite la contraseña</label>
                    <div class="col-md-4">
                        <input id="password2" name="password2" type="password" placeholder="" class="form-control input-md" required="required">

                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="mail">Correo electrónico</label>  
                    <div class="col-md-5">
                        <input id="mail" name="mail" type="email" placeholder="" class="form-control input-md" required="required">

                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="direccion">Dirección</label>  
                    <div class="col-md-6">
                        <input id="direccion" name="direccion" type="text" placeholder="" class="form-control input-md">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="tel_movil">Teléfono móvil</label>  
                    <div class="col-md-4">
                        <input id="tel_movil" name="tel_movil" type="text" placeholder="" class="form-control input-md">

                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="tel_casa">Teléfono fijo</label>  
                    <div class="col-md-4">
                        <input id="tel_casa" name="tel_casa" type="text" placeholder="" class="form-control input-md">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="enviar"></label>
                    <div class="col-md-4">
                        <input type="submit" id="enviar" name="enviar" class="btn btn-primary">
                    </div>
                </div>
            </fieldset>
        </form>

    </div>
</div>
</div>
</body>
<?php
$libs->imprimir('js', 'app/js/auth/signup_page.js');
?>
</html>