<?php
$nivel_dir = 2;
require_once("../src/core/incluir.php");

$libs = new Incluir($nivel_dir);
$menu = $libs->incluir('menu', array('nivel_dir'=>$nivel_dir));
$sesion = $libs->incluir('sesion');
$sesion->validar_acceso();
$bd = $libs->incluir('db');

$libs->incluir_clase('app/src/model/ClAnno.class.php');
$libs->incluir_clase('app/src/model/ClCarrera.class.php');
$libs->incluir_clase('app/src/model/ClGrado.class.php');

$cl_anno = new ClAnno($bd);
$cl_carrera = new ClCarrera($bd);
$cl_grado = new ClGrado($bd);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Educación FUNSEPA</title>
    <?php
    $libs->incluir('html_template');
    ?>
</head>
<body>
    <?php echo $menu->imprimir(); ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-3 well">
                <form id="form_clase" class="form-horizontal">
                    <fieldset>
                        <legend>Grado</legend>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="anno">Año</label>
                            <div class="col-md-4">
                                <select id="anno" name="anno" class="form-control">
                                    <?php
                                    foreach ($cl_anno->listar_anno() as $key => $anno) {
                                        echo '<option value="'.$anno['_id'].'">'.$anno['anno'].'</option>
                                        ';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="grado">Grado</label>
                            <div class="col-md-4">
                                <select id="grado" name="grado" class="form-control">
                                    <?php
                                    foreach ($cl_grado->listar_grado() as $key => $grado) {
                                        echo '<option value="'.$grado['_id'].'">'.$grado['grado'].'</option>
                                        ';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="carrera">Carrera</label>
                            <div class="col-md-4">
                                <select id="carrera" name="carrera" class="form-control">
                                    <?php
                                    foreach ($cl_carrera->listar_carrera() as $key => $carrera) {
                                        echo '<option value="'.$carrera['_id'].'">'.$carrera['carrera'].'</option>
                                        ';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="btn_clase"></label>
                            <div class="col-md-4">
                                <button id="btn_clase" name="btn_clase" class="btn btn-primary">Abrir plan</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="col-sm-9">
                <table id="tabla_plan" class="table table-hover well">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Competencia</th>
                            <th>Indicador de logro</th>
                            <th>Contenido</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_plan">
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<script>
function abrir_plan (id_plan) {
    $.ajax({
        url: nivel_entrada+'app/src/libs_plan/gn_plan.php',
        type: 'post',
        data: {
            fn_nombre: 'abrir_plan',
            id_plan: id_plan
        },
        success: function (respuesta) {
            var plan_actual = $.parseJSON(respuesta);
            $.each(plan_actual.arr_fila, function (index, fila_actual) {
                crear_fila(fila_actual._id);
            });
        }
    });
}
function crear_fila (f) {
    // body...
}
    $(document).ready(function () {
        $('#form_clase').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: nivel_entrada+'app/src/libs_plan/gn_plan.php',
                type: 'post',
                data: {
                    fn_nombre: 'buscar_plan',
                    args: JSON.stringify(datos_formulario($('#form_clase')))
                },
                success: function (respuesta) {
                    var respuesta = $.parseJSON(respuesta);
                    if(respuesta.msj=='si'){
                        abrir_plan(respuesta._id);
                    }
                }
            });
        });
    });
</script>
</html>