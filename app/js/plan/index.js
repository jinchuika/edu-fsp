var __CNB__ = new Cnb();
var modal_c = modal_carga_gn();
var barra_carga = new BarraCargaInf();
var modal_form = [];
modal_c.crear();

/**
* Abre el plan desde la base de datos
* @param  {integer} id_plan
* @uses abrir_registro()
* @uses habilitar_plan_editable()
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
                habilitar_plan_editable();
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
            abrir_info_plan(id_plan);
            modal_c.ocultar();
        }
    });
}

function abrir_info_plan (id_plan) {
    $('#info_plan').hide();
    $.post(nivel_entrada+'app/src/libs_plan/gn_plan.php?fn_nombre=abrir_info_plan', {
        id_plan: id_plan
    }, function (respuesta) {
        if(respuesta){
            $('#desc_plan').html(respuesta.clase.grado+' '+respuesta.clase.carrera+', '+respuesta.clase.anno);
            $('#nombre_plan').html('Creado por '+respuesta.usuario.nombre+' '+respuesta.usuario.apellido+' de '+respuesta.escuela.nombre+', '+respuesta.escuela.udi);
            $('#info_plan').show();
        }
    },
    'json');
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
    var s_fecha = '<td id="fecha_'+registro._id+'" class="td_fecha" data-id="'+registro._id+'">'+formato_fecha(registro.fecha)+'</td>';
    var s_contenido = '<td id="contenido_'+registro._id+'" data-id="'+registro.id_contenido+'">'+bloquear_contenido(registro.id_contenido).descripcion+'</td>';
    
    var s_funsepa = '<td id="td_funsepa_'+registro._id+'">';
    $.each(registro.arr_funsepa, function (index, item) {
        s_funsepa += escribir_reg_relacion(registro._id, 'funsepa', item.id_funsepa);
    });
    s_funsepa += '</td>';

    var s_metodo = '<td id="td_metodo_'+registro._id+'">';
    $.each(registro.arr_metodo, function (index, item) {
        s_metodo += escribir_reg_relacion(registro._id, 'metodo', item.id_metodo);
    });
    s_metodo += '</td>';

    var s_actividad = '<td id="actividad_'+registro._id+'"><a class="campo_registro" data-pk="'+registro._id+'" data-name="actividad">'+registro.actividad+'</a></td>';
    var s_recurso = '<td id="recurso_'+registro._id+'"><a class="campo_registro" data-pk="'+registro._id+'" data-name="recurso">'+registro.recurso+'</a></td>';
    $('#'+objetivo).append('<tr id="tr_'+registro._id+'" data-id="'+registro._id+'">'+s_fecha+s_contenido+s_funsepa+s_actividad+s_recurso+s_metodo+'</tr>');
}

/**
 * Crea un elemento para ir dentro del TD de contenido funsepa o método
 * @param  {integer} id_registro evidente
 * @param  {string} relacion    'funsepa'|'metodo'
 * @param  {integer} id_relacion el id dentro del CNB
 * @return {string}             El elemento con formato
 */
function escribir_reg_relacion (id_registro, relacion, id_relacion) {
    var temp_relacion = getObjects(__CNB__['arr_'+relacion], '_id', id_relacion)[0];
    var s_relacion = '';
    s_relacion += '<span class="reg_'+relacion+'_'+id_registro+'" data-pk="'+id_relacion+'">';
    s_relacion += '<a target="_blank" href="'+temp_relacion.link+'">'+temp_relacion.descripcion+'</a>';
    s_relacion += '</span>, ';
    return s_relacion;
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
function habilitar_plan_editable () {
    $('.dropdown-edit').remove();
    $('.td_fecha').html(function () {
        var id_registro = $(this).data('id');
        var s_drop = '<div class="dropdown dropdown-edit">'+
        '<a data-toggle="dropdown" href="#"><i class="fa fa-cog"></i></a>'+
        '<ul class="dropdown-menu" role="menu">'+
        '<li><a class="btn_editar_reg" id="btn_editar_reg_'+id_registro+'" data-id="'+id_registro+'" value="false">Editar</a></li>'+
        '<li><a href="#" class="btn_borrar_reg" data-id="'+id_registro+'">Borrar</a></li>'+
        '</ul>'+
        '</div>';
        return $(this).html()+s_drop;
    });
    
    $('.campo_registro').editable({
        url: nivel_entrada+'app/src/libs_plan/gn_plan.php?fn_nombre=editar_registro',
        placement: 'left'
    });

    $('.btn_editar_reg').off().on('click', function () {
        habilitar_edicion_registro($(this).data('id'), $(this).val());
    });
    $('.btn_borrar_reg').off().on('click', function () {
        borrar_registro($(this).data('id'));
    });
}

function habilitar_edicion_registro (id_registro, accion) {
    $('.temp_edit_'+id_registro).remove();
    if(accion!=='false'){
        //agregar_boton_eliminar_rel(id_registro, 'funsepa');
        //agregar_boton_eliminar_rel(id_registro, 'metodo');
        $('#fecha_'+id_registro).append('<button class="temp_edit_'+id_registro+' btn btn-info" id="btn_editar_ok_'+id_registro+'" onclick="habilitar_edicion_registro('+id_registro+',\'false\');"><i class="glyphicon glyphicon-ok"></button>');
        $('#td_funsepa_'+id_registro).append('<a class="temp_edit_'+id_registro+' btn btn-xs btn-info" id="a_funsepa_'+id_registro+'" onclick="agregar_select_rel('+id_registro+', \'funsepa\')">Editar contenido</a>');
        $('#td_metodo_'+id_registro).append('<a class="temp_edit_'+id_registro+' btn btn-xs btn-info" id="a_metodo_'+id_registro+'" onclick="agregar_select_rel('+id_registro+', \'metodo\')">Editar método</a>');

        $('.btn_editar_reg').val('false');
    }
    else{
        remover_boton_eliminar_rel(id_registro, 'funsepa');
        remover_boton_eliminar_rel(id_registro, 'metodo');
        $('.temp_edit_'+id_registro).remove();
        $('.btn_editar_reg').val('true');
    }
}

/**
 * Agrega el botón para eliminar el registro de contenido FUNSEPA y Método
 * @param  {string} relacion funsepa|metodo
 */
function agregar_boton_eliminar_rel (id_registro, relacion) {
    $('.reg_'+relacion+'_'+id_registro).html(function () {
        var contenido = $(this).html();
        contenido += '<a onclick="eliminar_reg_relacion('+id_registro+','+$(this).data('pk')+', \''+relacion+'\')" class="del_'+relacion+'_'+id_registro+' btn"> <i class="glyphicon glyphicon-remove"> </a>';
        return contenido;
    });
    $('.reg_'+relacion+'_'+id_registro).addClass('label label-warning');
}

/**
 * Remueve el botón para eliminar el registro de contenido FUNSEPA y Método
 * @param  {string} relacion funsepa|metodo
 */
function remover_boton_eliminar_rel (id_registro, relacion) {
    $('.reg_'+relacion+'_'+id_registro).removeClass('label label-warning');
    $('.del_'+relacion+'_'+id_registro).remove();
}

/**
 * Agrega el select para elegir contenido cuando se edita un registro
 * @param  {integer} id_registro El registro que se está editando
 * @param  {string} relacion    'funsepa'|'metodo'
 * @return {void}             
 */
function agregar_select_rel (id_registro, relacion) {
    bootbox.dialog({
        title: "Seleccione las opciones que desea.",
        message: '<div class="row">  ' +
            '<div class="col-md-12"> ' +
            '<form class="form-horizontal"> ' +
            '<div class="form-group"> ' +
            '<label class="col-md-4 control-label" for="temp_'+relacion+'">Seleccione los elementos</label> ' +
            '<div class="col-md-4"> ' +
            '<select id="temp_'+relacion+'" name="temp_'+relacion+'" type="text" class="form-control input-md" multiple="multiple"> </select>' +
            '</div> ' +
            '</form> </div>  </div>',
        buttons: {
            success: {
                label: "Guardar",
                className: "btn-success",
                callback: function () {
                    $.post(nivel_entrada+'app/src/libs_plan/gn_plan.php', {
                        fn_nombre: 'actualizar_'+relacion,
                        id_registro: id_registro,
                        args: JSON.stringify($('#temp_'+relacion).val())
                    },
                    function(result) {
                        var contenido_td = '';
                        if(result['arr_contenido_td']){
                            $.each(result['arr_contenido_td'], function (index, item) {
                                contenido_td += escribir_reg_relacion(id_registro, relacion, item);
                            });
                        }
                        $('#td_'+relacion+'_'+id_registro).html(contenido_td);
                        habilitar_plan_editable();
                        habilitar_edicion_registro(id_registro, relacion);
                        $('#temp_'+relacion).parents('.bootbox').modal('hide');
                    }, 'json');
                }
            }
        }
    }).on('shown.bs.modal', function () {
        var arr_contenido = (relacion=='funsepa' ? obtener_rel_funsepa($('#contenido_'+id_registro).data('id')) : __CNB__['arr_metodo']);
        populateSelect('temp_'+relacion, arr_contenido, true);
    }).on('success.form.bv', function(e) {
        e.preventDefault();
        enviar_form_rel($(e.target), id_registro, relacion);
    });
}

/**
 * Elimina un registro de contenido FUNSEPA o método
 * @param  {integer} id_registro la ID del registro
 * @param  {integer} id_relacion la del contenido FUNSEPA o metodo
 */
function eliminar_reg_relacion (id_registro, id_relacion, relacion) {
    $.ajax({
        url: nivel_entrada+'app/src/libs_plan/gn_plan.php',
        data: {
            fn_nombre: 'borrar_registro_'+relacion,
            id_registro: id_registro,
            id_relacion: id_relacion
        },
        dataType: 'json',
        success: function (data) {
            if(data.msj=='si'){
                $('#reg_'+relacion+'_'+id_registro+'-'+id_relacion).remove();
            }
        }
    });
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
* @param  {string} id_elemento
* @param  {Array} items    el listado para llenar el select
* @param {bool} usar_select2 renderiza el select
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
function poblar_formulario_registro () {
    /*Poblar métodos*/
    populateSelect('n_metodo', __CNB__.arr_metodo, true);
    /*Poblar indicadores*/
    $('#n_comp').off().on('change', function () {
        populateSelect('n_indicador', getObjects(__CNB__.arr_indicador, 'id_competencia', $(this).val()), true);
    });
    /*Poblar contenido de mineduc*/
    $('#n_indicador').off().on('change', function (value) {
        populateSelect('n_contenido', getObjects(__CNB__.arr_contenido, 'id_indicador', $(this).val()), true);
    });
    /*Poblar contenido de funsepa*/
    $('#n_contenido').off().on('change', function (value) {
        var arr_funsepa = obtener_rel_funsepa($(this).val());
        populateSelect('n_funsepa', arr_funsepa, true);
    });
    /*Poblar competencias*/
    populateSelect('n_comp', getObjects(__CNB__.arr_competencia, 'id_grado', __CNB__.plan_actual.id_grado), true);
}

/**
 * Obtiene el listador de contenido de FUNSEPA para uno del mineduc
 * @param  {intenger} id_mineduc id del contenido del mineduc
 * @return {Array}            El listado de elementos
 */
function obtener_rel_funsepa (id_mineduc) {
    var arr_rel_funsepa = getObjects(__CNB__.arr_rel_contenido, 'id_mineduc', id_mineduc)
    , arr_funsepa = {};
    for (var i = 0, reg_funsepa=[]; i < arr_rel_funsepa.length; i++) {
        reg_funsepa = getObjects(__CNB__.arr_funsepa, '_id', arr_rel_funsepa[i].id_funsepa);
        arr_funsepa[i] = reg_funsepa[0];
    };
    return arr_funsepa;
}

/**
 * Muestra el formulario para crear un nuevo registro
 */
function mostrar_form_registro () {
    bootbox.dialog({
        title: 'Nuevo registro',
        message: $('#form_registro'),
        show: false
    }).on('shown.bs.modal', function() {
        poblar_formulario_registro();
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
        $(this).draggable({
            handle: ".modal-header"
        });
        $('#n_fecha').on('dp.change dp.show', function(e) {
            $('#form_registro').bootstrapValidator('revalidateField', 'n_fecha');
        });
        $('#form_registro input[type=text], textarea').val('');
        $('#form_registro').bootstrapValidator('resetForm', true);
    }).on('hide.bs.modal', function(e) {
        $('#form_registro').hide().appendTo('body');
    })
    .on('success.form.bv', function(e) {
        e.preventDefault();
        enviar_form_registro($(e.target));
    })
    .modal('show');
}

/**
 * Envía el nuevo registro para guardar en linea
 * @param  {DOM.Element} formulario El formulario con los datos
 */
function enviar_form_registro (formulario) {
    $.post(nivel_entrada+'app/src/libs_plan/gn_plan.php', {
        fn_nombre: 'crear_registro',
        id_plan: __CNB__.plan_actual._id,
        args: JSON.stringify(datos_formulario(formulario))
    },
    function(result) {
        abrir_registro(result[0], 'tbody_plan');
        habilitar_plan_editable();
        formulario.parents('.bootbox').modal('hide');
    }, 'json');
}

function activar_modo_edicion (accion) {
    if(accion==true){

    }
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
        mostrar_form_registro();
    });
})
.ajaxSend(function () {
    barra_carga.mostrar();
})
.ajaxComplete(function () {
    barra_carga.ocultar();
});