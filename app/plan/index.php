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
    $libs->incluir('html_plan');
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
                            <label class="col-md-4 control-label" for="grado">Grado</label>
                            <div class="col-md-4">
                                <select id="grado" name="grado" class="form-control col-sm-12">

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="btn_clase"></label>
                            <div class="col-md-4">
                                <button type="submit" id="btn_clase" name="btn_clase" class="btn btn-primary">Abrir plan</button>
                                <button type="button" id="btn_registro" name="btn_registro" class="btn btn-success">Nuevo registro</button>
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
        <form class="form-horizontal" style="display: none;" id="form_registro">
            <fieldset>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="n_fecha">Fecha</label>  
                    <div class="col-md-4">
                        <input id="n_fecha" name="n_fecha" placeholder="DD/MM/AAAA" class="form-control input-md datepicker" required="" type="text">
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
                <input type="submit" class="btn btn-success">
            </fieldset>
        </form>
    </div>
</body>
<script>
var __CNB__ = new Cnb();
__CNB__.cargar_datos();
var modal_c = modal_carga_gn();
var modal_form = [];
modal_c.crear();

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
                abrir_registro(registro_actual, 'tbody_plan');
            });
            habilitar_reg();
            $('#tabla_plan').show();
            __CNB__.plan_actual = plan_actual;
            $('#btn_registro').show();
            modal_c.ocultar();
        }
    });
}

function abrir_registro (registro, objetivo) {
    var s_fecha = '<td id="fecha_'+registro._id+'">'+registro.fecha+'</td>';
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
    $('#'+objetivo).append('<tr id="tr_'+registro._id+'">'+s_fecha+s_contenido+s_funsepa+s_actividad+s_recurso+s_metodo+'</tr>');
    var s_drop = '<div class="dropdown">'+
    '<a data-toggle="dropdown" href="#"><span class="caret"></span></a>'+
    '<ul class="dropdown-menu" role="menu">'+
    '<li><a href="#" class="btn_editar_reg" data-id="'+registro._id+'">Editar</a></li>'+
    '<li><a href="#" class="btn_borrar_reg" data-id="'+registro._id+'">Borrar</a></li>'+
    '</ul>'+
    '</div>';
    $('#fecha_'+registro._id).append(s_drop);
}

function habilitar_reg () {
    $('.btn_editar_reg').off().on('click', function () {
        editar_registro($(this).data('id'));
    });
    $('.btn_borrar_reg').off().on('click', function () {
        borrar_registro($(this).data('id'));
    });
}

function editar_registro (id_registro) {
    console.log('Editato: '+id_registro);
}

/**
 * Borra el registro
 * @param  {integer} id_registro
 * @return
 */
function borrar_registro (id_registro) {
    bootbox.confirm('¿Está seguro de que desea borrar ese registro?', function (respuesta) {
        if(respuesta===true){
            $.post(nivel_entrada+'app/src/libs_plan/gn_plan.php?fn_nombre=borrar_registro', {
                id_registro: id_registro
            }, function (respuesta) {
                if(respuesta.msj=='si'){
                    $('#tr_'+id_registro).remove();
                    $.gritter.add({
                        title: 'Eliminado',
                        text: 'El registro se eliminó'
                    });
                }
                //(respuesta.msj=='si' ? $('#tr_'+id_registro).remove() : console.log('No se eliminó'));
            },
            'json');
        }
    });
}

/**
 * Llena un select
 * @param  {DOM.Element} el
 * @param  {Array} items
 * @return {[type]}       [description]
 */
 function populateSelect(id_elemento, items, usar_select2) {
    $('#'+id_elemento).empty();
    $.each(items, function (index, item) {
        $('#'+id_elemento).append('<option value="'+item._id+'">'+item.descripcion+'</option>');
    });
    $('#'+id_elemento).trigger('change');
    !usar_select2 ? '' : $('#'+id_elemento).select2();
}

/**
 * Llena los selects del formulario para un registro
 */
 function poblar_formulario () {
    //Poblar métodos
    populateSelect('n_metodo', __CNB__.arr_metodo, true);
    //Poblar indicadores
    $('#n_comp').off().on('change', function () {
        populateSelect('n_indicador', getObjects(__CNB__.arr_indicador, 'id_competencia', $(this).val()), true);
    });
    //Poblar contenido de mineduc
    $('#n_indicador').off().on('change', function (value) {
        populateSelect('n_contenido', getObjects(__CNB__.arr_contenido, 'id_indicador', $(this).val()), true);
    });
    //Poblar contenido de funsepa
    $('#n_contenido').off().on('change', function (value) {
        var arr_rel_funsepa = getObjects(__CNB__.arr_rel_contenido, 'id_mineduc', $(this).val())
        , arr_funsepa = {};
        for (var i = 0, reg_funsepa=[]; i < arr_rel_funsepa.length; i++) {
            reg_funsepa = getObjects(__CNB__.arr_funsepa, '_id', arr_rel_funsepa[i].id_funsepa);
            arr_funsepa[i] = reg_funsepa[0];
        };
        populateSelect('n_funsepa', arr_funsepa, true);
    });
    //Poblar competencias
    populateSelect('n_comp', getObjects(__CNB__.arr_competencia, 'id_grado', __CNB__.plan_actual.id_grado), true);
    
}

$(document).ready(function () {
    $('#form_clase').submit(function (e) {
        e.preventDefault();

        modal_c.mostrar();
        $('#tabla_plan').hide();
        $('#btn_registro').hide();
        
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

    $('#btn_registro').on('click', function () {
        bootbox.dialog({
            title: 'Nuevo registro',
            message: $('#form_registro'),
            show: false
        }).on('shown.bs.modal', function() {
            poblar_formulario();
            $('#n_fecha', this).datepicker()
            .prev('.btn').on('click', function (e) {
                e && e.preventDefault();
                $('#n_fecha', this).focus();
            });
            $('#form_registro').show().bootstrapValidator({
                fields:{
                    n_fecha:{
                        validators: {
                            notEmpty: {
                            },
                            date: {
                                format: 'DD/MM/YYYY'
                            }
                        }
                    }
                }
            }).datepicker();
            $('#n_fecha').on('dp.change dp.show', function(e) {
                $('#form_registro').bootstrapValidator('revalidateField', 'n_fecha');
            });
            $('#form_registro').bootstrapValidator('resetForm', true);
        }).on('hide.bs.modal', function(e) {
            $('#form_registro').hide().appendTo('body');
        })
        .on('success.form.bv', function(e) {
            e.preventDefault();
            var $form = $(e.target),
            bv = $form.data('bootstrapValidator');
            $.post(nivel_entrada+'app/src/libs_plan/gn_plan.php?nuevo_registro', {
                fn_nombre: 'crear_registro',
                id_plan: __CNB__.plan_actual._id,
                args: JSON.stringify(datos_formulario($form))
            },
            function(result) {
                console.log(result);
                abrir_registro(result[0], 'tbody_plan');
                habilitar_reg();
                $form.parents('.bootbox').modal('hide');
            }, 'json');
        })
        .modal('show');
    });

var arr_grado= new Array();
<?php
foreach ($cl_grado->listar_grado() as $key => $grado) {
    echo 'arr_grado['.$grado['_id'].'] = {_id: '.$grado['_id'].', descripcion: '.$grado['grado'].', id_carrera: '.$grado['id_carrera'].'};
    ';
}
?>

$('#carrera').on('change', function () {
    populateSelect('grado', getObjects(arr_grado, 'id_carrera', $(this).val()));
}).trigger('change');

});
</script>
</html>