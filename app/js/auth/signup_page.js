$(document).ready(function () {
    $('#form_nuevo').bootstrapValidator({
        feedbackIcons: {
            required: 'fa fa-asterisk',
            valid: 'fa fa-check',
            invalid: 'fa fa-times',
            validating: 'fa fa-refresh'
        },
        fields: {
            username: {
                validators: {
                    notEmpty: {
                        message: 'Este campo es obligatorio'
                    },
                    remote: {
                        message: 'El nombre de usuario no está disponible',
                        url: 'app/src/libs_usr/user.php?fn_nombre=validar_nombre'
                    },
                    regexp: {
                        regexp: /^[A-Za-z0-9_]+$/,
                        message: 'Solo se admiten letras, números y guiones bajos'
                    }
                }
            },
            mail: {
                validators: {
                    notEmpty: {
                        message: 'Este campo es obligatorio'
                    },
                    remote: {
                        message: 'El correo ya está en uso',
                        url: 'app/src/libs_usr/user.php?fn_nombre=validar_mail'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'Este campo es obligatorio'
                    },
                    identical: {
                        field: 'password2',
                        message: 'Las contraseñas no coinciden'
                    }
                }
            },
            password2: {
                validators: {
                    notEmpty: {
                        message: 'Este campo es obligatorio'
                    },
                    identical: {
                        field: 'password',
                        message: 'Las contraseñas no coinciden'
                    }
                }
            }
        }
    })
    .on('success.form.bv' ,function (e) {
        e.preventDefault();
        $.ajax({
            url: 'app/src/libs_usr/user.php',
            data: {
                fn_nombre: 'crear_usuario',
                args: JSON.stringify(datos_formulario($('#form_nuevo')))
            },
            success: function (data) {
                var respuesta = $.parseJSON(data);
                if(respuesta.msj=='si'){
                    $.gritter.add({
                        title: 'Creado correctamente',
                        text: 'Será dirigido en unos segundos'
                    });
                    setTimeout(function () {
                       window.location.href = "index.php";
                    }, 2500);
                }
            }
        });
});
});