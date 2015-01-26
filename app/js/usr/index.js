function activar_editable (habilitar) {
    if(habilitar){
        $('.campo').editable('enable');
        return false;
    }
    else{
        $('.campo').editable('disable');
        return true;
    }
}

function cambiar_password () {
    bootbox.dialog({
            title: 'Cambiar contraseña',
            message: '<div id="form-password" class="form-horizontal">'+
            '<fieldset>'+
            '<div class="form-group">'+
            '<label class="col-md-4 control-label" for="old_pass">Contraseña actual</label>'+
            '<div class="col-md-4">'+
            '<input id="old_pass" name="old_pass" type="password" placeholder="" class="form-control input-md" required="">'+
            '</div>'+
            '</div>'+
            '<div class="form-group">'+
            '<label class="col-md-4 control-label" for="new_pass1">Nueva contraseña</label>'+
            '<div class="col-md-4">'+
            '<input id="new_pass1" name="new_pass1" type="password" placeholder="" class="form-control input-md" required="">'+
            '</div>'+
            '</div>'+
            '<div class="form-group">'+
            '<label class="col-md-4 control-label" for="new_pass2">Repita la contraseña</label>'+
            '<div class="col-md-4">'+
            '<input id="new_pass2" name="new_pass2" type="password" placeholder="" class="form-control input-md" required="">'+
            '</div>'+
            '</div>'+
            '</fieldset>'+
            '</div>',
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-success",
                    callback: function () {
                        $.ajax({
                            url: nivel_entrada + 'app/src/libs_usr/user.php',
                            dataType: 'json',
                            data:{
                                fn_nombre: 'cambiar_password',
                                id_user: $('#id_user').val(),
                                old_pass: encriptar_string($('#old_pass').val()),
                                new_pass: encriptar_string($('#new_pass1').val())
                            },
                            success: function (data) {
                                if(data.msj=='si') alert('Cambiado correctamente');
                                if(data.msj=='no') alert(data.error);
                            }
                        })
                    }
                }
            }
        }).on('shown.bs.modal', function() {
            console.log('shown');
            $('#form-password').show()
            .bootstrapValidator({
                fields:{
                    new_pass1: {
                        validators: {
                            notEmpty: {
                                message: 'Este campo es obligatorio'
                            },
                            identical: {
                                field: 'new_pass2',
                                message: 'Las contraseñas no coinciden'
                            }
                        }
                    },
                    new_pass2: {
                        validators: {
                            notEmpty: {
                                message: 'Este campo es obligatorio'
                            },
                            identical: {
                                field: 'new_pass1',
                                message: 'Las contraseñas no coinciden'
                            }
                        }
                    }
                }
            });
            $('#form-password').bootstrapValidator('resetForm', true);
        }).on('hide.bs.modal', function(e) {
            
        })
        .modal('show');
}
$(document).ready(function () {
    $('#username').editable({
        url: nivel_entrada+'app/src/libs_usr/user.php?fn_nombre=editar_usuario',
        mode: 'inline',
        disabled: true,
        validate: function (value) {
            if(!value) return 'No se admite vacío';
            if(!(/^[A-Za-z0-9_]+$/.test(value))) return 'No se admiten espacios';
        },
        success: function (data, config) {
            console.log(config);
            var data = $.parseJSON(data);
            if(data.msj=='no'){
                return data.error;
            }
        }
    });
    $('#mail').editable({
        url: nivel_entrada+'app/src/libs_usr/user.php?fn_nombre=editar_persona',
        type: 'email',
        mode: 'inline',
        disabled: true,
        validate: function (value) {
            if(!value) return 'No se admite vacío';
        },
        success: function (data, config) {
            console.log(config);
            var data = $.parseJSON(data);
            if(data.msj=='no'){
                return data.error;
            }
        }
    });
    $('#tel_movil').editable({
        url: nivel_entrada+'app/src/libs_usr/user.php?fn_nombre=editar_persona',
        type: 'text',
        mode: 'inline',
        disabled: true
    });
    $('#direccion').editable({
        url: nivel_entrada+'app/src/libs_usr/user.php?fn_nombre=editar_persona',
        type: 'text',
        mode: 'inline',
        disabled: true
    });
    $('#id_escuela').editable({
        url: nivel_entrada+'app/src/libs_usr/user.php?fn_nombre=editar_usuario',
        type: 'select',
        mode: 'inline',
        disabled: true,
        source: nivel_entrada+'app/src/libs_gn/gn_escuela.php?fn_nombre=listar_escuela'
    });

    $('#btn-edit').click(function (e) {
        $(this).data('accion', activar_editable($(this).data('accion')));
    });

    $('#btn-password').click(function (e) {
        cambiar_password();
    });
});