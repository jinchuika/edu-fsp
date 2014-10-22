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
    $libs->incluir('cnb_js');
    ?>
</head>
<body>
    <?php echo $menu->imprimir(); ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <form id="form_clase" class="form-inline well">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="anno">Año</label>
                            <div class="col-md-4">
                                <select id="anno" name="anno" class="form-control col-sm-12">
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
                                <select id="grado" name="grado" class="form-control col-sm-12">
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
                            <label class="col-md-2 control-label" for="carrera">Carrera</label>
                            <div class="col-md-6">
                                <select id="carrera" name="carrera" class="form-control col-sm-12">
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
                                <button type="submit" id="btn_clase" name="btn_clase" class="btn btn-primary">Abrir plan</button>
                                <button type="button" id="btn_registro" name="btn_registro" class="btn btn-success" onclick="form_nuevo_registro();">Nuevo registro</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <table id="tabla_plan" class="table table-hover table-condensed table-bordered well">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Contenido MINEDUC</th>
                            <th>Contenido FUNSEPA</th>
                            <th>Actividad</th>
                            <th>Recurso</th>
                            <th>Método</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_plan">
                        
                    </tbody>
                </table>
            </div>
        </div>
        <form class="form-horizontal" style="visibility:hidden" id="form_registro">
            <fieldset>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="n_fecha">Fecha</label>  
                    <div class="col-md-4">
                        <input id="n_fecha" name="n_fecha" placeholder="DD/MM/AAAA" class="form-control input-md" required="" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="n_comp">Competencia</label>
                    <div class="col-md-5">
                        <select id="n_comp" name="n_comp" class="form-control select_cnb">
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="n_indicador">Indicador de logro</label>
                    <div class="col-md-5">
                        <select id="n_indicador" name="n_indicador" class="form-control select_cnb">
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="n_contenido">Contenido CNB</label>
                    <div class="col-md-5">
                        <select id="n_contenido" name="n_contenido" class="form-control select_cnb">
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="n_funsepa">Contenido FUNSEPA</label>
                    <div class="col-md-5">
                        <select id="n_funsepa" name="n_funsepa" class="form-control select_cnb" multiple="multiple">
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="n_metodo">Métodos de evaluación</label>
                    <div class="col-md-5">
                        <select id="n_metodo" name="n_metodo" class="form-control select_cnb" multiple="multiple">
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="n_actividad">Actividades</label>
                    <div class="col-md-4">                     
                        <textarea class="form-control" id="n_actividad" name="n_actividad"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="n_recursos">Recursos</label>
                    <div class="col-md-4">                     
                        <textarea class="form-control" id="n_recursos" name="n_recursos"></textarea>
                    </div>
                </div>
            </fieldset>
            </form>
    </div>
</body>
<script>
var __CNB__ = new Cnb();
var modal_c = modal_carga_gn();
var modal_form = [];
function form_registro  () {
    var contenido = $("#form_registro").clone(true);
    $(contenido).css('visibility','visible');
    $.each($(contenido).find('*'), function (index, item) {
        $(item).attr('id', $(item).attr('id')+'_temp');
    });
    $(contenido).attr('id', 'form_temp');
    return contenido;
} 
modal_c.crear();
var modal_registro = [];

function abrir_plan (id_plan) {
    modal_c.mostrar();
    $.ajax({
        url: nivel_entrada+'app/src/libs_plan/gn_plan.php',
        type: 'post',
        data: {
            fn_nombre: 'abrir_plan',
            id_plan: id_plan
        },
        success: function (respuesta) {
            var plan_actual = $.parseJSON(respuesta);
            $("#tabla_plan").find("tr:gt(0)").remove();
            $.each(plan_actual.arr_registro, function (index, registro_actual) {
                $('#tbody_plan').append(abrir_registro(registro_actual));
            });
            $('#tabla_plan').show();
            $('#btn_registro').attr('onclick', 'form_nuevo_registro('+plan_actual.id_grado+')');
            $('#btn_registro').show();
            modal_c.ocultar();
        }
    });
}

function abrir_registro (registro) {
    var s_tr = '<tr></tr>';
    var s_fecha = '<td id="fecha_'+registro._id+'">'+registro.fecha+'</td>';
    console.log(getObjects(__CNB__.arr_contenido, '_id', registro.id_contenido));
    var s_contenido = '<td id="contenido_'+registro._id+'">'+getObjects(__CNB__.arr_contenido, '_id', registro.id_contenido)[0].descripcion+'</td>';
    var s_funsepa = '<td>';
    for (var i = 0; i < registro.arr_funsepa.length; i++) {
        s_funsepa += getObjects(__CNB__.arr_funsepa, '_id', registro.arr_funsepa[i].id_funsepa)[0].descripcion+', ';
    };
    s_funsepa += '</td>';
    var s_metodo = '<td>';
    for (var i = 0; i < registro.arr_metodo.length; i++) {
        s_metodo += getObjects(__CNB__.arr_metodo, '_id', registro.arr_metodo[i].id_metodo)[0].descripcion+', ';
    };
    s_metodo += '</td>';
    var s_actividad = '<td id="actividad_'+registro._id+'">'+registro.actividad+'</td>';
    var s_recurso = '<td id="recurso_'+registro._id+'">'+registro.recurso+'</td>';
    return '<tr id="tr_'+registro._id+'">'+s_fecha+s_contenido+s_funsepa+s_actividad+s_recurso+s_metodo+'</tr>';
}

function form_nuevo_registro (id_grado) {
    modal_form = bootbox.dialog({
        title: 'Agregar fecha',
        message: function () {
            var form_ = form_registro();
            //$('#n_comp_temp').append('<option value="asd">asd</option>');
            return form_;
        },
        buttons: {
            success: {
                label: "Guardar",
                className: "btn-success",
                callback: function () {
                    console.log(datos_formulario($('#form_registro')));
                }
            }
        }
    });
    
    modal_form.on("shown.bs.modal", function() {
        $('#n_comp_temp').on('change', function () {
            populateSelect('n_indicador_temp', getObjects(__CNB__.arr_indicador, 'id_competencia', $(this).val()));
            $("#n_indicador_temp").select2();
            $('#n_indicador_temp').trigger('change');
        });
        $('#n_indicador_temp').on('change', function (value) {
            populateSelect('n_contenido_temp', getObjects(__CNB__.arr_contenido, 'id_indicador', $(this).val()));
            $("#n_contenido_temp").select2();
            $('#n_contenido_temp').trigger('change');
        });
        console.log(getObjects(__CNB__.arr_rel_contenido, 'id_mineduc', 4));
        $('#n_contenido_temp').on('change', function (value) {
            var arr_rel_funsepa = getObjects(__CNB__.arr_rel_contenido, 'id_mineduc', $(this).val());
            var arr_funsepa = {};
            for (var i = 0; i < arr_rel_funsepa.length; i++) {
                var reg_funsepa = getObjects(__CNB__.arr_funsepa, '_id', arr_rel_funsepa[i].id_funsepa);
                arr_funsepa[i] = reg_funsepa[0];
            };
            populateSelect('n_funsepa_temp', arr_funsepa);
            $("#n_funsepa_temp").select2();
        });
        populateSelect('n_comp_temp', getObjects(__CNB__.arr_competencia, 'id_grado', id_grado));
        $('#n_comp_temp').trigger('change');
        populateSelect('n_metodo_temp', __CNB__.arr_metodo);
        $("#n_comp_temp").select2();
        $("#n_metodo_temp").select2();
    });
}

/**
 * Llena un select
 * @param  {DOM.Element} el
 * @param  {Array} items
 * @return {[type]}       [description]
 */
function populateSelect(id_elemento, items) {
    $('#'+id_elemento).empty();
    $.each(items, function (index, item) {
        $('#'+id_elemento).append('<option value="'+item._id+'">'+item.descripcion+'</option>');
    });    
}

$(document).ready(function () {
    $('#form_clase').submit(function (e) {
        modal_c.mostrar();
        $('#tabla_plan').hide();
        $('#btn_registro').hide();
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