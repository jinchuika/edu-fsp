var __CNB__ = new Cnb(nivel_entrada, function () {
    $('#btn_clase').prop('disabled', false);
});

var modal_c = modal_carga_gn();
var barra_carga = new BarraCargaInf();
var modal_form = [];
modal_c.crear();

/**
 * Envía el formulario de la clase, al encontrarla abre el plan
 * @param  {DOM.Element} formulario El formulario
 */
function abrir_clase (formulario) {
    modal_c.mostrar();
    $('#tabla_plan').hide();

    $.ajax({
        url: nivel_entrada+'app/src/libs_plan/gn_plan.php',
        type: 'post',
        data: {
            fn_nombre: 'buscar_plan',
            args: JSON.stringify(datos_formulario($(formulario)))
        },
        success: function (respuesta) {
            var respuesta = $.parseJSON(respuesta);
            if(respuesta.msj=='si'){
                __CNB__.cargar_datos(function () {
                    abrir_plan(respuesta._id);
                });
            }
        }
    });
}

/**
 * Abre el plan desde la base de datos
 * @param  {integer} id_plan
 * @uses abrir_registro()
 */
function abrir_plan (id_plan, publico) {
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
            
            $.each(plan_actual['arr_registro'], function (index, registro_actual) {
                abrir_registro(registro_actual, 'tbody_plan');
            });

            if(publico!=true){
                habilitar_edicion_plan(false);
                $('.dom-privado').show();
                $('.lbl_public').hide();
            }
            else{
                $('.dom-privado').hide();
                $('.lbl_public').show();
            }
            $('#tabla_plan').show().stupidtable();
            __CNB__.plan_actual = plan_actual;
            abrir_info_usuario(id_plan);
            modal_c.ocultar();
        }
    });
}

/**
 * Muestra la información de quien creó el plan
 * @param  {integer} id_plan el id del plan
 */
function abrir_info_usuario (id_plan) {
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
 * @uses bloquear_contenido_cnb()
 */
function abrir_registro (registro, objetivo) {
    var s_fecha = '<td id="fecha_'+registro._id+'" class="td_fecha" data-id="'+registro._id+'"><a href="#" class="fecha_registro" data-pk="'+registro._id+'" data-name="fecha">'+formato_fecha(registro.fecha)+'</a></td>';
    var contenido = bloquear_contenido_cnb(registro.id_contenido).descripcion;
    var s_contenido = '<td id="contenido_'+registro._id+'" data-id="'+registro.id_contenido+'">'+contenido+'</td>';
    
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
    return true;
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
    return '<a target="_blank" href="'+temp_relacion.link+'">'+temp_relacion.descripcion+'</a>, ';
}

/**
 * Bloquea un contenido de la instancia de CNB actual
 * @param  {string} id_contenido
 * @return {Object} el contenido bloqueado
 */
function bloquear_contenido_cnb (id_contenido) {
    var contenido_temp = getObjects(__CNB__.arr_contenido, '_id', id_contenido)[0];
    if(contenido_temp){
        __CNB__.en_uso[contenido_temp._id-1] = contenido_temp;
        delete __CNB__.arr_contenido[contenido_temp._id-1];
        return(contenido_temp);
    }
    else{
        return {descripcion: 'Error al cargar. Por favor pida ayuda.'};
    }
}


/**
 * Habilita un contenido de la instancia de CNB actual
 * @param  {string} id_contenido
 * @return {Object} el contenido habilitado
 */
function habilitar_contenido_cnb (id_contenido) {
    var contenido_temp = getObjects(__CNB__.en_uso, '_id', id_contenido)[0];
    __CNB__.arr_contenido[contenido_temp._id-1] = contenido_temp;
    delete __CNB__.en_uso[contenido_temp._id-1];
    return(contenido_temp);
}

/**
 * Cambia los permisos de publicación del plan
 * @param  {integer} id_plan el ID del plan
 * @param  {boolean} tipo    si se comparte o no
 */
function publicar_plan (id_plan, tipo) {
    $.post(nivel_entrada+'app/src/libs_plan/gn_plan.php?fn_nombre=publicar_plan', {
        id_plan: id_plan,
        tipo: tipo
    }, function (respuesta) {
        if(respuesta.msj=='si' && tipo==true){
            var num_random = Math.floor((Math.random() * 9) + 1);
            id_plan = id_plan * num_random;
            bootbox.alert('Puede compartir su planificación usando el siguiente link<br><br><b>'+ location.host + location.pathname+'?id='+encriptar_string(id_plan+'_'+num_random)+'</b>');
        }
        if(respuesta.msj=='si' && tipo==false){
            $.gritter.add({
                title: 'Sin compartir',
                text: 'La planificación actual no se compartirá'
            });
        }
    },
    'json');
}

/**
 * Activa / desactiva el modo edición
 * @param  {boolean} accion Si se puede editar o no
 */
function habilitar_edicion_plan (accion) {
    $.each($('#tabla_plan').find('tr'), function (index, fila) {
        habilitar_edicion_registro($(fila).data('id'),  (accion ? 'true' : 'false'));
    });

    if(accion==true){
        $('.campo_registro').editable({
            url: nivel_entrada+'app/src/libs_plan/gn_plan.php?fn_nombre=editar_registro',
            placement: 'left'
        });
        $('.fecha_registro').editable({
            url: nivel_entrada+'app/src/libs_plan/gn_plan.php?fn_nombre=editar_fecha',
            placement: 'right',
            validate: function (value) {
                var anno_actual = $("#anno option[value='"+__CNB__.plan_actual.id_anno+"']").text(),
                fecha_nueva = value.split('/');
                if(anno_actual !== fecha_nueva[2]) return 'El año no coincide';
                if(!value) return 'No se admite vacío';
                if(!(validar_fecha(value))) return 'Formato de fecha incorrecto';
            }
        });
        $('#btn_editar').text('Editar (On)')
        .removeClass('btn-warning').addClass('btn-info')
        .attr('onclick', 'habilitar_edicion_plan(false)');
    }
    else{
        $('.campo_registro').editable('destroy');
        $('.fecha_registro').editable('destroy');
        
        $('#btn_editar').text('Editar (Off)')
        .removeClass('btn-info').addClass('btn-warning')
        .attr('onclick', 'habilitar_edicion_plan(true)');
    }
}

/**
 * Permite que una fila sea editada
 * @param  {integer} id_registro El id del registro a editar
 * @param  {string} accion      'false' para deshabilitar
 */
function habilitar_edicion_registro (id_registro, accion) {
    $('.temp_edit_'+id_registro).remove();
    if(accion!=='false'){
        $('#fecha_'+id_registro).append(' <a href="#" class="btn_borrar_reg temp_edit_'+id_registro+' label label-danger" data-id="'+id_registro+'" onclick="borrar_registro('+id_registro+');"><i class="glyphicon glyphicon-trash"></i></a>');
        $('#td_funsepa_'+id_registro).append(' <a class="temp_edit_'+id_registro+' label label-primary" id="a_funsepa_'+id_registro+'" onclick="agregar_select_rel('+id_registro+', \'funsepa\')"><i class="glyphicon glyphicon-edit"></i></a>');
        $('#td_metodo_'+id_registro).append(' <a class="temp_edit_'+id_registro+' label label-primary" id="a_metodo_'+id_registro+'" onclick="agregar_select_rel('+id_registro+', \'metodo\')"><i class="glyphicon glyphicon-edit"></i></a>');
    }
    else{
        $('.temp_edit_'+id_registro).remove();
    }
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
                        habilitar_edicion_registro(id_registro, relacion);
                        $('#temp_'+relacion).parents('.bootbox').modal('hide');
                    }, 'json');
                }
            }
        }
    }).on('shown.bs.modal', function () {
        var arr_contenido = (relacion=='funsepa' ? obtener_rel_funsepa($('#contenido_'+id_registro).data('id')) : __CNB__['arr_metodo']);
        poblar_select('temp_'+relacion, arr_contenido, true);
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
        if(respuesta===true){
            barra_carga.mostrar();
            $.post(nivel_entrada+'app/src/libs_plan/gn_plan.php?fn_nombre=borrar_registro', {
                id_registro: id_registro
            }, function (respuesta) {
                if(respuesta.msj=='si'){
                    habilitar_contenido_cnb($('#contenido_'+id_registro).data('id'));
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
        formulario.parents('.bootbox').modal('hide');
    }, 'json');
}

/**
 * Llena un select
 * @param  {string} id_elemento
 * @param  {Array} items    el listado para llenar el select
 * @param {bool} usar_select2 renderiza el select
 */
function poblar_select(id_elemento, items, usar_select2) {
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
    poblar_select('n_metodo', __CNB__.arr_metodo, true);
    /*Poblar indicadores*/
    $('#n_comp').off().on('change', function () {
        poblar_select('n_indicador', getObjects(__CNB__.arr_indicador, 'id_competencia', $(this).val()), true);
    });
    /*Poblar contenido de mineduc*/
    $('#n_indicador').off().on('change', function (value) {
        poblar_select('n_contenido', getObjects(__CNB__.arr_contenido, 'id_indicador', $(this).val()), true);
    });
    /*Poblar contenido de funsepa*/
    $('#n_contenido').off().on('change', function (value) {
        var arr_funsepa = obtener_rel_funsepa($(this).val());
        poblar_select('n_funsepa', arr_funsepa, true);
    });
    /*Poblar competencias*/
    poblar_select('n_comp', getObjects(__CNB__.arr_competencia, 'id_grado', __CNB__.plan_actual.id_grado), true);
}

/**
 * Llena el formulario para seleccionar la clase del plan
 */
function poblar_formulario_clase () {
    var arr_grado = new Array();
    $.each($('#grado').find('*'), function (index, item) {
        arr_grado[$(item).val()] = {_id: $(item).val(), descripcion: $(item).data('descripcion'), id_carrera: $(item).data('id_carrera')};
    });
    $('#carrera').on('change', function () {
        poblar_select('grado', getObjects(arr_grado, 'id_carrera', $(this).val()));
    }).trigger('change');
}

$(document).ready(function () {
    $('.dom-privado').hide();
    poblar_formulario_clase();

    $("#btn_export").click(function () {
        habilitar_edicion_plan(false);
        exportar_excel('tabla_plan');
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
                        publicar_plan(__CNB__.plan_actual._id, true);
                    }
                },
                danger: {
                    label: "No compartir",
                    className: "btn-danger",
                    callback: function() {
                        publicar_plan(__CNB__.plan_actual._id, false);
                    }
                },
            }
        });
    });
    
    $('#form_clase').off().on('submit', function (e) {
        e.preventDefault();
        abrir_clase($('#form_clase'));
    });

    $('#btn_nuevo_registro').off().on('click', function () {
        mostrar_form_registro();
    });
})
.ajaxSend(function () {
    barra_carga.mostrar();
})
.ajaxComplete(function () {
    barra_carga.ocultar();
});