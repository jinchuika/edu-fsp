var __CNB__ = new Cnb();
var modal_c = modal_carga_gn();
var barra_carga = new BarraCargaInf();
var modal_form = [];
modal_c.crear();

/**
 * Abre el plan desde la base de datos
 * @param  {integer} id_plan
 * @uses abrir_registro()
 * @uses habilitar_reg()
 */
function abrir_plan (id_plan, publico) {
    __CNB__.cargar_datos();
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
            if(publico!=true){
                habilitar_reg();
                $('.export').show();
                $('#btn_registro').show();
                $('#btn_export').show();
                $('.lbl_public').hide();
            }
            else{
                $('.lbl_public').show();
            }
            $('#tabla_plan').show();
            __CNB__.plan_actual = plan_actual;
            $("#tabla_plan").stupidtable();
            modal_c.ocultar();
        }
    });
}

/**
 * Agrega un registro a la tabla
 * @param  {Array} registro
 * @param  {string} objetivo tabla
 * @uses getObjects()
 * @uses formato_fecha()
 * @uses bloquear_contenido()
 * @todo Habilitar edición
 */
function abrir_registro (registro, objetivo) {
    var s_fecha = '<td id="fecha_'+registro._id+'">'+formato_fecha(registro.fecha)+'</td>';
    var s_contenido = '<td id="contenido_'+registro._id+'" data-id="'+registro.id_contenido+'">'+bloquear_contenido(registro.id_contenido).descripcion+'</td>';
    var s_funsepa = '<td>';
    for (var i = 0; i < registro.arr_funsepa.length; i++) {
        var temp_funsepa = getObjects(__CNB__.arr_funsepa, '_id', registro.arr_funsepa[i].id_funsepa)[0];
        s_funsepa += '<a href="'+temp_funsepa.link+'">'+temp_funsepa.descripcion+'</a>, ';
    };
    s_funsepa += '</td>';
    var s_metodo = '<td>';
    for (var i = 0; i < registro.arr_metodo.length; i++) {
        var temp_metodo = getObjects(__CNB__.arr_metodo, '_id', registro.arr_metodo[i].id_metodo)[0];
        s_metodo += '<a href="'+temp_metodo.link+'">' +temp_metodo.descripcion+'</a>, ';
    };
    s_metodo += '</td>';
    var s_actividad = '<td id="actividad_'+registro._id+'">'+registro.actividad+'</td>';
    var s_recurso = '<td id="recurso_'+registro._id+'">'+registro.recurso+'</td>';
    $('#'+objetivo).append('<tr id="tr_'+registro._id+'">'+s_fecha+s_contenido+s_funsepa+s_actividad+s_recurso+s_metodo+'</tr>');
    var s_drop = '<div class="dropdown">'+
    '<a data-toggle="dropdown" href="#"><i class="fa fa-cog"></i></a>'+
    '<ul class="dropdown-menu" role="menu">'+
    //'<li><a href="#" class="btn_editar_reg" data-id="'+registro._id+'">Editar</a></li>'+
    '<li><a href="#" class="btn_borrar_reg" data-id="'+registro._id+'">Borrar</a></li>'+
    '</ul>'+
    '</div>';
    $('#fecha_'+registro._id).append(s_drop);
}

/**
 * Bloquea un contenido de la instancia de CNB actual
 * @param  {string} id_contenido
 * @return {Object} el contenido bloqueado
 */
function bloquear_contenido (id_contenido) {
    var contenido_temp = getObjects(__CNB__.arr_contenido, '_id', id_contenido)[0];
    __CNB__.en_uso[contenido_temp._id] = contenido_temp;
    delete __CNB__.arr_contenido[contenido_temp._id];
    return(contenido_temp);
}
/**
 * Habilita un contenido de la instancia de CNB actual
 * @param  {string} id_contenido
 * @return {Object} el contenido habilitado
 */
function habilitar_contenido (id_contenido) {
    var contenido_temp = getObjects(__CNB__.en_uso, '_id', id_contenido)[0];
    __CNB__.arr_contenido[contenido_temp._id] = contenido_temp;
    delete __CNB__.en_uso[contenido_temp._id];
    return(contenido_temp);
}

function publicar_plan (id_plan, tipo) {
    $.post(nivel_entrada+'app/src/libs_plan/gn_plan.php?fn_nombre=publicar_plan', {
        id_plan: id_plan,
        tipo: tipo
    }, function (respuesta) {
        if(respuesta.msj=='si' && tipo==1){
            bootbox.alert('Puede compartir su planificación usando el siguiente link<br><br><b>'+ location.host + location.pathname+'?id='+id_plan+'</b>');
        }
        if(respuesta.msj=='si' && tipo==0){
            $.gritter.add({
                title: 'Sin compartir',
                text: 'La planificación actual no se compartirá'
            });
        }
    },
    'json');
}

/**
 * Habilita que los registros puedan ser editado/eliminado
 * @see borrar_registro()
 */
function habilitar_reg () {
    /*$('.btn_editar_reg').off().on('click', function () {
        editar_registro($(this).data('id'));
    });*/
    $('.btn_borrar_reg').off().on('click', function () {
        borrar_registro($(this).data('id'));
    });
}

function editar_registro (id_registro) {
//    console.log('Editato: '+id_registro);
}

/**
 * Borra el registro
 * @param  {integer} id_registro
 * @return
 */
 function borrar_registro (id_registro) {
    bootbox.confirm('¿Está seguro de que desea borrar ese registro?', function (respuesta) {
        barra_carga.mostrar();
        if(respuesta===true){
            $.post(nivel_entrada+'app/src/libs_plan/gn_plan.php?fn_nombre=borrar_registro', {
                id_registro: id_registro
            }, function (respuesta) {
                if(respuesta.msj=='si'){
                    habilitar_contenido($('#contenido_'+id_registro).data('id'));
                    $('#tr_'+id_registro).remove();
                    $.gritter.add({
                        title: 'Eliminado',
                        text: 'El registro se eliminó'
                    });
                }
                barra_carga.ocultar();
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
    $('#btn_registro').hide();
    $('.export').hide();
    $("#btn_export").click(function () {
        $("#tabla_plan").btechco_excelexport({
            containerid: "tabla_plan"
            , datatype: $datatype.Table
        });
    });
    $('#btn_public').on('click', function () {
        bootbox.dialog({
            message: "Decida si esta planificación es pública",
            title: "Compartir",
            buttons: {
                success: {
                    label: "Compartir",
                    className: "btn-success",
                    callback: function() {
                        publicar_plan(__CNB__.plan_actual._id, 1);
                    }
                },
                danger: {
                    label: "No compartir",
                    className: "btn-danger",
                    callback: function() {
                        publicar_plan(__CNB__.plan_actual._id, 0);
                    }
                },
            }
        });
    });
    
    var arr_grado = new Array();
    $.each($('#grado').find('*'), function (index, item) {
        arr_grado[$(item).val()] = {_id: $(item).val(), descripcion: $(item).data('descripcion'), id_carrera: $(item).data('id_carrera')};
    });
    $('#carrera').on('change', function () {
        populateSelect('grado', getObjects(arr_grado, 'id_carrera', $(this).val()));
    }).trigger('change');
    
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
            $('#form_registro').show().bootstrapValidator({
                fields:{
                    n_fecha:{
                        validators: {
                            notEmpty: {
                            },
                            date: {
                                format: 'DD/MM/YYYY'
                            },
                            callback: {
                                message: 'El año no coincide',
                                callback: function (value, validator, $field) {
                                    var anno_actual = $("#anno option[value='"+__CNB__.plan_actual.id_anno+"']").text(),
                                    fecha_nueva = value.split('/');
                                    return anno_actual == fecha_nueva[2];
                                }
                            }
                        }
                    }
                }
            });
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
            $.post(nivel_entrada+'app/src/libs_plan/gn_plan.php', {
                fn_nombre: 'crear_registro',
                id_plan: __CNB__.plan_actual._id,
                args: JSON.stringify(datos_formulario($form))
            },
            function(result) {
                abrir_registro(result[0], 'tbody_plan');
                habilitar_reg();
                $form.parents('.bootbox').modal('hide');
            }, 'json');
        })
        .modal('show');
    });
})
.ajaxSend(function () {
    barra_carga.mostrar();
})
.ajaxComplete(function () {
    barra_carga.ocultar();
});